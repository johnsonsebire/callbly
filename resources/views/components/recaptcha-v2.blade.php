@if(config('recaptcha.enable'))
    <div class="mb-3 d-flex justify-content-center">
        <div class="g-recaptcha" 
             data-sitekey="{{ config('recaptcha.site_key') }}"
             data-theme="light"></div>
    </div>
    @error('g-recaptcha-response')
        <div class="text-danger mt-1 text-center">{{ $message }}</div>
    @enderror
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif