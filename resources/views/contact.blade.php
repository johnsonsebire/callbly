<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact & Support — Callbly Cloud Telephony</title>
    <meta name="description" content="Get technical support, API integration assistance, or inquire about enterprise pricing for Callbly cloud telephony platform.">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --bg-main: oklch(12% 0.012 250);
            --bg-card: oklch(16% 0.014 250);
            --border-color: oklch(25% 0.018 250);
            --text-main: oklch(94% 0.008 250);
        }
        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            font-family: 'Outfit', sans-serif;
        }
        .form-input {
            width: 100%;
            background-color: oklch(9% 0.014 250);
            border: 1px solid var(--border-color);
            color: #f8fafc;
            padding: 0.75rem 1rem;
            border-radius: 0.6rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }
        .form-input:focus {
            outline: none;
            border-color: #0284c7;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.25);
        }
    </style>
</head>
<body class="antialiased selection:bg-brand-500 selection:text-white min-h-screen flex flex-col justify-between">

<!-- Header -->
<header class="sticky top-0 z-50 bg-slate-950/80 backdrop-blur-md border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <a href="/" class="flex items-center space-x-3">
            <img src="{{ asset('assets/media/logos/callbly-white.png') }}" alt="Callbly Logo" class="h-9 w-auto object-contain">
        </a>
        <div class="flex items-center space-x-4">
            <a href="/" class="text-sm text-slate-300 hover:text-white transition-colors">Home</a>
            <a href="{{ route('api.docs') }}" class="text-sm text-slate-300 hover:text-white transition-colors">API Docs</a>
            <a href="{{ route('status') }}" class="text-sm text-slate-300 hover:text-white transition-colors">Status</a>
            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-200 px-3.5 py-1.5 rounded-lg border border-slate-700 hover:bg-slate-800 transition-colors">Sign In</a>
        </div>
    </div>
</header>

<!-- Main Container -->
<main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-1 w-full space-y-12">
    
    <!-- Hero Header -->
    <div class="text-center max-w-3xl mx-auto space-y-4">
        <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-sky-950/80 border border-sky-800 text-sky-400 text-xs font-semibold uppercase tracking-wider">
            <span>Round the Clock Support</span>
        </div>
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white">
            How can we help your business?
        </h1>
        <p class="text-base sm:text-lg text-slate-300 leading-relaxed">
            Have questions about API integration, custom SMS throughput, or Sender ID registration? Reach out to our technical support team.
        </p>
    </div>

    <!-- Support Channel Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 hover:border-sky-500/50 transition-colors space-y-3">
            <div class="w-10 h-10 rounded-xl bg-sky-500/15 border border-sky-500/30 flex items-center justify-center text-sky-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-white">Developer API Support</h3>
            <p class="text-xs text-slate-400 leading-relaxed">Technical integration help with PHP, Node.js, Python, or Webhooks.</p>
            <a href="mailto:support@callbly.com" class="inline-block text-xs font-semibold text-sky-400 hover:underline">support@callbly.com &rarr;</a>
        </div>

        <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 hover:border-sky-500/50 transition-colors space-y-3">
            <div class="w-10 h-10 rounded-xl bg-purple-500/15 border border-purple-500/30 flex items-center justify-center text-purple-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-white">Sales & Enterprise</h3>
            <p class="text-xs text-slate-400 leading-relaxed">Custom volume pricing, SLA contracts, and dedicated account management.</p>
            <a href="mailto:sales@callbly.com" class="inline-block text-xs font-semibold text-purple-400 hover:underline">sales@callbly.com &rarr;</a>
        </div>

        <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 hover:border-sky-500/50 transition-colors space-y-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-white">Sender ID & KYC</h3>
            <p class="text-xs text-slate-400 leading-relaxed">Brand Sender ID registration, telecom carrier clearance, and DND compliance.</p>
            <a href="mailto:compliance@callbly.com" class="inline-block text-xs font-semibold text-emerald-400 hover:underline">compliance@callbly.com &rarr;</a>
        </div>

    </div>

    <!-- Contact Form & Info Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Contact Form (8 Cols) -->
        <div class="lg:col-span-8 bg-slate-900/60 border border-slate-800 rounded-2xl p-6 sm:p-8 space-y-6">
            <div>
                <h2 class="text-xl font-bold text-white">Send Us a Message</h2>
                <p class="text-xs text-slate-400 mt-1">Fill out the form below and our team will get back to you within 2 hours.</p>
            </div>

            <!-- Success Notification Banner -->
            <div id="contact-success" class="hidden bg-emerald-950/80 border border-emerald-800 text-emerald-300 p-4 rounded-xl text-sm flex items-center space-x-3">
                <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Thank you for contacting Callbly! Your message has been received and assigned to an engineer.</span>
            </div>

            <form id="contact-form" onsubmit="handleContactSubmit(event)" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Full Name *</label>
                        <input type="text" id="contact_name" required placeholder="John Doe" class="form-input">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Work Email *</label>
                        <input type="email" id="contact_email" required placeholder="john@company.com" class="form-input">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Phone Number</label>
                        <input type="tel" id="contact_phone" placeholder="+233201234567" class="form-input">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Inquiry Category *</label>
                        <select id="contact_category" required class="form-input">
                            <option value="technical">Technical API & Webhook Support</option>
                            <option value="sales">Sales & Enterprise Custom Rates</option>
                            <option value="sender_id">Sender ID & KYC Registration</option>
                            <option value="billing">Wallet Billing & Top-up Issue</option>
                            <option value="general">General Question</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Message *</label>
                    <textarea id="contact_message" required rows="4" placeholder="Describe your request or technical issue in detail..." class="form-input"></textarea>
                </div>

                <button type="submit" class="w-full sm:w-auto bg-sky-600 hover:bg-sky-700 text-white font-semibold py-3 px-8 rounded-xl transition-all shadow-lg flex justify-center items-center space-x-2">
                    <span>Submit Request</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>
        </div>

        <!-- Help Info Box (4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 space-y-4">
                <h3 class="text-base font-bold text-white">Live System Status</h3>
                <p class="text-xs text-slate-400 leading-relaxed">Check real-time network SLA uptime and carrier routing status on our operational dashboard.</p>
                <a href="{{ route('status') }}" class="inline-flex items-center space-x-2 text-xs font-semibold text-emerald-400 hover:underline">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>View System Status &rarr;</span>
                </a>
            </div>

            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 space-y-4">
                <h3 class="text-base font-bold text-white">Quick Answers</h3>
                <div class="space-y-3 text-xs">
                    <div>
                        <div class="font-semibold text-slate-200">How long does Sender ID registration take?</div>
                        <div class="text-slate-400 mt-0.5">Custom Sender IDs typically require 24–48 hours for mobile operator clearance.</div>
                    </div>
                    <hr class="border-slate-800">
                    <div>
                        <div class="font-semibold text-slate-200">Are API tokens instant?</div>
                        <div class="text-slate-400 mt-0.5">Yes! Bearer tokens are generated instantly inside your account dashboard.</div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</main>

<!-- Footer -->
<footer class="border-t border-slate-800 py-8 text-center text-xs text-slate-500">
    <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>&copy; {{ date('Y') }} Callbly Telephony Platform. All rights reserved.</div>
        <div class="flex space-x-4">
            <a href="/" class="hover:underline">Home</a>
            <a href="{{ route('privacy') }}" class="hover:underline">Privacy</a>
            <a href="{{ route('terms') }}" class="hover:underline">Terms</a>
            <a href="{{ route('status') }}" class="hover:underline">Status</a>
            <a href="{{ route('contact') }}" class="hover:underline text-slate-300">Support</a>
            <a href="{{ route('api.docs') }}" class="hover:underline">API Docs</a>
        </div>
    </div>
</footer>

<script>
    function handleContactSubmit(e) {
        e.preventDefault();
        document.getElementById('contact-form').reset();
        const successBanner = document.getElementById('contact-success');
        successBanner.classList.remove('hidden');
        window.scrollTo({ top: successBanner.offsetTop - 100, behavior: 'smooth' });
    }
</script>

</body>
</html>
