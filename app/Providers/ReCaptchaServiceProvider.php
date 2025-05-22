<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Blade;

class ReCaptchaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Add a custom validator for reCAPTCHA
        Validator::extend('recaptcha', function ($attribute, $value, $parameters, $validator) {
            if (!config('recaptcha.enable')) {
                return true;
            }

            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('recaptcha.secret_key'),
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            $responseData = $response->json();

            if (!$response->successful() || !$responseData['success']) {
                return false;
            }

            // For v3 reCAPTCHA, check the score
            if (config('recaptcha.version') === 'v3') {
                return $responseData['score'] >= config('recaptcha.score_threshold');
            }

            return true;
        }, 'The CAPTCHA verification failed. Please try again.');

        // Create a Blade directive for adding reCAPTCHA to forms
        Blade::directive('recaptcha', function () {
            if (config('recaptcha.version') === 'v3') {
                return '<?php echo view("components.recaptcha-v3")->render(); ?>';
            }
            
            return '<?php echo view("components.recaptcha-v2")->render(); ?>';
        });
    }
}
