<?php

namespace App\Exports;

use App\Models\PaymentModule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaymentExport implements FromCollection, WithHeadings, WithStyles, WithEvents, WithCustomStartCell
{
    protected $paymentModule;
    protected $totalCourseSales;
    protected $totalSalesAmount;
    protected $where;

    public function __construct(PaymentModule $paymentModule = null, $where = [])
    {
        $this->paymentModule = $paymentModule ?: new PaymentModule();
        $this->totalCourseSales = 0;
        $this->totalSalesAmount = 0;
        $this->where = $where;
    }

    public function collection()
    {
        $cat = 'all';
        $where = [];
        if (isset($this->where['start_date']) && isset($this->where['end_date'])) {
            $where = [
                'start_date' => $this->where['start_date'],
                'end_date' => $this->where['end_date']  
            ];
        }
        
        
        $PaymentData = $this->paymentModule->getPaymentDetails($where, $cat);
        $groupedData = [];

        foreach ($PaymentData as $row) {
            $id = $row['student_course_master_id'] ?? $row['id'];

            // Initialize if first time
            if (!isset($groupedData[$id])) {
                $groupedData[$id] = $row;
                $groupedData[$id]['installments'] = [];
            }

            // Handle installment payments
            if ($row['installment_status'] === "InstallmentPayment") {

                // Merge nested installments
                if (!empty($row['installments']) && is_array($row['installments'])) {
                    foreach ($row['installments'] as $inst) {
                        $groupedData[$id]['installments'][] = [
                            'paid_install_no'     => $inst['paid_install_no'],
                            'paid_install_amount' => $inst['paid_install_amount'],
                            'paid_install_date'   => $inst['created_at'],
                            'paid_install_status' => $inst['paid_install_status'],
                            'uni_order_id'        => $inst['uni_order_id'],
                            'id'                  => $inst['id'],
                            'multiple_install_no' => $row['multiple_install_no']
                        ];
                    }
                }

                // Add direct installment if applicable
                if (!empty($row['paid_install_no'])) {
                    $groupedData[$id]['installments'][] = [
                        'paid_install_no'     => $row['paid_install_no'],
                        'paid_install_amount' => $row['paid_install_amount'],
                        'paid_install_date'   => $row['created_at'],
                        'paid_install_status' => $row['paid_install_status'],
                        'uni_order_id'        => $row['uni_order_id'],
                        'id'                  => $row['id'],
                        'multiple_install_no' => $row['multiple_install_no']
                    ];
                }
            }

            // Full payment rows
            elseif ($row['installment_status'] === "FullPayment") {
                $groupedData[$id]['installments'] = [];
            }
        }

        // Sort installments by number
        foreach ($groupedData as $id => $data) {
            usort($groupedData[$id]['installments'], function($a, $b) {
                return $b['paid_install_no'] <=> $a['paid_install_no'];
            });
        }

        $groupedData = array_values($groupedData);
        $total_amount = 0;
        $formattedData = collect($groupedData)->map(function ($payment) {

            $name = isset($payment['name'])
                ? trim($payment['name'] . ' ' . ($payment['last_name'] ?? ''))
                : 'NA';

            $courseTitle = $payment['course_title'] ?? '-';
            $installments = $payment['installments'] ?? [];
            $installmentNos = '';
            $installmentAmounts = '';
            $installmentDates = '';
            $installmentStatuses = '';
            $totalPaid = 0;
            $uniOrderId = '';
            $finalCoursePrices = 0;

            if (count($installments) > 0) {
                foreach ($installments as $install) {
                    $no = $install['multiple_install_no'] ?? $install['paid_install_no'] ?? '-';
                    $installmentNos .= $no . "\n";

                    $amt = $install['paid_install_amount'] ?? '-';
                    $installmentAmounts .= $amt . "\n";

                    $uni_order_id = $install['uni_order_id'] ?? '-';
                    $uniOrderId .= $uni_order_id . "\n";

                    $date = $install['paid_install_date'] ?? '-';
                    $installmentDates .= $date . "\n";

                    $status = $install['paid_install_status'] ?? null;
                    if ($status == 0) {
                        $installmentStatuses .= "Success\n";
                        $totalPaid += floatval($install['paid_install_amount'] ?? 0);
                    } else {
                        $installmentStatuses .= "Failed\n";
                    }
                }
            } else {
                $status = $payment['status'] ?? '';
                if ($status == 0) {
                    $installmentStatuses = "Success";
                } else {
                    $installmentStatuses = "Failed";
                }
                $installmentNos = '-';
                $installmentAmounts = '-';
                $installmentDates = $payment['created_at'] ?? '-';
                $uniOrderId = $payment['uni_order_id'] . "\n" ?? '-';

            }

            // Calculate total
            $coursePrice = floatval($payment['course_price'] ?? 0);
            $promoDiscount = floatval($payment['promo_code_discount'] ?? 0);
            $finalCoursePrice = round($coursePrice - $promoDiscount, 2);

          
            if (str_contains($installmentStatuses, 'Success')) {
                $this->totalSalesAmount += ($totalPaid > 0) ? $totalPaid : $finalCoursePrice;
                $this->totalCourseSales++;
            }
            $total_amount = ($totalPaid > 0)
            ? $totalPaid . '/' . $finalCoursePrice
            : $finalCoursePrice;

            $total_amount = $total_amount ? $total_amount : '0';


            return [
                'Student Name'        => $name,
                'Course Name'         => $courseTitle,
                'Order ID'            => $uniOrderId,
                'Installment No'      => trim($installmentNos),
                'Installment Payment' => trim($installmentAmounts),
                'Total Amount'        => $total_amount,
                'Status'              => trim($installmentStatuses),
                'Date'                => trim($installmentDates),
            ];
        });
        return $formattedData;
    }

    public function headings(): array
    {
        return [
            "Student Name",
            "Course Name",
            "Order ID",
            "Installment No",
            "Installment Payment",
            "Total Amount",
            "Status",
            "Date"
        ];
    }

    public function styles(Worksheet $sheet)
{
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    $cellRange = 'A1:' . $highestColumn . $highestRow;

    $sheet->getStyle('A6:H6')->applyFromArray([
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => ['argb' => 'FFFF00'],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
    ]);
    // ✅ Apply borders to all cells
    $sheet->getStyle($cellRange)->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '000000'],
            ],
        ],
    ]);

    // ✅ Make first 4 lines (A1:H4) bold
    $sheet->getStyle('A1:A4')->getFont()->setBold(true);

    // ✅ Center align all data
    $dataRange = 'A7:' . $highestColumn . $highestRow;
    $sheet->getStyle($dataRange)->getAlignment()->setHorizontal(
        \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
    );
    $sheet->getStyle($dataRange)->getAlignment()->setVertical(
        \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
    );

    // ✅ Auto-size & wrap text for all columns
    foreach (range('A', 'H') as $col) {
        $sheet->getStyle($col)->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    return [];
}


    private function getPaymentStatus($status)
    {
        return $status;
        return match ($status) {
            0 => 'Success',
            1 => 'Failed',
            2 => 'Refunded',
            default => '-',
        };
    }
    
   
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->setCellValue('A1', 'Payment Report');
                $durationText = 'Duration - All records till date';
                if (!empty($this->where['start_date']) && !empty($this->where['end_date'])) {
                    $durationText = 'Duration - ' . $this->where['start_date'] . ' to ' . $this->where['end_date'];
                }

                $sheet->setCellValue('A2', $durationText);
                $sheet->setCellValue('A3', 'Total Courses Sales - ' . ($this->totalCourseSales ?? 0));
                $sheet->setCellValue('A4', 'Total Sales Amount (Paid) - ' . number_format($this->totalSalesAmount, 2));
            },
        ];
    }

    public function startCell(): string
    {
        return 'A6';
    }
}
