<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\{Auth, Redirect, Validator, Storage, DB, Hash, Http};
use Carbon\Carbon;

class PaymentModule extends Model
{

    use HasFactory;
    public $timestamps = false;
    public $table = 'payments';
    protected $guarded  = [];
    public function __construct()
    {
        parent::__construct();

     
    }

    public function UserData()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }

    public function OrderData()
    {
        return $this->hasMany(OrderModel::class, 'payment_id','id');
        
    }
    public function RefundData()
    {
        return $this->hasMany(PaymentRefundModel::class, 'payment_id','id');
        
    }


    public function getPaymentDetails($where = [], $cat )
    {   
    
        if (Auth::check()) {
            $where = $where;
            $subQuery = DB::table('payments as p')
            ->selectRaw("
                MAX(COALESCE(pi.created_at, p.created_at)) as latest_created_at,
                p.user_id,
                o.course_id,
                CASE WHEN p.installment_status = 'InstallmentPayment' THEN pi.paid_install_no ELSE NULL END as installment_no
            ")
            ->leftJoin('payment_installment as pi', 'pi.payment_id', '=', 'p.id')
            ->leftJoin('orders as o', 'o.payment_id', '=', 'p.id')
            ->where('p.is_deleted', 'No')
            ->groupBy(
                'p.user_id',
                'o.course_id',
                DB::raw("CASE WHEN p.installment_status = 'InstallmentPayment' THEN pi.paid_install_no ELSE NULL END")
            );
        
            $query = $this->with('UserData')->with('RefundData')
                ->leftJoin('payment_installment', 'payment_installment.payment_id', '=', 'payments.id')
                ->leftJoin('orders', 'orders.payment_id', '=', 'payments.id')
                ->leftJoin('users', 'users.id', '=', 'payments.user_id') // <-- join users table here
                ->joinSub($subQuery, 'latest', function($join) {
                    $join->on(DB::raw("COALESCE(payment_installment.created_at, payments.created_at)"), '=', 'latest.latest_created_at')
                    ->on('payments.user_id', '=', 'latest.user_id')
                    ->on('orders.course_id', '=', 'latest.course_id');
                })
                ->selectRaw("
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL, payments.uni_order_id, payment_installment.uni_order_id) as uni_order_id,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL, payments.first_name, payment_installment.first_name) as first_name,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL, payments.last_name, payment_installment.last_name) as last_name,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL, payments.email, payment_installment.email) as email,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL, payments.address, payment_installment.address) as address,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL, payments.session_id, payment_installment.session_id) as session_id,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL, payments.created_at, payment_installment.created_at) as created_at,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL, payments.status, payment_installment.paid_install_status) as status,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL, payments.id, payment_installment.id) as id,
                    paid_install_amount,
                    paid_install_date,
                    paid_install_status,
                    installment_status,
                    paid_install_no,
                    payment_type,
                    scholarship,
                    discount,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL, orders.course_title, payment_installment.course_title) as course_title,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL, orders.promo_code_name, '') as promo_code_name,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL, orders.promo_code_discount, '') as promo_code_discount,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL, orders.course_price, payment_installment.total_amount) as course_price,
                    orders.final_price,
                    users.name,
                    users.last_name as last_names,
                    payment_installment.student_course_master_id,
                    payment_installment.multiple_install_no
                ");
                

            // apply custom condition using $where
            if (!empty($where)) {
                if (!empty($where['start_date']) && !empty($where['end_date'])) {
                    $query->whereBetween(
                        DB::raw("IF(
                            payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL,
                            payments.created_at,
                            payment_installment.created_at
                        )"),
                        [$where['start_date'], $where['end_date']]
                    );
                }else{
                
                    $query->whereRaw("
                        IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL, payments.id, payment_installment.id) = ?
                    ", [$where]);
                }
            }
            // if($cat){
            //     $query->whereRaw("
            //     IF(payments.installment_status = 'FullPaymentcd', payments.user_id, payment_installment.user_id) = ?
            //     ", ['370']);
            // }

            if ($cat == "Paid") {
                $query->whereRaw("
                        IF(
                            (payments.installment_status = 'FullPayment'
                            OR payments.installment_status IS NULL),
                            payments.status,
                            payment_installment.paid_install_status
                        ) COLLATE utf8mb4_general_ci = '0'
                    ");
            }
            if ($cat == "Failed") {
                 $query->whereRaw("
                        IF(
                            (payments.installment_status = 'FullPayment'
                            OR payments.installment_status IS NULL),
                            payments.status,
                            payment_installment.paid_install_status
                        ) COLLATE utf8mb4_general_ci = '1'
                    ");
            }
            // print_r($where);
            $query->orderByRaw("IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL, payments.created_at, payment_installment.created_at) DESC");

            $PaymentData = $query->get()->toArray();
            return $PaymentData;
        }
        return redirect('/login');
    }
    
    public function getPaymentReportData($cat, $where = [])
    {
    
        if (Auth::check()) {
            $where = $where;
            $query = $this->with('OrderData')->with('UserData')->with('RefundData')->where('is_deleted', 'No')->WhereNotNull('status')->orderByDesc('id');
            $PaymentData = [];
            $today = Carbon::today();
            if($cat == 'Hold'){ 
                
                $query = $query->where('status','0')->whereDate('hold_date', '>', $today);

            }else if($cat == 'Paid'){
                
                $query = $query->where('status','0')->whereDate('hold_date', '<=', $today);
            }
           

            if (!empty($where) && is_array($where)) {
                $query = $query->where($where);
            }
    
            $PaymentData = $query->get()->toArray();

            return $PaymentData;
        }
        return redirect('/login');
    }


}
