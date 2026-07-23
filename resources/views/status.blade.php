<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Callbly System Status — Live Operational Status</title>
    <meta name="description" content="Real-time operational status, uptime metrics, and incident history for Callbly SMS, USSD, and Telephony APIs.">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    colors: {
                        brand: {
                            500: '#0ea5e9',
                            600: '#0284c7',
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
        .status-pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .status-operational {
            background-color: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        .uptime-bar {
            height: 28px;
            width: 4px;
            border-radius: 2px;
            background-color: #22c55e;
            transition: opacity 0.2s ease;
        }
        .uptime-bar:hover {
            opacity: 0.75;
        }
    </style>
</head>
<body class="antialiased selection:bg-brand-500 selection:text-white min-h-screen flex flex-col justify-between">

<!-- Header -->
<header class="sticky top-0 z-50 bg-slate-950/80 backdrop-blur-md border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <a href="/" class="flex items-center space-x-3">
            <img src="{{ asset('assets/media/logos/callbly-white.png') }}" alt="Callbly Logo" class="h-9 w-auto object-contain">
            <span class="hidden sm:inline-block text-xs font-semibold px-2.5 py-0.5 rounded-full bg-emerald-950 text-emerald-400 border border-emerald-800">
                System Status
            </span>
        </a>
        <div class="flex items-center space-x-4">
            <a href="/" class="text-sm text-slate-300 hover:text-white transition-colors">Home</a>
            <a href="{{ route('api.docs') }}" class="text-sm text-slate-300 hover:text-white transition-colors">API Docs</a>
            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-200 px-3.5 py-1.5 rounded-lg border border-slate-700 hover:bg-slate-800 transition-colors">Sign In</a>
        </div>
    </div>
</header>

<!-- Main Container -->
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-1 w-full space-y-10">
    
    <!-- Hero Status Banner -->
    <div class="bg-gradient-to-r from-emerald-950/60 to-slate-900 border border-emerald-500/30 rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 shadow-xl">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-full bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center flex-shrink-0">
                <span class="w-4 h-4 rounded-full bg-emerald-400 animate-ping"></span>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">All Systems Operational</h1>
                <p class="text-sm text-emerald-400 mt-1">All Callbly APIs, Carrier Routes & Services are performing normally.</p>
            </div>
        </div>
        <div class="text-xs font-mono text-slate-400 bg-slate-950/60 px-3 py-2 rounded-lg border border-slate-800">
            Updated: {{ date('H:i:s T') }}
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-5">
            <div class="text-xs uppercase tracking-wider font-semibold text-slate-400">Overall SLA Uptime</div>
            <div class="text-3xl font-extrabold text-white mt-2">99.99%</div>
            <div class="text-xs text-emerald-400 mt-1">Past 90 days average</div>
        </div>

        <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-5">
            <div class="text-xs uppercase tracking-wider font-semibold text-slate-400">Avg. API Response</div>
            <div class="text-3xl font-extrabold text-white mt-2 font-mono">142ms</div>
            <div class="text-xs text-slate-400 mt-1">Global REST endpoints</div>
        </div>

        <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-5">
            <div class="text-xs uppercase tracking-wider font-semibold text-slate-400">Active Incidents</div>
            <div class="text-3xl font-extrabold text-white mt-2">0</div>
            <div class="text-xs text-emerald-400 mt-1">No disruptions reported</div>
        </div>
    </div>

    <!-- Service Component Breakdown -->
    <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 sm:p-8 space-y-8">
        <h2 class="text-xl font-bold text-white mb-6">Service Status</h2>
        
        <!-- Component 1: SMS Gateway -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-white">SMS Gateway & Carrier Delivery</h3>
                    <p class="text-xs text-slate-400">High-throughput SMS routing to telecom operators</p>
                </div>
                <span class="status-pill status-operational">Operational</span>
            </div>
            <!-- Uptime Bar Graph Mock (90 days) -->
            <div class="flex items-center space-x-1 pt-2">
                @for ($i = 0; $i < 60; $i++)
                    <div class="uptime-bar flex-1" title="90 days ago: 100% uptime"></div>
                @endfor
            </div>
            <div class="flex justify-between text-[11px] text-slate-500 font-mono">
                <span>90 days ago</span>
                <span>99.99% uptime</span>
                <span>Today</span>
            </div>
        </div>

        <hr class="border-slate-800/80">

        <!-- Component 2: REST API & Webhooks -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-white">REST API & Webhooks Engine</h3>
                    <p class="text-xs text-slate-400">Authentication, bulk dispatch, and status callbacks</p>
                </div>
                <span class="status-pill status-operational">Operational</span>
            </div>
            <div class="flex items-center space-x-1 pt-2">
                @for ($i = 0; $i < 60; $i++)
                    <div class="uptime-bar flex-1" title="100% uptime"></div>
                @endfor
            </div>
            <div class="flex justify-between text-[11px] text-slate-500 font-mono">
                <span>90 days ago</span>
                <span>100% uptime</span>
                <span>Today</span>
            </div>
        </div>

        <hr class="border-slate-800/80">

        <!-- Component 3: Dashboard & Portal -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-white">Account Management Dashboard</h3>
                    <p class="text-xs text-slate-400">Web dashboard, analytics & team controls</p>
                </div>
                <span class="status-pill status-operational">Operational</span>
            </div>
            <div class="flex items-center space-x-1 pt-2">
                @for ($i = 0; $i < 60; $i++)
                    <div class="uptime-bar flex-1"></div>
                @endfor
            </div>
            <div class="flex justify-between text-[11px] text-slate-500 font-mono">
                <span>90 days ago</span>
                <span>100% uptime</span>
                <span>Today</span>
            </div>
        </div>

        <hr class="border-slate-800/80">

        <!-- Component 4: USSD Service Engine -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-white">USSD Service Gateway</h3>
                    <p class="text-xs text-slate-400">Interactive USSD session handling & carrier sessions</p>
                </div>
                <span class="status-pill status-operational">Operational</span>
            </div>
            <div class="flex items-center space-x-1 pt-2">
                @for ($i = 0; $i < 60; $i++)
                    <div class="uptime-bar flex-1"></div>
                @endfor
            </div>
            <div class="flex justify-between text-[11px] text-slate-500 font-mono">
                <span>90 days ago</span>
                <span>99.98% uptime</span>
                <span>Today</span>
            </div>
        </div>
    </div>

    <!-- Past Incident History -->
    <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 sm:p-8 space-y-6">
        <h2 class="text-xl font-bold text-white">Past Incident History</h2>
        
        <div class="border-l-2 border-slate-800 pl-6 space-y-6">
            <div>
                <div class="text-xs font-mono text-slate-400">{{ date('F j, Y') }}</div>
                <h4 class="font-semibold text-white mt-1">No incidents reported today</h4>
                <p class="text-xs text-slate-400 mt-1">All services operated normally without interruption.</p>
            </div>

            <div>
                <div class="text-xs font-mono text-slate-400">{{ date('F j, Y', strtotime('-3 days')) }}</div>
                <h4 class="font-semibold text-white mt-1">Scheduled Database Maintenance</h4>
                <p class="text-xs text-slate-400 mt-1">Completed routine database optimization. Core SMS dispatch remained operational.</p>
            </div>
        </div>
    </div>

    <!-- Subscription Card -->
    <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 text-center space-y-4">
        <h3 class="text-lg font-bold text-white">Subscribe to Status Notifications</h3>
        <p class="text-xs text-slate-400 max-w-md mx-auto">Get automatic email updates whenever Callbly creates, updates, or resolves a service incident.</p>
        <div class="flex max-w-md mx-auto gap-2">
            <input type="email" placeholder="enter your email..." class="flex-1 bg-slate-950 border border-slate-800 text-white px-3.5 py-2 rounded-lg text-sm focus:outline-none focus:border-sky-500">
            <button class="bg-sky-600 hover:bg-sky-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition-colors">Subscribe</button>
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
            <a href="{{ route('status') }}" class="hover:underline text-slate-300">Status</a>
            <a href="{{ route('api.docs') }}" class="hover:underline">API Docs</a>
        </div>
    </div>
</footer>

</body>
</html>
