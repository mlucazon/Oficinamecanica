<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliacoes dos clientes - AutoTech Pro</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=4">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800;900&family=DM+Sans:wght@300;400;500;700;900&display=swap" rel="stylesheet">
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

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            background:
                radial-gradient(circle at 78% -10%, var(--red-dim), transparent 34%),
                var(--bg);
        }

        :root[data-theme="light"] body {
            background:
                radial-gradient(circle at 86% 0%, rgba(176,0,0,.13), transparent 32%),
                linear-gradient(135deg, #f8f5ef 0%, #efe9e1 58%, #f6f1ea 100%);
        }

        a { color: inherit; }

        .topbar {
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
            background: rgba(255,250,245,.90);
            border-bottom-color: rgba(31,25,20,.12);
            box-shadow: 0 16px 40px rgba(66,45,25,.12);
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
            content: 'AT';
            position: relative;
            z-index: 1;
            font-family: 'Syne', sans-serif;
            font-weight: 900;
            letter-spacing: -.15em;
            color: #fff;
            transform: skewX(-8deg);
            text-shadow: 0 2px 10px rgba(0,0,0,.24);
        }

        .brand-mark::before { font-size: 16px; }

        .brand-mark::after,
        .page-loader-mark::after {
            content: '';
            position: absolute;
            right: 8px;
            bottom: 8px;
            width: 9px;
            height: 9px;
            border: 2px solid rgba(255,255,255,.94);
            border-radius: 50%;
            box-shadow: 0 0 0 3px rgba(255,255,255,.10);
        }

        .brand-mark i,
        .page-loader-mark i { display: none; }

        .brand-text strong {
            display: block;
            font-family: 'Syne', sans-serif;
            font-size: 1.05rem;
            line-height: 1;
            font-weight: 900;
        }

        .brand-text span {
            color: var(--muted2);
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .16em;
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
        }

        .btn-red {
            background: var(--red);
            border-color: var(--red);
            color: #fff;
        }

        .btn-line {
            background: transparent;
            color: var(--text2);
        }

        .theme-toggle {
            width: 42px;
            min-width: 42px;
            padding: 0;
        }

        .theme-toggle .theme-light-icon { display: none; }
        :root[data-theme="light"] .theme-toggle .theme-dark-icon { display: none; }
        :root[data-theme="light"] .theme-toggle .theme-light-icon { display: inline-block; }

        .page {
            width: min(var(--content), calc(100% - 2rem));
            margin: 0 auto;
            padding: clamp(2rem, 6vw, 4rem) 0;
        }

        .page-head {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 1rem;
            align-items: end;
            margin-bottom: 1.2rem;
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

        h1 {
            margin: .8rem 0 0;
            font-family: 'Syne', sans-serif;
            font-size: clamp(2rem, 5vw, 4rem);
            line-height: .95;
            text-transform: uppercase;
        }

        .page-head p {
            margin: .8rem 0 0;
            color: var(--muted);
            max-width: 680px;
            line-height: 1.55;
        }

        .rating-section {
            margin-top: 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: var(--surface);
            overflow: hidden;
        }

        :root[data-theme="light"] .rating-section,
        :root[data-theme="light"] .review-card {
            background: var(--surface);
            border-color: var(--border);
            box-shadow: none;
        }

        .rating-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid var(--border);
        }

        .rating-title strong {
            font-family: 'Syne', sans-serif;
            font-size: 1rem;
            text-transform: uppercase;
        }

        .stars {
            display: inline-flex;
            gap: .18rem;
            color: var(--red2);
        }

        .review-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .85rem;
            padding: 1rem;
        }

        .review-card {
            min-height: 210px;
            padding: 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: var(--surface);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .review-card p {
            margin: .9rem 0;
            color: var(--muted);
            line-height: 1.5;
        }

        .author {
            display: flex;
            align-items: center;
            gap: .7rem;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            display: grid;
            place-items: center;
            background: var(--red);
            font-family: 'Syne', sans-serif;
            font-weight: 900;
        }

        .author strong {
            display: block;
        }

        .author span {
            color: var(--muted2);
            font-size: .82rem;
        }

        .empty {
            padding: 1rem;
            color: var(--muted2);
        }

        .btn,
        .theme-toggle,
        .rating-section,
        .review-card {
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

        .rating-section:hover,
        .review-card:hover {
            transform: translateY(-2px);
            border-color: var(--red-border);
            box-shadow: 0 20px 52px rgba(0,0,0,.26);
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
            .rating-section:hover,
            .review-card:hover {
                transform: none;
            }

            .ui-ripple {
                display: none !important;
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

        @media (max-width: 900px) {
            .page-head,
            .review-grid {
                grid-template-columns: 1fr;
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

    <header class="topbar">
        <a href="{{ url('/') }}" class="brand">
            <span class="brand-mark"><i class="bi bi-gear-wide-connected"></i></span>
            <span class="brand-text">
                <strong>AutoTech</strong>
                <span>Oficina Pro</span>
            </span>
        </a>
        <div style="display:flex; gap:.6rem; align-items:center;">
            <button class="btn btn-line theme-toggle" type="button" id="themeToggle" title="Alternar modo claro/escuro" aria-label="Alternar modo claro e escuro">
                <i class="bi bi-sun theme-dark-icon"></i>
                <i class="bi bi-moon-stars theme-light-icon"></i>
            </button>
            <a href="{{ url('/#comentarios') }}" class="btn btn-red"><i class="bi bi-arrow-left"></i>Voltar</a>
        </div>
    </header>

    <main class="page">
        <div class="page-head">
            <div>
                <div class="eyebrow">avaliacoes reais</div>
                <h1>Comentarios dos clientes.</h1>
                <p>As avaliacoes ficam separadas por nota, de 5 ate 1 estrela, para facilitar a leitura.</p>
            </div>
            <a href="{{ route('register') }}" class="btn btn-red"><i class="bi bi-person-plus"></i>Criar conta</a>
        </div>

        @for($nota = 5; $nota >= 1; $nota--)
            @php $avaliacoes = $avaliacoesPorNota->get($nota, collect()); @endphp
            <section class="rating-section">
                <div class="rating-title">
                    <strong>{{ $nota }} estrela{{ $nota > 1 ? 's' : '' }}</strong>
                    <span class="stars" aria-label="{{ $nota }} de 5 estrelas">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi {{ $i <= $nota ? 'bi-star-fill' : 'bi-star' }}"></i>
                        @endfor
                    </span>
                </div>

                @if($avaliacoes->isNotEmpty())
                    <div class="review-grid">
                        @foreach($avaliacoes as $avaliacao)
                            @php
                                $nomeCliente = trim($avaliacao->cliente->nome ?? 'Cliente AutoTech');
                                $partesNome = preg_split('/\s+/', $nomeCliente);
                                $nomePublico = count($partesNome) > 1
                                    ? $partesNome[0] . ' ' . mb_substr(end($partesNome), 0, 1) . '.'
                                    : $nomeCliente;
                                $iniciais = collect($partesNome)->take(2)->map(fn ($parte) => mb_substr($parte, 0, 1))->implode('');
                            @endphp
                            <article class="review-card">
                                <div>
                                    <span class="stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= $avaliacao->nota ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                    </span>
                                    <p>{{ $avaliacao->comentario }}</p>
                                </div>
                                <div class="author">
                                    <span class="avatar">{{ $iniciais ?: 'AT' }}</span>
                                    <div><strong>{{ $nomePublico }}</strong><span>{{ $avaliacao->created_at->format('d/m/Y') }}</span></div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="empty">Nenhuma avaliacao com {{ $nota }} estrela{{ $nota > 1 ? 's' : '' }} ainda.</div>
                @endif
            </section>
        @endfor
    </main>
    <script>
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
