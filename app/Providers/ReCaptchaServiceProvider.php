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
     * Get the appropriate reCAPTCHA key based on version.
     */
    private function getRecaptchaSecret(): string
    {
        if (config('recaptcha.version') === 'v3') {
            return config('recaptcha.v3_secret_key', config('recaptcha.secret_key'));
        }
        return config('recaptcha.secret_key');
    }

    /**
     * Get the appropriate site key based on version.
     */
    private function getRecaptchaSiteKey(): string
    {
        if (config('recaptcha.version') === 'v3') {
            return config('recaptcha.v3_site_key', config('recaptcha.site_key'));
        }
        return config('recaptcha.site_key');
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
                'secret' => $this->getRecaptchaSecret(),
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            $responseData = $response->json();

            if (!$response->successful() || !$responseData['success']) {
                return false;
            }

            // For v3 reCAPTCHA, check the score
            if (config('recaptcha.version') === 'v3') {
                return $responseData['score'] >= config('recaptcha.score_threshold', 0.5);
            }

            return true;
        }, 'The CAPTCHA verification failed. Please try again.');

        // Create a Blade directive for adding reCAPTCHA to forms
        Blade::directive('recaptcha', function () {
            return sprintf(
                '<?php echo view("components.recaptcha-%s", ["siteKey" => "%s"])->render(); ?>',
                config('recaptcha.version', 'v2'),
                $this->getRecaptchaSiteKey()
            );
        });
    }
}
