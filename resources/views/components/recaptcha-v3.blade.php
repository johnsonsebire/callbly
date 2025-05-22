@if(config('recaptcha.enable'))
    <div class="mb-3 d-flex justify-content-center">
        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
    </div>
    @error('g-recaptcha-response')
        <div class="text-danger mt-1 text-center">{{ $message }}</div>
    @enderror
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.site_key') }}"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ config('recaptcha.site_key') }}', {action: 'submit'})
                .then(function(token) {
                    document.getElementById('g-recaptcha-response').value = token;
                });
        });
    </script>
@endif