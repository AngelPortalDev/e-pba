<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InstitutePartnerUniveristy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'institute:reminder';

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
        $today = Carbon::today('Europe/Malta')->format('Y-m-d');
        $InstituteData = DB::table('institute_profile_master')->leftJoin('users', 'users.id', '=', 'institute_profile_master.institute_id')->select('users.email','users.name','users.last_name','institute_profile_master.university_code','institute_profile_master.approved_on','institute_profile_master.last_email_approved_on','institute_profile_master.id','institute_profile_master.institute_id')->whereNotNull('institute_profile_master.approved_on')->where('institute_profile_master.status','0')->where('is_approved','1')->get();
        foreach($InstituteData as $key => $value){
            $PromoCode = DB::table('coupons')->where('institute_id',$value->institute_id)->where('course_id','3')->select('coupon_name')->first();
            if(empty($value->last_email_approved_on)){
                $approvedDate = Carbon::parse($value->approved_on)->format('Y-m-d');
                $approved = Carbon::parse($approvedDate);
                $today = Carbon::now(); // Or use your own date here
                $afterFifteenDays = $approved->copy()->addDays(16);
            }else{
                $approvedDate = Carbon::parse($value->last_email_approved_on)->format('Y-m-d');
                $approved = Carbon::parse($approvedDate);
                $today = Carbon::now(); // Or use your own date here]
                $afterFifteenDays = $approved->copy()->addDays(16);
            }
            $diffInDays = $today->diffInDays($approved);
            if ($diffInDays  > 0 && $diffInDays  % 15 === 0) {
                    mail_send(
                        63,
                        [
                            '#Institute_Name#',
                            '#Institute_Code#',
                        ],
                        [
                            $value->name,
                            $value->university_code,
                        ],
                        $value->email,
                        "mrodrigues@ascenciamalta.mt"
                    );
                    mail_send(
                        64,
                        [
                            '#Institute_Code#',
                            '#Coupon_Code#',
                        ],
                        [
                            $value->university_code,
                            $PromoCode->coupon_name,
                        ],
                        $value->email,
                        "mrodrigues@ascenciamalta.mt"
                    );
                DB::table('institute_profile_master')
                ->where('id', $value->id)
                ->update(['last_email_approved_on' => $afterFifteenDays]);
            }
        }
        
    }
}

