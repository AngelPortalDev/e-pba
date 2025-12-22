<?php

use Illuminate\Support\Facades\{DB, Storage, Mail, Exception, Queue,Log};
use App\Jobs\SendActionMails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Models\User;
use App\Notifications\SendNotification;
use Smalot\PdfParser\Parser;
use Illuminate\Database\Eloquent\Model;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Cache;
use App\Models\Translation;
use Illuminate\Support\Str;
use App\Models\CourseModule;

if (!function_exists('getDropDownlist')) {
    function getDropDownlist($table, $select, $limit = '')
    {

        if (!empty($select[1])) {
            $order = $select[1];
        } else {
            $order = $select[0];
        }
        if (isset($table) && !empty($table) && isset($select) && is_array($select)) {

            $query = DB::table($table)->select($select)->where('is_deleted', 'No')->orderBy($order,'ASC');

            if (!empty($limit) && isset($limit) && is_numeric($limit)) {
                $query->take($limit);
            }
            $data = $query->get();
            return $data;
        }
    }
}
if (!function_exists('getContentAsAssigned')) {
    function getContentAsAssigned($table, $select, $where)
    {


        if (!empty($select[1])) {
            $order = $select[1];
        } else {
            $order = $select[0];
        }
        if (isset($table) && !empty($table) && isset($select) && is_array($select)) {
            $query = DB::table($table)->select($select);
            if (isset($where) && !empty($where) && count($where) > 0  &&  is_array($where)) {
                $query->where($where);
            }
            $data = $query->get();
            return $data;
        }
    }
}
if (!function_exists('getData')) {
    function getData($table, $select, $where = '', $limit = '', $order_col = '', $order_dirc = 'DESC')
    {
        if (isset($table) && !empty($table) && isset($select) && is_array($select) && isset($where) && is_array($where)) {
            // $query = DB::table($table)->select($select)->where('is_deleted', 'No'); OLD
            $query = DB::table($table)->select($select); //New
            if (isset($where) && !empty($where) && $where != '' &&  is_array($where)) {
                $query->where($where);
            }
            if (isset($limit) && !empty($limit) && is_numeric($limit) && $limit != '') {
                $query->limit($limit);
            }
            if (isset($order_col) && !empty($order_col)) {
                $query->orderBy($order_col, $order_dirc);
            }

            $data = $query->get();
            return $data;
        }
    }
}
if (!function_exists('getPaidCourse')) {
    function getPaidCourse($where)
    {
        $data = [];
        if (isset($where['user_id']) && !empty($where['user_id']) && is_numeric($where['user_id'])) {

            $userId = $where['user_id'] ? $where['user_id'] : 0;

            $adjustedExpiryCondition = "IF(scm.exam_attempt_remain = 1, DATE_ADD(scm.course_expired_on, INTERVAL 15 DAY), scm.course_expired_on)";

            $query = DB::table('course_master as cm')
                ->select(
                    'cm.id as course_id',
                    'cm.course_title',
                    'user.id as userId',
                    'ord.id as orderId',
                    'cm.course_thumbnail_file',
                    'scm.course_expired_on',
                    'scm.exam_remark',
                    'scm.exam_attempt_remain',
                    'cm.category_id',
                    'cm.ects',
                    'scm.course_progress',
                    'scm.id as scmId',
                    'scm.preference_status',
                    'scm.preference_id',
                    'scm.payment_installment_type',
                    'scm.total_course_price',
                    DB::raw("$adjustedExpiryCondition as adjusted_expiry")
                )
                ->leftJoin('orders as ord', 'ord.course_id', '=', 'cm.id')
                ->leftJoin('users as user', 'ord.user_id', '=', 'user.id')
                ->leftJoin('student_course_master as scm', function ($join) {
                    $join->on('scm.course_id', '=', 'cm.id')
                        ->on('scm.user_id', '=', 'user.id');
                })
                ->where('ord.user_id', $userId)
                ->where('scm.is_deleted','No');
                if(isset($where['ementor_id'])){
                    $ementorId = $where['ementor_id'] ? $where['ementor_id'] : 0;
                    $query->where('cm.ementor_id', $ementorId);
                }
                if (isset($where['include_adjusted_expiry']) && $where['include_adjusted_expiry'] === true) {
                    $query->whereRaw("$adjustedExpiryCondition > NOW()");
                } else {
                    $query->where('scm.course_expired_on', '>', now());
                }

                $query = $query->where('ord.status', '0')
                ->whereNotNull('ord.course_id')
                ->where(function ($query) {
                    $query->where('scm.exam_remark', '!=', '1')
                        ->orWhereNull('scm.exam_remark');
                })
                ->where(function ($query) {
                    $query->where('scm.exam_attempt_remain', '!=', 0)
                        ->orWhere('scm.exam_attempt_remain', '>', 0);
                })
                ->whereIn('ord.id', function ($subquery) use ($userId) {
                    $subquery->select(DB::raw('MAX(ord.id)'))
                        ->from('orders as ord')
                        ->where('ord.user_id', $userId)
                        ->where('ord.status', '0')
                        ->groupBy('ord.course_id', 'ord.user_id');
                });

            $data = $query->get()->toArray();
        }
        return $data;
    }
}
if (!function_exists('getAllPaidCourse')) {
    function getAllPaidCourse($where)
    {
        $data = [];
        if (isset($where['user_id']) && !empty($where['user_id']) && is_numeric($where['user_id'])) {

            $userId = $where['user_id'] ? $where['user_id'] : 0;

            $adjustedExpiryCondition = "IF(scm.exam_attempt_remain = 1, DATE_ADD(scm.course_expired_on, INTERVAL 15 DAY), scm.course_expired_on)";

            $query = DB::table('course_master as cm')
                ->select(
                    'cm.id as course_id',
                    'cm.course_title',
                    'user.id as userId',
                    'ord.id as orderId',
                    'cm.course_thumbnail_file',
                    'scm.course_expired_on',
                    'scm.exam_remark',
                    'scm.exam_attempt_remain',
                    'cm.category_id',
                    'cm.ects',
                    'scm.course_progress',
                    'scm.id as scmId',
                    'scm.course_start_date',
                    'payments.total_amount as purchase_price',
                    DB::raw("$adjustedExpiryCondition as adjusted_expiry")
                )
                ->leftJoin('orders as ord', 'ord.course_id', '=', 'cm.id')
                ->leftJoin('users as user', 'ord.user_id', '=', 'user.id')
                ->leftJoin('student_course_master as scm', function ($join) {
                    $join->on('scm.course_id', '=', 'cm.id')
                        ->on('scm.user_id', '=', 'user.id');
                })
                ->leftJoin('payments', 'scm.payment_id', '=', 'payments.id')
                ->where('ord.user_id', $userId)
                ->where('user.is_deleted','No')
                ->where('user.is_active','Active')
                // ->where('user.is_verified','Verified')
                ->where('user.block_status','0')
                ->where('scm.is_deleted','No');
                if(isset($where['start_date'])){
                    $startDate = $where['start_date'];
                    $query->where('scm.'.$startDate[0], $startDate[1], $startDate[2]);
                }
                if(isset($where['course_id'])){
                    $startDate = $where['course_id'];
                    $query->whereIn('cm.id', $where['course_id']);
                }
                if(isset($where['student_course_master_id'])){
                    $startDate = $where['student_course_master_id'];
                    $query->whereIn('scm.id', $where['student_course_master_id']);
                }
                if(isset($where['end_date'])){
                    $endDate = $where['end_date'];
                    $query->where('scm.'.$endDate[0], $endDate[1], $endDate[2]);
                }
                if(isset($where['ementor_id'])){
                    $ementorId = $where['ementor_id'] ? $where['ementor_id'] : 0;
                    $query->where('cm.ementor_id', $ementorId);
                }
                // if (isset($where['include_adjusted_expiry']) && $where['include_adjusted_expiry'] === true) {
                //     $query->whereRaw("$adjustedExpiryCondition > NOW()");
                // }
                // else {
                //     if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
                //         $query->where('scm.course_expired_on', '>', now());
                //     }
                // }
                if(isset($where['before_expired_on'])){
                    $before_expired_on = $where['before_expired_on'] ? $where['before_expired_on'] : 0;
                    $query->where('scm.course_expired_on', '<', $before_expired_on);
                    $query->where(function($q) {
                        $q->whereNull('scm.withdraw_status')
                          ->orWhere('scm.withdraw_status', '');
                    });
                }
                if(isset($where['course_expired_not_on'])){
                    $course_expired_not_on = $where['course_expired_not_on'] ? $where['course_expired_not_on'] : 0;
                    $query->where('scm.course_expired_on', '>', $course_expired_not_on);
                }
                if(isset($where['withdraw_status_on'])){
                    $withdraw_status_on = $where['withdraw_status_on'] ? $where['withdraw_status_on'] : 0;
                    $query->where('scm.withdraw_status', '=', $withdraw_status_on);
                }
                
                if (isset($where['course_title']) && !empty($where['course_title'])) {
                    $query->where('cm.course_tile', 'like', '%' . $where['course_title'] . '%');
                }

                if(isset($where['cat'])){
                    if($where['cat'] == 'athe'){
                        $query->where('cm.category_id','>',5);
                    }elseif($where['cat'] == 'all'){
                        $query->where('cm.category_id','<=',5);
                    }
                }

                $query = $query->where('ord.status', '0')
                ->whereNotNull('ord.course_id')
                ->whereIn('ord.id', function ($subquery) use ($userId) {
                    $subquery->select(DB::raw('MAX(ord.id)'))
                        ->from('orders as ord')
                        ->where('ord.user_id', $userId)
                        ->groupBy('ord.course_id', 'ord.user_id');
                });

                if (isset($where['limit']) && !empty($where['limit']) && is_numeric($where['limit'])) {
                    $query = $query->orderByDesc('scm.id')->limit(1);
                }else{
                    $query = $query->orderByDesc('scm.id');
                }





            // if (isset($where) && !empty($where) && $where != '' &&  is_array($where)) {
            //     $query->where($where);
            // }
            // if (isset($limit) && !empty($limit) && is_numeric($limit) && $limit != '') {
            //     $query->limit($limit);
            // }
            // if (isset($order_col) && !empty($order_col)) {
            //     $query->orderBy($order_col, $order_dirc);
            // }

            $data = $query->get()->toArray();
        }
        return $data;
    }
}
if (!function_exists('getStudentExpiredCourse')) {
    function getStudentExpiredCourse($where)
    {
        $data = [];
        if (isset($where['user_id']) && !empty($where['user_id']) && is_numeric($where['user_id'])) {

            $userId = $where['user_id'] ? $where['user_id'] : 0;

            $query = DB::table('student_course_master as scm')
                ->select(
                    'cm.id as course_id',
                    'cm.course_title',
                    'user.id as userId',
                    'cm.course_thumbnail_file',
                    'scm.course_expired_on',
                    'cm.category_id',
                    'cm.ects',
                    'scm.course_progress',
                    'scm.exam_remark',
                    'scm.exam_attempt_remain',
                    'scm.id as scmId',
                )
                ->leftJoin('users as user', 'scm.user_id', '=', 'user.id')
                ->leftJoin('course_master as cm', 'cm.id', '=', 'scm.course_id')
                ->where('scm.user_id', $userId)
                ->where('scm.is_deleted', 'No')
                ->where(function ($query) {
                    $query->where('scm.course_expired_on', '<', now()) // First check if course is expired
                        ->orWhere(function ($subQuery) { // If not expired, apply other conditions
                            $subQuery->where('scm.exam_remark', '=', '1')
                                     ->orWhere('scm.exam_attempt_remain', '=', 0);
                        });
                });
                // ->where(function ($query) {
                //     $query->where('scm.exam_remark', '=', '1')
                //         ->orWhere('scm.exam_attempt_remain', '=', 0);
                // });
                if (isset($where['ementor_id'])) {
                    $ementorId = $where['ementor_id'] ? $where['ementor_id'] : 0;
                    $query->where('cm.ementor_id', $ementorId);
                }

            $data = $query->get()->toArray();
        }
        return $data;
    }
}
// if (!function_exists('is_expired')) {
//     function is_expired($where)
//     {
//         $planDetails = '';
//         if (isset($where) && !empty($where) && is_array($where)) {
//             $currentDate = Carbon::now('Europe/Malta');
//             $today = $currentDate->format('Y-m-d');
//             $planDetails = DB::table('student_course_master')->Join('users','users.id','student_course_master.user_id')->where('is_active','Active')->where('course_expired_on', '>=', $today)->where($where)->count();
//         }
//         return $planDetails;
//     }
// }

if (!function_exists('is_expired')) {
    function is_expired($where)
    {
        $planDetails = '';
        if (isset($where) && !empty($where) && is_array($where)) {
            $currentDate = Carbon::now('Europe/Malta');
            $today = $currentDate->format('Y-m-d');

            $expiredCourse = DB::table('student_course_master')
                ->where($where)
                ->where('course_expired_on', '<', $today)
                ->where('exam_attempt_remain', 1)
                ->where('exam_remark', '0')
                ->first(); // Fetch only one record


            // Store result for checking purpose
            $result = [];

            if ($expiredCourse) { // Check if a record exists
                $result = [
                    'id' => $expiredCourse->id,
                    'original_expiry' => $expiredCourse->course_expired_on,
                    'new_expiry' => Carbon::parse($expiredCourse->course_expired_on)->addDays(15)->format('Y-m-d'),
                ];
                $today = $result['new_expiry'];
            }

            $planDetails = DB::table('student_course_master')
                ->join('users', 'users.id', '=', 'student_course_master.user_id')
                ->where($where)
                ->where('users.is_active', 'Active');
                if ($expiredCourse) {
                    $planDetails->where('course_expired_on', '<=', $today);
                }else{
                    $planDetails->where('course_expired_on', '>=', $today);
                }
                $planDetails = $planDetails->count();


        }
        return $planDetails;
    }
}

if (!function_exists('jobList')) {
    function jobList($table, $select, $where = '', $limit = '', $order_col = '', $order_dirc = 'DESC')
    {
        if (isset($table) && !empty($table) && isset($select) && is_array($select) && isset($where) && is_array($where)) {
            // $query = DB::table($table)->select($select)->where('is_deleted', 'No'); OLD
            $query = DB::table($table)->select($select); //New
            if (isset($where) && !empty($where) && $where != '' &&  is_array($where)) {
                $query->where($where);
            }
            if (isset($limit) && !empty($limit) && is_numeric($limit) && $limit != '') {
                $query->limit($limit);
            }
            if (isset($order_col) && !empty($order_col)) {
                $query->orderBy($order_col, $order_dirc);
            }
            $query->where('job_expired_on', '>=', Carbon::now('Europe/Malta')->format('Y-m-d'));
            $data = $query->get();
            return $data;
        }
    }
}
if (!function_exists('multiSelectDropdown')) {
    function multiSelectDropdown($table, $select, $keys)
    {
        if (isset($table) && !empty($table) && isset($keys) && is_array($keys)) {

            foreach ($keys as $key) {
                $data[] = DB::table($table)->select($select)->where('id', $key)->get()->toArray();
            }
            return $data;
        }
    }
}
if (!function_exists('getDataArray')) {
    function getDataArray($table, $select, $where)
    {
        if (isset($table) && !empty($table)) {
            $query =  DB::table($table)->select($select);
            if (isset($where) && count($where)  > 0 &&  is_array($where)) {
                $query->where($where);
            }
            $data = $query->get()->toArray();
            return $data;
        }
    }
}
if (!function_exists('userEmailExist')) {
    function userExist($table, $select)
    {

        $data = DB::table($table)->select($select)->where('is_deleted', 'No')->get();
        return $data;
    }
}
if (!function_exists('jobseekerAction')) {
    function jobseekerAction($table, $data, $where = '')
    {
        $exists = DB::table($table)->where($where)->count();

        if ($exists != 0) {
            $data = DB::table($table)->where($where)->update($data);
            return $data;
        } else {
            $data = DB::table($table)->insert($data);
            return $data;
        }
    }
}
if (!function_exists('is_exist')) {
    function is_exist($table, $where)
    {
        $data = 0;
        if (isset($table) && !empty($table) && isset($where) && is_array($where)) {
            $data = DB::table($table)->where($where)->count();
        }
        return $data;
    }
}
if (!function_exists('is_exists')) {
    function is_exists(Model $modelInstance, array $where)
    {
        $data = 0;
        if (isset($modelInstance) && !empty($modelInstance) && isset($modelInstance)) {
            $data = $modelInstance->where($where)->count();
        }
        return $data;
    }
}
if (!function_exists('processData')) {
    function processData($tableInfo, $data = [], $where = [])
    {

        $exists = 0;
        if (count($where) > 0) {
            $exists =  is_exist($tableInfo[0], $where);
        }
        if (isset($tableInfo) && is_array($tableInfo) && count($tableInfo) === 2) {
            $query = DB::table($tableInfo[0]);
            $primarykeyCol = isset($tableInfo[1])  ? $tableInfo[1] : 0;

            if (isset($exists) && is_numeric($exists) && $exists === 0) {
                if (isset($data) && is_array($data) && count($data) > 0) {
                    $getId =  $query->insertGetId($data);
                    if (isset($getId) && is_numeric($getId) && $getId > 0) {
                        return ['status' => TRUE, 'id' => $getId];
                    }
                }
                return FALSE;
            } elseif (isset($exists) && is_numeric($exists) && $exists > 0) {
                if (isset($where) && is_array($where) && count($where) > 0) {
                    $query->where($where);
                }
                $getId = $query->first($primarykeyCol)->$primarykeyCol;
                if (isset($data) && is_array($data) && count($data) > 0) {
                    $response = $query->update($data);
                    if (isset($response) && is_numeric($response)) {
                        return ['status' => TRUE, 'id' => $getId];
                    }
                }
                return FALSE;
            }
            return FALSE;
        }
        return FALSE;
    }
}
if (!function_exists('saveData')) {
    function saveData($modelInstance, $data = [], $where = [])
    {
        $exists = 0;
        if (count($where) > 0) {
            $exists =  is_exists($modelInstance, $where);
        }
        if (isset($modelInstance)) {
            if (isset($exists) && is_numeric($exists) && $exists === 0) {
                if (isset($data) && is_array($data) && count($data) > 0) {
                    $getId =  $modelInstance->create($data);
                    if (isset($getId) && is_numeric($getId->getKey()) && $getId->getKey() > 0) {
                        return ['status' => TRUE, 'id' => $getId->getKey()];
                    }
                }
                return FALSE;
            } elseif (isset($exists) && is_numeric($exists) && $exists > 0 && isset($where) && is_array($where) && count($where) > 0) {
                $getData = $modelInstance->where($where)->first();
                if (isset($data) && is_array($data) && count($data) > 0) {
                    $getData->update($data);
                    if (isset($getData) && is_numeric($getData->getKey()) && $getData->getKey() > 0) {
                        return ['status' => TRUE, 'id' => $getData->getKey()];
                    }
                }
                return FALSE;
            }
            return FALSE;
        }
    }
}

if (!function_exists('jobCount')) {
    function jobCount($table, $where)
    {
        if (isset($table) && !empty($table) && isset($where) && is_array($where)) {
            $data = DB::table($table)->where($where)->where('job_expired_on', '>=', Carbon::now('Europe/Malta')->format('Y-m-d'))->count();
            return $data;
        }
    }
}

if (!function_exists('isImageCorrupt')) {
    function isImageCorrupt($file)
    {
        $filePath = $file->getPathname();  // This returns the temporary path (string)

        $fileCorrupt = false;

        $imageInfo = @getimagesize($filePath);  // Suppresses warnings for corrupt images

        if ($imageInfo === false) {
            $mimeType = @mime_content_type($filePath);  // Get the MIME type of the file

            if ($mimeType === 'application/pdf') {
                $filePath = $file->getPathname();
                try {
                    $pdf = new Fpdi();
                    $pdf->setSourceFile($filePath);
                    $fileCorrupt = false;
                } catch (\Exception $e) {
                    $fileCorrupt = true;
                }
                // As per ankita's Suggestion (17-02-2025) addition of zip and plain text validation
            } elseif ($mimeType === 'application/zip') {
                // For ZIP files, we simply check if it's a valid ZIP archive
                try {
                    $zip = new \ZipArchive();
                    $res = $zip->open($file->getRealPath());
                    if ($res !== true) {
                        $fileCorrupt = true; // If it can't be opened, it's corrupt
                    }
                    $zip->close();
                } catch (\Exception $e) {
                    $fileCorrupt = true;
                }

            } elseif ($mimeType === 'text/plain') {
                // For text files, just check if they are not empty or null
                $fileContent = file_get_contents($file->getRealPath());
                if (empty($fileContent)) {
                    $fileCorrupt = true; // If the file is empty, it's considered corrupt
                }
            }else if ($mimeType === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
                $fileCorrupt = false;
            }else if ($mimeType === 'application/vnd.ms-excel1'){
                $fileCorrupt = false;
            }else if ($mimeType === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                $fileCorrupt = false;
            }else if ($mimeType === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet1") {
                $fileCorrupt = false;
            } else {
                // If the MIME type is not PDF, then the file is corrupt
                $fileCorrupt = true;
            }
        }

        return $fileCorrupt;  // Return true if corrupt, false if valid
    }
}

if (!function_exists('UploadFiles')) {
    function UploadFiles($file, $folder, $old_file = '')
    {
        if (isset($file) && !empty($file) && isset($folder) && !empty($folder)) {

            $CorruptFile = isImageCorrupt($file);
            if($CorruptFile === false){  // If not corrupt
                $filePath = $file->getRealPath();
                if (function_exists('passthru')) {
                    ob_start();
                    passthru("clamscan $filePath", $returnCode);
                    $scanResult = ob_get_clean();
                    if ($returnCode === 0 && str_contains($scanResult, 'OK')) {
                        return FALSE;
                    } else {
                $is_uploaded = Storage::disk('local')->putFile($folder, $file);
                    }
                } else {
                    $is_uploaded = Storage::disk('local')->putFile($folder, $file);
                }
                if (isset($is_uploaded) && !empty($is_uploaded)) {
                    if (!empty($old_file) && isset($old_file) && Storage::disk('local')->exists($old_file)) {
                        Storage::disk('local')->delete($old_file);
                    }
                    return ['status' => TRUE, 'url' => $is_uploaded];
                } else {
                    return TRUE;
                }
            } else {
                return FALSE; // Return false if the image is corrupt
            }
        } else {

            return FALSE;
        }
    }
}

if (!function_exists('mail_send')) {
    function mail_send($tmpl_id, $repl_contain, $repl_value, $sendto, $cc = null, $replyTo = null)
    {
        $exists = is_exist('unsubscribe_emails', ['email' => $sendto]);
        if (isset($exists) && is_numeric($exists) && $exists == 0) {
            $templContain = getData('email_templates', ['email_subject', 'email_content'], ['is_deleted' => 'No', 'id' => $tmpl_id]);
            $email_subject = $templContain[0]->email_subject;
            $email_content = $templContain[0]->email_content;
            $data['newSubject'] = str_replace($repl_contain, $repl_value, $email_subject);
            $data['newContain'] = str_replace($repl_contain, $repl_value, $email_content);
            $tes = send(
                $data['newSubject'],
                $data['newContain'],
                $sendto,
                $cc,
                $replyTo
            );
        }
    }
}
if (!function_exists('send')) {
    function send($subject, $sendingData, $sendto, $cc = null, $replyTo = null)
    {

        try {
            // Queue::push(new SendActionMails($subject, $sendingData, $sendto, $cc, $replyTo));
            SendActionMails::dispatch($subject, $sendingData, $sendto, $cc, $replyTo);
            return TRUE;
        } catch (\Exception $error) {
            return FALSE;
        }
    }
}
if (!function_exists('duration')) {
    function duration($diff_date)
    {

        $date = new Carbon($diff_date, 'Europe/Malta');
        if ($date->diffInYears() != 0) {
            if ($date->diffInYears() > 1) {
                return $date->diffInYears() . " Years";
            } else {
                return $date->diffInYears() . " Year";
            }
        } elseif ($date->diffInMonths() != 0) {
            if ($date->diffInMonths() > 1) {
                return $date->diffInMonths() . " Months";
            } else {
                return $date->diffInMonths() . " Month";
            }
        } elseif ($date->diffInWeeks() != 0) {
            if ($date->diffInWeeks() > 1) {
                return $date->diffInWeeks() . " Weeks";
            } else {
                return $date->diffInWeeks() . " Week";
            }
        } elseif ($date->diffInDays() != 0) {
            if ($date->diffInDays() > 1) {
                return $date->diffInDays() . " Days";
            } else {
                return $date->diffInDays() . " Day";
            }
        } elseif ($date->diffInHours() != 0) {
            if ($date->diffInHours() > 1) {
                return $date->diffInHours() . " Hours";
            } else {
                return $date->diffInHours() . " Hour";
            }
        } elseif ($date->diffInMinutes() != 0) {
            if ($date->diffInMinutes() > 1) {
                return $date->diffInMinutes() . " Minutes";
            } else {
                return $date->diffInMinutes() . " Minute";
            }
        } elseif ($date->diffInMinutes() === 0) {
            return "Just Now";
        }
    }
}

if(!function_exists('block_ipaddress')){
    function block_ipaddress(){
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $table = 'users';
        $where = ['block_status' => '1','last_session_ip' => $ipAddress];
        $exists = DB::table($table)->where($where)->count();
        if (isset($exists) && is_numeric($exists) && $exists > 0) {
            return TRUE;
        }else{
            return FALSE;
        }
    }
}

function getCountryCodeByIp()
{
    try {
        $ip = request()->header('X-Forwarded-For') ?? request()->ip();


        // $response = Http::get("https://api.myip.com?ip=$ip");
        // // $response = Http::get("https://api.myip.com");
        // echo"<pre>";
        // print_r($response->json());

        $responseData = Http::get("https://ipwho.is/{$ip}");

        if ($responseData->successful()) {
            $countryName = $responseData->json(['country']);

            $countryData = DB::table('country_master')
                            ->where('country_name', $countryName)
                            ->select('country_code', 'country_flag', 'country_name', 'id')
                            ->first();

            return [
                'country_code' => $countryData ? $countryData->country_code : '',
                'country_flag' => $countryData ? $countryData->country_flag : '',
                'country_name' => $countryData ? $countryData->country_name : '',
                'country_id' => $countryData ? $countryData->id : ''
            ];
        }
    } catch (\Exception $e) {
    }

    return [
        'country_code' => '',
        'country_flag' => '',
        'country_name' => '',
        'country_id' => ''
    ];
}

if (!function_exists('is_enrolled')) {
    function is_enrolled($studentId = null, $courseId = null,$where = null)
    {
        $query = DB::table('student_course_master')
            ->join('users', 'users.id', '=', 'student_course_master.user_id')
            ->join('orders', function($join) {
                $join->on('orders.user_id', '=', 'student_course_master.user_id')
                    ->on('orders.course_id', '=', 'student_course_master.course_id')
                    ->where('orders.status', '0');
            })
            ->where('users.is_active', 'Active')
            ->where('users.is_verified', 'Verified')
            ->where('student_course_master.is_deleted', 'No')
            ->where('block_status', '0')
            ->where('orders.status', '0');

        // Apply conditions based on the presence of $studentId and $courseId
        if ($studentId) {
            $query->where('student_course_master.user_id', $studentId);
        }
        if ($courseId) {
            $query->where('student_course_master.course_id', $courseId);
            $query->where('orders.course_id', $courseId);
        }
        if($where){
            $query->where($where);
            return $query->distinct()->count('student_course_master.user_id');
        }
        // Get distinct count based on orders.id to avoid duplicate enrollments
        return $query->distinct('orders.id')->count();
    }
}
if (!function_exists('is_purchased')) {
    function is_purchased($studentId = null, $courseId = null,$where = null)
    {
        $query = DB::table('student_course_master')
            ->join('users', 'users.id', '=', 'student_course_master.user_id')
            ->join('orders', function($join) {
                $join->on('orders.user_id', '=', 'student_course_master.user_id')
                    ->on('orders.course_id', '=', 'student_course_master.course_id')
                    ->where('orders.status', '0');
            })
            ->where('student_course_master.is_deleted', 'No')
            ->where('orders.status', '0');

        // Apply conditions based on the presence of $studentId and $courseId
        if ($studentId) {
            $query->where('student_course_master.user_id', $studentId);
        }
        if ($courseId) {
            $query->where('student_course_master.course_id', $courseId);
            $query->where('orders.course_id', $courseId);
        }
        if($where){
            $query->where($where);
        }
        return $query->distinct()->count('student_course_master.user_id');

        // Get distinct count based on orders.id to avoid duplicate enrollments
        // return $query->distinct('orders.id')->count();
    }
}
if (!function_exists('total_purchased_students')) {
    function total_purchased_students($studentId = null, $courseId = null,$where = null)
    {
        $query = DB::table('student_course_master')
            ->join('users', 'users.id', '=', 'student_course_master.user_id')
            ->join('orders', function($join) {
                $join->on('orders.user_id', '=', 'student_course_master.user_id')
                    ->on('orders.course_id', '=', 'student_course_master.course_id')
                    ->where('orders.status', '0');
            })
            ->where('users.is_active', 'Active')
            ->where('users.is_verified', 'Verified')
            ->where('student_course_master.is_deleted', 'No')
            ->where('block_status', '0')
            ->where('orders.status', '0');

        // Apply conditions based on the presence of $studentId and $courseId
        if ($studentId) {
            $query->where('student_course_master.user_id', $studentId);
        }
        if ($courseId) {
            $query->where('student_course_master.course_id', $courseId);
            $query->where('orders.course_id', $courseId);
        }
        if($where){
            $query->where($where);
        }
        // return $query->distinct()->count();

        // Get distinct count based on orders.id to avoid duplicate enrollments
        return $query->distinct('orders.id')->count();
    }
}

if(!function_exists('is_enrolled_upload_doc')){
    function is_enrolled_upload_doc($studentId,$courseId)
    {
        $data = 0;
        $where = [];
        if (isset($courseId) && !empty($courseId)) {
            $where = ['student_course_master.course_id'=>$courseId];
        }
        if(isset($studentId) && !empty($studentId)){
            $where = array_merge($where,['student_course_master.user_id'=>$studentId]);
        }
        $data = DB::table('student_course_master')
        ->join('student_doc_verification', 'student_doc_verification.student_id', '=', 'student_course_master.user_id')
        ->join('users', 'users.id', 'student_course_master.user_id')
        ->where('identity_doc_file', '!=', '')
        ->where('edu_doc_file', '!=', '')
        ->where('english_score','>=','10')
        ->whereNotNull('resume_file')
        ->where('is_active','Active')
        ->where(function ($query) {
            $query->where('identity_trail_attempt', '!=', '0')
                  ->orWhere('identity_is_approved', '=', 'Reject')
                  ->orWhere('identity_is_approved', '=', 'Approved');

        })
        ->where(function ($query) {
            $query->where('edu_trail_attempt', '!=', '0')
                  ->orWhere('edu_is_approved', '=', 'Reject')
                  ->orWhere('edu_is_approved', '=', 'Approved');

        })
        ->where($where)
        ->count();

        return $data;
    }
}

if (!function_exists('deleteRecord')) {
    function deleteRecord($model, $where, $setDeletedBy = false)
    {
        if (empty($model) || empty($where) || !is_array($where)) {
            return ['status' => false, 'message' => 'Invalid parameters provided.'];
        }

        $record = $model::where($where)->first();

        if ($record) {
            if ($setDeletedBy) {
                $record->deleted_by = Auth::id();
                $record->is_deleted = 'Yes';
                $record->save();
            }
            $record->delete();

            return ['status' => true, 'id' => $record->id];
        }

        return false;
    }
}

if (!function_exists('canDeleteExam')) {
    function canDeleteExam($courseId)
    {
        if (empty($courseId)) {
            return ['status' => false, 'message' => 'Invalid course ID.'];
        }

        $currentDate = now();

        $adjustedExpiryCondition = "IF(scm.exam_attempt_remain = 1, DATE_ADD(scm.course_expired_on, INTERVAL 15 DAY), scm.course_expired_on)";

        $enrollmentExists = DB::table('student_course_master as scm')
            ->where('scm.course_id', $courseId)
            ->where(function ($query) {
                $query->where('scm.exam_remark', '!=', '1')
                      ->orWhereNull('scm.exam_remark');
            })
            ->where('scm.exam_attempt_remain', '>', 0)
            ->whereRaw("$adjustedExpiryCondition > ?", [$currentDate])
            ->exists();

        if ($enrollmentExists) {
            return ['status' => false, 'message' => 'Cannot delete exam: students are enrolled and have remaining attempts.'];
        }

        return ['status' => true, 'message' => 'Exam can be deleted.'];
    }
}

if (!function_exists('checkAnalysisResults')) {
    function checkAnalysisResults($analysisId, $apiKey) {
        $client = new Client();
        $url = "https://www.virustotal.com/api/v3/analyses/{$analysisId}";

        $response = $client->get($url, [
            'headers' => [
                'x-apikey' => $apiKey,
            ],
        ]);

        $result = json_decode($response->getBody(), true);
        return $result;

        if (isset($result['data'])) {
            $analysisData = $result['data'];
            if ($analysisData['attributes']['status'] === 'completed') {
                foreach ($analysisData['attributes']['stats'] as $scanner => $result) {
                    echo "$scanner: " . $result['result'] . "\n";
                }
            } else {
                echo "The analysis is still in progress. Please check back later.\n";
            }
        } else {
            if (isset($result['error'])) {
                echo "Error: " . $result['error']['message'] . "\n";
            } else {
                echo "Unexpected response structure:\n";
                print_r($result);
            }
        }
    }
}

if (!function_exists('getExamType')) {
    function getExamType($type)
    {
        switch ($type) {
            case 1:
                return 'Assignment';
            case 2:
                return 'Mock Interview';
            case 3:
                return 'Vlog';
            case 4:
                return 'Peer Review';
            case 5:
                return 'Forum Leadership';
            case 6:
                return 'Reflective Journal';
            case 7:
                return 'Multiple Choice';
            case 8:
                return 'Survey';
            case 9:
                return 'Artificial Intelligence';
            case 10:
                return 'Homework';
            default:
                return '';
        }
    }
}

if (!function_exists('getCourseExamCount')) {
    function getCourseExamCount($course_id)
    {
        if (Auth::check()) {
            $course_id  = isset($course_id) ? base64_decode($course_id) : 0;
            $courseMaster = getData('course_master', ['category_id'], ['id' => $course_id]);

            if ($courseMaster[0]->category_id != 1) {
                $courseIds = DB::table('master_course_management')
                            ->where('award_id', $course_id)
                            ->where('is_deleted', 'No')
                            ->pluck('course_id');

                $examCount = DB::table('exam_management_master')
                            ->whereIn('course_id', $courseIds)
                            ->where(['is_deleted' => 'No'])
                            ->where('exam_type', '!=', 5)
                            ->count();
            } else {
                $examCount = DB::table('exam_management_master')
                            ->where(['course_id' => $course_id, 'is_deleted' => 'No'])
                            ->where('exam_type', '!=', 5)
                            ->count();
            }
            return $examCount;
        }
        return redirect('/login ');
    }
}

if (!function_exists('getStudentCourseMaster')) {
    function getStudentCourseMaster($userId, $courseId)
    {
        if (Auth::check()) {
            $userId  = isset($userId) ? base64_decode($userId) : 0;
            $courseId  = isset($courseId) ? base64_decode($courseId) : 0;
            return DB::table('student_course_master')
                ->select(['id', 'exam_remark'])
                ->where([
                    'user_id' => $userId,
                    'course_id' => $courseId,
                    'is_deleted' => 'No'
                ])
                ->latest('id')
                ->first();
        }
        return redirect('/login ');
    }
}

if (!function_exists('determineExamResult')) {
    function determineExamResult($examAttemptRemain, $submittedExamsCount, $courseExamCount, $courseId, $userId, $scmId)
    {
        $completedExamCount = DB::table('exam_remark_master')->where([
            // 'course_id' => $courseId,
            // 'user_id' => $userId,
            'student_course_master_id' => $scmId,
            'is_active' => '1',
            'is_cheking_completed' => '2',
        ])
        ->where('exam_type', '!=', 5)
        ->count();


        if ($completedExamCount == $courseExamCount) {

            $studentCourseMaster = DB::table('student_course_master')->select(['id', 'exam_remark'])->where([
                // 'user_id' => $userId,
                // 'course_id' => $courseId,
                'id' => $scmId,
                'is_deleted' => 'No'
            ])
            ->first();

            if ($studentCourseMaster) {
                return evaluateFinalExamStatus($studentCourseMaster->exam_remark);
            }
        }

        if ($examAttemptRemain == 2 && $submittedExamsCount == 0) {
            return ['result' => 'Not Attempt', 'color' => 'warning'];
        }
        if($submittedExamsCount == $courseExamCount){
            return ['result' => 'Checking', 'color' => 'primary'];
        }elseif ($submittedExamsCount < $courseExamCount) {

            $studentCourseMaster = DB::table('student_course_master')->select(['id', 'exam_remark'])->where([
                // 'user_id' => $userId,
                // 'course_id' => $courseId,
                'id' => $scmId,
                'is_deleted' => 'No'
            ])
            ->first();

            if ($examAttemptRemain == 1 && (!isset($studentCourseMaster->exam_remark) || $studentCourseMaster->exam_remark == 0)) {
                return ['result' => 'Pending', 'color' => 'warning'];

            }
            return fetchExamRemarkStatus($courseId, $userId, $scmId, 'Pending');
        }

        return ['result' => 'Checking', 'color' => 'primary'];
    }
}

if (!function_exists('fetchExamRemarkStatus')) {
    function fetchExamRemarkStatus($courseId, $userId, $scmId, $defaultStatus)
    {

        $studentCourseMaster = DB::table('student_course_master')->select(['id', 'exam_remark'])->where([
            // 'user_id' => $userId,
            // 'course_id' => $courseId,
            'id' => $scmId,
            'is_deleted' => 'No'
        ])
        ->first();

        if ($studentCourseMaster) {
            return evaluateFinalExamStatus($studentCourseMaster->exam_remark);
        }

        return ['result' => $defaultStatus, 'color' => 'warning'];
    }
}

if (!function_exists('evaluateFinalExamStatus')) {
    function evaluateFinalExamStatus($examRemark)
    {
        return match ($examRemark) {
            '0' => ['result' => 'Fail', 'color' => 'danger'],
            '1' => ['result' => 'Pass', 'color' => 'success'],
            default => ['result' => 'Pending', 'color' => 'warning'],
        };
    }
}

if (!function_exists('sendNotification')) {

    function sendNotification(array $userIds, array $data = [])
    {
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                $notificationData = $data;

                // $user->notify(new SendNotification($notificationData));
                $user->notify((new SendNotification($notificationData))->onQueue('notifications'));
                broadcast(new \App\Events\NotificationSent($user, $notificationData));
            }
        }
    }
}
if (!function_exists('course_data_enrolled')) {
    function course_data_enrolled()
    {
        $query = DB::table('student_course_master')
        ->join('course_master', 'student_course_master.course_id', '=', 'course_master.id')
        ->join('users', 'users.id', '=', 'student_course_master.user_id')
        ->join('orders', function($join) {
            $join->on('orders.user_id', '=', 'student_course_master.user_id')
                ->on('orders.course_id', '=', 'student_course_master.course_id')
                ->where('orders.status', '=', '0');
        })
        ->select(
            'student_course_master.course_id',
            'course_master.course_title',
            DB::raw('COUNT(DISTINCT orders.id) as aggregate'),
           'category_id','course_master.status','course_thumbnail_file','course_master.id','ects','course_old_price','course_final_price'
        )
        ->where('users.is_active', 'Active')
        ->where('users.is_verified', 'Verified')
        ->where('student_course_master.is_deleted', 'No')
        ->where('block_status', '0')
        ->where('orders.status', '0')
        ->whereNull('award_dba')
        ->groupBy('student_course_master.course_id','course_master.course_title',
        'course_master.category_id',
        'course_master.status',
        'course_master.course_thumbnail_file',
        'course_master.id',
        'course_master.ects',
        'course_master.course_old_price',
        'course_master.course_final_price')
        ->orderByDesc('aggregate')
        ->limit(4)
        ->get();
        return $query;
    }
}

if (!function_exists('getPdfWordCount')) {
    function getPdfWordCount($docFile)
    {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($docFile);

        $text = $pdf->getText();

        if (empty(trim($text))) {
            return [
                'word_count' => 0,
                'is_text_based' => false
            ];
        }

        $wordCount = str_word_count($text);

        return [
            'word_count' => $wordCount,
            'is_text_based' => true
        ];
    }
}


if (!function_exists('getExamTable')) {
    function getExamTable($examType) {
        $tableMap = [
            1 => 'exam_assignments',
            2 => 'exam_mock_interview',
            3 => 'exam_vlog',
            4 => 'exam_peer_review',
            5 => 'exam_discord',
            6 => 'exam_reflective_journals',
            7 => 'exam_mcq',
            8 => 'exam_survey',
            9 => 'exam_artificial_intelligence',
            10 => 'exam_homework'
        ];

        return $tableMap[$examType] ?? null;
    }
}
if (!function_exists('getAssignedSubMentor')) {
    function getAssignedSubMentor($scmId)
    {
        $assignedSubEmentor = DB::table('subementor_student_relations')
            ->where(['student_course_master_id' => $scmId])
            ->where('is_deleted', 'No')
            ->first();

        return $assignedSubEmentor ? $assignedSubEmentor->sub_ementor_id : null;
    }
}

if (!function_exists('awardCoursesCountByMasterCourseId')) {
    function awardCoursesCountByMasterCourseId($masterCourseId, $student_course_master_id)
    {
        $courseCount = DB::table('master_course_management')
            ->where(['optional_course_id' => null, 'award_id' => $masterCourseId, 'is_deleted' => 'No'])
            ->count();

        $studentCourseMaster = getData('student_course_master', ['preference_id', 'preference_status'], ['id' => $student_course_master_id]);
        if($studentCourseMaster[0]->preference_status == '0'){
            if($studentCourseMaster[0]->preference_id != null){
                $preferenceIds = $studentCourseMaster[0]->preference_id;
                $preferenceIdsArray = explode(',', $preferenceIds);
            }
            $courseCount += count($preferenceIdsArray);
        }

        return $courseCount;
    }
}

if (!function_exists('getCoursePromoCode')) {
    function getCoursePromoCode($courseId)
    {
        $today = Carbon::now(); // Current date and time
        if (Auth::check() && Auth::user()->role =='user'){
            $studentCourseMaster = getData('student_course_master',['course_expired_on','exam_attempt_remain','exam_remark'],['user_id' => Auth::user()->id, 'course_id'=> $courseId ,'is_deleted'=>'No'], "", 'created_at');
            if (isset($studentCourseMaster) && !empty($studentCourseMaster[0]) &&  $studentCourseMaster[0]->course_expired_on > now() && ( ($studentCourseMaster[0]->exam_attempt_remain == '1' && $studentCourseMaster[0]->exam_remark == '0') ||  $studentCourseMaster[0]->exam_attempt_remain == '2' )
            ) {
                    $coursePromoCode = [];
            }else{
                $exists = DB::table('coupons')->where('course_id',$courseId)->where('status','Active')->count();
                $coursePromoCode = [];
                if (isset($exists) && is_numeric($exists) && !empty($exists) && $exists > 0) {

                    $coursePromoCode = getData('coupons',['id', 'coupon_name'], ['course_id'=>$courseId,'is_deleted'=>'No','status'=>'Active',['coupon_validity', '>', $today],['institute_id', '=', '']]);
                }

            }
        }else{
            $exists = DB::table('coupons')->where('course_id',$courseId)->where('status','Active')->count();
            $coursePromoCode = [];
            if (isset($exists) && is_numeric($exists) && !empty($exists) && $exists > 0) {

                $coursePromoCode = getData('coupons',['id', 'coupon_name'], ['course_id'=>$courseId,'is_deleted'=>'No','status'=>'Active',['coupon_validity', '>', $today],['institute_id', '=', '']]);
            }

        }
        return isset($coursePromoCode) && !empty($coursePromoCode[0]) ? $coursePromoCode[0]->coupon_name : '';

    }
}

if (!function_exists('getExamTitle')) {
    function getExamTitle($examType, $examId)
    {
        if($examType == 5){
            return 'Forum Leadership';
        }
        $examTable = getExamTable($examType);
        // $column = ($examType == 1) ? 'assignment_tittle' : 'title';
        $column = ($examType == 1) ? 'assignment_tittle' : (($examType == 10) ? 'homework_title' : 'title');
        $examTitle = DB::table($examTable)
            ->where(['id' => $examId, 'is_deleted' => 'No'])
            ->select($column)
            ->first();

        return $examTitle ? ucfirst(html_entity_decode($examTitle->$column)) : null;

    }
}
if(!function_exists('blockedOnboarding')){
    function blockedOnboarding($email){
        $table = 'users';
        $where = ['email'=>$email];
        $UserData = getData('users',['role'],$where,'1','','');

        if (isset($UserData) && !empty($UserData) && count($UserData) > 0) {
            $role = $UserData[0]->role;
            $wherePer = [];
            if($role == 'user'){
                $wherePer = ['student'=>'login','status'=>'0'];
                $checkRole = "Student";
            }
            if($role == 'institute'){
                $wherePer = ['institute'=>'login','status'=>'0'];
                $checkRole = "Institute";
            }
            if($role == 'instructor'){
                $wherePer = ['ementor'=>'login','status'=>'0'];
                $checkRole = "Ementor";

            }
            $isExists = is_exist('permission',$wherePer);
            if (isset($isExists) && is_numeric($isExists) && $isExists === 1) {
                return $checkRole ? $checkRole : FALSE;
            }
        }else{
            return FALSE;
        }
    }

}

if (!function_exists('getExamAmount')) {
    function getExamAmount($courseId, $examType, $examId)
    {

        $examAmount = DB::table('exam_amounts')
            ->where(['course_id' => base64_decode($courseId), 'exam_id' => base64_decode($examId), 'exam_type' => base64_decode($examType), 'is_deleted' => 'No'])
            ->select('amount')
            ->first();

        return $examAmount ? $examAmount->amount : 0;

    }
}

if (!function_exists('generateIconPath')) {
    function generateIconPath($iconType)
    {
        $iconPaths = [
            'success' => asset('frontend/images/icons/Shield Check.gif'),
            'error' => asset('frontend/images/icons/Shield Cross.gif'),
            'warning' => asset('frontend/images/icons/exclamation mark.gif'),
        ];
        return isset($iconPaths[$iconType]) ? $iconPaths[$iconType] : '';
    }
}

if(!function_exists('alreadyAwardBuy')){
    function alreadyAwardBuy($studentId, $courseId){
        $MainCourseCheck =  FALSE;
        $MessageCheck = FALSE;
        $masterCourseData = DB::table('master_course_management')
        ->whereIn('course_id', $courseId)
        ->where('is_deleted', 'No')
        ->pluck('award_id');
        foreach($masterCourseData as $dataCheck){
            $studentMasterCheck = getData('student_course_master',['course_expired_on','course_id','course_progress','exam_remark','exam_attempt_remain','preference_id'],['course_id'=>$dataCheck, 'user_id'=>Auth::user()->id,'is_deleted'=>'No'],'','created_at','desc');
            if(isset($studentMasterCheck) && !empty($studentMasterCheck[0]) && $studentMasterCheck[0]->course_expired_on > now() && ($studentMasterCheck[0]->exam_attempt_remain != "0" && $studentMasterCheck[0]->exam_remark == '0'  || $studentMasterCheck[0]->exam_attempt_remain == "2" || $studentMasterCheck[0]->exam_attempt_remain == '1' && $studentMasterCheck[0]->exam_remark == '0' )){
                $MessageCheck = TRUE;
                $MainCourseCheck =  TRUE;
            }
        }
        if($MainCourseCheck == FALSE){
            $studentMasterCheckOptional = getData('student_course_master',['course_expired_on','course_id','course_progress','exam_remark','exam_attempt_remain','preference_id'],[ 'user_id'=>Auth::user()->id,['preference_id', '!=', null],'is_deleted'=>'No'],'','created_at','desc');
            foreach($studentMasterCheckOptional as $optionalCheck){
                $preference_id = $optionalCheck->preference_id;
                $preferenceIdsArray = explode(',', $preference_id);
                $commonValues = array_intersect($courseId, $preferenceIdsArray);
                if (!empty($commonValues)) {
                    $masterCourseDataOptional = DB::table('master_course_management')
                    ->whereIn('optional_course_id', $preferenceIdsArray)
                    ->pluck('award_id');
                    foreach($masterCourseDataOptional as $dataOptionalCheck){
                        $studentMasterCheckMaster = getData('student_course_master',['course_expired_on','course_id','course_progress','exam_remark','exam_attempt_remain','preference_id'],['course_id'=>$dataOptionalCheck, 'user_id'=>Auth::user()->id,'is_deleted'=>'No'],'','created_at','desc');
                        if(isset($studentMasterCheckMaster) && !empty($studentMasterCheckMaster[0]) && $studentMasterCheckMaster[0]->course_expired_on > now() && ($studentMasterCheckMaster[0]->exam_attempt_remain != "0" && $studentMasterCheckMaster[0]->exam_remark == '0'  || $studentMasterCheckMaster[0]->exam_attempt_remain == "2" || $studentMasterCheckMaster[0]->exam_attempt_remain == '1' && $studentMasterCheckMaster[0]->exam_remark == '0' )){
                            $MessageCheck = TRUE;
                        }
                    }
                }
            }
        }
        return $MessageCheck;
    }
}

if(!function_exists('getCourseStatus')){
    function getCourseStatus($course)
    {


        if (
            ($course->exam_remark == 1) || ($course->exam_remark == 0 && $course->exam_attempt_remain == 0) ||
            ($course->exam_remark == 0 && $course->exam_attempt_remain == 1 && Carbon::parse($course->adjusted_expiry)->lt(Carbon::today())) ||
            (is_null($course->exam_remark) && $course->exam_attempt_remain == 2 && Carbon::parse($course->adjusted_expiry)->lt(Carbon::today()))
        ) {
            return [
                'status' => 'Expired',
                'color' => 'danger'
            ];
        }

        // 3. Otherwise, In Progress
        return [
            'status' => 'In Progress',
            'color' => 'primary'
        ];
    }
}


if(!function_exists('getCourseSectionId')){
    function getCourseSectionId($section_name)
    {
        $sectionId = DB::table('course_section_masters')
            ->where(['section_name' => $section_name, 'is_deleted' => 'No'])
            ->select('id')
            ->first();

        return $sectionId ? $sectionId->id : 0;
    }
}

//Translate using DEEPL AI Translate.

    // function getOrTranslate($modelType, $modelId, $field, $text, $locale)
    // {
    //     if ($locale === 'en') return $text;

    //     $cacheKey = "{$modelType}_{$modelId}_{$field}_{$locale}";

    //     return Cache::remember($cacheKey, 3600, function () use ($modelType, $modelId, $field, $text, $locale) {
    //         $translation = Translation::where([
    //             'model_type' => $modelType,
    //             'model_id' => $modelId,
    //             'field' => $field,
    //             'locale' => $locale,
    //         ])->first();

    //         if ($translation) {
    //             return $translation->translated_text;
    //         }

    //         $translatedText = translateViaDeepL($text, $locale);

    //         Translation::create([
    //             'model_type' => $modelType,
    //             'model_id' => $modelId,
    //             'field' => $field,
    //             'locale' => $locale,
    //             'translated_text' => $translatedText,
    //         ]);

    //         return $translatedText;
    //     });
    // }

    function checkDeepLUsage() {
        $apiKey = env('DEEPL_API_KEY');

        $response = Http::withHeaders([
            'Authorization' => 'DeepL-Auth-Key ' . $apiKey,
        ])->get('https://api-free.deepl.com/v2/usage');

        if ($response->successful()) {
            $data = $response->json();
            return [
                'character_count' => $data['character_count'],
                'character_limit' => $data['character_limit'],
                'available' => ($data['character_count'] < $data['character_limit']),
            ];
        }

        return ['available' => false];
    }

    function translateViaDeepL($text, $locale)
    {
        $langMap = ['fr' => 'FR', 'es' => 'ES', 'zh' => 'ZH','ar'=>"AR"];
        //$langMap = ['es' => 'ES'];

        $targetLang = $langMap[$locale] ?? 'EN';

        try {
            $response = Http::asForm()->post('https://api-free.deepl.com/v2/translate', [
                'auth_key' => env('DEEPL_API_KEY'),
                'text' => $text,
                'target_lang' => $targetLang,
                'tag_handling' => 'html',
                'ignore_tags' => 'code',
            ]);

            if ($response->successful()) {
                return $response['translations'][0]['text'] ?? $text;
            }
        } catch (\Exception $e) {
            \Log::error("DeepL Translation Error: " . $e->getMessage());
        }

        return $text;
    }

    function getOrTranslate($model, $model_id, $field, $original_text, $locale = 'en')
    {
        if ($locale === 'en') {
            return $original_text;
        }

        // 1. Check if translation already exists
        $translation = DB::table('translations')
            ->where('model_type', $model)
            ->where('model_id', $model_id)
            ->where('field', $field)
            ->where('locale', $locale)
            ->value('translated_text');

        if ($translation) {
            return $translation;
        }

        // 2. Check DeepL usage before translating
        $usage = checkDeepLUsage();

        if (!$usage['available']) {
           # return "[Translation unavailable - limit reached]";
            Log::info("Translation unavailable - limit reached for lecture ID: $model_id, field: $model, locale: $locale");
           return $original_text;
        }

        // 3. Translate using DeepL
        $translated = translateViaDeepL($original_text, $locale);

        // 4. Save translation if it’s different
        if ($translated && $translated !== $original_text) {
            DB::table('translations')->insert([
                'model_type' => $model,
                'model_id' => $model_id,
                'field' => $field,
                'locale' => $locale,
                'translated_text' => $translated,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $translated;
    }

    function translateNestedCourse(array $course, string $locale = 'en'): array
    {
        if (!empty($course['course_manage']) && isset($course['course_manage'])) {
            foreach ($course['course_manage'] as &$manage) {
                if (!empty($manage['sections'])) {
                    foreach ($manage['sections'] as &$section) {
                        if (!empty($section['section_name'])) {
                            $section['section_name'] = getOrTranslate('Section', $section['id'], 'section_name', $section['section_name'], $locale);
                        }
                        foreach ($section['section_manage'] as &$section_manage){
                            if(!empty($section_manage['content_type_id']) && $section_manage['content_type_id'] === 2){
                                foreach ($section_manage['course_article'] as &$article){
                                    if (!empty($article['docs_title'])) {
                                        $article['docs_title'] = getOrTranslate('course_article', $article['id'], 'docs_title', $article['docs_title'], $locale);
                                    }
                                }
                            }
                            if(!empty($section_manage['content_type_id']) && $section_manage['content_type_id'] === 1){
                                foreach ($section_manage['course_video'] as &$videos){
                                    if (!empty($videos['video_title'])) {
                                        $videos['video_title'] = getOrTranslate('course_video', $videos['id'], 'video_title', $videos['video_title'], $locale);
                                    }
                                }
                            }
                            if(!empty($section_manage['content_type_id']) && $section_manage['content_type_id'] === 3){
                                foreach ($section_manage['course_quiz'] as &$quizes){
                                    if (!empty($quizes['quiz_tittle'])) {
                                        $quizes['quiz_tittle'] = getOrTranslate('course_quiz', $quizes['id'], 'quiz_tittle', $quizes['quiz_tittle'], $locale);
                                    }
                                }
                            }
                        }
                    

                       
                    }
                }
            }
        }
        // die;
        return $course;
    }

    function translateMasterCourse(array $course,string $locale = 'en') : array
    {
        if (!empty($course['master_course_manage']) && isset($course['master_course_manage'])) {
            foreach($course['master_course_manage'] as &$sortingManage){
                if(!empty($sortingManage['course_modules'][0]['id'])){
                    $sortingManage['course_modules'][0]['course_title'] = getOrTranslate('Course', $sortingManage['course_modules'][0]['id'], 'course_title', $sortingManage['course_modules'][0]['course_title'] ?? '', $locale);

                    foreach ($sortingManage['course_manage'] as &$manage) {
                        if (!empty($manage['sections'])) {
                            foreach ($manage['sections'] as &$section) {
                                if (!empty($section['section_name'])) {
                                    $section['section_name'] = getOrTranslate('Section', $section['id'], 'section_name', $section['section_name'], $locale);
                                }

                                if(!empty($section['section_manage'])){
                                    foreach ($section['section_manage'] as &$section_manage){
                                        if(!empty($section_manage['content_type_id']) && $section_manage['content_type_id'] === 2){
                                            foreach ($section_manage['course_article'] as &$article){
                                                if (!empty($article['docs_title'])) {
                                                    $article['docs_title'] = getOrTranslate('course_article', $article['id'], 'docs_title', $article['docs_title'], $locale);
                                                }
                                            }
                                        }
                                        // print_r($section_manage['course_video']);
                                        if(!empty($section_manage['content_type_id']) && $section_manage['content_type_id'] === 1){
                                            foreach ($section_manage['course_video'] as &$videos){
                                                if (!empty($videos['video_title'])) {
                                                    $videos['video_title'] = getOrTranslate('course_video', $videos['id'], 'video_title', $videos['video_title'], $locale);
                                                }
                                            }
                                        }
                                        if(!empty($section_manage['content_type_id']) && $section_manage['content_type_id'] === 3){
                                            foreach ($section_manage['course_quiz'] as &$quizes){
                                                if (!empty($quizes['quiz_tittle'])) {
                                                    $quizes['quiz_tittle'] = getOrTranslate('course_quiz', $quizes['id'], 'quiz_tittle', $quizes['quiz_tittle'], $locale);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $course;
    }
    function translateNestedAward(array $course, string $locale = 'en'): array
    {
        foreach ($course as &$manage) {
            if (!empty($manage['sections'])) {
                foreach ($manage['sections'] as $key => &$section) {
                    if (!empty($section['section_name'])) {
                        $section['section_name'] = getOrTranslate('Section', $section['id'], 'section_name', $section['section_name'], $locale);
                    }

                    // Optional: SectionManage → CourseArticle
                    foreach ($section['section_manage'] as &$section_manage){
                        if(!empty($section_manage['content_type_id']) && $section_manage['content_type_id'] === 2){
                            foreach ($section_manage['course_article'] as &$article){
                                if (!empty($article['docs_title'])) {
                                    $article['docs_title'] = getOrTranslate('course_article', $article['id'], 'docs_title', $article['docs_title'], $locale);
                                }
                            }
                        }
                        // Optional: SectionManage → CourseVideo

                        if(!empty($section_manage['content_type_id']) && $section_manage['content_type_id'] === 1){
                            foreach ($section_manage['course_video'] as &$videos){
                                if (!empty($videos['video_title'])) {
                                    $videos['video_title'] = getOrTranslate('course_video', $videos['id'], 'video_title', $videos['video_title'], $locale);
                                }
                            }
                        }
                        // Optional: SectionManage → CourseQuiz

                        if(!empty($section_manage['content_type_id']) && $section_manage['content_type_id'] === 3){
                            foreach ($section_manage['course_quiz'] as &$quizes){
                                if (!empty($quizes['quiz_tittle'])) {
                                    $quizes['quiz_tittle'] = getOrTranslate('course_quiz', $quizes['id'], 'quiz_tittle', $quizes['quiz_tittle'], $locale);
                                }
                            }
                        }
                    }
                

                }
            }
        }
        return $course;
    }
    function translateNestedMaster(array $course,string $locale = 'en') : array
    {
        $orientationAdded = false;
        if (!empty($course)) {
            foreach($course as &$sortingManage){
                $sortingManage['coursemodules'][0]['course_title'] = getOrTranslate('Course', $sortingManage['coursemodules'][0]['id'], 'course_title', $sortingManage['coursemodules'][0]['course_title'] ?? '', $locale);
                $hasValidSection = false; // flag to track if course has at least one valid section
                $newCourseManage = [];
                foreach ($sortingManage['course_manage'] as &$manage) {
                    if (!empty($manage['sections'])) {
                        $newSections = [];
                        foreach ($manage['sections'] as &$section) {
                            if ($section['section_name'] === "Orientation") {
                                if (!$orientationAdded && $section['id'] == 83) {
                                    $orientationAdded = true; // mark as added
                                } else {
                                    continue; // skip additional Orientation sections
                                }
                            }
                            if (empty($section['section_name'])) {
                                continue;
                            }
                            $hasValidSection = true; // at least one valid section found

                            if (!empty($section['section_name'])) {
                                $section['section_name'] = getOrTranslate('Section', $section['id'], 'section_name', $section['section_name'], $locale);
                            }

                            foreach ($section['section_manage'] as &$section_manage){
                                if(!empty($section_manage['content_type_id']) && $section_manage['content_type_id'] === 2){
                                    foreach ($section_manage['course_article'] as &$article){
                                        if (!empty($article['docs_title'])) {
                                            $article['docs_title'] = getOrTranslate('course_article', $article['id'], 'docs_title', $article['docs_title'], $locale);
                                        }
                                    }
                                }
                                // print_r($section_manage['course_video']);
                                if(!empty($section_manage['content_type_id']) && $section_manage['content_type_id'] === 1){
                                    foreach ($section_manage['course_video'] as &$videos){
                                        if (!empty($videos['video_title'])) {
                                            $videos['video_title'] = getOrTranslate('course_video', $videos['id'], 'video_title', $videos['video_title'], $locale);
                                        }
                                    }
                                }
                                if(!empty($section_manage['content_type_id']) && $section_manage['content_type_id'] === 3){
                                    foreach ($section_manage['course_quiz'] as &$quizes){
                                        if (!empty($quizes['quiz_tittle'])) {
                                            $quizes['quiz_tittle'] = getOrTranslate('course_quiz', $quizes['id'], 'quiz_tittle', $quizes['quiz_tittle'], $locale);
                                        }
                                    }
                                }
                            }
                            $newSections[] = $section;
                        }
                        $manage['sections'] = $newSections;
                        if (!empty($manage['sections'])) {
                            $newCourseManage[] = $manage;
                        }
                    }
                }
                if (!empty($newCourseManage)) {
                    $sortingManage['course_manage'] = $newCourseManage;
                    $filteredCourses[] = $sortingManage;
                }
            }
            $course = $filteredCourses;
        }
        return $course;
    }

    if (!function_exists('getTranslatedCourseTitle')) {
        function getTranslatedCourseTitle($courseId, $field = 'course_title') {
            $locale = app()->getLocale(); // e.g., 'en', 'zh', 'fr'

            // Default fallback
            if ($locale === 'en') return null;

            return Translation::where([
                'model_type' => 'Course',
                'model_id' => $courseId,
                'field' => $field,
                'locale' => $locale,
            ])->value('translated_text');
        }
    }

    if (!function_exists('getTranslatedLectureField')) {
        function getTranslatedLectureField($lectureId, $fieldName,$defaultValue) {
            $locale = app()->getLocale();

            if ($locale === 'en') return $defaultValue;;

         $translatedValue = DB::table('lecture_translations')
                ->where('lecture_id', $lectureId)
                ->where('field_name', $fieldName)
                ->where('lang_code', $locale)
                ->value('translated_value');

        if ($translatedValue) {
            return $translatedValue;
        }
        $usage = checkDeepLUsage();
        if (!$usage['available']) {
           # return "[Translation unavailable - limit reached]";
           Log::info("Translation unavailable - limit reached for lecture ID: $lectureId, field: $fieldName, locale: $locale");
           return $defaultValue;
        }
        $translatedValue = translateViaDeepL($defaultValue, $locale);
         DB::table('lecture_translations')->insert([
            'lecture_id' => $lectureId,
            'field_name' => $fieldName,
            'lang_code' => $locale,
            'translated_value' => $translatedValue,
        ]);
        // fallback to default value if translation missing
        return $translatedValue ?: $defaultValue;

        }
    }

    if (!function_exists('getTranslatedInstituteField')){

        function getTranslatedInstituteField($instituteId, $fieldName, $defaultValue) {
            $locale = app()->getLocale();
            if ($locale === 'en') {
                return $defaultValue;
            }

            $translatedValue = DB::table('institute_translations')
                ->where('institute_id', $instituteId)
                ->where('field_name', $fieldName)
                ->where('lang_code', $locale)
                ->value('translated_value');

            if ($translatedValue) {
                return $translatedValue;
            }
            $usage = checkDeepLUsage();
            if (!$usage['available']) {
            # return "[Translation unavailable - limit reached]";
            return $defaultValue;
            }
            $translatedValue = translateViaDeepL($defaultValue, $locale);

            // Store the translation in DB for future
            DB::table('institute_translations')->insert([
                'institute_id' => $instituteId,
                'field_name' => $fieldName,
                'lang_code' => $locale,
                'translated_value' => $translatedValue,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            // fallback to default value if translation missing
            return $translatedValue ?: $defaultValue;
        }
    }
    if (!function_exists('cleanHtmlContent')){
        function cleanHtmlContent($string) {

            // Convert <li> and <p> to new lines
            $string = htmlspecialchars_decode($string);

            // First, clean invisible characters
            $string = preg_replace('/[\x{FEFF}\x{200B}\x{00A0}\x{00AD}]/u', '', $string);

            // Extract only the <li> tags inside <ol>
            preg_match_all('/<li[^>]*>(.*?)<\/li>/i', $string, $matches);

            $output = '';

            if (!empty($matches[1])) {
                foreach ($matches[1] as $index => $item) {
                    // Strip inner tags inside <li>, preserve only text
                    $cleanItem = strip_tags($item);
                    $output .= ($index + 1) . ') ' . trim($cleanItem) . "\n";
                }
            } else {
                // If no <ol><li> structure found, remove all other tagsppp
                $output = strip_tags($string);
            }

            return trim($output);

        }
    }
    if (!function_exists('generateAttendanceCertificate')) {
        function generateAttendanceCertificate($student_id,$course_id)
        {

            $studentData = DB::table('student_course_master as scm')
                ->join('course_master as c', 'c.id', '=', 'scm.course_id')
                ->join('users as u', 'u.id', '=', 'scm.user_id')
                ->where('scm.user_id', $student_id)
                ->where('scm.course_id', $course_id)
                ->select('scm.course_id', 'scm.id as student_course_id', 'scm.course_progress', 'scm.progress_updated_at','scm.attendance_certificate', 'c.course_title','c.total_learning', 'u.name as student_name', 'u.last_name')
                ->first();
             $videoHours = getData('video_progress',['user_id','course_id',"duration"],['user_id'=>$student_id,'course_id'=>$course_id]);
             $totalMinutes = $videoHours->sum(function ($item) {
                        return (float) $item->duration;
                    });

            $totalHours = round($totalMinutes / 60, 2);

           //dd("Total Duration: {$totalHours} hours");

            if(!empty(trim($studentData->attendance_certificate))){
                return ['status' => false,'message' =>'Already Genrate' ];
            }
            if (!$studentData) {
                return ['status' => false, 'message' => 'No eligible course for certificate.'];
            }

            $templatePath = 'certificates/attendance/certificate.png';
            if (!Storage::exists($templatePath)) {
                return ['status' => false, 'message' => 'Template image not found.'];
            }
            $templatePath = storage_path('app/public/certificates/attendance/certificate.png');
            $fontBold = public_path('admin/fonts/gd_fonts/Roboto-Bold.ttf');
            $fontRegular = public_path('admin/fonts/gd_fonts/Roboto-Regular.ttf');

            $templateImage = imagecreatefrompng($templatePath);
            $black = imagecolorallocate($templateImage, 0, 0, 0);
            $blue = imagecolorallocate($templateImage, 43, 57, 144);
            $imageWidth = imagesx($templateImage) + 90;
            // Certificate title
            $certificateText = "Certificate Of Attendance";
            $bbox = imagettfbbox(60, 0, $fontRegular, $certificateText);
            $textWidth = $bbox[2] - $bbox[0];
            $x = ($imageWidth - $textWidth) / 2;
            imagettftext($templateImage, 60, 0, $x, 320, $black, $fontBold, $certificateText);

            // Issued on
            $issuedText = 'Issued on: ' . date('d F, Y', strtotime($studentData->progress_updated_at));
            $bbox = imagettfbbox(30, 0, $fontRegular, $issuedText);
            $textWidth = $bbox[2] - $bbox[0];
            $x = ($imageWidth - $textWidth) / 2;
            imagettftext($templateImage, 25, 0, $x, 400, $black, $fontBold, $issuedText);
            // Awarded to:
            $awardText = "This Certificate is Awarded to :";
            $bbox = imagettfbbox(30, 0, $fontRegular, $awardText);
            $textWidth = $bbox[2] - $bbox[0];
            $x = ($imageWidth - $textWidth) / 2;
            imagettftext($templateImage, 27, 0, $x, 480, $black, $fontRegular, $awardText);

            // Student Name
            $studentName = $studentData->student_name . ' ' . $studentData->last_name;
            $bbox = imagettfbbox(70, 0, $fontRegular, $studentName);
            $textWidth = $bbox[2] - $bbox[0];
            $x = ($imageWidth - $textWidth) / 2;
            imagettftext($templateImage, 60, 0, $x, 600, $blue, $fontRegular, $studentName);

            // Center underline
            $lineStartX = $x - 250;
            $lineEndX = $x + $textWidth + 250;
            imageline($templateImage, $lineStartX, 610 + 20, $lineEndX, 610 + 20, $black);

            $fontSize = 22;
            $lineHeight = 40;
            $y = 690;

            // Full text with bold placeholders
            $beforeText = "$studentName has successfully attended the ";
            $boldCourse = trim(Str::replaceFirst('Award in', '', $studentData->course_title));
            $afterText = " conducted by Eascencia, demonstrating dedication and commitment throught the 80-hours program.";
            //$hourse ="";

            $fullSentence = $beforeText . $boldCourse . $afterText;


            $wrapped = wordwrap($fullSentence, 80, "\n", true);
            $lines = explode("\n", $wrapped);


            foreach ($lines as $line) {
                $words = explode(' ', $line);
                $lineText = implode(' ', $words);
                $lineBox = imagettfbbox($fontSize, 0, $fontRegular, $lineText);
                $lineWidth = $lineBox[2] - $lineBox[0];
                $x = ($imageWidth - $lineWidth) / 2;

                foreach ($words as $word) {
                    $fontToUse = $fontRegular;

                    // Check if this word (cleaned) is the student name or course title
                    if (stripos($studentName, trim($word, ',.')) !== false || stripos($boldCourse, trim($word, ',.')) !== false) {
                        $fontToUse = $fontBold;
                    }

                    $wordBox = imagettfbbox($fontSize, 0, $fontToUse, $word . ' ');
                    $wordWidth = $wordBox[2] - $wordBox[0];

                    // Draw the word
                    imagettftext($templateImage, $fontSize, 0, $x, $y, $black, $fontToUse, $word . ' ');

                    $x += $wordWidth;
                }

                $y += $lineHeight;
            }
            // header('Content-Type: image/png');
            // imagepng($templateImage);
            // imagedestroy($templateImage);
            // exit;
            $filename = 'certificates/student_attendance_certificate/attendance_' . $student_id . '_' . $studentData->course_id . '.png';
            imagepng($templateImage, storage_path('app/public/' . $filename));
            imagedestroy($templateImage);

            // Save path to DB

            $update = processData(['student_course_master','id'],['attendance_certificate' => $filename],['user_id' => $student_id, 'course_id' => $studentData->course_id]);
            if(isset($update) && $update['status'] == TRUE){
            return ['status' => true, 'path' => $filename,'course_name'=>$studentData->course_title];
            }else{
                return ['status' => false, 'path' => 'Not Store File'];
            }
        }
    }
    if (!function_exists('getCategory')) {
        function getCategory($category) {
            $tableMap = [
                1 => __('static.Award'),
                2 => __('static.certificate_name'),
                3 => __('static.Diploma'),
                4 => __('static.Masters'),
                5 => __('static.DBA'),
                6 => __('footer.line_24'),
                7 => __('footer.line_25'),
                8 => __('footer.line_26'),
            ];

            return $tableMap[$category] ?? null;
        }
    }
// if (!function_exists('getDocumentStatusClass')) {
//     function getDocumentStatusClass($course, $doc,$player,$getExistMasterCourse = null): string
//     {
//         $category_id = $course->category_id;
//         $edu_level = $doc->edu_level ?? 0;
//         $edu_athe_approved = $doc->edu_athe_approved ?? 0;
//         $courseTitle = $course->course_title ?? '';
//         $isTarget = isTargetCourse($courseTitle);
//         $edu_master_approved= $doc->edu_master_approved ?? '';
//         // Handle special ATHE target course with category > 5
//         if ($category_id > 5 && $isTarget) {
//             if ($doc->edu_is_approved == "Approved" && $doc->identity_is_approved === "Approved" && $doc->english_score >= 10) {
//                 if($player == "video"){
//                     return '';
//                 }
//                 return 'examTab';
//             }elseif($doc->edu_doc_file != "" && $doc->identity_doc_file != "" && $doc->english_score >= 10){
//                 if($player == "video"){
//                     return '';
//                 }
//                 if($player == "admin"){
//                     return 'unverified'; 
//                 }
//                 return 'documentVerified';  
//             }elseif($doc->english_score >= 10 && ($doc->edu_doc_file == "" || $doc->identity_doc_file == "")){
//                 return 'documentNotUploadedDoc';
//             }elseif($doc->english_test_attempt == "0" && $doc->english_score < 10 && (($doc->edu_doc_file == "" && $doc->edu_trail_attempt == 0) || ($doc->identity_doc_file == "" && $doc->identity_trail_attempt == 0))){
//                 return 'documentNotEligible';
//             }elseif(($doc->identity_is_approved == "Reject" && $doc->identity_trail_attempt == 0 ) || ($doc->edu_is_approved == "Reject" &&$doc->edu_trail_attempt == 0)){
//                 return 'documentRejected';
//             }elseif($doc->edu_doc_file == "" && $doc->identity_doc_file == "" && $player == "admin"){
//                 return 'documentNotUploaded';
//             }elseif($doc->edu_doc_file == "" || $doc->identity_doc_file == ""){
//                 if($player == "video"){
//                     return '';
//                 }
//                 if($player == "admin"){
//                     return 'documentVerified'; 
//                 }
//                 return 'documentNotUploaded';  
//             }elseif($doc->edu_doc_file == "" || $doc->identity_doc_file == ""){
//                 return 'documentNotUploaded';  
//             }elseif($doc->english_test_attempt == "2"){
//                     return 'documentEnglishTestPending';  
//             }elseif ($doc->english_test_attempt == "1" && $doc->english_score < 10) {
//                 return 'englishVerified';
//             }elseif ($doc->english_test_attempt == "0" && $doc->english_score < 10) {
//                 return 'englishAttempt'; 
//             }elseif($doc->edu_doc_file != "" && $doc->identity_doc_file != "") {
//                 if($player == "admin"){
//                     if($doc->edu_is_approved === "Pending" || $doc->identity_is_approved === "Pending"){
//                         return 'unverified';
//                     }
//                 }
//             }else{
//                 return 'documentNotUploaded';
//             }         
//         }
//         if ($category_id > 5) {
//             if ($doc->identity_is_approved === "Approved") {
//                 if($player == "video"){
//                     return '';
//                 }
//                 return 'examTab';
//             }elseif($doc->identity_doc_file != ""){
//                 if($player == "video"){
//                     return '';
//                 }
//                 if($player == "admin"){
//                     return 'unverified'; 
//                 }
//                 return 'documentVerified';
//             }elseif($doc->identity_is_approved == "Reject" && $doc->identity_trail_attempt == 0 ){
//                 return 'documentRejected';
//             }else{
//                 return 'documentNotUploadedATHE';
//             }
//         }

//         // Determine table based on category and edu_level
//         // $useAtheTable = $edu_level <= 5 && !empty($doc->edu_athe_approved);
//         $useAtheTable =  $category_id < 5 && $edu_level >= 5 && !empty($edu_athe_approved);

//         $studentId = $doc->student_id;

//         if($category_id < 5){
//             if($category_id < 5 && $edu_level >= 5 && !empty($edu_athe_approved)){
//                 // echo "hii";
//                 $document = DB::table('student_doc_verification')->select('document_verification.edu_is_approved','document_verification.edu_trail_attempt',
//                 DB::raw('CASE WHEN document_verification.edu_level > 0 THEN document_verification.edu_level ELSE student_doc_verification.edu_level END as edu_level'),
//                 DB::raw("COALESCE(NULLIF(document_verification.edu_doc_file, ''), student_doc_verification.edu_doc_file) as edu_doc_file")
//                 ,'identity_is_approved','resume_file','english_score','english_test_attempt','identity_doc_file','identity_trail_attempt')->leftJoin('document_verification','document_verification.student_id','=','student_doc_verification.student_id')->where('student_doc_verification.student_id',$studentId)->first();
//             }else{
//                 // echo "bye";
//                 $document = DB::table('student_doc_verification')->select('edu_is_approved','edu_trail_attempt','edu_level','edu_doc_file','identity_is_approved','resume_file','english_score','english_test_attempt','identity_doc_file','identity_trail_attempt')->where('student_doc_verification.student_id',$studentId)->first();
//             }
//             $verified = empty($document) ? $doc : $document;

//             // print_r($verified);

//             if (!$verified) return 'documentNotUploaded';

//             $identity = $verified->identity_is_approved ?? '';
//             $edu = $verified->edu_is_approved ?? '';
//             $resume = $verified->resume_file ?? '';
//             $english = $verified->english_score ?? 0;
//             $edu_level = $verified->edu_level ?? '';
//             $attempt = $verified->english_test_attempt ?? '0';
//             $edu_doc_file = $verified->edu_doc_file ?? '';
//             $iden_doc_file = $verified->identity_doc_file ?? '';

            
//             if (
//                 $identity === "Approved" &&
//                 $edu === "Approved" &&
//                 $edu_level > 5 && 
//                 $resume !== '' &&
//                 $english >= 10
//             ) {
//                 if($player == "video"){
//                     return '';
//                 }
//                 return 'examTab';
//             }
//             if($iden_doc_file != ""){
//                 if (
//                     (!empty($verified->edu_doc_file) &&
//                     $verified->edu_level <= 5))
//                 {
//                     $documentStatus = DB::table('student_doc_verification')->select('edu_is_approved','resume_file')->where('student_doc_verification.student_id',$studentId)->first();
//                     if($documentStatus->edu_is_approved == 'Pending' && $documentStatus->resume_file != ""){
//                         if($player == "video"){
//                             return '';
//                         }
//                         if($player == "admin"){
//                             return 'unverified';
//                         }
//                         if($verified->english_test_attempt == "2"){
//                             return 'documentEnglishTestPending';  
//                         }
//                         return 'documentVerified';
//                     }
//                     if($doc->resume_file == ""){
//                         return 'documentNotUploaded'; 
//                     }
//                     return 'documentUploadGreaterSix';
//                 }

//                 // if (
//                 //     (!empty($verified->edu_doc_file) &&
//                 //     $verified->edu_level > 5) )
//                 // {
//                 //     return 'documentUploadGreaterSix';
//                 // }
//             }

//             if (
//                 !empty($verified->edu_doc_file) &&
//                 !empty($verified->identity_doc_file) &&
//                 !empty($verified->resume_file) &&
//                 $edu_level > 5  &&
//                 $english >= 10
//             ) {
//                 if($player == "video"){
//                     return '';
//                 }
//                 if($player == "admin"){
//                     if($documentStatus->edu_is_approved == 'Pending'){
//                         return 'unverified';
//                     }
//                 }
//                 return 'documentVerified';
//             }


//             if (
//                 ($identity === "Reject" && ($verified->identity_trail_attempt ?? 0) == 0) ||
//                 ($edu === "Reject" && ($verified->edu_trail_attempt ?? 0) == 0)
//             ) {
//                 return 'documentRejected';
//             }


//             if($english >= 10 && ($verified->edu_doc_file == "" || $verified->identity_doc_file == "" || $verified->resume_file == "")){

//                 return 'documentNotUploadedDoc';
//             }

//             if($attempt == "0" && $english < 10 && (($verified->edu_doc_file == "" && $verified->edu_trail_attempt == 0) || ($verified->identity_doc_file == "" && $verified->identity_trail_attempt == 0))){

//                 return 'documentNotEligible';
//             }

//             if($player == "admin"){
//                 if (!empty($verified->edu_doc_file) && !empty($verified->identity_doc_file) && $verified->resume_file != "")
//                 {
//                     if($verified->edu_is_approved === 'Pending' || $verified->identity_is_approved === 'Pending'){
//                         return 'unverified';
//                     }
//                 }
//             }
//             if($player == "exam"){
//                 if (!empty($verified->edu_doc_file) && !empty($verified->identity_doc_file) && $verified->resume_file != "")
//                 {
//                     if($verified->edu_is_approved === 'Pending' || $verified->identity_is_approved === 'Pending'){
//                         return 'documentVerified';
//                     }
//                 }
//             }
//             if($doc->edu_doc_file == "" && $doc->identity_doc_file == "" && $doc->resume_file == ""){

//                 return 'documentNotUploaded'; 
//             }
//             if($player == "admin"){               
//                 if($doc->edu_doc_file == "" || $doc->identity_doc_file == "" || $doc->resume_file == ""){
//                     return 'documentVerified'; 
//                 }
//             }
            
//             // if($verified->edu_doc_file == "" || $verified->identity_doc_file == ""  || $verified->resume_file == ""){

//             //     return 'documentNotUploaded';  
//             // }

//             if($verified->english_test_attempt == "2"){
//                 return 'documentEnglishTestPending';  
//             }

//             if ($attempt == "1" && $english < 10) {
//                 return 'englishVerified';
//             }

//             if ($attempt == "0" && $english < 10) {
//                 return 'englishAttempt';
//             }


//             return 'documentNotUploaded';
//         }

//         if($category_id == 5){
//             if($category_id == 5 && (($edu_level >= 6 && !empty($edu_master_approved)) || ($edu_level >= 5 && !empty($edu_athe_approved)))){
//                 $document = DB::table('student_doc_verification')->select('document_verification.edu_is_approved','document_verification.edu_trail_attempt',
//                 DB::raw('CASE WHEN document_verification.edu_level > 0 THEN document_verification.edu_level ELSE student_doc_verification.edu_level END as edu_level'),
//                 DB::raw("COALESCE(NULLIF(document_verification.edu_doc_file, ''), student_doc_verification.edu_doc_file) as edu_doc_file"),
//                 'identity_is_approved','resume_file','english_score','english_test_attempt','identity_doc_file','identity_trail_attempt','research_proposal_file','proposal_is_approved')->leftJoin('document_verification','document_verification.student_id','=','student_doc_verification.student_id')->where('student_doc_verification.student_id',$studentId)->first();
//             }else{
//                 $document = DB::table('student_doc_verification')->select('edu_is_approved','edu_trail_attempt','edu_level','edu_doc_file','identity_is_approved','resume_file','english_score','english_test_attempt','identity_doc_file','identity_trail_attempt','research_proposal_file','proposal_is_approved')->where('student_doc_verification.student_id',$studentId)->first();
//             }
//             $verified = empty($document) ? $doc : $document;

//             if (!$verified) return 'documentNotUploaded';

//             $identity = $verified->identity_is_approved ?? '';
//             $edu = $verified->edu_is_approved ?? '';
//             $resume = $verified->resume_file ?? '';
//             $english = $verified->english_score ?? 0;
//             $edu_level = $verified->edu_level ?? '';
//             $attempt = $verified->english_test_attempt ?? '0';
//             $edu_doc_file = $verified->edu_doc_file ?? '';
//             $iden_doc_file = $verified->identity_doc_file ?? '';
//             $proposal_is_approved = $verified->proposal_is_approved ?? '';


//             if ($category_id == 5 && count((array)$getExistMasterCourse) > 0) {
//                 if (
//                     $identity === "Approved" &&
//                     $edu === "Approved" &&
//                     $edu_level > 6 && 
//                     $resume !== '' &&
//                     $english >= 10 &&
//                     $proposal_is_approved === "Approved"
//                 ) {
//                     if($player == "video"){
//                         return '';
//                     }
//                     return 'examTab';
//                 }
//             }else{
//                 if($player ==  "exam"){
//                     return 'noMasterCourse';
//                 }
//                 if($player ==  "admin"){
//                     return 'noMasterCourse';
//                 }
//             }
            
//             if (
//                 $identity === "Approved" &&
//                 $edu === "Approved" &&
//                 $edu_level > 6 && 
//                 $resume !== '' &&
//                 $english >= 10 &&
//                 $proposal_is_approved === "Approved"
//             ) {
//                 if($player == "video"){
//                     return '';
//                 }
//                 return 'examTab';
//             }
            
//             if($iden_doc_file != ""){
//                 if (
//                     (!empty($verified->edu_doc_file) &&
//                     $verified->edu_level <= 6))
//                 {
//                     if($edu == 'Pending'){
//                         if($player == "video"){
//                             return '';
//                         }
//                         return 'documentVerified';
//                     }
//                     return 'documentUploadGreaterSix';
//                 }

//                 // if (
//                 //     (!empty($verified->edu_doc_file) &&
//                 //     $verified->edu_level > 6))
//                 // {
//                 //     return 'documentUploadGreaterSix';
//                 // }
//             }

//             if (
//                 !empty($verified->edu_doc_file) &&
//                 !empty($verified->identity_doc_file) &&
//                 !empty($verified->resume_file) &&
//                 $edu_level > 6  &&
//                 $english >= 10 &&
//                 !empty($verified->research_proposal_file)
//             ) {
//                 if($player == "video"){
//                     return '';
//                 }
//                 return 'documentVerified';
//             }


//             if (
//                 ($identity === "Reject" && ($verified->identity_trail_attempt ?? 0) == 0) ||
//                 ($edu === "Reject" && ($verified->edu_trail_attempt ?? 0) == 0) ||
//                 ($proposal_is_approved === "Reject")
//             ) {
//                 return 'documentRejected';
//             }


//             if($english >= 10 && ($verified->edu_doc_file == "" || $verified->identity_doc_file == "" || $verified->resume_file == "" || $verified->research_proposal_file == "")){

//                 return 'documentNotUploadedDoc';
//             }

//             if($attempt == "0" && $english < 10 && (($verified->edu_doc_file == "" && $verified->edu_trail_attempt == 0) || ($verified->identity_doc_file == "" && $verified->identity_trail_attempt == 0))){

//                 return 'documentNotEligible';
//             }

//             if($player == "admin"){
//                 if (!empty($verified->edu_doc_file) && !empty($verified->identity_doc_file) && $verified->resume_file != "" && !empty($verified->research_proposal_file))
//                 {
//                     if($verified->edu_is_approved === 'Pending' || $verified->identity_is_approved === 'Pending' || $verified->proposal_is_approved === 'Pending'){
//                         return 'unverified';
//                     }
//                 }
//             }

//             if($doc->edu_doc_file == "" && $doc->identity_doc_file == "" && $doc->resume_file == ""){

//                 return 'documentNotUploaded'; 
//             }

//             if($player == "admin"){
//                 if($doc->edu_doc_file == "" || $doc->identity_doc_file == "" || $doc->resume_file == ""){

//                     return 'documentVerified'; 
//                 }
//             }
            
//             if($verified->edu_doc_file == "" || $verified->identity_doc_file == ""  || $verified->resume_file == ""){

//                 return 'documentNotUploaded';  
//             }

//             if($verified->english_test_attempt == "2"){
//                 return 'documentEnglishTestPending';  
//             }

//             if ($attempt == "1" && $english < 10) {
//                 return 'englishVerified';
//             }

//             if ($attempt == "0" && $english < 10) {
//                 return 'englishAttempt';
//             }


//             return 'documentNotUploaded';
//         }
//     }
// }
if (!function_exists('getDocumentStatusClass')) {
    function getDocumentStatusClass($course, $doc,$player,$getExistMasterCourse = null): string
    {
        $category_id = $course->category_id;
        $edu_level = $doc->edu_level ?? 0;
        $edu_athe_approved = $doc->edu_athe_approved ?? 0;
        $courseTitle = $course->course_title ?? '';
        $isTarget = isTargetCourse($courseTitle);
        $edu_master_approved= $doc->edu_master_approved ?? '';
        // Handle special ATHE target course with category > 5
        // echo $isTarget;
        if ($category_id > 5 && $isTarget) {
            if ($doc->edu_is_approved == "Approved" && $doc->identity_is_approved === "Approved" && $doc->english_score >= 10) {
                if($player == "video"){
                    return '';
                }
                return 'examTab'; 
            }
            if($doc->english_test_attempt == "0" && $doc->english_score < 10 && (($doc->edu_doc_file == "" && $doc->edu_trail_attempt == 0) || ($doc->identity_doc_file == "" && $doc->identity_trail_attempt == 0))){
                return 'documentNotEligible';
            }
            if(($doc->identity_is_approved == "Reject" && $doc->identity_trail_attempt == 0 ) || ($doc->edu_is_approved == "Reject" &&$doc->edu_trail_attempt == 0)){
                return 'documentRejected';
            }
            if($doc->edu_doc_file == "" && $doc->identity_doc_file == "" && $doc->english_test_attempt == "2"){
                return 'documentNotUploaded'; 
            }
            if($doc->edu_doc_file != "" && $doc->identity_doc_file != ""){
                if($doc->english_test_attempt == 2){
                    return 'documentEnglishTestPending';
                }  
                if($doc->english_score >= 10){
                    if($doc->identity_is_approved == "Pending" || $doc->edu_is_approved == "Pending"){
                        if($player == "admin"){
                            return 'unverified';
                        }
                        if($player == "video"){
                            return '';
                        }
                        return 'documentVerified';  
                    }
                }
            }
            if($doc->edu_doc_file == "" || $doc->identity_doc_file == "" || $doc->english_test_attempt == "2"){
                if($doc->identity_is_approved == "pending" || $doc->edu_is_approved == "pending"){
                    if($player == "admin"){                  
                        return 'documentVerified';
                    }
                    return 'documentNotUploaded';  
                }else{
                    if($player == "admin"){
                        return 'documentVerified';
                    }
                    return 'documentNotUploaded'; 
                }
            }
          
            // if($doc->edu_doc_file != "" && $doc->identity_doc_file != "" && $doc->english_score >= 10){
            //     if($doc->identity_is_approved == "pending" || $doc->edu_is_approved == "pending"){
            //         if($player == "admin"){
            //             return 'unverified';
            //         }
            //         if($player == "video"){
            //             return '';
            //         }
            //         return 'documentVerified';  
            //     }
            // }
            if ($doc->english_test_attempt == "1" && $doc->english_score < 10) {
                return 'englishVerified';
            }
            if ($doc->english_test_attempt == "0" && $doc->english_score < 10) {
                return 'englishAttempt'; 
            }
    
        }
        if ($category_id > 5 && !$isTarget) {
            if ($doc->identity_is_approved === "Approved") {
                if($player == "video"){
                    return '';
                }
                return 'examTab';
            }elseif($doc->identity_doc_file != ""){
                if($player == "video"){
                    return '';
                }
                if($player == "admin"){
                    return 'unverified'; 
                }
                return 'documentVerified';
            }elseif($doc->identity_is_approved == "Reject" && $doc->identity_trail_attempt == 0 ){
                return 'documentRejected';
            }else{
                return 'documentNotUploadedATHE';
            }
        }

        // Determine table based on category and edu_level
        // $useAtheTable = $edu_level <= 5 && !empty($doc->edu_athe_approved);
        $useAtheTable =  $category_id < 5 && $edu_level >= 5 && !empty($edu_athe_approved);

        $studentId = $doc->student_id;

        if($category_id < 5){
            if($category_id < 5 && $edu_level >= 5 && !empty($edu_athe_approved)){
                // echo "hii";
                $document = DB::table('student_doc_verification')->select('document_verification.edu_is_approved','document_verification.edu_trail_attempt',
                DB::raw('CASE WHEN document_verification.edu_level > 0 THEN document_verification.edu_level ELSE student_doc_verification.edu_level END as edu_level'),
                DB::raw("COALESCE(NULLIF(document_verification.edu_doc_file, ''), student_doc_verification.edu_doc_file) as edu_doc_file")
                ,'identity_is_approved','resume_file','english_score','english_test_attempt','identity_doc_file','identity_trail_attempt')->leftJoin('document_verification','document_verification.student_id','=','student_doc_verification.student_id')->where('student_doc_verification.student_id',$studentId)->first();
            }else{
                // echo "bye";
                $document = DB::table('student_doc_verification')->select('edu_is_approved','edu_trail_attempt','edu_level','edu_doc_file','identity_is_approved','resume_file','english_score','english_test_attempt','identity_doc_file','identity_trail_attempt')->where('student_doc_verification.student_id',$studentId)->first();
            }
            $verified = empty($document) ? $doc : $document;

            // print_r($verified);

            if (!$verified) return 'documentNotUploaded';

            $identity = $verified->identity_is_approved ?? '';
            $edu = $verified->edu_is_approved ?? '';
            $resume = $verified->resume_file ?? '';
            $english = $verified->english_score ?? 0;
            $edu_level = $verified->edu_level ?? '';
            $attempt = $verified->english_test_attempt ?? '0';
            $edu_doc_file = $verified->edu_doc_file ?? '';
            $iden_doc_file = $verified->identity_doc_file ?? '';

            
            if (
                $identity === "Approved" &&
                $edu === "Approved" &&
                $edu_level > 5 && 
                $resume !== '' &&
                $english >= 10
            ) {
                if($player == "video"){
                    return '';
                }
                return 'examTab';
            }

            $documentStatus = DB::table('student_doc_verification')->select('edu_doc_file','edu_level','edu_is_approved')->where('student_doc_verification.student_id',$studentId)->first();
            if (!empty($documentStatus->edu_doc_file) && $documentStatus->edu_is_approved == "Approved" &&  $verified->identity_doc_file != "" && $verified->resume_file != "" && $verified->english_score >= 10 && $documentStatus->edu_level <= 5){
                if($verified->edu_is_approved == "Pending"){
                    if($player == "admin"){
                        return 'unverified';
                    }
                    if($player == "video"){
                        return '';
                    }
                    return 'documentVerified';  
                }
                return 'documentUploadGreaterSix';
            }

            if (
                ($identity === "Reject" && ($verified->identity_trail_attempt ?? 0) == 0) ||
                ($edu === "Reject" && ($verified->edu_trail_attempt ?? 0) == 0)
            ) {
                return 'documentRejected';
            }

            if(($attempt == "0" && $english < 10) || ($verified->edu_doc_file == "" && $verified->edu_trail_attempt == 0) || ($verified->identity_doc_file == "" && $verified->identity_trail_attempt == 0)){

                return 'documentNotEligible';
            }
            if($verified->edu_doc_file == "" && $verified->identity_doc_file == "" && $verified->resume_file == "" && $verified->english_test_attempt == "2"){

                return 'documentNotUploaded'; 
            }

            if($verified->edu_doc_file != "" && $verified->identity_doc_file != "" && $verified->resume_file != "" && $verified->english_score >= 10){
                if($verified->identity_is_approved == "Pending" || $documentStatus->edu_is_approved == "Pending"){
                    if($player == "admin"){
                        return 'unverified';
                    }
                    if($player == "video"){
                        return '';
                    }
                    return 'documentVerified';  
                }
            }
            
            if($verified->edu_doc_file != "" && $verified->identity_doc_file != "" && $verified->english_score >= 10){
                if($verified->resume_file == ""){
                    if($player == "admin"){
                        return 'documentVerified';
                    }
                    return 'documentNotUploadedDoc';  
                }
            }
            if($verified->edu_doc_file != "" && $verified->identity_doc_file != "" && $verified->resume_file != ""){
                if($verified->english_test_attempt == 2){
                    return 'documentEnglishTestPending';
                }  
                if($verified->english_score >= 10){
                    if($verified->identity_is_approved == "Pending" || $documentStatus->edu_is_approved == "Pending"){
                        if($player == "admin"){
                            return 'unverified';
                        }
                        if($player == "video"){
                            return '';
                        }
                        return 'documentVerified';  
                    }
                }
            }

            if($verified->edu_doc_file == "" || $verified->identity_doc_file == "" || $verified->resume_file == "" || $verified->english_test_attempt == "2"){
                if($verified->identity_is_approved == "Pending" || $documentStatus->edu_is_approved == "Pending"){

                    if($player == "admin"){

                        return 'documentVerified';
                    }
                    return 'documentNotUploaded';  
                }else{
                    return 'documentNotUploaded'; 
                }
            }
            
            if ($attempt == "1" && $english < 10) {
                return 'englishVerified';
            }

            if ($attempt == "0" && $english < 10) {
                return 'englishAttempt';
            }


            return 'documentNotUploaded';
        }

        if($category_id == 5){
            if($category_id == 5 && (($edu_level >= 6 && !empty($edu_master_approved)) || ($edu_level >= 5 && !empty($edu_athe_approved)))){
                $document = DB::table('student_doc_verification')->select('document_verification.edu_is_approved','document_verification.edu_trail_attempt',
                DB::raw('CASE WHEN document_verification.edu_level > 0 THEN document_verification.edu_level ELSE student_doc_verification.edu_level END as edu_level'),
                DB::raw("COALESCE(NULLIF(document_verification.edu_doc_file, ''), student_doc_verification.edu_doc_file) as edu_doc_file"),
                'identity_is_approved','resume_file','english_score','english_test_attempt','identity_doc_file','identity_trail_attempt','research_proposal_file','proposal_is_approved')->leftJoin('document_verification','document_verification.student_id','=','student_doc_verification.student_id')->where('student_doc_verification.student_id',$studentId)->first();
            }else{
                $document = DB::table('student_doc_verification')->select('edu_is_approved','edu_trail_attempt','edu_level','edu_doc_file','identity_is_approved','resume_file','english_score','english_test_attempt','identity_doc_file','identity_trail_attempt','research_proposal_file','proposal_is_approved')->where('student_doc_verification.student_id',$studentId)->first();
            }
            $verified = empty($document) ? $doc : $document;
            
            if (!$verified) return 'documentNotUploaded';

            $identity = $verified->identity_is_approved ?? '';
            $edu = $verified->edu_is_approved ?? '';
            $resume = $verified->resume_file ?? '';
            $english = $verified->english_score ?? 0;
            $edu_level = $verified->edu_level ?? '';
            $attempt = $verified->english_test_attempt ?? '0';
            $edu_doc_file = $verified->edu_doc_file ?? '';
            $iden_doc_file = $verified->identity_doc_file ?? '';
            $proposal_is_approved = $verified->proposal_is_approved ?? '';

    
            if (
                $identity == "Approved" &&
                $edu == "Approved" &&
                $edu_level > 6 && 
                $resume != '' &&
                $english >= 10 &&   
                $proposal_is_approved === "Approved"
            ) {
                return $player === "video" ? '' : 'examTab';
            }
            
            if (empty($getExistMasterCourse)) {
                if ($player === "video") {
                    if (
                        $identity === "Approved" &&
                        $edu === "Approved" &&
                        $edu_level > 6 && 
                        !empty($resume) &&
                        $english >= 10 &&
                        $proposal_is_approved === "Approved"
                    ) {
                        return '';
                    }
                } elseif (in_array($player, ["exam", "admin"])) {
                    return 'noMasterCourse';
                }
            }
            
            $documentStatus = DB::table('student_doc_verification')->select('edu_is_approved','edu_doc_file','edu_level')->where('student_doc_verification.student_id',$studentId)->first();
            if (!empty($documentStatus->edu_doc_file) && $documentStatus->edu_is_approved == "Approved" &&  $verified->identity_doc_file != "" && $verified->resume_file != "" && $verified->english_score >= 10 && $documentStatus->edu_level <= 6){
                if($verified->edu_is_approved == "Pending"){
                    if($player == "admin"){
                        return 'unverified';
                    }
                    if($player == "video"){
                        return '';
                    }
                    return 'documentVerified';  
                }
                return 'documentUploadGreaterSix';
            }
            
            if (
                ($identity === "Reject" && ($verified->identity_trail_attempt ?? 0) == 0) ||
                ($edu === "Reject" && ($verified->edu_trail_attempt ?? 0) == 0)
            ) {
                return 'documentRejected';
            }

            if(($attempt == "0" && $english < 10) || ($verified->edu_doc_file == "" && $verified->edu_trail_attempt == 0) || ($verified->identity_doc_file == "" && $verified->identity_trail_attempt == 0)){

                return 'documentNotEligible';
            }
            if($verified->edu_doc_file == "" && $verified->identity_doc_file == "" && $verified->resume_file == "" && $verified->english_test_attempt == "2"){

                return 'documentNotUploaded'; 
            }

            if($verified->edu_doc_file != "" && $verified->identity_doc_file != "" && $verified->resume_file != "" && $verified->english_score >= 10){
                if($verified->identity_is_approved == "Pending" || $documentStatus->edu_is_approved == "Pending" || $verified->proposal_is_approved == "Pending"){
                    if($player == "admin"){
                        return 'unverified';
                    }
                    if($player == "video"){
                        return '';
                    }
                    return 'documentVerified';  
                }
            }
                
            if($verified->edu_doc_file != "" && $verified->identity_doc_file != "" && $verified->english_score >= 10){
                if($verified->resume_file == ""){
                    if($player == "admin"){
                        return 'documentVerified';
                    }
                    return 'documentNotUploadedDoc';  
                }
                if($verified->research_proposal_file == ""){
                    if($player == "admin"){
                        return 'documentVerified';
                    }
                    return 'documentNotUploadedDoc';  
                }
            }
            if($verified->edu_doc_file != "" && $verified->identity_doc_file != "" && $verified->resume_file != ""){
                if($verified->english_test_attempt == 2){
                    return 'documentEnglishTestPending';
                }  
                if($verified->english_score >= 10){
                    if($verified->identity_is_approved == "Pending" || $documentStatus->edu_is_approved == "Pending" || $verified->proposal_is_approved == "Pending"){
                        if($player == "admin"){
                            return 'unverified';
                        }
                        if($player == "video"){
                            return '';
                        }
                        return 'documentVerified';  
                    }
                }
            }

            if($verified->edu_doc_file == "" || $verified->identity_doc_file == "" || $verified->resume_file == "" || $verified->english_test_attempt == "2"){
                if($verified->identity_is_approved == "Pending" || $documentStatus->edu_is_approved == "Pending"  || $verified->proposal_is_approved == "Pending"){

                    if($player == "admin"){

                        return 'unverified';
                    }
                    return 'documentNotUploaded';  
                }else{
                    return 'documentNotUploaded'; 
                }
            }
            
            if ($attempt == "1" && $english < 10) {
                return 'englishVerified';
            }

            if ($attempt == "0" && $english < 10) {
                return 'englishAttempt';
            }


            return 'documentNotUploaded';
        }
    }
}


if (!function_exists('canAccessExam')) {
    function canAccessExam($course, $doc,$player=''): bool
    {
        $class = getDocumentStatusClass($course, $doc, $player='');
        return $class === 'examTab';
    }
}

if (!function_exists('isTargetCourse')) {
    function isTargetCourse($courseTitle): bool
    {
        $targetCourses = [
            "ATHE Level 5 Extended Diploma in Business and Management (Gen. Ed.)",
            "ATHE Level 4 Extended Diploma in Business and Management (Gen. Ed.)",
            "ATHE Level 3 Extended Diploma in Business and Management (180 credits) 610/1765/6",
        ];

        return in_array(Str::lower($courseTitle), array_map('Str::lower', $targetCourses));
    }
}


if (!function_exists('getDocumentVerificationStatus')) {
    function getDocumentVerificationStatus($CourseId): string
    {
        $studentId = auth()->id();

        $course = DB::table('student_course_master')
            ->select('category_id', 'course_id', 'course_title')
            ->join('course_master', 'course_master.id', '=', 'student_course_master.course_id')
            ->where('course_id', $CourseId)
            ->where('student_course_master.is_deleted', 'No')
            ->first();

        if (!$course) return 'NotVerified';

        $doc_verified = getData('student_doc_verification', [
            'english_score', 'identity_is_approved', 'edu_is_approved', 'identity_doc_file',
            'edu_doc_file', 'resume_file', 'edu_trail_attempt', 'identity_trail_attempt',
            'english_test_attempt', 'edu_level', 'edu_athe_approved'
        ], ['student_id' => $studentId]);

        if (empty($doc_verified)) return 'NotVerified';

        $doc = $doc_verified[0];

        // Check if we need master document data
        $doc_verified_master = ($doc->edu_level < 5 && $doc->edu_athe_approved != '')
            ? getData('document_verification', [
                'edu_is_approved', 'edu_doc_file', 'edu_trail_attempt', 'edu_level'
              ], ['student_id' => $studentId])
            : [$doc];

        $masterDoc = $doc_verified_master[0];
        // Target Courses
        $targetCourses = [
            "ATHE Level 5 Extended Diploma in Business and Management (Gen. Ed.)",
            "ATHE Level 4 Extended Diploma in Business and Management (Gen. Ed.)",
            "ATHE Level 3 Extended Diploma in Business and Management (180 credits) 610/1765/6"
        ];

        $isTargetCourse = in_array(Str::lower($course->course_title), array_map('Str::lower', $targetCourses));
        $categoryId = $course->category_id;

        // Begin verification logic
        if (empty($doc->identity_doc_file)) {
            return 'NotVerified';
        }

        if ($doc->identity_is_approved !== 'Approved') {
            return 'NotVerified';
        }

        if ($categoryId < 5) {
            if (
                $masterDoc->edu_is_approved === 'Approved' &&
                $masterDoc->edu_level > 5 &&
                !empty($doc->resume_file) &&
                $doc->english_score >= 10
            ) return '';

            if (
                ($doc->identity_is_approved === 'Reject' && $doc->identity_trail_attempt == 0) ||
                ($masterDoc->edu_is_approved === 'Reject' && $masterDoc->edu_trail_attempt == 0)
            ) return '';

            if ($doc->english_test_attempt === "1" && $doc->english_score < 10) return 'englishVerified';

            if ($doc->english_test_attempt === "0" && $doc->english_score < 10) return 'englishAttempt';

            if (
                !empty($masterDoc->edu_doc_file) &&
                !empty($doc->identity_doc_file) &&
                !empty($doc->resume_file) &&
                $doc->english_score >= 10
            ) return '';

            return 'NotVerified';
        }

        if ($categoryId > 5 && $isTargetCourse) {
            if (
                $doc->edu_is_approved === 'Approved' &&
                $doc->edu_level <= 5 &&
                $doc->english_score >= 10
            ) return '';

            if ($doc->english_test_attempt === "1" && $doc->english_score < 10) return 'englishVerified';

            if ($doc->english_test_attempt === "0" && $doc->english_score < 10) return 'englishAttempt';

            if (
                !empty($doc->edu_doc_file) &&
                !empty($doc->identity_doc_file) &&
                $doc->english_score >= 10
            ) return '';

            return 'NotVerified';
        }

        // For other category_id > 5 (not in target course)
        return '';
    }
}

if (!function_exists('youtubeClient')) {
    function youtubeClient()
    {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID_YOUTUBE'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET_YOUTUBE'));
        $client->setRedirectUri(route('youtube.callback'));
        $client->setScopes(['https://www.googleapis.com/auth/youtube.upload']);

        // Use session token if available
        if (session()->has('youtube_access_token')) {
            $client->setAccessToken(session('youtube_access_token'));

            // Refresh token if expired
            if ($client->isAccessTokenExpired() && $client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                session(['youtube_access_token' => $client->getAccessToken()]);
            }
        }

        return new Google_Service_YouTube($client);
    }
}
if (!function_exists('getYoutubeViews')) {
    function getYoutubeViews($CourseId)
    {
        $course = CourseModule::find($CourseId);
        if (!$course || !$course->youtube_id) {
            return [
                'error' => 'Video not found for this course.'
            ];
        }

        $videoId = $course->youtube_id;
        $apiKey = env('GOOGLE_API_KEY');
        $videoId = $videoId;
        $url = "https://www.googleapis.com/youtube/v3/videos?part=statistics&id={$videoId}&key={$apiKey}"; 
        $response = file_get_contents($url);
        $data = json_decode($response, true); 
        $views = $data['items'][0]['statistics']['viewCount'] ?? 0; 
        return [
            'views' => $views ?? 0
        ];

        // $response = $youtube->videos->listVideos('statistics', [
        //     'id' => $videoId
        // ]);
        // if (empty($response->items)) {
        //     return [
        //         'error' => 'Video not found on YouTube.'
        //     ];
        // }

        // $stats = $response->items[0]->statistics;

        // return [
        //     // 'videoId' => $videoId,
        //     'views' => $stats->viewCount ?? 0
        //     // 'likes' => $stats->likeCount ?? 0,
        //     // 'comments' => $stats->commentCount ?? 0,
        // ];
    }

    if (!function_exists('InstallPaymentData')) {
        function InstallPaymentData($user_id,$course_id,$student_course_master_id,$total_amount)
        {
            $paymentInstall = DB::table('payment_installment')
            ->select(
                'paid_install_amount',
                'user_id',
                'course_id',
                'student_course_master_id',
                'paid_install_status',
                'total_amount'
            )->where('user_id',$user_id)
            ->where('course_id',$course_id)
            ->where('student_course_master_id',$student_course_master_id)
            ->where('paid_install_status','0')
            ->get();

            $payment_paid = 0;
            foreach($paymentInstall as $payment){
                $payment_paid += $payment->paid_install_amount;
            }
            if($total_amount == ''){
                return '';
            }
            if($payment_paid >= $total_amount){
                return 'FullPaymentDone';
            }else{
                return 'HalfPaymentDone';
            }    

        }
    }
    // if (!function_exists('ordinalSuffix')) {    
    //     function ordinalSuffix($number) {
    //         if (!in_array(($number % 100), [11, 12, 13])) {
    //             switch ($number % 10) {
    //                 case 1:  return $number . 'st';
    //                 case 2:  return $number . 'nd';
    //                 case 3:  return $number . 'rd';
    //             }
    //         }
    //         return $number . 'th';
    //     }
    // }
    if (!function_exists('ordinalSuffix')) {
        function ordinalSuffix($numbers) {
            // Handle comma-separated or array input
            if (is_string($numbers)) {
                $numbers = explode(',', $numbers);
            }
    
            if (!is_array($numbers)) {
                $numbers = [$numbers];
            }
    
            $formatted = [];
    
            foreach ($numbers as $num) {
                $n = abs((int) trim($num));
                $suffix = 'th';
                if (($n % 100) < 11 || ($n % 100) > 13) {
                    switch ($n % 10) {
                        case 1: $suffix = 'st'; break;
                        case 2: $suffix = 'nd'; break;
                        case 3: $suffix = 'rd'; break;
                    }
                }
                $formatted[] = $n . $suffix;
            }
    
            // Return comma-separated ordinals (e.g. "2nd, 3rd")
            return implode(', ', $formatted);
        }
    }

    if (!function_exists('buyNowDisabledClass')) {
        function buyNowDisabledClass()
        {
            $user = Auth::user();
    
            if (!$user) {
                return ''; // Not logged in — no disable
            }
    
            // 🔒 Only disable for this particular user (by email or ID)
            $disabledUsers = [
                env('Lockeduser')
            ];

            if (in_array($user->email, $disabledUsers)) {
                // Return a CSS class or inline style for disabling
                return 'user-access'; // custom class
            }
            
            return ''; // No disable
        }
    }
    if (!function_exists('isVideoLockedUser')) {
        /**
         * Returns true if the current user should have only the first video unlocked
         */
        function isVideoLockedUser()
        {
            $user = Auth::user();
            if (!$user) return false;
    
            $lockedUsers = [ env('Lockeduser')]; // user IDs for whom only first video is unlocked
            return in_array($user->email, $lockedUsers);
        }
    }
   
    if (!function_exists('calculateWatchProgress')) {
        function calculateWatchProgress($watchRanges, $duration, $fullCheck = 'No')
        {
            if ($fullCheck === 'Yes') {
                return [
                    'watched_seconds' => $duration,
                    'duration_seconds' => $duration,
                    'percentage' => 100,
                    'formatted' => formatTime($duration) . ' / ' . formatTime($duration) ,
                ];
            }

            $totalWatched = 0;

            if (!empty($watchRanges)) {
                $ranges = json_decode($watchRanges, true);

                if (is_array($ranges)) {
                    foreach ($ranges as $range) {
                        $totalWatched += ($range['end'] - $range['start']);
                    }
                }
            }

            $percentage = $duration > 0 ? ($totalWatched / $duration) * 100 : 0;

            return [
                'watched_seconds' => round($totalWatched, 2),
                'duration_seconds' => round($duration, 2),
                'percentage' => round($percentage, 2),
                'formatted' => formatTime($totalWatched) . ' / ' . formatTime($duration),
            ];
        }
    }
    if (!function_exists('formatTime')) {

        function formatTime($seconds)
        {
            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            $secs = floor($seconds % 60);

            if ($hours > 0) {
                return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
            }
            return sprintf('%d:%02d', $minutes, $secs);
        }
    }

    if (!function_exists('calculateSectionProgress')) {
        function calculateSectionProgress($section_manage, $userId, $courseId, $studentCourseId, $watchContentIds,$awardId='',$sectionName='')
        {
            $totalItems = 0;
            $completedItems = 0;
            $totalPercentage = 0;
            // Convert "pd_1,qu_3" → array
            $watchedArray = !empty($watchContentIds) ? explode(',', $watchContentIds) : [];
            foreach ($section_manage as $item) {
                if (empty($item['content_type_id'])) continue;

                /** 🎬 VIDEO ITEMS **/
                if ($item['content_type_id'] == 1 && !empty($item['course_video'])) {
                    foreach ($item['course_video'] as $video) {

                            //$CourseIdAward = $awardId;
                        $CourseIdAward = !empty($awardId) ? $awardId : $courseId;
                        // echo $CourseIdAward;
                        $totalItems++;
                        $progress = DB::table('video_progress')
                            ->where('video_id', $video['id'])
                            ->where('course_id', $CourseIdAward)
                            ->where('user_id', $userId)
                            ->where('student_course_master_id', $studentCourseId)
                            ->first(['full_check', 'duration', 'watchedRanges']);
                        if ($progress) {
                            if ($progress->full_check === 'Yes') {
                                $percentage = 100;
                            } else {
                                $result = calculateWatchProgress($progress->watchedRanges, $progress->duration, $progress->full_check);
                                $percentage = $result['percentage'];
                            }
                            $totalPercentage += $percentage;
                            $completedItems++;
                        }
                    }
                }
    
                /** 📄 PDF ITEMS **/
                if ($item['content_type_id'] == 2 && !empty($item['course_article'])) {
                    foreach ($item['course_article'] as $pdf) {
                        $totalItems++;
                        $pdfKey = 'pd_' . $pdf['id'];
                        $docKey = 'do_' . $pdf['id'];
                        $isWatched = in_array($pdfKey, $watchedArray) || in_array($docKey, $watchedArray);
                        $percentage = $isWatched ? 100 : 0;
                        $totalPercentage += $percentage;
                        if ($percentage >= 100) $completedItems++;
                    }
                }
    
                /** 🧩 QUIZ ITEMS **/
                if ($item['content_type_id'] == 3 && !empty($item['course_quiz'])) {
                    foreach ($item['course_quiz'] as $quiz) {
                        $totalItems++;
                        $contentKey = 'qu_' . $item['content_id'];
                        $percentage = in_array($contentKey, $watchedArray) ? 100 : 0;
                        $totalPercentage += $percentage;
                        if ($percentage >= 100) $completedItems++;
                    }
                }
            }

            $avgPercent = $totalItems > 0 ? $totalPercentage / $totalItems : 0;

            return [
                'total_items' => $totalItems,
                'completed_items' => $completedItems,
                'percentage' => round($avgPercent, 2),
                'formatted' => round($avgPercent, 2) . '%',
            ];
        }
    }
    if (!function_exists('calculateCourseProgress')) {
        function calculateCourseProgress($allSections, $userId, $courseId, $studentCourseId, $watchContentIds, $awardId = '')
        {
            $totalSections = 0;
            $sumOfSectionPercentages = 0;
            foreach ($allSections as $sectionName => $section_manage) {
                $sectionResult = calculateSectionProgress(
                    $section_manage['sections'][0]['section_manage'],
                    $userId,
                    $courseId,
                    $studentCourseId,
                    $watchContentIds,
                    $awardId,
                    $sectionName
                );
                $totalSections++;
                $sumOfSectionPercentages += $sectionResult['percentage'];
            }

            $coursePercent = ($totalSections > 0) ? $sumOfSectionPercentages / $totalSections : 0;
            return [
                'total_items'     => $totalSections,
                'percentage'      => round($coursePercent, 2),
                'formatted'       => round($coursePercent, 2) . '%',
            ];
        }
    }

    if (!function_exists('calculateOverallCourseProgress')) {
        function calculateOverallCourseProgress($allCourses, $userId, $studentCourseId, $watchContentIds)
        {
            $totalCourses = 0;
            $sumCoursePercentages = 0;
            foreach ($allCourses as $courseId => $courseData) {
                // each course has sections
                $courseResult = calculateCourseProgress(
                    $courseData['course_manage'],
                    $userId,
                    $courseId,
                    $studentCourseId,
                    $watchContentIds,
                    $courseData['award_id'] ?? ''
                );
    
                $sumCoursePercentages += $courseResult['percentage'];
                $totalCourses++;
            }
    
            $overall = ($totalCourses > 0) ? $sumCoursePercentages / $totalCourses : 0;
            return [
                'total_courses' => $totalCourses,
                'percentage'    => round($overall, 2),
                'formatted'     => round($overall, 2) . '%'
            ];
        }
    }

    if (!function_exists('purchaseTotalAmount')) {
        function purchaseTotalAmount($student_course_master_id,$course_id)
        {
            return DB::table('student_course_master')
            ->leftJoin('payments', 'payments.id', '=', 'student_course_master.payment_id')
            ->leftJoin('payment_installment', 'payment_installment.payment_id', '=', 'payments.id')
            ->leftJoin('orders', 'orders.payment_id', '=', 'payments.id')
            ->where('student_course_master.id', $student_course_master_id)
            ->where('student_course_master.course_id', $course_id)
            ->whereRaw("
                IF(
                    (payments.installment_status = 'FullPayment'
                    OR payments.installment_status IS NULL),
                    payments.status,
                    payment_installment.paid_install_status
                ) COLLATE utf8mb4_general_ci = '0'
            ")
            ->selectRaw("
                CASE 
                    WHEN payments.installment_status = 'FullPayment' 
                        OR payments.installment_status IS NULL
                    THEN ROUND(
                            orders.course_price - 
                            CASE 
                                WHEN orders.promo_code_discount IS NOT NULL AND orders.promo_code_discount > 0 
                                THEN orders.promo_code_discount 
                                ELSE 0 
                            END
                    )
                    ELSE (
                        SELECT SUM(pi.paid_install_amount)
                        FROM payment_installment pi
                        WHERE paid_install_status = '0'
                        AND pi.payment_id = payments.id
                    )
                END AS total_amount
            ")
            ->value('total_amount');
        }
    }
}




