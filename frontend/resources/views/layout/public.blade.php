<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoTech Pro</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=6">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800;900&family=DM+Sans:wght@300;400;500;700;900&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script>
        (function () {
            const theme = localStorage.getItem('autotech-theme') || 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    <style>
        :root {
            --red:          #C40000;
            --red-h:        #E00000;
            --red-dim:      rgba(196,0,0,.10);
            --red-glow:     rgba(196,0,0,.20);
            --red-border:   rgba(196,0,0,.28);
            --bg:           #070707;
            --surface:      #0F0F0F;
            --surface2:     #151515;
            --surface3:     #1C1C1C;
            --border:       rgba(255,255,255,.055);
            --border2:      rgba(255,255,255,.10);
            --border3:      rgba(255,255,255,.16);
            --text:         #F0F0F0;
            --text2:        #D0D0D0;
            --text3:        #A8A8A8;
            --radius:       10px;
            --radius-sm:    7px;
            --red2:         var(--red-h);
            --ink:          var(--bg);
            --line:         var(--border2);
            --muted:        var(--text2);
            --muted2:       var(--text3);
            --content: 1180px;
        }

        :root[data-theme="light"] {
            --red:          #B00000;
            --red-h:        #D00000;
            --red-dim:      rgba(176,0,0,.10);
            --red-glow:     rgba(176,0,0,.16);
            --red-border:   rgba(176,0,0,.24);
            --bg:           #F4F1EC;
            --surface:      #FFFFFF;
            --surface2:     #F7F3EE;
            --surface3:     #ECE6DE;
            --border:       rgba(31,25,20,.10);
            --border2:      rgba(31,25,20,.16);
            --border3:      rgba(31,25,20,.24);
            --text:         #181512;
            --text2:        #4F4840;
            --text3:        #7D7469;
            --red2:         var(--red-h);
            --ink:          var(--bg);
            --line:         var(--border2);
            --muted:        var(--text2);
            --muted2:       var(--text3);
        }

        * { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            background: var(--ink);
            overflow-x: hidden;
        }

        :root[data-theme="light"] body {
            background:
                radial-gradient(circle at 82% -10%, rgba(176,0,0,.12), transparent 32%),
                linear-gradient(135deg, #f8f5ef 0%, #efe9e1 58%, #f6f1ea 100%);
        }

        a { color: inherit; }

        .site-shell {
            min-height: 100vh;
            background:
                radial-gradient(circle at 78% -10%, var(--red-dim), transparent 34%),
                var(--bg);
        }

        :root[data-theme="light"] .site-shell {
            background:
                radial-gradient(circle at 78% -10%, rgba(176,0,0,.12), transparent 34%),
                radial-gradient(circle at 12% 10%, rgba(255,255,255,.85), transparent 28%),
                linear-gradient(135deg, #F8F5EF 0%, #EFE9E1 58%, #F6F1EA 100%);
        }

        .topbar {
            position: fixed;
            inset: 0 0 auto;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            min-height: 78px;
            padding: .9rem clamp(1rem, 4vw, 3.2rem);
            background: rgba(7,7,7,.92);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(14px);
        }

        :root[data-theme="light"] .topbar {
            background: rgba(255,250,245,.94);
            border-bottom-color: var(--border2);
            box-shadow: 0 12px 30px rgba(66,45,25,.12);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: .8rem;
            text-decoration: none;
        }

        .brand-mark,
        .page-loader-mark {
            position: relative;
            overflow: hidden;
            display: grid;
            place-items: center;
            background:
                radial-gradient(circle at 22% 16%, rgba(255,255,255,.32), transparent 34%),
                linear-gradient(135deg, #ff2424 0%, var(--red) 48%, #650000 100%);
            box-shadow: 0 16px 38px rgba(196,0,0,.28), inset 0 1px 0 rgba(255,255,255,.20);
        }

        .brand-mark {
            width: 46px;
            height: 46px;
            border-radius: 14px;
        }

        .brand-mark::before,
        .page-loader-mark::before {
            content: '\F3E5';
            position: relative;
            z-index: 1;
            font-family: "bootstrap-icons";
            font-weight: 400;
            letter-spacing: 0;
            color: #fff;
            animation: brandGearSpin 7s linear infinite;
            text-shadow: 0 2px 10px rgba(0,0,0,.24);
        }

        .brand-mark::before { font-size: 16px; }

        .brand-mark::after,
        .page-loader-mark::after { display: none; }

        .brand-mark i,
        .page-loader-mark i { display: none; }

        @keyframes brandGearSpin {
            to { transform: rotate(360deg); }
        }

        .brand-text strong {
            display: block;
            font-family: 'Syne', sans-serif;
            font-size: 1.05rem;
            line-height: 1;
            font-weight: 900;
            letter-spacing: .02em;
        }

        .brand-text span {
            color: var(--muted2);
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .16em;
        }

        .nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: clamp(.8rem, 2vw, 1.5rem);
        }

        .nav a {
            color: var(--muted);
            text-decoration: none;
            font-size: .82rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .12em;
        }

        .nav a:hover { color: #fff; }
        :root[data-theme="light"] .nav a:hover { color: var(--text); }

        .actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: .65rem;
        }

        .btn {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            padding: .72rem 1rem;
            border-radius: var(--radius-sm);
            border: 1px solid var(--line);
            text-decoration: none;
            font-size: .86rem;
            font-weight: 700;
            transition: border-color .18s ease, background .18s ease, color .18s ease, box-shadow .18s ease;
        }

        .btn:hover { transform: none; }

        .btn-red {
            background: var(--red);
            border-color: var(--red);
            color: #fff;
            box-shadow: none;
        }

        .btn-red:hover {
            background: var(--red-h);
            border-color: var(--red-h);
            box-shadow: 0 4px 16px rgba(196,0,0,.3);
        }

        .btn-line {
            background: transparent;
            color: var(--text2);
        }

        :root[data-theme="light"] .btn-line {
            background: transparent;
            color: var(--text);
            border-color: var(--border2);
        }

        .theme-toggle {
            width: 42px;
            min-width: 42px;
            padding: 0;
        }

        .theme-toggle .theme-light-icon { display: none; }
        :root[data-theme="light"] .theme-toggle .theme-dark-icon { display: none; }
        :root[data-theme="light"] .theme-toggle .theme-light-icon { display: inline-block; }

        .hero {
            min-height: 100vh;
            display: grid;
            align-items: end;
            padding: 120px clamp(1rem, 4vw, 3.2rem) clamp(1.6rem, 5vw, 4rem);
            position: relative;
            overflow: hidden;
            background:
                linear-gradient(90deg, rgba(7,7,7,.94) 0%, rgba(7,7,7,.78) 45%, rgba(7,7,7,.30) 100%),
                linear-gradient(180deg, rgba(7,7,7,.18), var(--bg) 95%),
                url('https://images.unsplash.com/photo-1487754180451-c456f719a1fc?auto=format&fit=crop&w=1800&q=80') center right / cover;
        }

        :root[data-theme="light"] .hero {
            background:
                linear-gradient(90deg, rgba(255,250,245,.96) 0%, rgba(255,250,245,.82) 44%, rgba(255,250,245,.30) 100%),
                linear-gradient(180deg, rgba(255,255,255,.10), #f4f1ec 95%),
                url('https://images.unsplash.com/photo-1487754180451-c456f719a1fc?auto=format&fit=crop&w=1800&q=80') center right / cover;
        }

        .hero::after {
            content: '';
            position: absolute;
            inset: auto 0 0;
            height: 140px;
            background: linear-gradient(180deg, transparent, #050505);
            pointer-events: none;
        }

        :root[data-theme="light"] .hero::after {
            background: linear-gradient(180deg, transparent, #f4f1ec);
        }

        .hero-content {
            position: relative;
            z-index: 1;
            width: min(1120px, 100%);
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            color: #fff;
            font-size: .78rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .18em;
        }

        .eyebrow::before {
            content: '';
            width: 44px;
            height: 2px;
            background: var(--red2);
        }

        .hero h1 {
            margin: .8rem 0 0;
            max-width: 780px;
            font-family: 'Syne', sans-serif;
            font-size: clamp(2rem, 4.7vw, 4.45rem);
            line-height: 1.02;
            font-weight: 900;
            letter-spacing: 0;
            text-transform: uppercase;
        }

        .hero h1 .hero-red {
            color: var(--red2);
        }

        .dynamic-word {
            display: inline-flex;
            align-items: baseline;
            min-width: 6.8em;
            color: var(--red2);
        }

        .dynamic-word::after {
            content: '';
            width: .08em;
            height: .9em;
            margin-left: .08em;
            background: currentColor;
            animation: caretBlink .75s steps(1) infinite;
        }

        .dynamic-wrap {
            display: inline-flex;
            align-items: baseline;
            white-space: nowrap;
            color: var(--red2);
        }

        .dynamic-wrap .dynamic-word {
            color: inherit;
        }

        @keyframes caretBlink {
            0%, 48% { opacity: 1; }
            49%, 100% { opacity: 0; }
        }

        .hero-copy {
            margin: 1.2rem 0 0;
            max-width: 620px;
            color: var(--muted);
            font-size: clamp(1rem, 2vw, 1.22rem);
            line-height: 1.55;
        }

        .hero-actions {
            display: flex;
            gap: .75rem;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .8rem;
            width: min(720px, 100%);
            margin-top: 2rem;
        }

        .stat {
            min-height: 96px;
            padding: .95rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: var(--surface);
            backdrop-filter: blur(10px);
        }

        :root[data-theme="light"] .stat,
        :root[data-theme="light"] .eco-card,
        :root[data-theme="light"] .service-intro,
        :root[data-theme="light"] .service-item,
        :root[data-theme="light"] .testimonial-card,
        :root[data-theme="light"] .empty-reviews {
            background: var(--surface);
            border-color: var(--border);
            box-shadow: none;
        }

        .stat strong {
            display: block;
            color: #fff;
            font-family: 'DM Mono', monospace;
            font-size: 1.55rem;
        }

        :root[data-theme="light"] .stat strong,
        :root[data-theme="light"] .eco-card h3,
        :root[data-theme="light"] .service-intro h3,
        :root[data-theme="light"] .service-item strong,
        :root[data-theme="light"] .testimonial-author strong,
        :root[data-theme="light"] .section-head h2,
        :root[data-theme="light"] .hero h1,
        :root[data-theme="light"] .eyebrow {
            color: var(--text);
        }

        .stat span {
            display: block;
            margin-top: .25rem;
            color: var(--muted2);
            font-size: .82rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-weight: 900;
        }

        .section {
            padding: clamp(2rem, 6vw, 5rem) clamp(1rem, 4vw, 3.2rem);
        }

        .section-head {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 1rem;
            margin: 0 auto 1rem;
            width: min(var(--content), 100%);
        }

        .section-head h2 {
            margin: 0;
            max-width: 760px;
            font-family: 'Syne', sans-serif;
            font-size: clamp(1.7rem, 4vw, 3.4rem);
            line-height: .95;
            text-transform: uppercase;
            font-weight: 900;
        }

        .section-head p {
            margin: 0;
            max-width: 420px;
            color: var(--muted);
            line-height: 1.5;
        }

        .ecosystem {
            width: min(var(--content), 100%);
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .85rem;
        }

        .eco-card {
            min-height: 270px;
            padding: 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: var(--surface);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            position: relative;
        }

        .eco-card::before {
            content: '';
            position: absolute;
            inset: 0 0 auto;
            height: 3px;
            background: var(--red);
        }

        .eco-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-sm);
            display: grid;
            place-items: center;
            background: var(--red-dim);
            color: var(--red2);
            font-size: 1.35rem;
        }

        .eco-card h3 {
            margin: 1rem 0 .45rem;
            font-family: 'Syne', sans-serif;
            font-size: 1.18rem;
            font-weight: 900;
            text-transform: uppercase;
        }

        .eco-card p {
            margin: 0;
            color: var(--muted);
            line-height: 1.48;
        }

        .eco-card a {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            margin-top: 1rem;
            color: var(--red-h);
            font-weight: 900;
            text-decoration: none;
        }

        .services-showcase {
            width: min(var(--content), 100%);
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .service-intro {
            padding: clamp(1rem, 3vw, 1.4rem);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: var(--surface);
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: end;
            gap: 1rem;
        }

        .service-intro h3 {
            margin: 0;
            color: #fff;
            font-family: 'Syne', sans-serif;
            font-size: clamp(1.65rem, 3vw, 3rem);
            line-height: 1;
            font-weight: 900;
            text-transform: uppercase;
            max-width: 760px;
            overflow-wrap: break-word;
        }

        .service-intro p {
            margin: .85rem 0 0;
            color: var(--muted);
            line-height: 1.55;
        }

        .service-list {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .8rem;
        }

        .service-item {
            min-height: 230px;
            padding: 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: var(--surface);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .service-item-top {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr);
            align-items: center;
            gap: .75rem;
        }

        .service-icon {
            width: 42px;
            height: 42px;
            border-radius: var(--radius-sm);
            display: grid;
            place-items: center;
            background: var(--red-dim);
            color: var(--red2);
        }

        .service-item strong {
            display: block;
            color: #fff;
            font-size: 1.02rem;
            line-height: 1.2;
        }

        .service-item p {
            margin: .75rem 0 0;
            color: var(--muted2);
            font-size: .92rem;
            line-height: 1.4;
        }

        .service-tag {
            display: inline-flex;
            width: fit-content;
            padding: .24rem .5rem;
            border-radius: var(--radius-sm);
            color: #fff;
            background: var(--red-dim);
            border: 1px solid var(--red-border);
            font-size: .72rem;
            font-weight: 900;
            white-space: nowrap;
        }

        .testimonial-grid {
            width: min(var(--content), 100%);
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .85rem;
        }

        .testimonial-card {
            min-height: 230px;
            padding: 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: var(--surface);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .stars {
            display: flex;
            gap: .18rem;
            color: var(--red2);
            font-size: .92rem;
        }

        .testimonial-card p {
            margin: 1rem 0;
            color: var(--muted);
            line-height: 1.55;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: .7rem;
        }

        .author-avatar {
            width: 42px;
            height: 42px;
            border-radius: var(--radius-sm);
            display: grid;
            place-items: center;
            background: var(--red);
            color: #fff;
            font-family: 'Syne', sans-serif;
            font-weight: 900;
        }

        .testimonial-author strong {
            display: block;
            color: #fff;
            font-size: .95rem;
        }

        .testimonial-author span {
            color: var(--muted2);
            font-size: .82rem;
        }

        .reviews-actions {
            width: min(var(--content), 100%);
            margin: 1rem auto 0;
            display: flex;
            justify-content: center;
        }

        .empty-reviews {
            width: min(var(--content), 100%);
            margin: 0 auto;
            padding: 1.2rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: var(--surface);
            color: var(--muted);
            text-align: center;
        }

        .footer-cta {
            padding: clamp(2rem, 6vw, 4rem) clamp(1rem, 4vw, 3.2rem);
            border-top: 1px solid var(--border);
            background: var(--surface);
            color: var(--text);
        }

        :root[data-theme="light"] .footer-cta {
            background: var(--surface);
            color: var(--text);
        }

        :root[data-theme="light"] .footer-inner p {
            color: var(--text2);
        }

        :root[data-theme="light"] .footer-cta .btn-line {
            color: var(--text);
            border-color: var(--border2);
            background: transparent;
        }

        .footer-inner {
            width: min(var(--content), 100%);
            margin: 0 auto;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 1rem;
            align-items: center;
        }

        .footer-inner h2 {
            margin: 0;
            font-family: 'Syne', sans-serif;
            font-size: clamp(1.7rem, 4vw, 3.4rem);
            line-height: .95;
            text-transform: uppercase;
        }

        .footer-inner p {
            margin: .5rem 0 0;
            color: var(--text2);
            max-width: 660px;
        }

        .footer-cta .btn-line {
            color: var(--text2);
            border-color: var(--border2);
            background: transparent;
        }

        .btn,
        .theme-toggle,
        .eco-card,
        .service-item,
        .testimonial-card,
        .stat,
        .step-card {
            transition:
                transform .18s ease,
                box-shadow .18s ease,
                border-color .18s ease,
                background .18s ease,
                color .18s ease;
        }

        .btn,
        .theme-toggle {
            position: relative;
            overflow: hidden;
            transform: translateZ(0);
        }

        .btn:hover,
        .theme-toggle:hover {
            transform: translateY(-1px);
        }

        .btn:active,
        .theme-toggle:active {
            transform: translateY(0) scale(.98);
        }

        .eco-card:hover,
        .service-item:hover,
        .testimonial-card:hover,
        .stat:hover,
        .step-card:hover {
            transform: translateY(-3px);
            border-color: var(--red-border);
            box-shadow: 0 22px 58px rgba(0,0,0,.28);
        }

        .ui-ripple {
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
            transform: scale(0);
            opacity: .44;
            background: rgba(255,255,255,.45);
            animation: uiRipple .62s ease-out forwards;
        }

        @keyframes uiRipple {
            to {
                transform: scale(18);
                opacity: 0;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: .001ms !important;
                transition-duration: .001ms !important;
                scroll-behavior: auto !important;
            }

            .btn:hover,
            .theme-toggle:hover,
            .eco-card:hover,
            .service-item:hover,
            .testimonial-card:hover,
            .stat:hover,
            .step-card:hover {
                transform: none;
            }

            .ui-ripple {
                display: none !important;
            }

            .brand-mark::before,
            .page-loader-mark::before {
                animation: none !important;
            }
        }

        .page-loader {
            display: none !important;
        }

        .page-loader.is-active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .page-loader-card {
            min-width: min(320px, calc(100vw - 40px));
            display: grid;
            justify-items: center;
            gap: 14px;
            padding: 28px 26px;
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 12px;
            background: linear-gradient(145deg, rgba(24,24,24,.92), rgba(12,12,12,.92));
            box-shadow: 0 28px 80px rgba(0,0,0,.42);
            color: #fff;
            text-align: center;
        }

        .page-loader-mark {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            animation: loaderPulse 1.1s ease-in-out infinite;
        }

        .page-loader-mark::before { font-size: 18px; }

        .page-loader-text strong {
            display: block;
            font-family: 'Syne', sans-serif;
            font-size: .95rem;
            font-weight: 800;
            letter-spacing: .04em;
        }

        .page-loader-text span {
            display: block;
            margin-top: 2px;
            color: rgba(255,255,255,.68);
            font-size: .82rem;
        }

        .page-loader-bar {
            width: 180px;
            height: 4px;
            overflow: hidden;
            border-radius: 999px;
            background: rgba(255,255,255,.10);
        }

        .page-loader-bar span {
            display: block;
            width: 42%;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, transparent, #ff2020, transparent);
            animation: loaderBar 1s ease-in-out infinite;
        }

        :root[data-theme="light"] .page-loader {
            background:
                radial-gradient(circle at 50% 38%, rgba(196,0,0,.14), transparent 28%),
                rgba(248,245,239,.78);
        }

        :root[data-theme="light"] .page-loader-card {
            background: linear-gradient(145deg, rgba(255,250,245,.94), rgba(246,240,232,.94));
            border-color: rgba(31,25,20,.14);
            color: #17120f;
            box-shadow: 0 28px 80px rgba(70,35,15,.20);
        }

        :root[data-theme="light"] .page-loader-text span {
            color: rgba(31,25,20,.62);
        }

        :root[data-theme="light"] .page-loader-bar {
            background: rgba(31,25,20,.10);
        }

        @keyframes loaderSpin {
            to { transform: rotate(360deg); }
        }

        @keyframes loaderPulse {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-2px) scale(1.04); }
        }

        @keyframes loaderBar {
            0% { transform: translateX(-120%); }
            100% { transform: translateX(260%); }
        }

        @media (max-width: 980px) {
            .nav { display: none; }

            .ecosystem,
            .services-showcase,
            .testimonial-grid,
            .footer-inner {
                grid-template-columns: 1fr;
            }

            .ecosystem,
            .service-list {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .service-intro {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 680px) {
            .topbar {
                position: sticky;
                top: 0;
                align-items: center;
                flex-direction: row;
                background: rgba(5,5,5,.96);
            }

            .hero-actions .btn,
            .footer-inner .btn {
                width: 100%;
            }

            .actions {
                width: auto;
                flex: 0 0 auto;
                gap: .5rem;
            }

            .actions .btn {
                min-height: 44px;
                padding-inline: .85rem;
            }

            .hero {
                min-height: auto;
                padding-top: 1.6rem;
                background-position: center;
            }

            .hero h1 {
                max-width: 100%;
                font-size: clamp(1.9rem, 8.6vw, 2.75rem);
                line-height: 1;
            }

            .hero-stats,
            .ecosystem,
            .service-list,
            .testimonial-grid {
                grid-template-columns: 1fr;
            }

            .dynamic-word {
                min-width: 0;
            }

            .dynamic-wrap {
                display: inline-flex;
                width: auto;
                max-width: 100%;
                white-space: normal;
            }

            .section-head {
                display: block;
            }

            .section-head p {
                margin-top: .75rem;
            }
        }

        @media (max-width: 520px) {
            html,
            body {
                width: 100%;
                max-width: 100%;
                overflow-x: hidden;
            }

            .site-shell {
                width: 100%;
                max-width: 100vw;
                padding-inline: .55rem;
                overflow-x: hidden;
            }

            .topbar {
                width: 100%;
                max-width: 100%;
                gap: .55rem;
                padding: .72rem;
                border-radius: 0 0 16px 16px;
            }

            .brand {
                flex: 1 1 auto;
                min-width: 0;
                gap: .6rem;
            }

            .brand-mark {
                width: 42px;
                height: 42px;
                border-radius: 12px;
            }

            .brand-text strong {
                font-size: .98rem;
            }

            .brand-text span {
                font-size: .68rem;
                letter-spacing: .14em;
            }

            .brand-text strong,
            .brand-text span {
                max-width: calc(100vw - 145px);
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .actions {
                display: grid;
                grid-template-columns: 44px 46px;
                gap: .5rem;
            }

            .actions .btn {
                min-height: 44px;
                justify-content: center;
                padding-inline: .75rem;
            }

            .actions .btn-login-top {
                width: 46px;
                padding-inline: 0;
            }

            .actions .btn-login-top span {
                display: none;
            }

            .theme-toggle {
                width: 44px;
                min-width: 44px;
            }

            .hero {
                padding: 1.15rem 0 2rem;
            }

            .hero h1,
            .section-title,
            .workflow-title {
                overflow-wrap: anywhere;
                word-break: normal;
                hyphens: auto;
            }

            .hero h1 {
                max-width: 13ch;
                font-size: clamp(1.85rem, 8.8vw, 2.35rem);
                line-height: 1.02;
            }

            .dynamic-wrap {
                display: inline;
            }

            .dynamic-word {
                display: inline;
                min-width: 0;
            }

            .hero-copy {
                max-width: 28rem;
                font-size: .96rem;
                line-height: 1.5;
            }

            .hero-stats {
                gap: .55rem;
                margin-top: 1.2rem;
            }

            .stat {
                min-height: 86px;
                padding: .85rem;
            }

            .stat strong {
                font-size: 1.38rem;
            }

            .stat span {
                font-size: .72rem;
                letter-spacing: .06em;
            }

            .section {
                padding: 1.8rem 0;
                width: 100%;
                max-width: 100%;
                overflow: hidden;
            }

            .section-head,
            .ecosystem,
            .services-showcase,
            .testimonial-grid,
            .footer-inner {
                width: 100%;
                max-width: 100%;
                min-width: 0;
            }

            .section-head h2,
            .service-intro h3,
            .footer-inner h2 {
                max-width: 100%;
                font-size: clamp(1.45rem, 8.6vw, 2.15rem);
                line-height: 1.02;
                overflow-wrap: anywhere;
                word-break: normal;
            }

            .section-head p,
            .service-intro p,
            .eco-card p,
            .service-item p {
                font-size: .95rem;
            }

            .eco-card,
            .service-intro,
            .service-item,
            .testimonial-card {
                width: 100%;
                max-width: 100%;
                min-width: 0;
            }

            .eco-card {
                min-height: auto;
                padding: .95rem;
            }

            .service-intro {
                grid-template-columns: 1fr;
                align-items: start;
            }

            .service-list {
                grid-template-columns: 1fr;
                width: 100%;
                max-width: 100%;
            }

            .service-item {
                min-height: auto;
            }

            .service-item strong {
                font-size: .98rem;
                overflow-wrap: anywhere;
            }

            .hero-actions,
            .footer-actions {
                display: grid;
                grid-template-columns: 1fr;
                gap: .65rem;
                width: 100%;
            }

            .hero-actions .btn,
            .footer-actions .btn {
                min-height: 48px;
                justify-content: center;
            }

            section,
            .section-card,
            .workflow-card,
            .service-card,
            .testimonial-card,
            .footer-inner {
                border-radius: 14px;
            }

            .section-pad,
            .section-card,
            .workflow-card,
            .service-card,
            .testimonial-card {
                padding: 1rem;
            }

            .hero-stats {
                gap: .65rem;
            }

            .stat-item {
                min-height: 92px;
            }
        }

        @media (max-width: 390px) {
            .site-shell {
                padding-inline: .45rem;
            }

            .topbar {
                padding-inline: .55rem;
            }

            .brand-mark {
                width: 40px;
                height: 40px;
            }

            .brand-text strong {
                font-size: .9rem;
            }

            .brand-text span {
                font-size: .62rem;
                letter-spacing: .12em;
            }

            .brand-text strong,
            .brand-text span {
                max-width: calc(100vw - 138px);
            }

            .hero h1 {
                max-width: 12ch;
                font-size: clamp(1.62rem, 8.4vw, 2.05rem);
            }

            .section-head h2,
            .service-intro h3,
            .footer-inner h2 {
                font-size: clamp(1.28rem, 7.8vw, 1.85rem);
            }

            .section,
            .section-pad,
            .section-card,
            .workflow-card,
            .service-card,
            .testimonial-card,
            .service-intro,
            .eco-card,
            .service-item {
                padding-inline: .85rem;
            }
        }
    </style>
</head>
<body>
    <div class="page-loader" id="pageLoader" aria-hidden="true">
        <div class="page-loader-card" role="status" aria-live="polite">
            <span class="page-loader-mark"><i class="bi bi-gear-wide-connected"></i></span>
            <span class="page-loader-text">
                <strong>Carregando</strong>
                <span>Preparando a proxima tela</span>
            </span>
            <span class="page-loader-bar"><span></span></span>
        </div>
    </div>

    <div class="site-shell">
        <header class="topbar">
            <a href="{{ url('/') }}" class="brand">
                <span class="brand-mark"><i class="bi bi-gear-wide-connected"></i></span>
                <span class="brand-text">
                    <strong>AutoTech</strong>
                    <span>Oficina Pro</span>
                </span>
            </a>

            <nav class="nav">
                <a href="#oficina">Oficina</a>
                <a href="#servicos">Servicos</a>
                <a href="#atendimento">Atendimento</a>
                <a href="#comentarios">Comentarios</a>
            </nav>

            <div class="actions">
                <button class="btn btn-line theme-toggle" type="button" id="themeToggle" title="Alternar modo claro/escuro" aria-label="Alternar modo claro e escuro">
                    <i class="bi bi-sun theme-dark-icon"></i>
                    <i class="bi bi-moon-stars theme-light-icon"></i>
                </button>
                <a href="{{ route('login') }}" class="btn btn-red btn-login-top" aria-label="Entrar no AutoTech">
                    <i class="bi bi-person-circle"></i>
                    <span>Entrar</span>
                </a>
            </div>
        </header>

        <main>
            <section class="hero" id="oficina">
                <div class="hero-content">
                    <div class="eyebrow">oficina conectada</div>
                    <h1>Servicos de oficina com atendimento <span class="dynamic-wrap"><span class="dynamic-word" id="dynamicWord">preciso</span>.</span></h1>
                    <p class="hero-copy">Abra uma solicitacao, cadastre seu veiculo, acompanhe o orcamento e saiba cada etapa do atendimento sem precisar correr atras de informacao.</p>
                    <div class="hero-actions">
                        <a href="{{ route('login') }}" class="btn btn-red"><i class="bi bi-speedometer2"></i>Acessar sistema</a>
                        <a href="{{ route('register') }}" class="btn btn-line"><i class="bi bi-person-plus"></i>Criar conta de cliente</a>
                    </div>

                    <div class="hero-stats">
                        <div class="stat"><strong>24h</strong><span>acompanhamento</span></div>
                        <div class="stat"><strong>100%</strong><span>fluxo digital</span></div>
                        <div class="stat"><strong>OS</strong><span>sem papelada</span></div>
                    </div>
                </div>
            </section>

            <section class="section" id="servicos">
                <div class="section-head">
                    <h2>Com o que trabalhamos.</h2>
                    <p>Servicos que o cliente consegue solicitar, acompanhar e aprovar com clareza dentro do AutoTech.</p>
                </div>

                <div class="ecosystem">
                    @forelse($servicosPorCategoria ?? [] as $categoria => $dados)
                        <article class="eco-card">
                            <div>
                                <span class="eco-icon"><i class="bi {{ $dados['icone'] }}"></i></span>
                                <h3>{{ $dados['nome_display'] }}</h3>
                                <p>
                                    @if($dados['categoria'] === 'mecanica')
                                        Servicos preventivos e corretivos: {{ $dados['servicos']->pluck('nome')->implode(', ') }}
                                    @elseif($dados['categoria'] === 'eletrica')
                                        Sistemas eletricos e diagnosticos: {{ $dados['servicos']->pluck('nome')->implode(', ') }}
                                    @elseif($dados['categoria'] === 'funilaria')
                                        Trabalhos em carroceria e pintura: {{ $dados['servicos']->pluck('nome')->implode(', ') }}
                                    @endif
                                </p>
                            </div>
                            <a href="{{ route('register') }}">Solicitar <i class="bi bi-arrow-right"></i></a>
                        </article>
                    @empty
                        <article class="eco-card">
                            <div>
                                <span class="eco-icon"><i class="bi bi-tools"></i></span>
                                <h3>Diagnostico</h3>
                                <p>Analise da queixa do cliente, sintomas do veiculo e primeiras verificacoes da oficina.</p>
                            </div>
                            <a href="{{ route('register') }}">Solicitar <i class="bi bi-arrow-right"></i></a>
                        </article>
                        <article class="eco-card">
                            <div>
                                <span class="eco-icon"><i class="bi bi-tools"></i></span>
                                <h3>Manutencao</h3>
                                <p>Servicos preventivos e corretivos para manter o carro seguro e confiavel.</p>
                            </div>
                            <a href="{{ route('register') }}">Cadastrar <i class="bi bi-arrow-right"></i></a>
                        </article>
                        <article class="eco-card">
                            <div>
                                <span class="eco-icon"><i class="bi bi-car-front"></i></span>
                                <h3>Veiculos</h3>
                                <p>Cadastro do seu carro para acompanhar historico, placa, modelo e ordens abertas.</p>
                            </div>
                            <a href="{{ route('register') }}">Adicionar <i class="bi bi-arrow-right"></i></a>
                        </article>
                        <article class="eco-card">
                            <div>
                                <span class="eco-icon"><i class="bi bi-receipt"></i></span>
                                <h3>Orcamento</h3>
                                <p>Receba valores, aprove o servico e acompanhe o andamento da OS pelo sistema.</p>
                            </div>
                            <a href="{{ route('login') }}">Acompanhar <i class="bi bi-arrow-right"></i></a>
                        </article>
                    @endforelse
                </div>
            </section>

            <section class="section" id="atendimento">
                <div class="section-head">
                    <h2>Como funciona o atendimento.</h2>
                    <p>Um fluxo simples para o cliente entender o caminho do veiculo dentro da oficina.</p>
                </div>

                <div class="services-showcase">
                    <div class="service-intro">
                        <div>
                            <div class="eyebrow">atendimento automotivo</div>
                            <h3>Atendimento claro do inicio ao fim.</h3>
                            <p>A pagina inicial precisa deixar claro o que acontece depois do cadastro: o cliente envia a solicitacao, a oficina avalia, prepara o orcamento e o cliente acompanha tudo pelo sistema.</p>
                        </div>
                        <div class="hero-actions">
                            <a href="{{ route('register') }}" class="btn btn-red"><i class="bi bi-person-plus"></i>Comecar cadastro</a>
                            <a href="{{ route('login') }}" class="btn btn-line"><i class="bi bi-box-arrow-in-right"></i>Ja tenho conta</a>
                        </div>
                    </div>

                    <div class="service-list">
                        <article class="service-item">
                            <div>
                                <div class="service-item-top">
                                    <span class="service-icon"><i class="bi bi-person-check"></i></span>
                                    <strong>Cadastro do cliente</strong>
                                </div>
                                <p>O cliente cria a conta e completa os dados basicos para a oficina conseguir atender melhor.</p>
                            </div>
                            <span class="service-tag">Passo 01</span>
                        </article>
                        <article class="service-item">
                            <div>
                                <div class="service-item-top">
                                    <span class="service-icon"><i class="bi bi-car-front"></i></span>
                                    <strong>Cadastro do veiculo</strong>
                                </div>
                                <p>Modelo, placa e informacoes do carro ficam registrados para facilitar novos atendimentos.</p>
                            </div>
                            <span class="service-tag">Passo 02</span>
                        </article>
                        <article class="service-item">
                            <div>
                                <div class="service-item-top">
                                    <span class="service-icon"><i class="bi bi-chat-square-text"></i></span>
                                    <strong>Envio dos sintomas</strong>
                                </div>
                                <p>O cliente informa o que percebeu no carro para orientar a primeira analise da oficina.</p>
                            </div>
                            <span class="service-tag">Passo 03</span>
                        </article>
                        <article class="service-item">
                            <div>
                                <div class="service-item-top">
                                    <span class="service-icon"><i class="bi bi-clipboard2-check"></i></span>
                                    <strong>Orcamento e aprovacao</strong>
                                </div>
                                <p>A oficina envia o orcamento e o cliente acompanha a decisao dentro da propria conta.</p>
                            </div>
                            <span class="service-tag">Passo 04</span>
                        </article>
                    </div>
                </div>
            </section>

            <section class="section" id="comentarios">
                <div class="section-head">
                    <h2>Comentarios de clientes.</h2>
                    <p>Mostramos aqui as melhores avaliacoes publicadas por clientes apos finalizarem uma OS.</p>
                </div>

                @if(($avaliacoesDestaque ?? collect())->isNotEmpty())
                    <div class="testimonial-grid">
                        @foreach($avaliacoesDestaque as $avaliacao)
                            @php
                                $nomeCliente = trim($avaliacao->cliente->nome ?? 'Cliente AutoTech');
                                $partesNome = preg_split('/\s+/', $nomeCliente);
                                $nomePublico = count($partesNome) > 1
                                    ? $partesNome[0] . ' ' . mb_substr(end($partesNome), 0, 1) . '.'
                                    : $nomeCliente;
                                $iniciais = collect($partesNome)->take(2)->map(fn ($parte) => mb_substr($parte, 0, 1))->implode('');
                            @endphp
                            <article class="testimonial-card">
                                <div>
                                    <div class="stars" aria-label="{{ $avaliacao->nota }} de 5 estrelas">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= $avaliacao->nota ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                    </div>
                                    <p>{{ $avaliacao->comentario }}</p>
                                </div>
                                <div class="testimonial-author">
                                    <span class="author-avatar">{{ $iniciais ?: 'AT' }}</span>
                                    <div><strong>{{ $nomePublico }}</strong><span>{{ $avaliacao->nota }} de 5 estrelas</span></div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    @if(($totalAvaliacoesPublicas ?? 0) > 6)
                        <div class="reviews-actions">
                            <a href="{{ route('comentarios.public') }}" class="btn btn-line"><i class="bi bi-chat-square-heart"></i>Ver mais avaliacoes</a>
                        </div>
                    @endif
                @else
                    <div class="empty-reviews">
                        Nenhuma avaliacao publicada ainda. Quando um cliente avaliar uma OS finalizada, o comentario aparece aqui.
                    </div>
                @endif
            </section>

            <section class="footer-cta">
                <div class="footer-inner">
                    <div>
                        <h2>Entre no AutoTech.</h2>
                        <p>Acesse sua conta ou cadastre-se para acompanhar servicos, veiculos e comunicacoes da oficina.</p>
                    </div>
                    <div class="actions">
                        <a href="{{ route('login') }}" class="btn btn-red"><i class="bi bi-box-arrow-in-right"></i>Entrar</a>
                        <a href="{{ route('register') }}" class="btn btn-line"><i class="bi bi-person-plus"></i>Cadastrar</a>
                    </div>
                </div>
            </section>
        </main>
    </div>
    <script>
        const dynamicWord = document.getElementById('dynamicWord');
        const words = ['preciso', 'rapido', 'confiavel', 'eficiente'];
        let wordIndex = 0;
        let charIndex = words[0].length;
        let deleting = true;

        function typeHeroWord() {
            const current = words[wordIndex];

            if (deleting) {
                charIndex -= 1;
                dynamicWord.textContent = current.slice(0, charIndex);

                if (charIndex === 0) {
                    deleting = false;
                    wordIndex = (wordIndex + 1) % words.length;
                    setTimeout(typeHeroWord, 260);
                    return;
                }
            } else {
                const next = words[wordIndex];
                charIndex += 1;
                dynamicWord.textContent = next.slice(0, charIndex);

                if (charIndex === next.length) {
                    deleting = true;
                    setTimeout(typeHeroWord, 1500);
                    return;
                }
            }

            setTimeout(typeHeroWord, deleting ? 54 : 78);
        }

        setTimeout(typeHeroWord, 1300);

        document.addEventListener('click', function (event) {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

            const target = event.target.closest('.btn, .theme-toggle');
            if (!target) return;

            const rect = target.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const ripple = document.createElement('span');

            ripple.className = 'ui-ripple';
            ripple.style.width = `${size}px`;
            ripple.style.height = `${size}px`;
            ripple.style.left = `${event.clientX - rect.left - size / 2}px`;
            ripple.style.top = `${event.clientY - rect.top - size / 2}px`;

            target.appendChild(ripple);
            setTimeout(() => ripple.remove(), 650);
        });

        (function () {
            const loader = document.getElementById('pageLoader');
            if (!loader) return;

            return;

            let timer = null;

            const showLoader = () => {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    loader.classList.add('is-active');
                    loader.setAttribute('aria-hidden', 'false');
                }, 80);
            };

            const hideLoader = () => {
                clearTimeout(timer);
                loader.classList.remove('is-active');
                loader.setAttribute('aria-hidden', 'true');
            };

            const isInternalNavigation = (link) => {
                if (!link || link.target === '_blank' || link.hasAttribute('download')) return false;
                const href = link.getAttribute('href') || '';
                if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) return false;

                const url = new URL(link.href, window.location.href);
                if (url.origin !== window.location.origin) return false;
                if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash) return false;

                return true;
            };

            document.addEventListener('click', (event) => {
                if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
                const link = event.target.closest('a[href]');
                if (isInternalNavigation(link)) showLoader();
            });

            document.addEventListener('submit', (event) => {
                const form = event.target;
                if (!(form instanceof HTMLFormElement) || form.target === '_blank') return;
                showLoader();
            });

            window.addEventListener('pageshow', hideLoader);
            window.addEventListener('load', hideLoader);
        })();

        document.getElementById('themeToggle')?.addEventListener('click', function () {
            const current = document.documentElement.getAttribute('data-theme') || 'dark';
            const next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('autotech-theme', next);
        });
    </script>
</body>
</html>
