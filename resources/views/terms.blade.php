<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service — Callbly Cloud Telephony</title>
    <meta name="description" content="Callbly Terms of Service. Legal agreement governing API access, messaging rules, billing, and platform usage.">
    
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
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 px-3">Terms Navigation</h3>
                <a href="#acceptance" class="block px-3 py-1.5 text-sm text-slate-300 hover:text-sky-400 rounded-md hover:bg-slate-800 transition-colors">1. Acceptance</a>
                <a href="#account" class="block px-3 py-1.5 text-sm text-slate-300 hover:text-sky-400 rounded-md hover:bg-slate-800 transition-colors">2. Account Terms</a>
                <a href="#acceptable-use" class="block px-3 py-1.5 text-sm text-slate-300 hover:text-sky-400 rounded-md hover:bg-slate-800 transition-colors">3. Anti-Spam & Messaging</a>
                <a href="#billing" class="block px-3 py-1.5 text-sm text-slate-300 hover:text-sky-400 rounded-md hover:bg-slate-800 transition-colors">4. Wallet & Billing</a>
                <a href="#api-terms" class="block px-3 py-1.5 text-sm text-slate-300 hover:text-sky-400 rounded-md hover:bg-slate-800 transition-colors">5. API & SLA Terms</a>
                <a href="#liability" class="block px-3 py-1.5 text-sm text-slate-300 hover:text-sky-400 rounded-md hover:bg-slate-800 transition-colors">6. Limitation of Liability</a>
                <a href="#termination" class="block px-3 py-1.5 text-sm text-slate-300 hover:text-sky-400 rounded-md hover:bg-slate-800 transition-colors">7. Termination</a>
            </div>
        </aside>

        <!-- Document Content -->
        <main class="flex-1 max-w-3xl space-y-12">
            
            <div>
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-sky-950/80 border border-sky-800 text-sky-400 text-xs font-medium mb-4">
                    <span>Effective Date: July 23, 2026</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-4">Terms of Service</h1>
                <p class="text-base sm:text-lg text-slate-300 leading-relaxed">
                    These Terms of Service ("Agreement") govern your access to and use of Callbly's APIs, platform, and cloud telephony services.
                </p>
            </div>

            <section id="acceptance" class="bg-slate-900/60 p-6 rounded-xl border border-slate-800">
                <h2 class="text-xl font-bold text-white mb-3">1. Acceptance of Terms</h2>
                <p class="text-slate-300 text-sm leading-relaxed mb-3">
                    By creating an account, accessing Callbly APIs, or using our messaging platform, you agree to be legally bound by these Terms and our Privacy Policy.
                </p>
                <p class="text-slate-300 text-sm leading-relaxed">
                    If you are using Callbly on behalf of an organization or business entity, you represent that you have full authority to bind that entity to this Agreement.
                </p>
            </section>

            <section id="account" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">2. Account Registration & Security</h2>
                <div class="space-y-3 text-slate-300 text-sm leading-relaxed">
                    <p><strong class="text-white">Credentials:</strong> You must provide accurate, current, and complete registration information. You are responsible for keeping your password and API tokens confidential.</p>
                    <p><strong class="text-white">API Token Privileges:</strong> API keys grant programmatic control over your wallet credits and dispatch capabilities. Any request authorized via your Bearer token is deemed executed by your account.</p>
                </div>
            </section>

            <section id="acceptable-use" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">3. Messaging & Anti-Spam Regulations</h2>
                <div class="bg-amber-950/40 border-l-4 border-amber-500 p-4 rounded-r-lg mb-4 text-sm text-amber-200">
                    <strong>Zero Tolerance Anti-Spam Policy:</strong> Callbly strictly prohibits unsolicited commercial messaging (spam), phishing, fraud, or transmission of illegal content.
                </div>
                <ul class="list-disc list-inside space-y-2 text-slate-300 text-sm leading-relaxed">
                    <li><strong class="text-white">Consent Requirement:</strong> You must obtain explicit opt-in consent from recipients prior to sending SMS broadcasts or promotional messages.</li>
                    <li><strong class="text-white">Sender ID Registration:</strong> Sender IDs must accurately identify your registered business or brand. Impersonating financial institutions or government agencies will result in immediate termination.</li>
                    <li><strong class="text-white">Opt-Out Mechanisms:</strong> Marketing broadcasts must include clear unsubscribe mechanisms (e.g. "Reply STOP").</li>
                </ul>
            </section>

            <section id="billing" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">4. Wallet Billing & Credits</h2>
                <div class="space-y-3 text-slate-300 text-sm leading-relaxed">
                    <p><strong class="text-white">Prepaid Credits:</strong> Callbly operates on a prepaid wallet model. Messages are deducted from your active credit balance upon submission.</p>
                    <p><strong class="text-white">Non-Refundability:</strong> Purchased wallet credits are non-refundable, except where explicitly mandated by applicable statutory consumer protection law.</p>
                    <p><strong class="text-white">Pricing Tier Adjustments:</strong> Per-message pricing is calculated based on destination networks and volume tiers as defined in our official pricing schedule.</p>
                </div>
            </section>

            <section id="api-terms" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">5. API Usage & Service Level Agreement</h2>
                <p class="text-slate-300 text-sm leading-relaxed">
                    Callbly maintains a target 99.99% network SLA uptime for core API endpoints. We enforce rate limits to protect infrastructure stability. Unreasonable burst traffic designed to degrade system performance is prohibited.
                </p>
            </section>

            <section id="liability" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">6. Limitation of Liability</h2>
                <p class="text-slate-300 text-sm leading-relaxed">
                    To the maximum extent permitted by law, Callbly shall not be liable for any indirect, incidental, special, or consequential damages resulting from network delays by downstream mobile operators, SMS delivery drops caused by recipient carrier filters, or unauthorized token access.
                </p>
            </section>

            <section id="termination" class="bg-slate-900/60 p-6 rounded-xl border border-slate-800 space-y-2">
                <h2 class="text-xl font-bold text-white mb-2">7. Termination</h2>
                <p class="text-slate-300 text-sm leading-relaxed">
                    We reserve the right to suspend or terminate API access immediately without prior notice if we detect suspicious fraud, spam violations, or breach of this Agreement.
                </p>
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
            <a href="{{ route('privacy') }}" class="hover:underline">Privacy</a>
            <a href="{{ route('terms') }}" class="hover:underline text-slate-300">Terms</a>
            <a href="{{ route('status') }}" class="hover:underline">Status</a>
            <a href="{{ route('api.docs') }}" class="hover:underline">API Docs</a>
        </div>
    </div>
</footer>

</body>
</html>
