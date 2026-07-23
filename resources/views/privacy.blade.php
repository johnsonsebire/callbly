<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy — Callbly Cloud Telephony</title>
    <meta name="description" content="Callbly Privacy Policy. Learn how we collect, protect, and handle your data and messaging privacy.">
    
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
            --text-muted: oklch(72% 0.01 250);
        }
        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            font-family: 'Outfit', sans-serif;
        }
        html { scroll-behavior: smooth; }
        section[id] { scroll-margin-top: 100px; }
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
            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-200 px-3.5 py-1.5 rounded-lg border border-slate-700 hover:bg-slate-800 transition-colors">Sign In</a>
        </div>
    </div>
</header>

<!-- Main Container -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-1">
    <div class="flex flex-col lg:flex-row gap-12">
        
        <!-- Sidebar Navigation -->
        <aside class="w-full lg:w-64 flex-shrink-0">
            <div class="sticky top-24 space-y-2 bg-slate-900/60 p-4 rounded-xl border border-slate-800">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 px-3">On This Page</h3>
                <a href="#overview" class="block px-3 py-1.5 text-sm text-slate-300 hover:text-sky-400 rounded-md hover:bg-slate-800 transition-colors">1. Overview</a>
                <a href="#collection" class="block px-3 py-1.5 text-sm text-slate-300 hover:text-sky-400 rounded-md hover:bg-slate-800 transition-colors">2. Information Collection</a>
                <a href="#usage" class="block px-3 py-1.5 text-sm text-slate-300 hover:text-sky-400 rounded-md hover:bg-slate-800 transition-colors">3. How We Use Data</a>
                <a href="#sharing" class="block px-3 py-1.5 text-sm text-slate-300 hover:text-sky-400 rounded-md hover:bg-slate-800 transition-colors">4. Telecom Carriers</a>
                <a href="#security" class="block px-3 py-1.5 text-sm text-slate-300 hover:text-sky-400 rounded-md hover:bg-slate-800 transition-colors">5. Security & Retention</a>
                <a href="#rights" class="block px-3 py-1.5 text-sm text-slate-300 hover:text-sky-400 rounded-md hover:bg-slate-800 transition-colors">6. Your Rights</a>
                <a href="#contact" class="block px-3 py-1.5 text-sm text-slate-300 hover:text-sky-400 rounded-md hover:bg-slate-800 transition-colors">7. Contact Us</a>
            </div>
        </aside>

        <!-- Document Content -->
        <main class="flex-1 max-w-3xl space-y-12">
            
            <div>
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-sky-950/80 border border-sky-800 text-sky-400 text-xs font-medium mb-4">
                    <span>Effective Date: July 23, 2026</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-4">Privacy Policy</h1>
                <p class="text-base sm:text-lg text-slate-300 leading-relaxed">
                    At Callbly, we are committed to protecting the privacy, security, and integrity of the personal information and communication data processed through our cloud telephony, SMS, and USSD platform.
                </p>
            </div>

            <section id="overview" class="bg-slate-900/60 p-6 rounded-xl border border-slate-800">
                <h2 class="text-xl font-bold text-white mb-3">1. Overview</h2>
                <p class="text-slate-300 text-sm leading-relaxed mb-3">
                    This Privacy Policy explains how Callbly ("we", "us", or "our") collects, uses, discloses, and safeguards your information when you use our website, developer APIs, dashboard, and messaging services.
                </p>
                <p class="text-slate-300 text-sm leading-relaxed">
                    By accessing or using Callbly services, you consent to the data practices described in this policy.
                </p>
            </section>

            <section id="collection" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">2. Information We Collect</h2>
                <div class="space-y-3 text-slate-300 text-sm leading-relaxed">
                    <p><strong class="text-white">Account & Profile Data:</strong> When you register for a Callbly account, we collect your name, email address, phone number, company name, and payment/billing details.</p>
                    <p><strong class="text-white">Messaging & Telephony Content:</strong> We process data necessary to route and deliver your SMS, USSD, and voice traffic, including sender IDs, recipient phone numbers, message payload content, timestamp logs, and delivery status reports.</p>
                    <p><strong class="text-white">Technical & Usage Logs:</strong> We automatically log IP addresses, browser types, API access tokens, request headers, error codes, and endpoint usage metrics for SLA performance and abuse prevention.</p>
                </div>
            </section>

            <section id="usage" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">3. How We Use Your Data</h2>
                <ul class="list-disc list-inside space-y-2 text-slate-300 text-sm leading-relaxed">
                    <li>To provide, operate, and maintain high-throughput SMS, USSD, and telephony services.</li>
                    <li>To verify API requests, manage account balances, process billing transactions, and enforce rate limits.</li>
                    <li>To monitor delivery success rates, debug network latency, and detect fraudulent or spam messaging.</li>
                    <li>To send critical system status notices, security alerts, and customer support communications.</li>
                </ul>
            </section>

            <section id="sharing" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">4. Telecom Carriers & Third Parties</h2>
                <p class="text-slate-300 text-sm leading-relaxed">
                    To deliver SMS and voice communications worldwide, Callbly transmits recipient numbers and message payloads to downstream Mobile Network Operators (MNOs) and licensed telecommunication transit carriers.
                </p>
                <p class="text-slate-300 text-sm leading-relaxed">
                    We do not sell your personal data or message logs to advertisers. We share information only with authorized service providers under strict data processing agreements.
                </p>
            </section>

            <section id="security" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">5. Security & Data Retention</h2>
                <div class="bg-slate-900/60 p-6 rounded-xl border border-slate-800 space-y-3 text-sm text-slate-300">
                    <p><strong class="text-white">Encryption Standard:</strong> All API traffic and database records are protected using industry-standard TLS 1.3 in transit and AES-256 encryption at rest.</p>
                    <p><strong class="text-white">Retention Period:</strong> Message payload logs are retained for standard operational auditing (30 days) before being purged, unless longer retention is required for legal compliance or fraud investigation.</p>
                </div>
            </section>

            <section id="rights" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">6. Your Data Rights</h2>
                <p class="text-slate-300 text-sm leading-relaxed">
                    Depending on your jurisdiction, you have the right to request access to your personal data, request correction or deletion, object to direct marketing, or export your account logs.
                </p>
                <p class="text-slate-300 text-sm leading-relaxed">
                    You may exercise these rights by submitting a request to our Data Protection Officer at <a href="mailto:privacy@callbly.com" class="text-sky-400 hover:underline">privacy@callbly.com</a>.
                </p>
            </section>

            <section id="contact" class="bg-slate-900/60 p-6 rounded-xl border border-slate-800 space-y-2">
                <h2 class="text-xl font-bold text-white mb-2">7. Contact Information</h2>
                <p class="text-slate-300 text-sm">If you have questions regarding this Privacy Policy, please contact our team:</p>
                <p class="text-sky-400 font-mono text-sm">Email: privacy@callbly.com</p>
                <p class="text-slate-400 text-xs mt-2">Callbly Platform Ltd. — Data Protection Office</p>
            </section>

        </main>
    </div>
</div>

<!-- Footer -->
<footer class="border-t border-slate-800 py-8 text-center text-xs text-slate-500">
    <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>&copy; {{ date('Y') }} Callbly Telephony Platform. All rights reserved.</div>
        <div class="flex space-x-4">
            <a href="/" class="hover:underline">Home</a>
            <a href="{{ route('privacy') }}" class="hover:underline text-slate-300">Privacy</a>
            <a href="{{ route('terms') }}" class="hover:underline">Terms</a>
            <a href="{{ route('status') }}" class="hover:underline">Status</a>
            <a href="{{ route('api.docs') }}" class="hover:underline">API Docs</a>
        </div>
    </div>
</footer>

</body>
</html>
