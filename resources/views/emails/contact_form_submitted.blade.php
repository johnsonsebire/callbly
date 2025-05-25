<div style="font-family: Arial, sans-serif; font-size: 16px; color: #222;">
    <h2>New Contact Form Submission</h2>
    <p><strong>Name:</strong> {{ $data['name'] }}</p>
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    <p><strong>Message:</strong></p>
    <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; border: 1px solid #eee;">
        {{ $data['message'] }}
    </div>
</div>
