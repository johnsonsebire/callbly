<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Callbly API Documentation — Cloud Telephony & SMS API</title>
    <meta name="description" content="Official REST API documentation for Callbly Cloud Telephony Platform. Integrate SMS, bulk messaging, and wallet management into your apps.">
    
    <!-- Fonts: Outfit for brand matching & JetBrains Mono for code -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,400;0,500;0,600;1,400&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Theme Initializer Script (Prevents FOUC) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('callbly_theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('light');
            } else {
                document.documentElement.classList.add('light');
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        mono: ['JetBrains Mono', 'ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', 'monospace'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                            glow: 'oklch(78% 0.19 250)',
                        },
                        darkSurface: {
                            0: 'oklch(12% 0.012 250)',
                            1: 'oklch(16% 0.012 250)',
                            2: 'oklch(20% 0.012 250)',
                            3: 'oklch(25% 0.012 250)',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Prism Syntax Highlighter -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet" />
    
    <style>
        :root {
            --bg-main: #f8fafc;
            --bg-card: #ffffff;
            --bg-sidebar: #ffffff;
            --border-color: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #475569;
            --text-subtle: #64748b;
            --brand-primary: #0284c7;
            --brand-primary-hover: #0369a1;
            --brand-glow: rgba(2, 132, 199, 0.15);
            --code-bg: #0f172a;
            --nav-bg: rgba(255, 255, 255, 0.85);
        }

        html.dark {
            --bg-main: oklch(12% 0.012 250);
            --bg-card: oklch(16% 0.014 250);
            --bg-sidebar: oklch(14% 0.014 250);
            --border-color: oklch(25% 0.018 250);
            --text-main: oklch(94% 0.008 250);
            --text-muted: oklch(72% 0.01 250);
            --text-subtle: oklch(55% 0.008 250);
            --brand-primary: oklch(78% 0.19 250);
            --brand-primary-hover: oklch(85% 0.19 250);
            --brand-glow: oklch(78% 0.19 250 / 0.25);
            --code-bg: oklch(9% 0.014 250);
            --nav-bg: oklch(12% 0.012 250 / 0.85);
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            font-family: 'Outfit', sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Glassmorphic Navigation */
        .glass-header {
            background: var(--nav-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-color);
        }

        /* Sidebar Styling */
        .sidebar {
            background-color: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        /* Endpoint Methods */
        .endpoint-method {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .method-post {
            background-color: rgba(16, 185, 129, 0.12);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        html.dark .method-post {
            background-color: rgba(16, 185, 129, 0.2);
            color: #34d399;
        }
        
        .method-get {
            background-color: rgba(14, 165, 233, 0.12);
            color: #0284c7;
            border: 1px solid rgba(14, 165, 233, 0.3);
        }
        html.dark .method-get {
            background-color: rgba(56, 189, 248, 0.2);
            color: #38bdf8;
        }

        /* Code Container & Tabs */
        .code-container {
            background-color: var(--code-bg);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .tab-btn {
            padding: 10px 16px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #94a3b8;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .tab-btn:hover {
            color: #e2e8f0;
        }
        .tab-btn.active {
            color: var(--brand-primary);
            border-bottom-color: var(--brand-primary);
            font-weight: 600;
        }
        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* Terminal Window */
        .terminal-output {
            background-color: #090d16;
            color: #38bdf8;
            font-family: 'JetBrains Mono', monospace;
            padding: 1.25rem;
            border-radius: 0.5rem;
            min-height: 140px;
            white-space: pre-wrap;
            overflow-x: auto;
            border: 1px solid #1e293b;
            font-size: 0.85rem;
            line-height: 1.5;
        }

        /* Form Inputs */
        .live-input {
            width: 100%;
            background-color: var(--bg-main);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            padding: 0.625rem 0.875rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }
        .live-input:focus {
            outline: none;
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 3px var(--brand-glow);
        }
        .live-label {
            display: block;
            font-size: 0.75rem;
            color: var(--text-subtle);
            margin-bottom: 0.35rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.3);
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.5);
        }

        /* Cards and sections */
        .doc-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        /* Smooth Anchor Scroll */
        html {
            scroll-behavior: smooth;
        }
        section[id] {
            scroll-margin-top: 90px;
        }
    </style>
</head>
<body class="antialiased selection:bg-brand-500 selection:text-white">

<!-- Top Navigation Header -->
<header class="sticky top-0 z-50 glass-header transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        
        <div class="flex items-center space-x-4">
            <!-- Mobile Menu Toggle Button -->
            <button id="mobile-menu-btn" onclick="toggleMobileSidebar()" class="md:hidden p-2 rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Callbly Brand Logo -->
            <a href="/" class="flex items-center space-x-3 group">
                <img src="{{ asset('assets/media/logos/callbly logo.png') }}" alt="Callbly Logo" class="h-9 w-auto object-contain transition-transform group-hover:scale-105 dark:hidden">
                <img src="{{ asset('assets/media/logos/callbly-white.png') }}" alt="Callbly Logo" class="h-9 w-auto object-contain transition-transform group-hover:scale-105 hidden dark:block">
                <span class="hidden sm:inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-brand-100 text-brand-700 dark:bg-brand-900/60 dark:text-brand-300 border border-brand-200 dark:border-brand-700/50">
                    API Reference v1
                </span>
            </a>
        </div>

        <!-- Navigation Links & Theme Switcher -->
        <div class="flex items-center space-x-3 sm:space-x-5">
            <a href="/" class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 transition-colors hidden sm:inline-flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Main Site
            </a>

            <a href="/login" class="text-sm font-medium px-3.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors hidden sm:inline-block">
                Dashboard
            </a>

            <!-- Theme Toggle Button -->
            <button id="theme-toggle-btn" onclick="toggleDarkMode()" aria-label="Toggle dark mode" class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 transition-all shadow-sm">
                <!-- Sun Icon (for Dark Mode) -->
                <svg id="sun-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <!-- Moon Icon (for Light Mode) -->
                <svg id="moon-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
            </button>
        </div>
    </div>
</header>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex">
    
    <!-- Sidebar Navigation -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 pt-20 pb-10 sidebar overflow-y-auto transform -translate-x-full md:translate-x-0 md:static md:z-auto transition-transform duration-300 ease-in-out md:block flex-shrink-0">
        <div class="px-6 pb-6">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3">
                Documentation
            </h2>
            
            <nav class="space-y-6">
                <div>
                    <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Getting Started</div>
                    <div class="space-y-1">
                        <a href="#introduction" class="nav-link block px-3 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 rounded-lg hover:bg-brand-50 hover:text-brand-600 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition-colors">
                            Introduction
                        </a>
                        <a href="#authentication" class="nav-link block px-3 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 rounded-lg hover:bg-brand-50 hover:text-brand-600 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition-colors">
                            Authentication
                        </a>
                    </div>
                </div>

                <div>
                    <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">SMS API</div>
                    <div class="space-y-1">
                        <a href="#send-bulk" class="nav-link flex items-center justify-between px-3 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 rounded-lg hover:bg-brand-50 hover:text-brand-600 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition-colors">
                            <span>Send Bulk SMS</span>
                            <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-950 px-1.5 py-0.5 rounded">POST</span>
                        </a>
                        <a href="#send-single" class="nav-link flex items-center justify-between px-3 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 rounded-lg hover:bg-brand-50 hover:text-brand-600 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition-colors">
                            <span>Send Single SMS</span>
                            <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-950 px-1.5 py-0.5 rounded">POST</span>
                        </a>
                    </div>
                </div>

                <div>
                    <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Wallet API</div>
                    <div class="space-y-1">
                        <a href="#check-balance" class="nav-link flex items-center justify-between px-3 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 rounded-lg hover:bg-brand-50 hover:text-brand-600 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition-colors">
                            <span>Check Balance</span>
                            <span class="text-[10px] font-bold text-brand-600 dark:text-brand-400 bg-brand-100 dark:bg-brand-950 px-1.5 py-0.5 rounded">GET</span>
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </aside>

    <!-- Overlay for mobile sidebar -->
    <div id="sidebar-overlay" onclick="toggleMobileSidebar()" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-30 hidden md:hidden"></div>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-4xl py-10 px-4 sm:px-6 lg:px-10">
        
        <!-- Section: Introduction -->
        <section id="introduction" class="mb-14">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-brand-50 dark:bg-brand-950/80 border border-brand-200 dark:border-brand-800 text-brand-600 dark:text-brand-400 text-xs font-medium mb-4">
                <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
                <span>REST API Platform</span>
            </div>
            
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-4">
                Callbly API Reference
            </h1>
            <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                Welcome to the Callbly REST API! Integrate SMS dispatching, broadcast messaging, and real-time wallet verification seamlessly into your web or mobile applications.
            </p>
            
            <!-- Global API Token Playground Card -->
            <div class="doc-card p-6 border-l-4 border-l-brand-500 shadow-sm">
                <div class="flex items-start space-x-4">
                    <div class="p-2 bg-brand-100 dark:bg-brand-900/50 text-brand-600 dark:text-brand-400 rounded-lg flex-shrink-0">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <div class="w-full">
                        <h3 class="text-lg font-bold">Interactive Playground</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">
                            Enter your API token here to test live requests directly from the documentation.
                        </p>
                        
                        <div>
                            <label for="global_api_token" class="live-label">Your Bearer Token</label>
                            <div class="relative">
                                <input type="password" id="global_api_token" placeholder="Paste your API token here..." class="live-input pr-10">
                                <button type="button" onclick="toggleTokenVisibility()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                    <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                Tokens are kept in session memory only and are never saved or sent to external servers.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <hr class="border-slate-200 dark:border-slate-800 my-10">

        <!-- Section: Authentication -->
        <section id="authentication" class="mb-16">
            <h2 class="text-2xl font-bold tracking-tight mb-4">Authentication</h2>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                The Callbly API uses Bearer Tokens to authenticate requests. Manage your tokens inside your Callbly account dashboard under <strong>API Settings</strong>.
            </p>
            
            <div class="bg-amber-50 dark:bg-amber-950/40 border-l-4 border-amber-500 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-amber-800 dark:text-amber-300">
                            <strong>Security Warning:</strong> Keep your tokens secure! Never publish secret tokens to public repositories or client-side JavaScript.
                        </p>
                    </div>
                </div>
            </div>

            <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                Always send requests over HTTPS. Include your API token in the <code class="bg-slate-100 dark:bg-slate-800 text-rose-600 dark:text-rose-400 px-1.5 py-0.5 rounded text-xs font-mono">Authorization</code> header.
            </p>
            
            <div class="code-container p-4 font-mono text-sm text-slate-200 relative group">
                <button onclick="copySnippet(this)" class="absolute right-3 top-3 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs px-2.5 py-1 rounded border border-slate-700 flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    Copy
                </button>
<pre class="!m-0"><code class="language-http">Authorization: Bearer YOUR_API_TOKEN</code></pre>
            </div>
        </section>

        <hr class="border-slate-200 dark:border-slate-800 my-10">

        <!-- Endpoint: Send Bulk SMS -->
        <section id="send-bulk" class="mb-16">
            <h2 class="text-2xl font-bold tracking-tight mb-2">Send Bulk SMS</h2>
            <div class="flex items-center space-x-3 mb-6">
                <span class="endpoint-method method-post">POST</span>
                <code class="text-sm font-mono bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-md border border-slate-200 dark:border-slate-700">/api/v1/sms/send-bulk</code>
            </div>
            
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                Dispatch identical SMS messages to multiple recipients in a single HTTP request. Recommended for broadcast campaigns.
            </p>

            <h4 class="font-bold text-slate-800 dark:text-slate-200 mb-3">Request Parameters (JSON Body)</h4>
            
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
                    <thead class="bg-slate-50 dark:bg-slate-900/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Parameter</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Description</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                        <tr>
                            <td class="px-4 py-3 font-mono font-medium text-brand-600 dark:text-brand-400">recipients</td>
                            <td class="px-4 py-3 text-slate-500 font-mono text-xs">Array&lt;String&gt;</td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300"><strong class="text-rose-500">Required.</strong> List of recipient phone numbers (e.g., <code class="font-mono text-xs">+233201234567</code>).</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono font-medium text-brand-600 dark:text-brand-400">message</td>
                            <td class="px-4 py-3 text-slate-500 font-mono text-xs">String</td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300"><strong class="text-rose-500">Required.</strong> SMS body content.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono font-medium text-brand-600 dark:text-brand-400">sender_name</td>
                            <td class="px-4 py-3 text-slate-500 font-mono text-xs">String</td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300"><strong class="text-rose-500">Required.</strong> Approved alphanumeric Sender ID (max 11 characters).</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Code Examples Box -->
            <div class="code-container overflow-hidden">
                <div class="flex items-center justify-between border-b border-slate-800 bg-slate-950 px-3 pt-2">
                    <div class="flex space-x-1">
                        <button class="tab-btn" onclick="switchTab(this, 'bulk-php')">PHP</button>
                        <button class="tab-btn" onclick="switchTab(this, 'bulk-node')">Node.js</button>
                        <button class="tab-btn" onclick="switchTab(this, 'bulk-python')">Python</button>
                        <button class="tab-btn" onclick="switchTab(this, 'bulk-curl')">cURL</button>
                    </div>
                    <button class="tab-btn active text-brand-400 flex items-center" onclick="switchTab(this, 'bulk-live')">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Try it Live
                    </button>
                </div>
                
                <div class="p-4 text-sm relative">
                    <div id="bulk-php" class="tab-content relative group">
                        <button onclick="copySnippet(this)" class="absolute right-2 top-2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs px-2.5 py-1 rounded border border-slate-700">Copy</button>
<pre class="!m-0"><code class="language-php">&lt;?php
$response = Http::withToken('YOUR_API_TOKEN')
    ->acceptJson()
    ->post('https://callbly.com/api/v1/sms/send-bulk', [
        'recipients' => ['+233201234567', '+233241234567'],
        'message' => 'Hello from Callbly! This is a bulk message.',
        'sender_name' => 'MyBrand',
    ]);

if ($response->successful()) {
    echo "Message sent successfully!";
}</code></pre>
                    </div>

                    <div id="bulk-node" class="tab-content relative group">
                        <button onclick="copySnippet(this)" class="absolute right-2 top-2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs px-2.5 py-1 rounded border border-slate-700">Copy</button>
<pre class="!m-0"><code class="language-javascript">const axios = require('axios');

axios.post('https://callbly.com/api/v1/sms/send-bulk', {
  recipients: ['+233201234567', '+233241234567'],
  message: 'Hello from Callbly! This is a bulk message.',
  sender_name: 'MyBrand'
}, {
  headers: {
    'Authorization': 'Bearer YOUR_API_TOKEN',
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})
.then(response => console.log(response.data))
.catch(error => console.error(error));</code></pre>
                    </div>

                    <div id="bulk-python" class="tab-content relative group">
                        <button onclick="copySnippet(this)" class="absolute right-2 top-2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs px-2.5 py-1 rounded border border-slate-700">Copy</button>
<pre class="!m-0"><code class="language-python">import requests

url = "https://callbly.com/api/v1/sms/send-bulk"
payload = {
    "recipients": ["+233201234567", "+233241234567"],
    "message": "Hello from Callbly! This is a bulk message.",
    "sender_name": "MyBrand"
}
headers = {
    "Authorization": "Bearer YOUR_API_TOKEN",
    "Accept": "application/json"
}

response = requests.post(url, json=payload, headers=headers)
print(response.json())</code></pre>
                    </div>

                    <div id="bulk-curl" class="tab-content relative group">
                        <button onclick="copySnippet(this)" class="absolute right-2 top-2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs px-2.5 py-1 rounded border border-slate-700">Copy</button>
<pre class="!m-0"><code class="language-bash">curl -X POST "https://callbly.com/api/v1/sms/send-bulk" \
     -H "Authorization: Bearer YOUR_API_TOKEN" \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{
           "recipients": ["+233201234567", "+233241234567"],
           "message": "Hello from Callbly! This is a bulk message.",
           "sender_name": "MyBrand"
         }'</code></pre>
                    </div>
                    
                    <!-- Live Interactive Playground -->
                    <div id="bulk-live" class="tab-content active">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="live-label">Recipients (comma separated)</label>
                                    <input type="text" id="bulk_recipients" class="live-input" placeholder="+233201234567, +233241234567" value="+233201234567">
                                </div>
                                <div>
                                    <label class="live-label">Message</label>
                                    <textarea id="bulk_message" class="live-input" rows="3" placeholder="Write your message here...">Hello from Callbly Live Playground!</textarea>
                                </div>
                                <div>
                                    <label class="live-label">Sender Name</label>
                                    <input type="text" id="bulk_sender" class="live-input" placeholder="Callbly" value="Callbly">
                                </div>
                                <button onclick="runLiveBulk()" class="w-full bg-brand-600 hover:bg-brand-700 dark:bg-brand-500 dark:hover:bg-brand-400 text-white font-semibold py-2.5 px-4 rounded-lg transition-all shadow-md flex justify-center items-center">
                                    Execute Request
                                </button>
                            </div>
                            <div>
                                <label class="live-label">Response Terminal</label>
                                <div id="bulk_terminal" class="terminal-output">> Ready. Awaiting request execution...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <hr class="border-slate-200 dark:border-slate-800 my-10">

        <!-- Endpoint: Send Single SMS -->
        <section id="send-single" class="mb-16">
            <h2 class="text-2xl font-bold tracking-tight mb-2">Send Single SMS</h2>
            <div class="flex items-center space-x-3 mb-6">
                <span class="endpoint-method method-post">POST</span>
                <code class="text-sm font-mono bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-md border border-slate-200 dark:border-slate-700">/api/v1/sms/send</code>
            </div>
            
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                Send an SMS message to a single recipient number.
            </p>

            <h4 class="font-bold text-slate-800 dark:text-slate-200 mb-3">Request Parameters (JSON Body)</h4>
            
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
                    <thead class="bg-slate-50 dark:bg-slate-900/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Parameter</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Description</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                        <tr>
                            <td class="px-4 py-3 font-mono font-medium text-brand-600 dark:text-brand-400">recipient</td>
                            <td class="px-4 py-3 text-slate-500 font-mono text-xs">String</td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300"><strong class="text-rose-500">Required.</strong> A single valid phone number.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono font-medium text-brand-600 dark:text-brand-400">message</td>
                            <td class="px-4 py-3 text-slate-500 font-mono text-xs">String</td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300"><strong class="text-rose-500">Required.</strong> SMS text message content.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono font-medium text-brand-600 dark:text-brand-400">sender_name</td>
                            <td class="px-4 py-3 text-slate-500 font-mono text-xs">String</td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300"><strong class="text-rose-500">Required.</strong> Sender ID (max 11 characters).</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="code-container overflow-hidden">
                <div class="flex items-center justify-between border-b border-slate-800 bg-slate-950 px-3 pt-2">
                    <div class="flex space-x-1">
                        <button class="tab-btn" onclick="switchTab(this, 'single-php')">PHP</button>
                        <button class="tab-btn" onclick="switchTab(this, 'single-curl')">cURL</button>
                    </div>
                    <button class="tab-btn active text-brand-400 flex items-center" onclick="switchTab(this, 'single-live')">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Try it Live
                    </button>
                </div>
                
                <div class="p-4 text-sm relative">
                    <div id="single-php" class="tab-content relative group">
                        <button onclick="copySnippet(this)" class="absolute right-2 top-2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs px-2.5 py-1 rounded border border-slate-700">Copy</button>
<pre class="!m-0"><code class="language-php">&lt;?php
$response = Http::withToken('YOUR_API_TOKEN')
    ->post('https://callbly.com/api/v1/sms/send', [
        'recipient' => '+233201234567',
        'message' => 'Hello from Callbly!',
        'sender_name' => 'MyBrand',
    ]);</code></pre>
                    </div>

                    <div id="single-curl" class="tab-content relative group">
                        <button onclick="copySnippet(this)" class="absolute right-2 top-2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs px-2.5 py-1 rounded border border-slate-700">Copy</button>
<pre class="!m-0"><code class="language-bash">curl -X POST "https://callbly.com/api/v1/sms/send" \
     -H "Authorization: Bearer YOUR_API_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{
           "recipient": "+233201234567",
           "message": "Hello from Callbly!",
           "sender_name": "MyBrand"
         }'</code></pre>
                    </div>
                    
                    <div id="single-live" class="tab-content active">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="live-label">Recipient Phone Number</label>
                                    <input type="text" id="single_recipient" class="live-input" placeholder="+233201234567" value="+233201234567">
                                </div>
                                <div>
                                    <label class="live-label">Message</label>
                                    <textarea id="single_message" class="live-input" rows="3" placeholder="Write message...">Hello single recipient!</textarea>
                                </div>
                                <div>
                                    <label class="live-label">Sender Name</label>
                                    <input type="text" id="single_sender" class="live-input" placeholder="Callbly" value="Callbly">
                                </div>
                                <button onclick="runLiveSingle()" class="w-full bg-brand-600 hover:bg-brand-700 dark:bg-brand-500 dark:hover:bg-brand-400 text-white font-semibold py-2.5 px-4 rounded-lg transition-all shadow-md flex justify-center items-center">
                                    Execute Request
                                </button>
                            </div>
                            <div>
                                <label class="live-label">Response Terminal</label>
                                <div id="single_terminal" class="terminal-output">> Ready. Awaiting request execution...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <hr class="border-slate-200 dark:border-slate-800 my-10">

        <!-- Endpoint: Check Wallet Balance -->
        <section id="check-balance" class="mb-16">
            <h2 class="text-2xl font-bold tracking-tight mb-2">Check Wallet Balance</h2>
            <div class="flex items-center space-x-3 mb-6">
                <span class="endpoint-method method-get">GET</span>
                <code class="text-sm font-mono bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-md border border-slate-200 dark:border-slate-700">/api/v1/wallet/balance</code>
            </div>
            
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                Retrieve your remaining account credits and wallet balance.
            </p>

            <div class="code-container overflow-hidden mb-8">
                <div class="flex items-center justify-between border-b border-slate-800 bg-slate-950 px-3 pt-2">
                    <div class="flex space-x-1">
                        <button class="tab-btn" onclick="switchTab(this, 'balance-php')">PHP</button>
                        <button class="tab-btn" onclick="switchTab(this, 'balance-node')">Node.js</button>
                        <button class="tab-btn" onclick="switchTab(this, 'balance-python')">Python</button>
                        <button class="tab-btn" onclick="switchTab(this, 'balance-curl')">cURL</button>
                    </div>
                    <button class="tab-btn active text-brand-400 flex items-center" onclick="switchTab(this, 'balance-live')">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Try it Live
                    </button>
                </div>
                
                <div class="p-4 text-sm relative">
                    <div id="balance-php" class="tab-content relative group">
                        <button onclick="copySnippet(this)" class="absolute right-2 top-2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs px-2.5 py-1 rounded border border-slate-700">Copy</button>
<pre class="!m-0"><code class="language-php">&lt;?php
$response = Http::withToken('YOUR_API_TOKEN')
    ->acceptJson()
    ->get('https://callbly.com/api/v1/wallet/balance');

if ($response->successful()) {
    $balance = $response->json()['data']['balance'];
    echo "Current Balance: ₵" . $balance;
}</code></pre>
                    </div>

                    <div id="balance-node" class="tab-content relative group">
                        <button onclick="copySnippet(this)" class="absolute right-2 top-2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs px-2.5 py-1 rounded border border-slate-700">Copy</button>
<pre class="!m-0"><code class="language-javascript">const axios = require('axios');

axios.get('https://callbly.com/api/v1/wallet/balance', {
  headers: {
    'Authorization': 'Bearer YOUR_API_TOKEN',
    'Accept': 'application/json'
  }
})
.then(response => console.log(response.data.data.balance))
.catch(error => console.error(error));</code></pre>
                    </div>

                    <div id="balance-python" class="tab-content relative group">
                        <button onclick="copySnippet(this)" class="absolute right-2 top-2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs px-2.5 py-1 rounded border border-slate-700">Copy</button>
<pre class="!m-0"><code class="language-python">import requests

url = "https://callbly.com/api/v1/wallet/balance"
headers = {
    "Authorization": "Bearer YOUR_API_TOKEN",
    "Accept": "application/json"
}

response = requests.get(url, headers=headers)
print(response.json()['data']['balance'])</code></pre>
                    </div>

                    <div id="balance-curl" class="tab-content relative group">
                        <button onclick="copySnippet(this)" class="absolute right-2 top-2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs px-2.5 py-1 rounded border border-slate-700">Copy</button>
<pre class="!m-0"><code class="language-bash">curl -X GET "https://callbly.com/api/v1/wallet/balance" \
     -H "Authorization: Bearer YOUR_API_TOKEN" \
     -H "Accept: application/json"</code></pre>
                    </div>
                    
                    <div id="balance-live" class="tab-content active">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-4 flex flex-col justify-center">
                                <p class="text-slate-400 text-sm leading-relaxed">
                                    Fetches the current wallet balance associated with the Bearer token configured in the interactive playground.
                                </p>
                                <button onclick="runLiveBalance()" class="w-full bg-brand-600 hover:bg-brand-700 dark:bg-brand-500 dark:hover:bg-brand-400 text-white font-semibold py-2.5 px-4 rounded-lg transition-all shadow-md flex justify-center items-center">
                                    Check Wallet Balance
                                </button>
                            </div>
                            <div>
                                <label class="live-label">Response Terminal</label>
                                <div id="balance_terminal" class="terminal-output">> Ready. Awaiting request execution...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <h4 class="font-bold text-slate-800 dark:text-slate-200 mb-3">Example JSON Response</h4>
            <div class="code-container p-4 overflow-x-auto font-mono text-sm">
<pre class="!m-0"><code class="language-json">{
  "success": true,
  "data": {
    "balance": 1450.50,
    "currency": "GHS",
    "formatted_balance": "₵1,450.50"
  }
}</code></pre>
            </div>
        </section>
        
    </main>
</div>

<!-- Footer -->
<footer class="border-t border-slate-200 dark:border-slate-800 mt-20 py-8 text-center text-xs text-slate-500 dark:text-slate-400">
    <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            &copy; {{ date('Y') }} Callbly Telephony Platform. All rights reserved.
        </div>
        <div class="flex space-x-4">
            <a href="/" class="hover:underline">Home</a>
            <a href="/login" class="hover:underline">Dashboard</a>
            <a href="#introduction" class="hover:underline">API Docs</a>
        </div>
    </div>
</footer>

<!-- Prism.js Syntax Highlighting -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup-templating.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-python.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-bash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-json.min.js"></script>

<script>
    // Theme Management Switcher
    function updateThemeUI() {
        const isDark = document.documentElement.classList.contains('dark');
        const sunIcon = document.getElementById('sun-icon');
        const moonIcon = document.getElementById('moon-icon');
        
        if (isDark) {
            sunIcon.classList.remove('hidden');
            moonIcon.classList.add('hidden');
        } else {
            sunIcon.classList.add('hidden');
            moonIcon.classList.remove('hidden');
        }
    }

    function toggleDarkMode() {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            document.documentElement.classList.add('light');
            localStorage.setItem('callbly_theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            document.documentElement.classList.remove('light');
            localStorage.setItem('callbly_theme', 'dark');
        }
        updateThemeUI();
    }

    // Toggle Password Visibility for Token Input
    function toggleTokenVisibility() {
        const tokenInput = document.getElementById('global_api_token');
        if (tokenInput.type === 'password') {
            tokenInput.type = 'text';
        } else {
            tokenInput.type = 'password';
        }
    }

    // Mobile Sidebar Drawer Toggle
    function toggleMobileSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }

    // Copy Code Snippet
    function copySnippet(btn) {
        const pre = btn.parentElement.querySelector('pre');
        if (!pre) return;
        
        const text = pre.innerText;
        navigator.clipboard.writeText(text).then(() => {
            const originalText = btn.innerText;
            btn.innerText = 'Copied!';
            btn.classList.add('text-emerald-400', 'border-emerald-500');
            setTimeout(() => {
                btn.innerText = originalText;
                btn.classList.remove('text-emerald-400', 'border-emerald-500');
            }, 2000);
        });
    }

    // Code Block Tab Switcher
    function switchTab(btn, targetId) {
        const container = btn.closest('.code-container');
        container.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        container.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        
        btn.classList.add('active');
        document.getElementById(targetId).classList.add('active');
    }

    // Terminal Logger
    function writeToTerminal(terminalId, text, isError = false) {
        const terminal = document.getElementById(terminalId);
        if (isError) {
            terminal.innerHTML = `<span style="color: #f87171;">${text}</span>`;
        } else {
            terminal.innerHTML = text;
        }
    }

    function getApiToken() {
        const token = document.getElementById('global_api_token').value.trim();
        if (!token) {
            alert('Please enter your API Token in the "Interactive Playground" section at the top of the page.');
            return null;
        }
        return token;
    }

    // Live Request: Send Bulk SMS
    async function runLiveBulk() {
        const token = getApiToken();
        if (!token) return;
        
        const terminalId = 'bulk_terminal';
        writeToTerminal(terminalId, '> Executing POST /api/v1/sms/send-bulk...\n> Awaiting response...');
        
        const recipientsInput = document.getElementById('bulk_recipients').value;
        const message = document.getElementById('bulk_message').value;
        const sender = document.getElementById('bulk_sender').value;
        const recipients = recipientsInput.split(',').map(s => s.trim()).filter(s => s !== '');

        try {
            const res = await fetch('/api/v1/sms/send-bulk', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    recipients: recipients,
                    message: message,
                    sender_name: sender
                })
            });
            
            const data = await res.json();
            const formatted = JSON.stringify(data, null, 2);
            
            if (res.ok) {
                writeToTerminal(terminalId, `<span style="color: #4ade80;">> Status: ${res.status} ${res.statusText}</span>\n\n${formatted}`);
            } else {
                writeToTerminal(terminalId, `<span style="color: #f87171;">> Status: ${res.status} ${res.statusText}</span>\n\n${formatted}`);
            }
        } catch (err) {
            writeToTerminal(terminalId, '> Error connecting to the API:\n' + err.message, true);
        }
    }

    // Live Request: Send Single SMS
    async function runLiveSingle() {
        const token = getApiToken();
        if (!token) return;
        
        const terminalId = 'single_terminal';
        writeToTerminal(terminalId, '> Executing POST /api/v1/sms/send...\n> Awaiting response...');
        
        const recipient = document.getElementById('single_recipient').value.trim();
        const message = document.getElementById('single_message').value;
        const sender = document.getElementById('single_sender').value;
        
        try {
            const res = await fetch('/api/v1/sms/send', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    recipient: recipient,
                    message: message,
                    sender_name: sender
                })
            });
            
            const data = await res.json();
            const formatted = JSON.stringify(data, null, 2);
            
            if (res.ok) {
                writeToTerminal(terminalId, `<span style="color: #4ade80;">> Status: ${res.status} ${res.statusText}</span>\n\n${formatted}`);
            } else {
                writeToTerminal(terminalId, `<span style="color: #f87171;">> Status: ${res.status} ${res.statusText}</span>\n\n${formatted}`);
            }
        } catch (err) {
            writeToTerminal(terminalId, '> Error connecting to the API:\n' + err.message, true);
        }
    }

    // Live Request: Wallet Balance
    async function runLiveBalance() {
        const token = getApiToken();
        if (!token) return;
        
        const terminalId = 'balance_terminal';
        writeToTerminal(terminalId, '> Executing GET /api/v1/wallet/balance...\n> Awaiting response...');
        
        try {
            const res = await fetch('/api/v1/wallet/balance', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });
            
            const data = await res.json();
            const formatted = JSON.stringify(data, null, 2);
            
            if (res.ok) {
                writeToTerminal(terminalId, `<span style="color: #4ade80;">> Status: ${res.status} ${res.statusText}</span>\n\n${formatted}`);
            } else {
                writeToTerminal(terminalId, `<span style="color: #f87171;">> Status: ${res.status} ${res.statusText}</span>\n\n${formatted}`);
            }
        } catch (err) {
            writeToTerminal(terminalId, '> Error connecting to the API:\n' + err.message, true);
        }
    }

    // On Load Initializations
    document.addEventListener('DOMContentLoaded', () => {
        updateThemeUI();
    });
</script>

</body>
</html>
