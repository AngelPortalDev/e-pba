<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use function Pest\Laravel\get;

class ValidEmailDomain implements Rule
{
    // protected $disposableDomains = [
    //     'yopmail.com',
    //     'mailinator.com',
    //     '10minutemail.com',
    //     'guerrillamail.com',
    //     'tempmail.com',
    //     'dispostable.com',
    // ];
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    protected $reason = '';
    public function passes($attribute, $value)
    {
        $domain = strtolower(substr(strrchr($value, "@"), 1));


        $blocked = is_exist('blocked_email_domains',['domain'=>$domain,'is_active'=>true]);

        if (isset($blocked) && is_numeric($blocked) && !empty($blocked) && $blocked > 0) {
            return false;
        }

        if (!checkdnsrr($domain, "MX")) {
             return false;
        }
        // dd($domain);
         return true;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if ($this->reason === 'blocked') {
            return 'This email domain is blocked.';
        } elseif ($this->reason === 'invalid') {
            return 'The email domain does not have valid MX records.';
        }
        return 'The email domain is not allowed.';
    }
}
