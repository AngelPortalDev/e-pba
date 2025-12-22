<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Redirect, Validator, Storage,Http, DB,Session};
use Carbon\Carbon;
use DateTime;
class CartController extends Controller
{

    public function addtocart(Request $req){

        if ($req->isMethod('POST')) {

            $user = Auth::user();
            if ($user && in_array($user->email, [env('Lockeduser')])) {
                session()->forget('intended_action_cart');
                return redirect()->route('index')->with('error', 'Access restricted for your account.');
            }else{
                if(session('intended_action_cart')){
                    $admin_id = auth()->user()->id;
                    $intended_action = session('intended_action_cart');

                    $action  = isset($intended_action['action']) ? base64_decode($intended_action['action']) : '';
                    $courseid = isset($intended_action['courseid']) ? base64_decode($intended_action['courseid']) : '';
                }else{
                    $admin_id = auth()->user()->id;
                    $action  = isset($req->action) ? base64_decode($req->input('action')) : '';
                    $courseid = isset($req->courseid) ? base64_decode($req->input('courseid')) : '';
                }
                $where = [];

                if($action == 'add'){


                    $exists = is_exist('cart', ['student_id' => $admin_id,'course_id'=>$courseid]);

                    if (isset($exists) && is_numeric($exists) && !empty($exists) && $exists > 0) {
                        $getCartData = getData('cart',['id','is_deleted','cart_wishlist','is_by','status'], ['student_id' => $admin_id,'course_id' => $courseid]);
                        $studentCourseMaster = getData('student_course_master',['course_expired_on','course_progress','exam_remark','exam_attempt_remain'],['course_id'=>$courseid, 'user_id'=>$admin_id,'is_deleted'=>'No'],'','created_at','desc');

                        if(isset($studentCourseMaster) && !empty($studentCourseMaster[0]) && $studentCourseMaster[0]->course_expired_on > now() && ($studentCourseMaster[0]->exam_attempt_remain != "0" && $studentCourseMaster[0]->exam_remark == '0'  || $studentCourseMaster[0]->exam_attempt_remain == "2" || $studentCourseMaster[0]->exam_attempt_remain == '1' && $studentCourseMaster[0]->exam_remark == '0' )){

                            return json_encode(['code' => 200, 'title' => __('response.cart.already_buy'), "message" => __('response.cart.already_context'), "icon" => generateIconPath("success")]);
                        }

                        if($getCartData[0]->is_deleted == 'Yes'){
                            $select = [
                                'is_deleted' => 'No',
                                'status' => 'Active',
                                'updated_by' => $admin_id,
                                'updated_at' =>  $this->time,
                                'cart_wishlist'=>'0',
                            ];
                            $where = ['id' => $getCartData[0]->id];
                            $updateCourse = processData(['cart', 'id'], $select,$where);

                            if (isset($updateCourse) && $updateCourse['status'] == '1') {

                                return json_encode(['code' => 200, 'title' => __('response.success_added'), "message" => __('response.cart.sucessfullyadded'), "icon" => generateIconPath("success")]);

                            }
                        }else if($getCartData[0]->status == "Inactive" && $getCartData[0]->is_by == '0' ){

                                $exists = is_exist('cart', ['student_id' => $admin_id,'course_id'=>$courseid,'status'=>'Active']);

                                if (isset($exists) && is_numeric($exists) && empty($exists) && $exists == 0) {
                                    $select = [
                                        'course_id' => $courseid,
                                        'student_id' => $admin_id,
                                        'status' => 'Active',
                                        'created_by' => $admin_id,
                                        'created_at' =>  $this->time,
                                        'cart_wishlist'=>'0'
                                    ];

                                    $updateCourse = processData(['cart', 'id'], $select,$where);

                                    if (isset($updateCourse) && $updateCourse['status'] == '1') {

                                        return json_encode(['code' => 200, 'title' => __('response.success_added'), "message" => __('response.cart.sucessfullyadded'), "icon" => generateIconPath("success")]);

                                    }else{

                                        return json_encode(['code' => 201, 'title' => __('response.cart.something_went_wrong'), 'message' => __('response.please_try_again'), "icon" => generateIconPath("error")]);

                                    }
                                }else{
                                    return json_encode(['code' => 200, 'title' => 'Already Added to Cart', "message" => "This course is already in the cart.", "icon" => generateIconPath("success")]);
                                }
                            }else{
                                if($getCartData[0]->cart_wishlist == '1'){

                                    $select = [
                                        'cart_wishlist'=>'0',
                                    ];
                                    $where = ['id' => $getCartData[0]->id];
                                    $updateCart = processData(['cart', 'id'], $select,$where);

                                    return json_encode(['code' => 200, 'title' => __('response.success_added'), "message" => __('response.cart.sucessfullyadded'), "icon" => generateIconPath("success")]);
                                }else{
                                    return json_encode(['code' => 200, 'title' => __('response.cart.already_added'), "message" => __('response.cart.already_added_text'), "icon" => generateIconPath("success")]);
                                }


                            }


                    }

                    $studentCourseMaster = getData('student_course_master',['course_expired_on','course_progress','exam_remark','exam_attempt_remain'],['course_id'=>$courseid, 'user_id'=>$admin_id,'is_deleted'=>'No'],'','created_at','desc');

                    if(isset($studentCourseMaster) && !empty($studentCourseMaster[0]) && $studentCourseMaster[0]->course_expired_on > now() && ($studentCourseMaster[0]->exam_attempt_remain != "0" && $studentCourseMaster[0]->exam_remark == '0'  || $studentCourseMaster[0]->exam_attempt_remain == "2" || $studentCourseMaster[0]->exam_attempt_remain == '1' && $studentCourseMaster[0]->exam_remark == '0' )){

                        return json_encode(['code' => 200, 'title' => 'Already buy', "message" => "You have already purchased this course. Please check your my learning to access it.", "icon" => generateIconPath("success")]);

                    }else{
                        $select = [
                            'course_id' => $courseid,
                            'student_id' => $admin_id,
                            'status' => 'Active',
                            'created_by' => $admin_id,
                            'created_at' =>  $this->time,
                            'cart_wishlist'=>'0'
                        ];

                        $updateCourse = processData(['cart', 'id'], $select,$where);

                        if (isset($updateCourse) && $updateCourse['status'] == '1') {

                            return json_encode(['code' => 200, 'title' => __('response.success_added'), "message" => __('response.cart.sucessfullyadded'), "icon" => generateIconPath("success")]);

                        }else{

                            return json_encode(['code' => 201, 'title' => __('response.cart.something_went_wrong'), 'message' => __('response.cart.please_try_again'), "icon" => generateIconPath("error")]);

                        }
                    }
                }else if($action == 'remove'){

                    $exists = is_exist('cart', ['id' => $courseid]);

                    if (isset($exists) && is_numeric($exists) && !empty($exists) && $exists > 0) {

                        $removeCart =  DB::table('cart')->where(['id'=>$courseid])->where(['student_id'=>$admin_id])->update(['is_deleted'=>'Yes','status'=>'Inactive']);


                        if (isset($removeCart) && $removeCart == '1') {

                            return json_encode(['code' => 200, 'title' => __('response.success_removed'), "message" => __('response.cart.removefromcart'), "icon" => generateIconPath("success")]);

                        }else{

                            return json_encode(['code' => 201, 'title' => __('response.cart.something_went_wrong'), 'message' => __('response.cart.please_try_again'), "icon" => generateIconPath("error")]);

                        }
                    }else{

                        return json_encode(['code' => 201, 'title' => __('response.cart.course_doesnot_exit'), 'message' => __('response.cart.please_try_again'), "icon" => generateIconPath("error")]);

                    }


                }else if($action == 'wishlist'){

                    $exists = is_exist('wishlist', ['student_id' => $admin_id,'course_id'=> $courseid]);

                    $getCartData = getData('cart',['id','is_deleted'], ['student_id' => $admin_id,'course_id' => $courseid]);
                    if(isset($getCartData[0])){
                        if($getCartData[0]->is_deleted == 'No'){
                            $select = [
                            'cart_wishlist'=>'1',
                            ];
                            $where = ['id' => $getCartData[0]->id];
                            $updateCart = processData(['cart', 'id'], $select,$where);
                        }
                    }

                    if (isset($exists) && is_numeric($exists) && !empty($exists) && $exists > 0) {
                        $getWishData = getData('wishlist',['id'], ['student_id' => $admin_id,'course_id' => $courseid]);
                        $where = ['id' => $getWishData[0]->id];

                            $select = [
                                'cart_wishlist'=>'0',
                                'status' =>'Active'
                            ];
                        $updateCart = processData(['wishlist', 'id'], $select,$where);

                        return json_encode(['code' => 200, 'title' => __('response.cart.item_moved_to_wishlist'), "message" => __('response.cart.wishlist_successfully'), "icon" => generateIconPath("success")]);

                    }else{
                        $select = [
                            'course_id' => $courseid,
                            'student_id' => $admin_id,
                            'status' => 'Active',
                            'created_by' => $admin_id,
                            'created_at' =>  $this->time,
                            'cart_wishlist'=>'0'
                        ];
                        $updateCourse = processData(['wishlist', 'id'], $select,$where);
                    }



                    if (isset($updateCourse) && $updateCourse['status'] == '1') {

                        return json_encode(['code' => 200, 'title' =>  __('response.cart.item_moved_to_wishlist'), "message" => __('response.cart.wishlist_successfully'), "icon" => generateIconPath("success")]);

                    }else{

                        return json_encode(['code' => 201, 'title' => __('response.cart.something_went_wrong'), 'message' => __('response.please_try_again'), "icon" => generateIconPath("error")]);

                    }
                }else if($action == 'wishlist_remove'){

                    $exists = is_exist('wishlist', ['student_id' => $admin_id,'course_id'=> $courseid]);

                    if (isset($exists) && is_numeric($exists) && !empty($exists) && $exists > 0) {

                        $getWishData = getData('wishlist',['id'], ['student_id' => $admin_id,'course_id' => $courseid]);

                        $where = ['id' => $getWishData[0]->id];

                        $select = [
                            // 'is_deleted'=>'Yes',
                            'status'=>'Inactive',
                            'cart_wishlist'=>'0'
                        ];

                        $updateWish = processData(['wishlist', 'id'], $select,$where);

                            if (isset($updateWish) && $updateWish['status'] == '1') {

                                return json_encode(['code' => 200, 'title' => __('response.success_removed'), "message" => __('response.cart.item_removed_to_wishlist'), "icon" => generateIconPath("success")]);

                            }
                    }else{

                        return json_encode(['code' => 201, 'title' => __('response.cart.something_went_wrong'), 'message' => __('response.cart.please_try_again'), "icon" => generateIconPath("error")]);

                    }
                }
            }

        }else {
            return json_encode(['code' => 201, 'title' => __('response.cart.please_login'), 'message' => __('response.cart.please_try_again'), "icon" => generateIconPath("error")]);
        }
    }
    public function CouponCode(Request $req){

        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {

            $admin_id = auth()->user()->id;
            $coupon_code  = isset($req->coupon_code) ? base64_decode($req->input('coupon_code')) : '';
            $course_id = isset($req->course_id) ? base64_decode($req->input('course_id')) : '';
            // $discount_total = isset($req->discount_total) ? base64_decode($req->input('discount_total')) : '';
            // $discount_code = isset($req->discount_code) ? base64_decode($req->input('discount_code')) : '';
            // $total_old_price = isset($req->total_old_price) ? base64_decode($req->input('total_old_price')) : '';
            // $overall_total = isset($req->overall_total) ? base64_decode($req->input('overall_total')) : '';
            // $overall_old_total = isset($req->overall_old_total) ? base64_decode($req->input('overall_old_total')) : '';

            $exists = 0;
            $getCouponData = getData('coupons',['id', 'coupon_discount','coupon_validity','institute_id'], ['coupon_name' => $coupon_code,'course_id'=>$course_id]);

            foreach($getCouponData as $coupon){
                if($coupon->institute_id){
                    $InstituteData = getData('institute_profile_master',['university_code','institute_id'],['institute_id'=>$coupon->institute_id]);
                    $existsUser = is_exist('users', ['university_code' => $InstituteData[0]->university_code,'id'=> $admin_id]);
                    if (isset($existsUser) && is_numeric($existsUser) && !empty($existsUser) && $existsUser > 0) {
                        $exists = DB::table('coupons')->whereRaw('BINARY coupon_name = ?', [$coupon_code])->where('course_id',$course_id)->where('institute_id',$InstituteData[0]->institute_id)->where('status','Active')->count();
                    }
                }else{
                    $exists = DB::table('coupons')->whereRaw('BINARY coupon_name = ?', [$coupon_code])->where('course_id',$course_id)->where('status','Active')->count();
                }
            }
            if (isset($exists) && is_numeric($exists) && !empty($exists) && $exists > 0) {

                $today = Carbon::today();
                $getCouponData = getData('coupons',['id', 'coupon_discount','coupon_validity'], ['coupon_name' => $coupon_code,'course_id'=>$course_id]);
                if (isset($existsUser) && is_numeric($existsUser) && !empty($existsUser) && $existsUser > 0) {
                    $getCouponData = getData('coupons',['id', 'coupon_discount','coupon_validity'], ['coupon_name' => $coupon_code,'course_id'=>$course_id,'institute_id'=>$InstituteData[0]->institute_id]);
                }
                $coupon_validity = $getCouponData[0]->coupon_validity;
                $futureDate = new DateTime($coupon_validity);

                $today = new DateTime($today);
                $data = [];
                $Message = '';
                $data['coupon_validity'] = $coupon_validity;
                $data['coupon_id'] = $getCouponData[0]->id;
                $data['coupon_discount'] = $getCouponData[0]->coupon_discount;


                $where = ['id'=> $getCouponData[0]->id];
                // $select = [
                //     'used_status' => '0',
                // ];
                // $updateCourse = processData(['cart', 'id'], $select,$where);
                    // $full_price = $overall_total - $overall_old_total;
                    // $promo_code_discount = $total_old_price * $discount_code/100;

                    // $data['promo_code_discount'] = $discount_total + $promo_code_discount;
                    // $DiscountOverAllTotal = $discount_total;
                    // // if(DiscountOverAllTotal){
                    // //     var DiscountOverAllTotal = promo_code_discount;
                    // // }
                    // $data['full_total_price'] = $full_price - $data['promo_code_discount'];
                if($futureDate < $today){

                    $data['Message'] = "The promo code has expired";
                    return json_encode(['code' => 201, 'title' => __('response.promo_code_does_not_exist'), 'message' => __('response.please_try_again'), "icon" => "warning","data"=>$data]);

                }
                return json_encode(['code' => 200, "message" => __('response.orientation_data'), "icon" => "success","data"=>$data]);

            }else{
                $data['Message'] = __('response.promo_code_does_not_exist');

                return json_encode(['code' => 201, 'title' => __('response.promo_code_does_not_exist'), 'message' => __('response.please_try_again'), "icon" => "warning","data"=>$data]);

            }

        }else {

            return json_encode(['code' => 201, 'title' => __('response.cart.something_went_wrong'), 'message' => __('response.please_try_again'), "icon" => "error"]);
        }
    }

    public function addWishlist(Request $req){

        if ($req->isMethod('POST')) {
            $user = Auth::user();
            if ($user && in_array($user->email, [env('Lockeduser')])) {
                session()->forget('intended_action_wishlist');
                return redirect()->route('index')->with('error', 'Access restricted for your account.');
            }else{
                if(session('intended_action_wishlist')){
                    $admin_id = auth()->user()->id;
                    $intended_action = session('intended_action_wishlist');
                    $action  = isset($intended_action['action']) ? base64_decode($intended_action['action']) : '';
                    $courseid = isset($intended_action['course_id']) ? base64_decode($intended_action['course_id']) : '';
                }else{
                    $admin_id = auth()->user()->id;
                    $action  = isset($req->action) ? base64_decode($req->input('action')) : '';
                    $courseid = isset($req->courseid) ? base64_decode($req->input('courseid')) : '';
                }
                $where = [];

                $exists = is_exist('wishlist', ['student_id' => $admin_id,'course_id'=> $courseid]);
                if (isset($exists) && is_numeric($exists) && !empty($exists) && $exists > 0) {

                    $getWishData = getData('wishlist',['id','status'], ['student_id' => $admin_id,'course_id' => $courseid]);
                    $where = ['id' => $getWishData[0]->id];

                    if($getWishData[0]->status == 'Active'){
                        $select = [
                            // 'is_deleted'=>'Yes',
                            'status'=>'Inactive',
                            'cart_wishlist'=>'0'
                        ];
                        $action= __('response.success_removed');;
                        $message = __('response.wishlist_removed');
                    }else{
                        $select = [
                            'status' =>'Active',
                            'cart_wishlist'=>'0',
                            // 'is_deleted'=>'No'
                        ];
                        $action= __('response.success_added');
                        $message= __('response.wishlist_added');


                    }
                    $updateCart = processData(['wishlist', 'id'], $select,$where);

                    return json_encode(['code' => 200, 'title' => $action, "message" => $message, "icon" => generateIconPath("success")]);
                    // return json_encode(['code' => 200, 'title' => "Successfully $action ", "message" => "You have successfully  $message course to your wishlist.", "icon" => generateIconPath("success")]);

                }else{

                    $select = [
                        'course_id' => $courseid,
                        'student_id' => $admin_id,
                        'status' => 'Active',
                        'created_by' => $admin_id,
                        'created_at' =>  $this->time,
                        'cart_wishlist'=>'0'
                    ];
                    $updateCourse = processData(['wishlist', 'id'], $select,$where);
                    return json_encode(['code' => 200, 'title' => __('response.success_added'), "message" => __('response.wishlist_added'), "icon" => generateIconPath("success")]);

                }
            }

        }else {
            return json_encode(['code' => 201, 'title' => __('response.cart.please_login'), 'message' => __('response.please_try_again'), "icon" => generateIconPath("error")]);
        }
    }

    public function storeIntendedWishlist(Request $request)
    {
        // Validate the request data
        $intendedAction = [
            'course_id' => $request->course_id,
            'action' => $request->action,
            'wishlist' => 'wishlist'
        ];
        Session::put('intended_action_wishlist', $intendedAction);

        return response()->json(['status' => 'success']);
    }
    public function storeIntendedCart(Request $request)
    {
        // Validate the request data
        $intendedAction = [
            'courseid' => $request->course_id,
            'action' => $request->action,
            'wishlist' => 'wishlist'
        ];
        Session::put('intended_action_cart', $intendedAction);

        return response()->json(['status' => 'success']);
    }
}
