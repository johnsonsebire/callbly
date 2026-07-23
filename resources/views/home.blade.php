<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Callbly — Cloud Telephony Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --neon-primary: oklch(78% 0.19 250);
            --neon-primary-dim: oklch(60% 0.14 250);
            --neon-secondary: oklch(72% 0.16 290);
            --neon-warm: oklch(75% 0.18 50);
            --surface-0: oklch(12% 0.012 250);
            --surface-1: oklch(16% 0.012 250);
            --surface-2: oklch(20% 0.012 250);
            --surface-3: oklch(25% 0.012 250);
            --text-primary: oklch(92% 0.008 250);
            --text-secondary: oklch(68% 0.01 250);
            --text-tertiary: oklch(50% 0.008 250);
            --border: oklch(25% 0.015 250);
            --glow-primary: oklch(78% 0.19 250 / 0.35);
            --glow-secondary: oklch(72% 0.16 290 / 0.25);
            --ease-out-expo: cubic-bezier(0.16, 1, 0.3, 1);
            --ease-out-quart: cubic-bezier(0.25, 1, 0.5, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            font-weight: 350;
            font-size: 1rem;
            line-height: 1.65;
            letter-spacing: 0.012em;
            color: var(--text-primary);
            background: var(--surface-0);
            font-optical-sizing: auto;
            -webkit-font-smoothing: antialiased;
        }

        article p { text-wrap: pretty; }
        h1, h2, h3 { text-wrap: balance; }

        /* Smooth scroll */
        html { scroll-behavior: smooth; }

        section[id] {
            scroll-margin-top: 68px;
        }

        /* Nav */
        .nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            padding: 20px 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: oklch(12% 0.012 250 / 0.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
        }

        .nav-brand {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--neon-primary);
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-brand svg {
            width: 28px;
            height: 28px;
        }

        .nav-links {
            display: flex;
            gap: 36px;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 400;
            transition: color 200ms var(--ease-out-expo);
        }

        .nav-links a:hover {
            color: var(--text-primary);
        }

        .nav-cta {
            padding: 10px 24px;
            background: var(--neon-primary);
            color: oklch(15% 0.02 250);
            border: none;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 150ms var(--ease-out-expo), box-shadow 200ms var(--ease-out-expo);
        }

        .nav-cta:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 24px var(--glow-primary);
        }

        /* Hero */
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 140px 48px 96px;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -200px;
            left: 50%;
            transform: translateX(-50%);
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, oklch(78% 0.19 250 / 0.07) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-eyebrow {
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--neon-primary);
            margin-bottom: 24px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border: 1px solid oklch(78% 0.19 250 / 0.25);
            border-radius: 100px;
            background: oklch(78% 0.19 250 / 0.05);
        }

        .hero-eyebrow::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--neon-primary);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        .hero h1 {
            font-size: clamp(2.8rem, 6vw, 4.5rem);
            font-weight: 800;
            line-height: 1.08;
            letter-spacing: -0.035em;
            max-width: 900px;
            margin-bottom: 28px;
        }

        .hero h1 span {
            color: var(--neon-primary);
        }

        .hero-sub {
            font-size: 1.2rem;
            font-weight: 350;
            color: var(--text-secondary);
            max-width: 560px;
            margin-bottom: 48px;
            line-height: 1.7;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .btn-primary {
            padding: 16px 36px;
            background: var(--neon-primary);
            color: oklch(12% 0.02 250);
            border: none;
            border-radius: 12px;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 150ms var(--ease-out-expo), box-shadow 200ms var(--ease-out-expo);
            box-shadow: 0 0 32px var(--glow-primary), 0 2px 8px oklch(0% 0 0 / 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 48px var(--glow-primary), 0 8px 32px oklch(0% 0 0 / 0.4);
        }

        .btn-secondary {
            padding: 16px 36px;
            background: transparent;
            color: var(--text-primary);
            border: 1px solid var(--border);
            border-radius: 12px;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: border-color 200ms var(--ease-out-expo), background 200ms var(--ease-out-expo);
        }

        .btn-secondary:hover {
            border-color: var(--text-tertiary);
            background: var(--surface-1);
        }

        .hero-stats {
            margin-top: 80px;
            display: flex;
            gap: 64px;
        }

        .hero-stat {
            text-align: center;
        }

        .hero-stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            font-variant-numeric: tabular-nums;
        }

        .hero-stat-label {
            font-size: 0.8rem;
            color: var(--text-tertiary);
            margin-top: 4px;
        }

        /* Features */
        .features {
            padding: 120px 48px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-label {
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--neon-primary);
            margin-bottom: 16px;
        }

        .section-title {
            font-size: clamp(2rem, 3.5vw, 2.8rem);
            font-weight: 700;
            letter-spacing: -0.025em;
            line-height: 1.15;
            margin-bottom: 64px;
            max-width: 600px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2px;
        }

        .feature-item {
            padding: 48px 36px;
            background: var(--surface-1);
            position: relative;
            transition: background 300ms var(--ease-out-expo);
        }

        .feature-item:first-child {
            border-radius: 16px 0 0 16px;
        }

        .feature-item:last-child {
            border-radius: 0 16px 16px 0;
        }

        .feature-item:hover {
            background: var(--surface-2);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            color: var(--neon-primary);
        }

        .feature-item:nth-child(2) .feature-icon {
            color: var(--neon-secondary);
        }

        .feature-item:nth-child(3) .feature-icon {
            color: var(--neon-warm);
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: 600;
            letter-spacing: -0.015em;
            margin-bottom: 12px;
        }

        .feature-desc {
            font-size: 0.925rem;
            color: var(--text-secondary);
            line-height: 1.7;
            max-width: 65ch;
        }

        .feature-metric {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            font-variant-numeric: tabular-nums;
        }

        .feature-metric-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--neon-primary);
        }

        .feature-item:nth-child(2) .feature-metric-value {
            color: var(--neon-secondary);
        }

        .feature-item:nth-child(3) .feature-metric-value {
            color: var(--neon-warm);
        }

        .feature-metric-label {
            font-size: 0.8rem;
            color: var(--text-tertiary);
            margin-top: 2px;
        }

        /* Video Demo */
        .demo {
            padding: 120px 48px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .demo-header {
            text-align: center;
            margin-bottom: 64px;
        }

        .demo-player {
            position: relative;
            width: 100%;
            aspect-ratio: 16/9;
            background: var(--surface-1);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: border-color 300ms var(--ease-out-expo);
        }

        .demo-player:hover {
            border-color: var(--neon-primary);
        }

        .demo-player:hover .play-btn {
            transform: scale(1.08);
            box-shadow: 0 0 48px var(--glow-primary);
        }

        .demo-player-bg {
            position: absolute;
            inset: 0;
            background: 
                linear-gradient(135deg, oklch(78% 0.19 250 / 0.03) 0%, transparent 50%),
                linear-gradient(225deg, oklch(72% 0.16 290 / 0.03) 0%, transparent 50%);
        }

        .demo-player-grid {
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(var(--border) 1px, transparent 1px),
                linear-gradient(90deg, var(--border) 1px, transparent 1px);
            background-size: 60px 60px;
            opacity: 0.3;
        }

        .play-btn {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--neon-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
            box-shadow: 0 0 32px var(--glow-primary);
            transition: transform 200ms var(--ease-out-expo), box-shadow 200ms var(--ease-out-expo);
        }

        .play-btn svg {
            width: 28px;
            height: 28px;
            margin-left: 4px;
            color: oklch(12% 0.02 250);
        }

        .demo-caption {
            text-align: center;
            margin-top: 24px;
            font-size: 0.875rem;
            color: var(--text-tertiary);
        }

        /* Social Proof */
        .proof {
            padding: 96px 48px;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .proof-inner {
            max-width: 1200px;
            margin: 0 auto;
        }

        .proof-logos {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 64px;
            margin-bottom: 72px;
            flex-wrap: wrap;
        }

        .proof-logo {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-tertiary);
            letter-spacing: -0.01em;
            opacity: 0.6;
            transition: opacity 200ms var(--ease-out-expo);
        }

        .proof-logo:hover {
            opacity: 1;
        }

        .proof-testimonial {
            max-width: 720px;
            margin: 0 auto;
            text-align: center;
        }

        .proof-quote {
            font-size: 1.4rem;
            font-weight: 400;
            line-height: 1.65;
            color: var(--text-primary);
            margin-bottom: 32px;
            font-style: italic;
        }

        .proof-author {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
        }

        .proof-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--surface-2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--neon-primary);
        }

        .proof-author-info {
            text-align: left;
        }

        .proof-author-name {
            font-weight: 600;
            font-size: 0.95rem;
        }

        .proof-author-role {
            font-size: 0.825rem;
            color: var(--text-tertiary);
        }

        /* Pricing */
        .pricing {
            padding: 120px 48px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .pricing-header {
            text-align: center;
            margin-bottom: 64px;
        }

        .pricing-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: var(--surface-1);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .pricing-table thead {
            background: var(--surface-2);
        }

        .pricing-table th {
            padding: 28px 32px;
            text-align: left;
            font-weight: 500;
            font-size: 0.85rem;
            color: var(--text-tertiary);
            letter-spacing: 0.04em;
            text-transform: uppercase;
            border-bottom: 1px solid var(--border);
        }

        .pricing-table th:not(:first-child) {
            text-align: center;
        }

        .pricing-table th.featured {
            color: var(--neon-primary);
            position: relative;
        }

        .pricing-table th.featured::after {
            content: 'Popular';
            position: absolute;
            top: 8px;
            right: 12px;
            font-size: 0.65rem;
            padding: 3px 8px;
            background: var(--neon-primary);
            color: oklch(12% 0.02 250);
            border-radius: 4px;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            font-weight: 600;
        }

        .pricing-table td {
            padding: 20px 32px;
            border-bottom: 1px solid var(--border);
            font-size: 0.925rem;
            color: var(--text-secondary);
        }

        .pricing-table td:not(:first-child) {
            text-align: center;
        }

        .pricing-table tr:last-child td {
            border-bottom: none;
        }

        .pricing-table .plan-price {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text-primary);
            font-variant-numeric: tabular-nums;
        }

        .pricing-table .plan-price span {
            font-size: 0.85rem;
            font-weight: 400;
            color: var(--text-tertiary);
        }

        .pricing-table .plan-price.featured-price {
            color: var(--neon-primary);
        }

        .pricing-table .check {
            color: var(--neon-primary);
            font-size: 1.1rem;
        }

        .pricing-table .dash {
            color: var(--text-tertiary);
        }

        .pricing-table .featured-col {
            background: oklch(78% 0.19 250 / 0.03);
        }

        .pricing-cta-row td {
            padding: 28px 32px !important;
        }

        .pricing-cta-btn {
            padding: 12px 28px;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text-primary);
            transition: all 200ms var(--ease-out-expo);
        }

        .pricing-cta-btn:hover {
            border-color: var(--text-tertiary);
            background: var(--surface-2);
        }

        .pricing-cta-btn.primary {
            background: var(--neon-primary);
            color: oklch(12% 0.02 250);
            border-color: var(--neon-primary);
            box-shadow: 0 0 20px var(--glow-primary);
        }

        .pricing-cta-btn.primary:hover {
            box-shadow: 0 0 32px var(--glow-primary);
            transform: translateY(-1px);
        }

        /* Footer */
        .footer {
            padding: 64px 48px;
            border-top: 1px solid var(--border);
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-brand {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-tertiary);
        }

        .footer-links {
            display: flex;
            gap: 32px;
            list-style: none;
        }

        .footer-links a {
            color: var(--text-tertiary);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 200ms var(--ease-out-expo);
        }

        .footer-links a:hover {
            color: var(--text-primary);
        }

        /* Animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero-eyebrow, .hero h1, .hero-sub, .hero-actions, .hero-stats {
            animation: fadeUp 800ms var(--ease-out-expo) backwards;
        }

        .hero h1 { animation-delay: 100ms; }
        .hero-sub { animation-delay: 200ms; }
        .hero-actions { animation-delay: 300ms; }
        .hero-stats { animation-delay: 450ms; }

        /* Mobile Menu */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            cursor: pointer;
            padding: 8px;
            z-index: 101;
            position: relative;
        }

        .mobile-menu-toggle svg {
            width: 28px;
            height: 28px;
            transition: transform 300ms var(--ease-out-expo);
        }

        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: oklch(12% 0.012 250 / 0.95);
            backdrop-filter: blur(24px);
            z-index: 99;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 24px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 300ms var(--ease-out-expo);
        }

        .mobile-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .mobile-overlay-content {
            transform: translateY(20px);
            opacity: 0;
            transition: all 400ms var(--ease-out-expo);
        }

        .mobile-overlay.active .mobile-overlay-content {
            transform: translateY(0);
            opacity: 1;
            transition-delay: 100ms;
        }

        .mobile-nav-links {
            list-style: none;
            text-align: center;
            margin-bottom: 48px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .mobile-nav-links a {
            color: var(--text-primary);
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: -0.01em;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .features-grid {
                grid-template-columns: 1fr;
                gap: 2px;
            }

            .feature-item:first-child {
                border-radius: 16px 16px 0 0;
            }

            .feature-item:last-child {
                border-radius: 0 0 16px 16px;
            }

            .hero-stats {
                gap: 40px;
            }

            .proof-logos {
                gap: 32px;
            }
        }

        @media (max-width: 768px) {
            .nav {
                padding: 16px 20px;
            }

            .nav-links, .nav-btn-signin, .nav-btn-signup {
                display: none !important;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .hero {
                padding: 110px 20px 80px;
            }

            .hero h1 {
                font-size: clamp(2.2rem, 8vw, 2.8rem);
                line-height: 1.15;
                margin-bottom: 20px;
            }

            .hero-sub {
                font-size: 1.05rem;
                margin-bottom: 32px;
            }

            .hero-actions {
                flex-direction: column;
                width: 100%;
            }

            .btn-primary, .btn-secondary {
                width: 100%;
                text-align: center;
            }

            .hero-stats {
                flex-direction: column;
                gap: 24px;
            }

            .features, .demo, .pricing {
                padding: 80px 24px;
            }

            .proof {
                padding: 64px 24px;
            }

            .pricing-table {
                display: block;
                overflow-x: auto;
            }

            .footer {
                flex-direction: column;
                gap: 24px;
                text-align: center;
                padding: 48px 24px;
            }

            .proof-quote {
                font-size: 1.15rem;
            }
        }

        /* CTA Section */
        .cta-section {
            padding: 90px 24px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .cta-card {
            position: relative;
            background: radial-gradient(circle at 50% 0%, oklch(22% 0.03 250) 0%, oklch(14% 0.015 250) 100%);
            border: 1px solid var(--border);
            border-radius: 28px;
            padding: 72px 48px;
            text-align: center;
            overflow: hidden;
            box-shadow: 0 24px 64px -16px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }
        .cta-glow-bg {
            position: absolute;
            top: -150px;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, var(--glow-primary) 0%, transparent 65%);
            pointer-events: none;
            opacity: 0.85;
        }
        .cta-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            border-radius: 100px;
            background: oklch(78% 0.19 250 / 0.1);
            border: 1px solid oklch(78% 0.19 250 / 0.3);
            color: var(--neon-primary);
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 24px;
        }
        .cta-badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--neon-primary);
            box-shadow: 0 0 8px var(--neon-primary);
            animation: pulse 2s infinite;
        }
        .cta-title {
            font-size: clamp(2rem, 4vw, 3.25rem);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.03em;
            color: var(--text-primary);
            max-width: 820px;
            margin: 0 auto 20px;
        }
        .cta-highlight {
            background: linear-gradient(135deg, var(--neon-primary) 0%, oklch(85% 0.15 220) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .cta-subtitle {
            font-size: 1.15rem;
            color: var(--text-secondary);
            max-width: 640px;
            margin: 0 auto 40px;
            line-height: 1.65;
            font-weight: 350;
        }
        .cta-actions {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 36px;
        }
        .cta-features-list {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 32px;
            flex-wrap: wrap;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }
        .cta-feature-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .cta-check-icon {
            width: 18px;
            height: 18px;
            color: var(--neon-primary);
        }
        @media (max-width: 768px) {
            .cta-card {
                padding: 48px 24px;
                border-radius: 20px;
            }
            .cta-features-list {
                gap: 16px;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="nav">
        <div class="nav-brand">
            <a href="/" style="display:flex; align-items:center;">
                <img src="{{ asset('assets/media/logos/callbly-white.png') }}" alt="Callbly Logo" style="height: 40px; width: auto; object-fit: contain;">
            </a>
        </div>
        <ul class="nav-links">
            <li><a href="#features">Features</a></li>
            <!-- <li><a href="#demo">Demo</a></li> -->
            <li><a href="#pricing">Pricing</a></li>
            <li><a href="{{ route('api.docs') }}">Developers</a></li>
        </ul>
        <div style="display: flex; align-items: center; gap: 12px;">
            @auth
                <a href="{{ route('profile.show') }}" class="btn-secondary nav-btn-signin" style="text-decoration:none; display:inline-flex; align-items:center; gap: 8px; padding: 10px 20px; font-size: 0.875rem; border-radius: 8px;">
                    <svg style="width: 16px; height: 16px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profile
                </a>
                <a href="{{ route('dashboard') }}" class="nav-cta nav-btn-signup" style="text-decoration:none; display:inline-block; text-align:center;">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-secondary nav-btn-signin" style="text-decoration:none; display:inline-flex; align-items:center; gap: 8px; padding: 10px 20px; font-size: 0.875rem; border-radius: 8px;">
                    <svg style="width: 16px; height: 16px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Sign In
                </a>
                <a href="{{ route('register') }}" class="nav-cta nav-btn-signup" style="text-decoration:none; display:inline-block; text-align:center;">Start Free Trial</a>
            @endauth
            
            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" id="mobile-menu-btn" aria-label="Toggle mobile menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>
    </nav>

    <!-- Mobile Overlay Menu -->
    <div class="mobile-overlay" id="mobile-overlay">
        <div class="mobile-overlay-content">
            <ul class="mobile-nav-links">
                <li><a href="#features" class="mobile-link">Features</a></li>
                <!-- <li><a href="#demo" class="mobile-link">Demo</a></li> -->
                <li><a href="#pricing" class="mobile-link">Pricing</a></li>
                <li><a href="{{ route('api.docs') }}" class="mobile-link">Developers</a></li>
            </ul>
            <div class="mobile-nav-actions">
                @auth
                    <a href="{{ route('profile.show') }}" class="btn-secondary mobile-link" style="width:100%; text-decoration:none; display:flex; justify-content:center; align-items:center; gap: 8px; padding: 12px 20px; font-size: 1rem; border-radius: 8px; margin-bottom:12px;">
                        <svg style="width: 18px; height: 18px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profile
                    </a>
                    <a href="{{ route('dashboard') }}" class="nav-cta mobile-link" style="width:100%; text-decoration:none; display:block; text-align:center; padding: 12px 20px; font-size: 1rem; border-radius: 8px;">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary mobile-link" style="width:100%; text-decoration:none; display:flex; justify-content:center; align-items:center; gap: 8px; padding: 12px 20px; font-size: 1rem; border-radius: 8px; margin-bottom:12px;">
                        <svg style="width: 18px; height: 18px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="nav-cta mobile-link" style="width:100%; text-decoration:none; display:block; text-align:center; padding: 12px 20px; font-size: 1rem; border-radius: 8px;">Start Free Trial</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Hero -->
    <section class="hero">
        <div class="hero-eyebrow">Now available in 140+ countries</div>
        <h1>Business calls, SMS, and contact center. <span>One platform.</span></h1>
        <p class="hero-sub">Route calls intelligently, send messages at scale, and manage your entire contact center from a single cloud-native platform. No hardware, no headaches.</p>
        <div class="hero-actions">
            <a href="{{ route('register') }}" class="btn-primary" style="text-decoration:none; display:inline-block; text-align:center;">Start Free</a>
            <a href="{{ route('login') }}" class="btn-secondary" style="text-decoration:none; display:inline-block; text-align:center;">Log In</a>
        </div>
        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hero-stat-value">99.99%</div>
                <div class="hero-stat-label">Uptime SLA</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-value">4.2M+</div>
                <div class="hero-stat-label">Calls routed daily</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-value">&lt;200ms</div>
                <div class="hero-stat-label">Avg. latency</div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="features" id="features">
        <div class="section-label">Capabilities</div>
        <h2 class="section-title">Everything your team needs to connect with customers</h2>
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Programmable Voice</h3>
                <p class="feature-desc">Build IVR trees, route calls by skill or geography, record and transcribe in real-time. Full API control over every leg of every call.</p>
                <div class="feature-metric">
                    <div class="feature-metric-value">140+</div>
                    <div class="feature-metric-label">Countries with local numbers</div>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                    </svg>
                </div>
                <h3 class="feature-title">SMS & Messaging</h3>
                <p class="feature-desc">Transactional alerts, marketing campaigns, two-way conversations. High-throughput delivery with carrier-grade reliability and compliance built in.</p>
                <div class="feature-metric">
                    <div class="feature-metric-value">98.7%</div>
                    <div class="feature-metric-label">Delivery rate globally</div>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                        <path d="M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                </div>
                <h3 class="feature-title">Contact Center</h3>
                <p class="feature-desc">Omnichannel queues, agent presence, live dashboards, whisper coaching. Spin up a full contact center without procurement cycles.</p>
                <div class="feature-metric">
                    <div class="feature-metric-value">47%</div>
                    <div class="feature-metric-label">Avg. reduction in wait time</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Demo -->
    <!--
    <section class="demo" id="demo">
        <div class="demo-header">
            <div class="section-label">See it in action</div>
            <h2 class="section-title" style="margin: 0 auto;">Two minutes to understand the platform</h2>
        </div>
        <div class="demo-player" onclick="this.querySelector('.play-btn').style.display='none'; this.innerHTML='<iframe src=\'about:blank\' style=\'width:100%;height:100%;border:none;\' allow=\'autoplay\'></iframe>'">
            <div class="demo-player-bg"></div>
            <div class="demo-player-grid"></div>
            <div class="play-btn">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <polygon points="5,3 19,12 5,21"/>
                </svg>
            </div>
        </div>
        <p class="demo-caption">Watch how healthcare providers cut their call abandonment rate by 62% in 3 weeks</p>
    </section>
    -->

    <!-- Social Proof -->
    <section class="proof">
        <div class="proof-inner">
            <div class="proof-logos">
                <span class="proof-logo">Healthcare</span>
                <span class="proof-logo">Finance</span>
                <span class="proof-logo">Logistics</span>
                <span class="proof-logo">Education</span>
                <span class="proof-logo">Retail</span>
                <span class="proof-logo">Technology & SaaS</span>
            </div>
            <div class="proof-testimonial">
                <p class="proof-quote">"Callbly enables us to seamlessly communicate with students on all occasions. From urgent updates to campus announcements, our messages deliver instantly with complete reliability."</p>
                <div class="proof-author">
                    <div class="proof-avatar">CP</div>
                    <div class="proof-author-info">
                        <div class="proof-author-name">Charles Agyemang Prempeh</div>
                        <div class="proof-author-role">PNMTC, Dormaa-Ahenkro</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing -->
    <section class="pricing" id="pricing">
        <div class="pricing-header">
            <div class="section-label">Pricing</div>
            <h2 class="section-title" style="margin: 0 auto;">Transparent pricing, no per-minute surprises</h2>
        </div>
        <table class="pricing-table">
            <thead>
                <tr>
                    <th></th>
                    <th>Basic</th>
                    <th>Plus</th>
                    <th class="featured">Premium</th>
                    <th>Gold</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Price per SMS</td>
                    <td><div class="plan-price">₵0.035</div></td>
                    <td><div class="plan-price">₵0.032</div></td>
                    <td class="featured-col"><div class="plan-price featured-price">₵0.029</div></td>
                    <td><div class="plan-price">₵0.025</div></td>
                </tr>
                <tr>
                    <td>Target Audience</td>
                    <td>For businesses just getting started</td>
                    <td>Better rates for regular users</td>
                    <td class="featured-col">Great value for high volume</td>
                    <td>Best rates for enterprise</td>
                </tr>
                <tr>
                    <td>SMS Features</td>
                    <td>All SMS Features</td>
                    <td>All Basic Features</td>
                    <td class="featured-col">All Plus Features</td>
                    <td>All Premium Features</td>
                </tr>
                <tr>
                    <td>Cost Savings</td>
                    <td><span class="dash">—</span></td>
                    <td>8.6% Savings</td>
                    <td class="featured-col">17% Savings</td>
                    <td>29% Savings</td>
                </tr>
                <tr>
                    <td>Purchase Range</td>
                    <td>₵0 - ₵1,499</td>
                    <td>₵1,500 - ₵2,999</td>
                    <td class="featured-col">₵3,000 - ₵5,999</td>
                    <td>₵6,000+</td>
                </tr>
                <tr>
                    <td>Support Level</td>
                    <td>Standard</td>
                    <td>Priority Support</td>
                    <td class="featured-col">Dedicated Account Manager</td>
                    <td>24/7 Premium Support</td>
                </tr>
                <tr>
                    <td>Additional Perks</td>
                    <td>Custom Sender Names<br>Delivery Reports<br>API Access</td>
                    <td>Advanced Analytics</td>
                    <td class="featured-col">Custom Integrations</td>
                    <td>SLA Guarantee</td>
                </tr>
                <tr class="pricing-cta-row">
                    <td></td>
                    <td><button class="pricing-cta-btn">Buy Credits</button></td>
                    <td><button class="pricing-cta-btn">Buy Credits</button></td>
                    <td class="featured-col"><button class="pricing-cta-btn primary">Buy Credits</button></td>
                    <td><button class="pricing-cta-btn">Buy Credits</button></td>
                </tr>
            </tbody>
        </table>
    </section>

    <!-- Call To Action Section -->
    <section class="cta-section" id="get-started">
        <div class="cta-card">
            <div class="cta-glow-bg"></div>
            <div class="cta-badge">
                <span class="cta-badge-dot"></span>
                Start Sending Today
            </div>
            <h2 class="cta-title">
                Ready to Transform Your <span class="cta-highlight">Business Communication?</span>
            </h2>
            <p class="cta-subtitle">
                Join thousands of businesses using Callbly's cloud telephony solutions to enhance customer interactions and drive growth.
            </p>
            <div class="cta-actions">
                <a href="{{ route('register') }}" class="btn-primary" style="text-decoration:none; display:inline-block; text-align:center;">Get Started Free</a>
                <a href="{{ route('api.docs') }}" class="btn-secondary" style="text-decoration:none; display:inline-block; text-align:center;">Explore API Docs &rarr;</a>
            </div>
            <div class="cta-features-list">
                <span class="cta-feature-item">
                    <svg class="cta-check-icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    No credit card required
                </span>
                <span class="cta-feature-item">
                    <svg class="cta-check-icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Instant API access
                </span>
                <span class="cta-feature-item">
                    <svg class="cta-check-icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    99.99% SLA Uptime
                </span>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-brand">© {{ date('Y') }} Callbly</div>
        <ul class="footer-links">
            <li><a href="{{ route('privacy') }}">Privacy</a></li>
            <li><a href="{{ route('terms') }}">Terms</a></li>
            <li><a href="{{ route('status') }}">Status</a></li>
            <li><a href="{{ route('contact') }}">Support</a></li>
            <li><a href="{{ route('api.docs') }}">API Docs</a></li>
        </ul>
    </footer>
    <script>
        // Smooth scroll — uses scrollIntoView so scroll-margin-top handles the nav offset precisely
        document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
            anchor.addEventListener('click', function (e) {
                var targetId = this.getAttribute('href').slice(1);
                var target = document.getElementById(targetId);
                if (!target) return;
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                history.pushState(null, '', '#' + targetId);
            });
        });

        // Mobile Menu Toggle
        document.addEventListener('DOMContentLoaded', () => {
            const menuBtn = document.getElementById('mobile-menu-btn');
            const mobileOverlay = document.getElementById('mobile-overlay');
            const mobileLinks = document.querySelectorAll('.mobile-link');

            function toggleMenu() {
                const isOpen = mobileOverlay.classList.contains('active');
                if (isOpen) {
                    mobileOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                } else {
                    mobileOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            }

            if (menuBtn && mobileOverlay) {
                menuBtn.addEventListener('click', toggleMenu);
            }

            mobileLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (mobileOverlay.classList.contains('active')) {
                        toggleMenu();
                    }
                });
            });
        });
    </script>
</body>
</html>