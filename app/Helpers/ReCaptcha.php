<?php

namespace App\Helpers;

use ReCaptcha\ReCaptcha as GoogleReCaptcha;

class ReCaptcha
{
    public static function verify($response)
    {
        $recaptcha = new GoogleReCaptcha(env('RECAPTCHA_SECRET_KEY'));
        $resp = $recaptcha->verify($response, $_SERVER['REMOTE_ADDR']);
        
        return $resp->isSuccess();
    }
    
    public static function render()
    {
        return '<div class="g-recaptcha" data-sitekey="' . env('RECAPTCHA_SITE_KEY') . '"></div>';
    }
    
    public static function script()
    {
        return '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
    }
}