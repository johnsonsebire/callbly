<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;

class ReCaptchaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/recaptcha.php', 'recaptcha');
    }

    /**
     * Get the appropriate reCAPTCHA key based on version.
     */
    private function getRecaptchaSecret(): string
    {
        return config('recaptcha.version') === 'v3' 
            ? config('recaptcha.v3_secret_key') 
            : config('recaptcha.secret_key');
    }

    /**
     * Get the appropriate site key based on version.
     */
    private function getRecaptchaSiteKey(): string
    {
        return config('recaptcha.version') === 'v3' 
            ? config('recaptcha.v3_site_key') 
            : config('recaptcha.site_key');
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

            if (empty($value)) {
                return false;
            }

            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $this->getRecaptchaSecret(),
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            $responseData = $response->json();

            if (!$response->successful() || !isset($responseData['success']) || !$responseData['success']) {
                return false;
            }

            // For v3 reCAPTCHA, check the score if it exists
            if (config('recaptcha.version') === 'v3' && isset($responseData['score'])) {
                return $responseData['score'] >= config('recaptcha.score_threshold', 0.5);
            }

            return true;
        }, 'The CAPTCHA verification failed. Please try again.');

        // Create a Blade directive for adding reCAPTCHA to forms
        Blade::directive('recaptcha', function () {
            $version = config('recaptcha.version', 'v2');
            $siteKey = $this->getRecaptchaSiteKey();
            
            return "<?php echo view('components.recaptcha-{$version}', ['siteKey' => '{$siteKey}'])->render(); ?>";
        });
    }
}
