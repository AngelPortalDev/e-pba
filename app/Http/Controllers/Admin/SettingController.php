<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Redirect, Validator, Storage, DB, Hash};
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use File;
use App\Rules\WordCount;
class SettingController extends Controller
{

    public function BlocksList($cat = '')
    {
        if (Auth::check()) {

            if (isset($cat) && !empty($cat) && $cat == 'all'){
                // $userBlockData = getData('users', ['last_session_ip','id'], ['block_status' => '1']);
                $userBlockData = DB::table('users')->select(['last_session_ip'])->where('block_status','1')->groupBy('last_session_ip')->get();
                return response()->json($userBlockData);
            }else{
                 return view('admin.settings.blocklist');
            }
        }
        return redirect('/login');
    }


    public function addIPblock(Request $request)
    {
        if ($request->isMethod('POST') && $request->ajax() && Auth::check()) {
            $ipaddress = isset($request->ipaddress) ? htmlspecialchars($request->input('ipaddress')) : '';

            try {
                $data = $request->validate([
                    'ipaddress' => ['required', 'string'],
                ],[
                    'ipaddress.required' => 'Please enter IP address',
                ]);

            } catch (ValidationException $e) {
                // Validation failed, return the validation errors
                $errors = $e->validator->errors();
                return response()->json([
                    'code' => 202,
                    'title' => 'Required Fields are Missing.',
                    'message' => 'Please Provide Required Info',
                    'icon' => generateIconPath('error'),
                    'data' => $errors
                ]);
            }
            $where = ['last_session_ip' => $ipaddress];
            $exists = is_exist('users', $where);

            if (isset($exists) && $exists > 0) {
                $where = ['ipaddress' => $ipaddress];
                $exists = is_exist('user_block_list', $where);

                $whereUser = ['block_status' => '0','last_session_ip'=>$ipaddress];
                $existsBlock = is_exist('users', $whereUser);

                if (isset($exists) && $exists == 0 && isset($existsBlock) && $existsBlock > 0) {
                    $i=0;
                    $userBlockData = getData('users', ['id'], ['last_session_ip' => $ipaddress,'block_status'=>'0']);

                    foreach($userBlockData as $key => $value){

                        $selectUser = [
                            'block_status' => '1'
                        ];
                        $updateUser = processData(['users', 'id'], $selectUser,['id' => $value->id]);
                    }

                    $select = [
                        'ipaddress' => $ipaddress,
                        'created_by' => Auth::user()->id
                    ];
                    $updateBlockList = processData(['user_block_list', 'id'], $select);

                    if ((isset($updateBlockList) && $updateBlockList !== FALSE) && (isset($updateUser)  && $updateUser !== FALSE)){
                        $i++;
                    }

                    if ($i > 0) {
                        return json_encode(['code' => 200, 'title' => "Successfully blocked", 'message' => 'User blocked successfully', "icon" => generateIconPath("success")]);
                    } else {
                        return json_encode(['code' => 201, 'title' => "Unable to Block", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
                    }
                } else {
                    return json_encode(['code' => 201, 'title' => 'Ip address blocked', 'message' => 'Ip address alredy blocked.', 'remark' => 'warning', "icon" => generateIconPath("error")]);

                }
            } else {
                return json_encode(['code' => 201, 'title' => 'Ip address not available', 'message' => 'Ip address does not exist.', 'remark' => 'warning', "icon" => generateIconPath("error")]);

            }
        } else {
            return json_encode(['code' => 201, 'title' => 'Something Went Wrong', 'message' => 'Please Provide Required Info', "icon" => generateIconPath("error")]);
        }
    }


    public function unblockIPadd(Request $request)
    {
        if ($request->isMethod('POST') && $request->ajax() && Auth::check()) {
            $ipaddress = isset($request->ipaddress) ? htmlspecialchars($request->input('ipaddress')) : '';

            try {
                $data = $request->validate([
                    'ipaddress' => ['required', 'string'],
                ],[
                    'ipaddress.required' => 'Please enter IP address',
                ]);

            } catch (ValidationException $e) {
                // Validation failed, return the validation errors
                $errors = $e->validator->errors();
                return response()->json([
                    'code' => 202,
                    'title' => 'Required Fields are Missing.',
                    'message' => 'Please Provide Required Info',
                    'icon' => 'error',
                    'data' => $errors
                ]);
            }
            $where = ['last_session_ip' => $ipaddress,'block_status' => '1'];
            $exists = is_exist('users', $where);

            $where = ['ipaddress' => $ipaddress];
            $existsBlock = is_exist('user_block_list', $where);

            if (isset($exists) && $exists > 0 && isset($existsBlock) && $existsBlock > 0 ) {

                $updateBlockList = DB::table('user_block_list')->where('ipaddress',$ipaddress)->delete();

                $userBlockData = getData('users', ['id'], ['last_session_ip' => $ipaddress,'block_status'=>'1']);

                foreach($userBlockData as $key => $value){
                    $selectUser = [
                        'block_status' => '0'
                    ];
                    $updateUser = processData(['users', 'id'], $selectUser,['id' => $value->id]);
                }

                if ((isset($updateUser) && $updateBlockList > 0 && $updateUser !== FALSE)){

                    return json_encode(['code' => 200, 'title' => "Successfully unblocked", 'message' => 'IP address unblocked successfully.', "icon" => generateIconPath("success")]);

                } else {

                    return json_encode(['code' => 201, 'title' => "Unable to Block", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
                }

            } else {
                return json_encode(['code' => 201, 'title' => 'Already unlocked.', 'message' => 'Ip address already unblocked.', 'remark' => 'warning', "icon" => generateIconPath("warning")]);

            }
        } else {
            return json_encode(['code' => 201, 'title' => 'Something Went Wrong', 'message' => 'Please Provide Required Info', "icon" => generateIconPath("error")]);
        }
    }

    public function BoardingPermission(Request $request){
        if ($request->isMethod('POST') && $request->ajax() && Auth::check()) {
            $content = isset($request->content) ? base64_decode($request->input('content')) : '';
            $status = isset($request->status) ? base64_decode($request->input('status')) : '';

            // if($content == "inst_reg"){
            //     $where = ['institute' => 'register'];
            //     $exists = is_exist('permission', $where);
            // }
            $status = $status == 'Active' ? '0' : '1';
                if($content == "inst_reg"){
                    $where = ['institute' => 'register'];
                    $blockContent = 'register';
                    $select = [
                        'status' => $status,
                        'institute' => $blockContent
                    ];
                }
                if($content == "inst_log"){
                    $where = ['institute' => 'login'];
                    $blockContent = 'login';
                    $select = [
                        'status' => $status,
                        'institute' => $blockContent
                    ];
                }

                if($content == "stu_log"){
                    $where = ['student' => 'login'];
                    $blockContent = 'login';
                    $select = [
                        'status' => $status,
                        'student' => $blockContent
                    ];
                }
                if($content == "stu_reg"){
                    $where = ['student' => 'register'];
                    $blockContent = 'register';
                    $select = [
                        'status' => $status,
                        'student' => $blockContent
                    ];
                }
                if($content == "emen_log"){
                    $where = ['ementor' => 'login'];
                    $blockContent = 'login';
                    $select = [
                        'status' => $status,
                        'ementor' => $blockContent
                    ];
                }
                if($content == "emen_reg"){
                    $where = ['ementor' => 'register'];
                    $blockContent = 'register';
                    $select = [
                        'status' => $status,
                        'ementor' => $blockContent
                    ];
                }
                if($content == "teach_reg"){
                    $where = ['teacher' => 'register'];
                    $blockContent = 'register';
                    $select = [
                        'status' => $status,
                        'teacher' => $blockContent
                    ];
                }
                $select = array_merge($select, ['updated_at' => $this->time,'updated_by'=>auth()->user()->id]);

                $updateUser = processData(['permission', 'id'], $select,$where);

                if ((isset($updateUser) && $updateUser !== FALSE)){

                    return json_encode(['code' => 200, 'title' => "Successfully unblocked", 'message' => 'IP address unblocked successfully.', "icon" => generateIconPath("success")]);

                } else {

                    return json_encode(['code' => 201, 'title' => "Unable to Block", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
                }

            } else {
                 return json_encode(['code' => 201, 'title' => 'Something Went Wrong', 'message' => 'Please Provide Required Info', "icon" => generateIconPath("error")]);

            }
        // } else {
        //     return json_encode(['code' => 201, 'title' => 'Something Went Wrong', 'message' => 'Please Provide Required Info', "icon" => generateIconPath("error")]);
        // }
    }

    public function TicketsList($cat = '')
    {

        if (Auth::check()) {
            if (isset($cat) && !empty($cat) && $cat == 'all'){
                $TicketData = getData('tickets', ['*'], []);
                return response()->json($TicketData);
            }else{
                $TicketData = getData('tickets', ['*'], ['id'=> base64_decode($cat)]);
                return response()->json($TicketData);
            }
        }
        return redirect('/login');
    }
    public function addTickets(Request $request)
    {
        if ($request->isMethod('POST') && $request->ajax() && Auth::check()) {
            $subject = isset($request->subject) ? ($request->input('subject')) : '';
            $error_type = isset($request->error_type) ? base64_decode($request->input('error_type')) : '';
            $assigned_to = isset($request->assigned_to) ? base64_decode($request->input('assigned_to')) : '';
            $error_details = isset($request->error_details) ? htmlspecialchars($request->input('error_details')) : '';
            $priority = isset($request->priority) ? base64_decode($request->input('priority')) : '';
            $error_screenshot = $request->hasFile('error_screenshot') ? $request->file('error_screenshot') : '';
            $validate_rules = [
                    'subject' => ['required', 'string'],
                    'error_type' => ['required', 'string'],
                    'assigned_to' => ['required', 'string'],
                    'error_details' => ['required', 'string'],
                    'priority' => ['required', 'string'],
                    'error_screenshot' => 'mimes:jpeg,png,jpg,svg|max:1024'
                ];

            $validate = Validator::make($request->all(), $validate_rules);

            if (!$validate->fails()) {
                    $url = '';
                    $folderPath = public_path("storage/ticketError/");
                    if (!is_dir($folderPath)) {
                        $folder = 'ticketError';
                        $makeFolder = File::makeDirectory(public_path("storage/" .$folder), $mode = 0777, true, true);
                        if (!isset($makeFolder) && $makeFolder === 0 && is_numeric($makeFolder)) {
                            return false;
                        }
                    }
                    if ($request->hasFile('error_screenshot')) {
                        $error_screenshot =  UploadFiles($error_screenshot, 'ticketError', '');
                        if ($error_screenshot === FALSE) {
                            return json_encode(['code' => 201, 'message' => 'File is corrupt', 'title' => "File is corrupt", "icon" => generateIconPath("error")]);
                        }
                        $url = $error_screenshot['url'];
                    }
                    $select = [
                        'subject' => $subject,
                        'error_type'=>$error_type,
                        'assigned_to'=>$assigned_to,
                        'error_details'=>$error_details,
                        'priority'=>$priority,
                        'error_screenshot'=>$url,
                        'created_by'=> Auth::user()->id,
                        'created_at'=>$this->time
                    ];
                    $updateBlockList = processData(['tickets', 'id'], $select);

                    if ((isset($updateBlockList) && $updateBlockList['status'] == TRUE)){

                        return json_encode(['code' => 200, 'title' => "Successfully added", 'message' => 'Add ticket successfully.', "icon" => generateIconPath("success")]);

                    }
                } else {
                    return json_encode(['code' => 202, 'title' => 'Required Fields are Missing', 'message' => 'Please Provide Required Info', "icon" => generateIconPath("error"), 'data' => $validate->errors()]);
                }

        } else {
            return json_encode(['code' => 201, 'title' => 'Something Went Wrong', 'message' => 'Please Provide Required Info', "icon" => generateIconPath("error")]);
        }
    }


    public function statusTicket(Request $request){
        if ($request->isMethod('POST') && $request->ajax() && Auth::check()) {
            $ticket_id = isset($request->ticket_id) ? base64_decode($request->input('ticket_id')) : '';

            $where = ['id' => $ticket_id,'status' => 'Open'];
            $exists = is_exist('tickets', $where);

            if (isset($exists) && $exists > 0 ) {

                $select = [
                    'status' => 'Closed',
                    'updated_by'=>auth()->user()->id,
                    'updated_at'=>$this->time
                ];
                $updateTicket = processData(['tickets', 'id'], $select,['id' => $ticket_id]);


                if ((isset($updateTicket) &&  $updateTicket['status'] == TRUE)){

                    return json_encode(['code' => 200, 'title' => "Successfully closed", 'message' => 'Ticket closed successfully.', "icon" => generateIconPath("success")]);

                } else {

                    return json_encode(['code' => 201, 'title' => 'Something Went Wrong', 'message' => 'Please Provide Required Info', "icon" => generateIconPath("error")]);
                }

            } else {
                return json_encode(['code' => 201, 'title' => "Unable to ticket", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);

            }
        } else {
            return json_encode(['code' => 201, 'title' => 'Something Went Wrong', 'message' => 'Please Provide Required Info', "icon" => generateIconPath("error")]);
        }
    }


    public function BlocksDomainList($cat = '') {
        if (Auth::check()) {
            if (!empty($cat) && $cat == 'all') {
                $userBlockData = DB::table('blocked_email_domains')->select('domain', 'is_active')->get();
                return response()->json($userBlockData);
            } else {
                return view('admin.settings.blockdomainlist');
            }
        }
        return redirect('/login');
    }

    public function toggleBlockDomain(Request $request)
    {

            // $request->validate([
            //     'domain' => 'required|string|email:rfc,dns',
            //     'action' => 'required|in:block,unblock'
            // ]);
            $email = $request->input('domain');

            $domain = strtolower(substr(strrchr($email, "@"), 1));
            $action = $request->input('action');
            $is_active = ($action === 'block') ? 1 : 0;

            $tableInfo = ['blocked_email_domains', 'id'];
            $where = ['domain' => $domain];
            $data = [
                'domain' => $domain,
                'is_active' => $is_active,
                'updated_at' => now()
            ];

            if (!is_exist('blocked_email_domains', $where)) {
                $data['created_at'] = now();
            }

            $response = processData($tableInfo, $data, $where);

            if ($response && $response['status'] === true) {
                return response()->json([
                    'status' => 'success',
                    'message' => "Domain has been " . ($is_active ? 'blocked' : 'unblocked') . ".",
                    'id' => $response['id']
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update the domain status.'
            ], 500);
    }

    public function addDomainBlock(Request $request)
    {
        if ($request->isMethod('POST') && $request->ajax() && Auth::check()) {
            $domain = isset($request->domain) ? strtolower(trim($request->input('domain'))) : '';

            try {
                $request->validate([
                    'domain' => ['required', 'string', 'regex:/^([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/'],
                ], [
                    'domain.required' => 'Please enter a domain name.',
                    'domain.regex' => 'Invalid domain format.',
                ]);
            } catch (ValidationException $e) {
                return response()->json([
                    'code' => 202,
                    'title' => 'Validation Failed',
                    'message' => 'Please enter a valid domain.',
                    'icon' => generateIconPath('error'),
                    'data' => $e->validator->errors()
                ]);
            }


            $where = ['domain' => $domain];
            $domainExists = is_exist('blocked_email_domains', $where);

            if ($domainExists) {

                $domainData = getData('blocked_email_domains', ['id', 'is_active'], $where, 1);

                if ($domainData && $domainData[0]->is_active == 1) {
                    return response()->json([
                        'code' => 201,
                        'title' => 'Already Blocked',
                        'message' => 'This domain is already blocked.',
                        'icon' => generateIconPath('error')
                    ]);
                } else {

                    $updateData = ['is_active' => 1];
                    $result = processData(['blocked_email_domains', 'id'], $updateData, ['id' => $domainData[0]->id]);

                    if ($result !== false) {
                        return response()->json([
                            'code' => 200,
                            'title' => 'Domain Re-blocked',
                            'message' => 'Domain has been successfully re-blocked.',
                            'icon' => generateIconPath('success')
                        ]);
                    }
                }
            } else {
                // Add new blocked domain
                $insertData = [
                    'domain' => $domain,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $inserted = processData(['blocked_email_domains', 'id'], $insertData);

                if ($inserted !== false) {
                    return response()->json([
                        'code' => 200,
                        'title' => 'Domain Blocked',
                        'message' => 'Domain has been blocked successfully.',
                        'icon' => generateIconPath('success')
                    ]);
                }
            }

            return response()->json([
                'code' => 201,
                'title' => 'Failed',
                'message' => 'Unable to block the domain. Please try again.',
                'icon' => generateIconPath('error')
            ]);
        }

        return response()->json([
            'code' => 201,
            'title' => 'Unauthorized',
            'message' => 'Invalid request or session expired.',
            'icon' => generateIconPath('error')
        ]);
    }

}
