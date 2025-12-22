<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseModule;
class BlockDisabledUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $formData = session('form_data');
        if(!empty($formData)){
            $course_id = $formData['course_id'];
        }else{
            $course_id = $request['course_id'];
        }
        if ($user && in_array($user->email, [env('Lockeduser')])) {
            // Option 1: redirect to dashboard or homepage
            $CourseMaster = CourseModule::where('category_id', '5')
            ->where('id',base64_decode($course_id))
            ->first();
            if(empty($CourseMaster)){
                return redirect()->route('index')->with('error', 'Access restricted for your account.');
            }

            // Option 2: show 403 forbidden
            // abort(403, 'Access Denied');
        }

        return $next($request);
    }
}
