<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendPaymentInstallmentReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:installreminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $today = Carbon::today('Europe/Malta');

        $sendDays = [10, 8, 6, 4, 3, 2, 1]; // BEFORE due date

        $studentCourses = DB::table('payment_installment as pi')
            ->join('course_master as c', 'c.id', '=', 'pi.course_id')
            ->select(
                'c.id as course_id',
                'c.course_title as course_name',
                'c.no_of_installment',
                'c.installment_duration',
                'c.installment_amount',
                'pi.user_id',
                'pi.paid_install_date'
            )
            ->distinct('pi.user_id', 'pi.course_id')
            ->get();

        $installmentData = [];

        // ----------------------------------------
        // 2. BUILD INSTALLMENTS PER COURSE
        // ----------------------------------------
        foreach ($studentCourses as $course) {

            $paidInstallments = DB::table('payment_installment')
                ->where('user_id', $course->user_id)
                ->where('course_id', $course->course_id)
                ->where('paid_install_status', '0') // 0=PAID
                ->orderBy('paid_install_no')
                ->get();

            // Map paid installments
            $paidByNo = [];
            foreach ($paidInstallments as $paid) {

                $nos = $paid->multiple_install_no && $paid->multiple_install_no != "0"
                    ? explode(",", $paid->multiple_install_no)
                    : [$paid->paid_install_no];

                foreach ($nos as $no) {
                    $paidByNo[trim($no)] = [
                        'status' => $paid->paid_install_status,
                        'amount' => $paid->paid_install_amount,
                        'pay_date' => $paid->created_at,
                        'student_course_master_id' => $paid->student_course_master_id,
                    ];
                }
            }

            // first paid date OR now()
            $startDate = $course->paid_install_date ?? now();

            for ($i = 1; $i <= $course->no_of_installment; $i++) {

                $dueDate = $i == 1
                    ? '-'
                    : Carbon::parse($startDate)
                        ->addMonths($course->installment_duration * ($i - 1))
                        ->format('Y-m-d');

                $installmentData[$course->user_id][] = [
                    'user_id'   => $course->user_id,
                    'course_id' => $course->course_id,
                    'course_name' => $course->course_name,
                    'install_no' => $i,
                    'due_date' => $dueDate,
                    'amount' => $paidByNo[$i]['amount'] ?? $course->installment_amount,
                    'status' => $paidByNo[$i]['status'] ?? 1, // 1 = UNPAID
                ];
            }
        }
        // print_r($installmentData);
        // die;
        // ----------------------------------------
        // 3. GENERATE REMINDER LIST
        // ----------------------------------------

        $reminderList = [];

        foreach ($installmentData as $userId => $installments) {

            $firstUnpaid = null;
            foreach ($installments as $inst) {
                if ($inst['status'] == 1 && $inst['due_date'] != '-') {
                    $firstUnpaid = $inst;
                    break;
                }
            }
            if (!$firstUnpaid) continue; // all paid
        
            $due = Carbon::parse($firstUnpaid['due_date']);
            $diff = $today->diffInDays($due, false); // positive if due in future
            
            $user = DB::table('users')
            ->select('name', 'last_name', 'email')
            ->where('id', $firstUnpaid['user_id'])
            ->first();
            // send reminder only BEFORE due date
            if ($diff > 0 && in_array($diff, $sendDays)) {
        
                if (!$user || !$user->email) continue;
        
                $firstUnpaid['diff_days'] = $diff;
                $firstUnpaid['user_name'] = ($user->name ?? '') . ' ' . ($user->last_name ?? '');
                $firstUnpaid['user_email'] = $user->email;
        
                $reminderList[] = $firstUnpaid;
        
                // Uncomment to actually send email
                mail_send(
                    68,
                    ['#StudentName#', '#days_no#', '#due_date#'],
                    [
                        trim(($user->name ?? '') . ' ' . ($user->last_name ?? '')),
                        $diff,
                        $due->format('Y-m-d'),
                    ],
                    $user->email
                );
            }
            if ($diff < 0) {

                if (!$user || !$user->email) continue;
                
                mail_send(
                    69,
                    ['#StudentName#','#due_date#','#days_no#'],
                    [
                        trim(($user->name ?? '') . ' ' . ($user->last_name ?? '')),
                        $due->format('Y-m-d'),
                        $diff
                    ],
                    $user->email
                );
        
                continue;
            }

        }
        

        // ----------------------
        // 4. Print Output
        // ----------------------
        // dd($reminderList);

    } 
}
