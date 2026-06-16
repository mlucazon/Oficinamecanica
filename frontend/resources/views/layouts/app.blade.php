<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AutoTech Pro') — AutoTech</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=4">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
	    <script>
	        (function () {
	            const theme = localStorage.getItem('autotech-theme') || 'dark';
	            document.documentElement.setAttribute('data-theme', theme);
	        })();
	    </script>

	    <style>
        *, *::before, *::after { box-sizing: border-box; }

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
            --success:      #1F7A3A;
            --success-bg:   rgba(31,122,58,.12);
            --success-text: #4BC970;
            --warning:      #C8860A;
            --warning-bg:   rgba(200,134,10,.12);
            --warning-text: #EFB34F;
            --info:         #2D7DD2;
            --info-bg:      rgba(45,125,210,.12);
            --info-text:    #5BA8F5;
            --danger-bg:    rgba(196,0,0,.12);
            --danger-text:  #E05555;
            --sidebar-w:    228px;
            --topbar-h:     54px;
            --radius:       10px;
	            --radius-sm:    7px;
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
	            --danger-bg:    rgba(176,0,0,.10);
	            --danger-text:  #B00000;
	        }

        html { scroll-behavior: smooth; }

	        body {
	            font-family: 'DM Sans', sans-serif;
	            background: var(--bg);
            color: var(--text);
            margin: 0;
            min-height: 100vh;
	            overflow-x: hidden;
	        }

	        :root[data-theme="light"] body {
	            background:
	                radial-gradient(circle at 78% -10%, rgba(176,0,0,.12), transparent 34%),
	                radial-gradient(circle at 12% 10%, rgba(255,255,255,.85), transparent 28%),
	                linear-gradient(135deg, #F8F5EF 0%, #EFE9E1 58%, #F6F1EA 100%);
	        }

        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #2a2a2a; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #3a3a3a; }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='1'/%3E%3C/svg%3E");
            opacity: .022;
            pointer-events: none;
            z-index: 9999;
        }

        #sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--surface);
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--border);
            transition: transform .28s cubic-bezier(.4,0,.2,1);
        }

        .sidebar-brand {
            padding: 1rem 1.1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .sidebar-brand::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, var(--red) 0%, transparent 70%);
            opacity: .5;
        }

        .brand-icon-wrap {
            width: 42px; height: 42px;
            background:
                radial-gradient(circle at 22% 16%, rgba(255,255,255,.32), transparent 34%),
                linear-gradient(135deg, #ff2424 0%, var(--red) 48%, #650000 100%);
            border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
            box-shadow: 0 14px 34px rgba(196,0,0,.32), inset 0 1px 0 rgba(255,255,255,.20);
        }

        .brand-icon-wrap::before {
            content: 'AT';
            position: relative;
            z-index: 1;
            font-family: 'Syne', sans-serif;
            font-size: 15px;
            font-weight: 900;
            letter-spacing: -.15em;
            color: #fff;
            transform: skewX(-8deg);
            text-shadow: 0 2px 10px rgba(0,0,0,.24);
        }

        .brand-icon-wrap::after {
            content: '';
            position: absolute;
            right: 7px;
            bottom: 7px;
            width: 8px;
            height: 8px;
            border: 2px solid rgba(255,255,255,.94);
            border-radius: 50%;
            box-shadow: 0 0 0 3px rgba(255,255,255,.10);
        }

        .brand-icon-wrap i { display: none; }

        .brand-name {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 15px;
            letter-spacing: .04em;
            color: #fff;
            line-height: 1.1;
        }

        .brand-sub {
            font-size: 9px;
            color: var(--text3);
            text-transform: uppercase;
            letter-spacing: .18em;
            display: block;
            margin-top: 1px;
        }

        .nav-scroll { flex: 1; overflow-y: auto; padding: .8rem 0; }

        .nav-label {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: .18em;
            padding: .85rem 1.2rem .25rem;
            font-weight: 500;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 7px 1.1rem;
            font-size: 13px;
            color: #BEBEBE;
            border-left: 2px solid transparent;
            transition: all .15s;
            text-decoration: none;
            position: relative;
            margin: 1px 0;
        }

        .nav-link i { font-size: 15px; width: 18px; text-align: center; flex-shrink: 0; }

        .nav-link:hover {
            color: var(--text);
            background: rgba(255,255,255,.03);
        }

        .nav-link.active {
            color: #fff;
            background: var(--red-dim);
            border-left-color: var(--red);
        }

        .nav-link.active i { color: var(--red); }

        .nav-link .nav-badge {
            margin-left: auto;
            font-size: 9px;
            padding: 2px 6px;
            border-radius: 99px;
            background: var(--red-dim);
            color: var(--red-h);
            font-weight: 600;
        }

        .sidebar-footer {
            padding: .9rem 1.1rem;
            border-top: 1px solid var(--border);
        }

        .user-row {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 10px;
        }

        .user-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: transparent;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Syne', sans-serif;
            font-size: 11px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
            position: relative;
        }

        .user-avatar-inner {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 28%, #ff5858 0%, var(--red) 48%, #650000 100%);
            border: 1px solid rgba(255,255,255,.16);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,.05);
            position: relative;
        }

        .avatar-fallback-initials {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        .user-avatar img,
        .client-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            position: relative;
            z-index: 1;
        }

        .status-dot {
            display: inline-block;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            border: 2px solid var(--surface);
            flex-shrink: 0;
        }

        .status-dot.online { background: #3ee66f; box-shadow: 0 0 0 3px rgba(62,230,111,.12); }
        .status-dot.offline { background: #8b8f98; box-shadow: none; }

        .user-avatar .status-dot {
            position: absolute;
            right: -1px;
            bottom: -1px;
        }

        .client-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--surface2);
            border: 1px solid var(--border2);
            color: var(--text);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-size: 11px;
            font-weight: 700;
            position: relative;
            flex-shrink: 0;
            overflow: visible;
        }

        .client-avatar > img,
        .client-avatar > .client-avatar-initials {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .client-avatar .status-dot {
            position: absolute;
            right: -1px;
            bottom: -1px;
        }

        .client-name-stack {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info-name {
            font-size: 12.5px;
            font-weight: 500;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role-badge {
            margin-left: auto;
            font-size: 9px;
            background: rgba(255,255,255,.08);
            color: #AAAAAA;
            padding: 2px 7px;
            border-radius: 99px;
            text-transform: uppercase;
            letter-spacing: .1em;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .btn-logout {
            width: 100%;
            padding: 6px;
            background: transparent;
            border: 1px solid rgba(196,0,0,.3);
            border-radius: var(--radius-sm);
            color: #D05555;
            font-size: 11.5px;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 6px;
            transition: all .18s;
        }

        .btn-logout:hover {
            background: var(--red-dim);
            border-color: var(--red);
            color: #fff;
        }

        #topbar {
            margin-left: var(--sidebar-w);
            height: var(--topbar-h);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 900;
            gap: 1rem;
        }

        #topbar::after {
            content: '';
            position: absolute;
            bottom: -1px; left: 0;
            width: 180px; height: 1px;
            background: linear-gradient(90deg, var(--red), transparent);
            opacity: .7;
        }

        .breadcrumb-wrap {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #999;
        }

        .breadcrumb-wrap .bc-sep { opacity: .4; font-size: 10px; }

        .breadcrumb-wrap .bc-current {
            color: #F0F0F0;
            font-weight: 500;
        }

	        .topbar-right { margin-left: auto; display: flex; gap: 8px; align-items: center; }

	        .profile-menu-wrap {
	            position: relative;
	        }

            .profile-check-nudge {
                position: absolute;
                top: calc(100% + 14px);
                right: 0;
                width: min(320px, calc(100vw - 32px));
                padding: .85rem .95rem;
                border: 1px solid var(--red-border);
                border-radius: 8px;
                background: var(--surface);
                color: var(--text);
                box-shadow: 0 18px 44px rgba(0,0,0,.38), 0 0 0 1px rgba(255,255,255,.04) inset;
                z-index: 9700;
                animation: profileNudgeIn .28s ease-out both, profileNudgeFloat 2.6s ease-in-out .28s infinite;
            }

            .profile-check-nudge::before {
                content: '';
                position: absolute;
                top: -9px;
                right: 34px;
                width: 16px;
                height: 16px;
                transform: rotate(45deg);
                background: var(--surface);
                border-left: 1px solid var(--red-border);
                border-top: 1px solid var(--red-border);
            }

            .profile-check-nudge .nudge-row {
                display: flex;
                align-items: flex-start;
                gap: .7rem;
            }

            .profile-check-nudge .nudge-arrow {
                color: var(--red-h);
                font-size: 1.25rem;
                line-height: 1;
                transform: rotate(-45deg);
                margin-top: .08rem;
            }

            .profile-check-nudge strong {
                display: block;
                margin-bottom: .15rem;
                font-weight: 900;
            }

            .profile-check-nudge span {
                display: block;
                color: var(--text2);
                font-size: .88rem;
                line-height: 1.3;
            }

            .profile-check-nudge a {
                display: inline-flex;
                align-items: center;
                gap: .35rem;
                margin-top: .55rem;
                color: var(--red-h);
                font-weight: 800;
                text-decoration: none;
            }

            @keyframes profileNudgeIn {
                from { opacity: 0; transform: translateY(-6px); }
                to { opacity: 1; transform: translateY(0); }
            }

            @keyframes profileNudgeFloat {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-5px); }
            }

	        .topbar-user {
	            border: 0;
	            background: transparent;
	            display: flex;
	            align-items: center;
	            gap: 9px;
	            color: var(--text);
	            padding: 4px 8px;
	            border-radius: var(--radius-sm);
	            cursor: pointer;
	            font-family: 'DM Sans', sans-serif;
	        }

	        .topbar-user:hover {
	            background: rgba(255,255,255,.05);
	        }

	        .profile-dropdown {
	            position: absolute;
	            top: calc(100% + 10px);
	            right: 0;
	            width: 210px;
	            background: var(--surface);
	            border: 1px solid var(--border2);
	            border-radius: 12px;
	            padding: 8px;
	            box-shadow: 0 22px 60px rgba(0,0,0,.38);
	            z-index: 9800;
	            display: none;
	        }

	        .profile-dropdown.show {
	            display: block;
	        }

	        .profile-dropdown-item {
	            width: 100%;
	            min-height: 42px;
	            border: 0;
	            background: transparent;
	            color: var(--text);
	            display: flex;
	            align-items: center;
	            gap: 10px;
	            padding: 9px 10px;
	            border-radius: 9px;
	            font-size: 13px;
	            text-decoration: none;
	            text-align: left;
	            font-family: 'DM Sans', sans-serif;
	        }

	        .profile-dropdown-item:hover {
	            background: var(--surface3);
	            color: var(--text);
	        }

	        .profile-dropdown-item.danger {
	            color: var(--danger-text);
	        }

	        .profile-dropdown-item.danger:hover {
	            background: var(--danger-bg);
	            color: var(--danger-text);
	        }

        .topbar-btn {
            width: 34px; height: 34px;
            background: transparent;
            border: 1px solid var(--border2);
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            color: var(--text2);
            font-size: 15px;
            cursor: pointer;
            transition: all .15s;
            text-decoration: none;
            position: relative;
        }

	        .topbar-btn:hover { background: var(--surface3); color: var(--text); border-color: var(--border3); }

	        .theme-toggle .theme-light-icon { display: none; }
	        :root[data-theme="light"] .theme-toggle .theme-dark-icon { display: none; }
	        :root[data-theme="light"] .theme-toggle .theme-light-icon { display: inline-block; }
	        :root[data-theme="light"] .brand-name,
	        :root[data-theme="light"] .bc-current {
	            color: var(--text);
	        }
	        :root[data-theme="light"] .nav-label,
	        :root[data-theme="light"] .breadcrumb-wrap {
	            color: var(--text3);
	        }
	        :root[data-theme="light"] .nav-link {
	            color: var(--text2);
	        }
	        :root[data-theme="light"] .nav-link.active {
	            color: var(--red-h);
	        }
	        :root[data-theme="light"] .user-role-badge {
	            background: rgba(31,25,20,.06);
	            color: var(--text3);
	        }

        .notif-dot {
            position: absolute;
            top: 6px; right: 6px;
            width: 6px; height: 6px;
            background: var(--red);
            border-radius: 50%;
            border: 1.5px solid var(--surface);
        }

        .btn-nova-os {
            display: flex; align-items: center; gap: 7px;
            padding: 7px 15px;
            background: var(--red);
            border: none;
            border-radius: var(--radius-sm);
            color: #fff;
            font-size: 12.5px;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            letter-spacing: .04em;
            cursor: pointer;
            transition: all .18s;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .btn-nova-os::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.12) 0%, transparent 50%);
            opacity: 0;
            transition: opacity .18s;
        }

        .btn-nova-os:hover {
            background: var(--red-h);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(196,0,0,.35);
        }

        .btn-nova-os:hover::before { opacity: 1; }

        .btn-nova-os:active { transform: translateY(0); }

        .sidebar-toggle {
            display: none !important;
            background: none;
            border: 1px solid var(--border2);
            border-radius: var(--radius-sm);
            color: var(--text2);
            padding: 5px 8px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 4px;
        }

        .sidebar-logo-button {
            width: 38px;
            height: 38px;
            padding: 0;
            border: 0;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--red), #6f0000);
            color: #fff;
            align-items: center;
            justify-content: center;
            box-shadow: 0 12px 30px rgba(196,0,0,.28);
            text-decoration: none;
            transition: transform .22s ease, box-shadow .22s ease, background .22s ease;
            flex-shrink: 0;
        }

        .sidebar-logo-button:hover {
            color: #fff;
            transform: rotate(18deg) scale(1.04);
            box-shadow: 0 16px 36px rgba(196,0,0,.36);
        }

        .layout-logo-main {
            width: 42px !important;
            height: 42px !important;
            min-width: 42px !important;
            border: 0 !important;
            border-radius: 50% !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #fff !important;
            background: radial-gradient(circle at 35% 28%, #ff5858 0%, var(--red) 42%, #650000 100%) !important;
            box-shadow: 0 14px 34px rgba(196,0,0,.34), inset 0 0 0 1px rgba(255,255,255,.16) !important;
            text-decoration: none !important;
            flex: 0 0 42px !important;
            margin-right: 8px !important;
            transition: transform .22s ease, box-shadow .22s ease !important;
        }

        .layout-logo-main i {
            font-size: 18px !important;
            line-height: 1 !important;
        }

        .layout-logo-main:hover {
            color: #fff !important;
            transform: rotate(18deg) scale(1.04) !important;
            box-shadow: 0 18px 42px rgba(196,0,0,.42), inset 0 0 0 1px rgba(255,255,255,.2) !important;
        }

        #content {
            margin-left: var(--sidebar-w);
            padding: 1.6rem 1.8rem;
            min-height: calc(100vh - var(--topbar-h));
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text);
            box-shadow: none;
        }

        .card-header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: .85rem 1.2rem;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 13.5px;
            color: var(--text);
            border-radius: var(--radius) var(--radius) 0 0 !important;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-header .ch-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--red);
            flex-shrink: 0;
        }

        .card-footer {
            background: var(--surface);
            border-top: 1px solid var(--border);
        }

        .info-block-list {
            display: grid;
            gap: 12px;
        }

        .info-block {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            align-items: center;
            gap: 16px;
            width: 100%;
            padding: 1rem 1.15rem;
            border: 1px solid var(--border2);
            border-radius: 8px;
            background:
                linear-gradient(135deg, rgba(255,255,255,.035), rgba(255,255,255,.012)),
                rgba(255,255,255,.018);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.045);
        }

        .info-block-icon {
            width: 56px;
            height: 56px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            flex: 0 0 auto;
            background: var(--red-dim);
            color: var(--red-h);
            font-size: 22px;
        }

        .info-block-icon.success {
            background: rgba(34,197,94,.14);
            color: #4ade80;
        }

        .info-block-icon.warning {
            background: rgba(245,158,11,.16);
            color: #fbbf24;
        }

        .info-block-icon.info {
            background: rgba(45,125,210,.16);
            color: var(--info-text);
        }

        .info-block-kicker {
            display: block;
            margin-bottom: .22rem;
            color: var(--text3);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .info-block-title {
            color: var(--text);
            font-weight: 800;
            line-height: 1.25;
            overflow-wrap: anywhere;
        }

        .info-block-text {
            margin-top: .2rem;
            color: var(--text2);
            line-height: 1.45;
            overflow-wrap: anywhere;
        }

        .info-block-meta {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: .45rem;
        }

        .info-block-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
            min-width: 150px;
        }

        .info-block-actions .btn {
            min-height: 40px;
        }

        :root[data-theme="light"] .info-block {
            background:
                linear-gradient(135deg, rgba(255,255,255,.82), rgba(255,255,255,.52)),
                rgba(255,255,255,.58);
            border-color: rgba(31,25,20,.14);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.8);
        }

        .report-chart {
            display: grid;
            gap: .85rem;
        }

        .report-chart-row {
            display: grid;
            grid-template-columns: minmax(140px, 220px) minmax(0, 1fr) auto;
            align-items: center;
            gap: .85rem;
        }

        .report-chart-label {
            color: var(--text);
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .report-chart-track {
            height: 14px;
            overflow: hidden;
            border-radius: 999px;
            background: rgba(255,255,255,.08);
            border: 1px solid var(--border2);
        }

        .report-chart-bar {
            display: block;
            height: 100%;
            min-width: 6px;
            border-radius: inherit;
            background: linear-gradient(90deg, var(--red), var(--red-h));
            box-shadow: 0 0 22px var(--red-glow);
        }

        .report-chart-value {
            color: var(--text);
            font-family: 'DM Mono', monospace;
            font-weight: 800;
            white-space: nowrap;
        }

        .report-chart-muted {
            color: var(--text2);
            font-size: .86rem;
        }

        :root[data-theme="light"] .report-chart-track {
            background: rgba(31,25,20,.08);
            border-color: rgba(31,25,20,.12);
        }

        .report-hero {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: end;
            gap: 1rem;
            margin-bottom: 1rem;
            padding: 1.2rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background:
                linear-gradient(135deg, rgba(196,0,0,.16), transparent 34%),
                linear-gradient(90deg, rgba(255,255,255,.035), transparent),
                var(--surface);
            overflow: hidden;
        }

        .report-hero h1 {
            margin: 0;
            font-size: clamp(1.25rem, 2vw, 1.85rem);
            font-weight: 900;
            letter-spacing: 0;
        }

        .report-hero p {
            margin: .35rem 0 0;
            max-width: 760px;
            color: var(--text2);
        }

        .report-hero-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: .5rem;
            flex-wrap: wrap;
        }

        .metric-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: .85rem;
            margin-bottom: 1rem;
        }

        .metric-tile {
            display: flex;
            gap: .85rem;
            align-items: center;
            min-height: 92px;
            padding: 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: linear-gradient(135deg, rgba(255,255,255,.045), rgba(255,255,255,.012));
        }

        .metric-icon {
            width: 46px;
            height: 46px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex: 0 0 46px;
        }

        .metric-icon.red { background: var(--danger-bg); color: var(--danger-text); }
        .metric-icon.green { background: var(--success-bg); color: var(--success-text); }
        .metric-icon.amber { background: var(--warning-bg); color: var(--warning-text); }
        .metric-icon.blue { background: var(--info-bg); color: var(--info-text); }

        .metric-label {
            color: var(--text2);
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-weight: 800;
        }

        .metric-value {
            margin-top: .2rem;
            color: var(--text);
            font-size: 1.45rem;
            line-height: 1;
            font-weight: 900;
        }

        .metric-note {
            margin-top: .25rem;
            color: var(--text3);
            font-size: .86rem;
        }

        .viz-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.35fr) minmax(280px, .65fr);
            gap: 1rem;
            align-items: stretch;
        }

        .viz-card {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: var(--surface);
            overflow: hidden;
        }

        .viz-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            padding: 1rem 1rem .75rem;
            border-bottom: 1px solid var(--border);
        }

        .viz-title {
            display: flex;
            align-items: center;
            gap: .55rem;
            margin: 0;
            color: var(--text);
            font-size: .98rem;
            font-weight: 900;
        }

        .viz-body {
            padding: 1rem;
        }

        .column-chart {
            min-height: 280px;
            display: grid;
            grid-template-columns: repeat(var(--columns, 12), minmax(26px, 1fr));
            align-items: end;
            gap: .55rem;
            padding-top: 1rem;
        }

        .column-item {
            display: grid;
            grid-template-rows: 1fr auto;
            gap: .55rem;
            min-width: 0;
            height: 100%;
        }

        .column-bar-wrap {
            height: 220px;
            display: flex;
            align-items: end;
            border-radius: 8px;
            background: linear-gradient(180deg, rgba(255,255,255,.04), rgba(255,255,255,.015));
            border: 1px solid var(--border2);
            overflow: hidden;
        }

        .column-bar {
            width: 100%;
            min-height: 5%;
            border-radius: 8px 8px 0 0;
            background: linear-gradient(180deg, #ffca3a, var(--red-h) 58%, #6f0000);
            box-shadow: 0 -14px 30px rgba(255,202,58,.12);
        }

        .column-label {
            min-height: 2.4em;
            color: var(--text2);
            font-size: .78rem;
            text-align: center;
            overflow-wrap: anywhere;
        }

        .donut-viz {
            --percent: 0;
            --donut-color: var(--success);
            width: min(220px, 100%);
            aspect-ratio: 1;
            margin: .5rem auto 1rem;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background:
                radial-gradient(circle, var(--surface) 0 56%, transparent 57%),
                conic-gradient(var(--donut-color) calc(var(--percent) * 1%), rgba(255,255,255,.09) 0);
            border: 1px solid var(--border);
        }

        .donut-viz strong {
            color: var(--text);
            font-size: 2.1rem;
            line-height: 1;
            font-weight: 900;
        }

        .donut-viz span {
            display: block;
            margin-top: .25rem;
            color: var(--text2);
            font-size: .82rem;
            text-align: center;
        }

        .ranking-list {
            display: grid;
            gap: .7rem;
        }

        .ranking-row {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            gap: .75rem;
            align-items: center;
            padding: .8rem;
            border: 1px solid var(--border2);
            border-radius: 8px;
            background: rgba(255,255,255,.025);
        }

        .ranking-pos {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--red-dim);
            color: var(--red-h);
            font-weight: 900;
        }

        .ranking-title {
            color: var(--text);
            font-weight: 850;
            overflow-wrap: anywhere;
        }

        .ranking-sub {
            color: var(--text2);
            font-size: .86rem;
        }

        .ranking-value {
            color: var(--text);
            font-family: 'DM Mono', monospace;
            font-weight: 900;
            white-space: nowrap;
        }

        @media (max-width: 900px) {
            .report-hero,
            .viz-grid {
                grid-template-columns: 1fr;
            }

            .report-hero-actions {
                justify-content: flex-start;
            }

            .column-chart {
                overflow-x: auto;
                grid-template-columns: repeat(var(--columns, 12), minmax(52px, 1fr));
            }
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.1rem 1.2rem;
            position: relative;
            overflow: hidden;
            transition: border-color .2s, transform .2s;
            cursor: default;
        }

        .stat-card:hover {
            border-color: var(--border2);
            transform: translateY(-2px);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            border-radius: var(--radius) var(--radius) 0 0;
        }

        .stat-card.sc-red::before    { background: var(--red); }
        .stat-card.sc-blue::before   { background: var(--info); }
        .stat-card.sc-amber::before  { background: var(--warning); }
        .stat-card.sc-green::before  { background: var(--success); }

        .stat-card .sc-bg-icon {
            position: absolute;
            right: 14px; bottom: 8px;
            font-size: 52px;
            opacity: .045;
            line-height: 1;
        }

        .stat-card .sc-top {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 12px;
        }

        .stat-card .sc-icon {
            width: 34px; height: 34px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px;
        }

        .sc-red  .sc-icon { background: var(--danger-bg);  color: var(--danger-text); }
        .sc-blue .sc-icon { background: var(--info-bg);    color: var(--info-text); }
        .sc-amber .sc-icon { background: var(--warning-bg); color: var(--warning-text); }
        .sc-green .sc-icon { background: var(--success-bg); color: var(--success-text); }

        .sc-pill {
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 99px;
            font-weight: 500;
        }

        .sc-pill.up   { background: var(--success-bg); color: var(--success-text); }
        .sc-pill.neu  { background: rgba(255,255,255,.05); color: var(--text3); }
        .sc-pill.down { background: var(--danger-bg); color: var(--danger-text); }

        .stat-value {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 26px;
            color: #fff;
            line-height: 1;
            margin-bottom: 4px;
            letter-spacing: -.01em;
        }

        .stat-label {
            font-size: 10px;
            color: #AAAAAA;
            text-transform: uppercase;
            letter-spacing: .12em;
            font-weight: 500;
        }

        .table {
            color: #E8E8E8;
            --bs-table-bg: transparent;
            --bs-table-color: #E8E8E8;
            --bs-table-hover-bg: rgba(255,255,255,.04);
            margin: 0;
        }

        .table th {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: #888 !important;
            border-color: rgba(255,255,255,.08) !important;
            padding: .75rem 1rem;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            white-space: nowrap;
            background: rgba(255,255,255,.02) !important;
        }

        .table td {
            vertical-align: middle;
            border-color: rgba(255,255,255,.06) !important;
            padding: .8rem 1rem;
            font-size: 13.5px;
            color: #E8E8E8 !important;
            background: transparent !important;
        }

        .table td a {
            color: #E8E8E8 !important;
            text-decoration: none;
        }

        .table td a:hover { color: var(--red-h) !important; }

        .table-hover tbody tr { transition: background .12s; cursor: default; }

        .table-hover tbody tr:hover td {
            background: rgba(255,255,255,.04) !important;
        }

        .table > :not(caption) > * > * {
            color: #E8E8E8;
            background-color: transparent;
        }

        .os-num {
            font-family: 'DM Mono', monospace;
            font-size: 11.5px;
            color: var(--red-h);
            font-weight: 500;
            letter-spacing: .03em;
        }

        .font-mono { font-family: 'DM Mono', monospace; font-size: .88rem; }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .status-badge::before {
            content: '';
            width: 5px; height: 5px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .badge-aberta               { background: rgba(255,255,255,.07); color: #999; }
        .badge-aberta::before       { background: #555; }
        .badge-em_diagnostico       { background: var(--info-bg);    color: var(--info-text); }
        .badge-em_diagnostico::before { background: var(--info); }
        .badge-aguardando_aprovacao { background: var(--warning-bg); color: var(--warning-text); }
        .badge-aguardando_aprovacao::before { background: var(--warning); }
        .badge-aprovada             { background: var(--info-bg);    color: var(--info-text); }
        .badge-aprovada::before     { background: var(--info); }
        .badge-em_execucao          { background: var(--danger-bg);  color: var(--danger-text); }
        .badge-em_execucao::before  { background: var(--red); animation: pulse-dot 1.4s ease infinite; }
        .badge-aguardando_finalizacao { background: var(--warning-bg); color: var(--warning-text); }
        .badge-aguardando_finalizacao::before { background: var(--warning); animation: pulse-dot 1.4s ease infinite; }
        .badge-aguardando_pecas     { background: rgba(253,126,20,.12); color: #FD9B52; }
        .badge-aguardando_pecas::before { background: #fd7e14; }
        .badge-finalizada           { background: var(--success-bg); color: var(--success-text); }
        .badge-finalizada::before   { background: var(--success); }
        .badge-cancelada            { background: rgba(255,255,255,.05); color: #666; }
        .badge-cancelada::before    { background: #444; }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: .5; transform: scale(.7); }
        }

        .estoque-critico { color: var(--danger-text); font-weight: 600; }

        .btn-primary {
            background: var(--red);
            border-color: var(--red);
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-weight: 500;
            transition: all .18s;
        }

        .btn-primary:hover {
            background: var(--red-h);
            border-color: var(--red-h);
            color: #fff;
            box-shadow: 0 4px 16px rgba(196,0,0,.3);
        }

        .btn-outline-primary {
            color: var(--red-h);
            border-color: var(--red-border);
            font-weight: 500;
        }

        .btn-outline-primary:hover {
            background: var(--red-dim);
            border-color: var(--red);
            color: #fff;
        }

        .btn-outline-secondary {
            color: var(--text2);
            border-color: var(--border2);
        }

        .btn-outline-secondary:hover {
            background: var(--surface3);
            color: var(--text);
            border-color: var(--border3);
        }

        .btn-outline-danger {
            color: var(--danger-text);
            border-color: rgba(196,0,0,.35);
        }

        .btn-outline-danger:hover {
            background: var(--red-dim);
            border-color: var(--red);
            color: #fff;
        }

        .btn-success {
            background: var(--success);
            border-color: var(--success);
        }

        .btn-warning {
            background: var(--warning);
            border-color: var(--warning);
            color: #000;
        }

        .form-control, .form-select {
            background: var(--surface2);
            border-color: var(--border2);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            border-radius: var(--radius-sm);
            transition: border-color .18s, box-shadow .18s;
        }

        .form-control:focus, .form-select:focus {
            background: var(--surface2);
            border-color: var(--red);
            color: var(--text);
            box-shadow: 0 0 0 3px rgba(196,0,0,.15);
        }

	        .form-control::placeholder { color: var(--text3); }

	        .form-select option {
	            background: var(--surface);
	            color: var(--text);
	        }

	        :root[data-theme="light"] .form-select option {
	            background: #ffffff;
	            color: #17130f;
	        }

        .form-label {
            color: #C0C0C0;
            font-size: .85rem;
            font-weight: 500;
            margin-bottom: .35rem;
        }

        .input-group-text {
            background: var(--surface3);
            border-color: var(--border2);
            color: var(--text3);
        }

        .form-check-input {
            background-color: var(--surface3);
            border-color: var(--border2);
        }

        .form-check-input:checked {
            background-color: var(--red);
            border-color: var(--red);
        }

        .form-text { color: var(--text3); font-size: .8rem; }

        .toast-container {
            position: fixed;
            top: calc(var(--topbar-h) + 16px);
            right: 20px;
            z-index: 9999;
        }

        .toast-at {
            background: var(--surface);
            border: 1px solid var(--border2);
            border-radius: var(--radius);
            box-shadow: 0 8px 32px rgba(0,0,0,.5);
            padding: .75rem 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            min-width: 260px;
            animation: slideInRight .28s cubic-bezier(.34,1.56,.64,1);
        }

        .toast-at.toast-success { border-left: 3px solid var(--success-text); }
        .toast-at.toast-error   { border-left: 3px solid var(--red); }

        .toast-at i { font-size: 16px; flex-shrink: 0; }

        .toast-at.toast-success i { color: var(--success-text); }
        .toast-at.toast-error   i { color: var(--danger-text); }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(20px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideOutRight {
            from { opacity: 1; transform: translateX(0); }
            to   { opacity: 0; transform: translateX(20px); }
        }

        .modal-content {
            background: var(--surface);
            border: 1px solid var(--border2);
            border-radius: var(--radius);
            color: var(--text);
            box-shadow: 0 24px 64px rgba(0,0,0,.7);
        }

        .modal-header {
            border-bottom: 1px solid var(--border);
            padding: 1rem 1.2rem;
        }

        .modal-footer {
            border-top: 1px solid var(--border);
            padding: .8rem 1.2rem;
        }

        .modal-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 15px;
        }

        .btn-close { filter: invert(1) brightness(.6); }

	        .modal-backdrop { background: rgba(0,0,0,.75); }

	        .modal {
	            z-index: 10050;
	        }

	        .modal-backdrop {
	            z-index: 10040;
	        }

	        .modal-dialog {
	            pointer-events: auto;
	        }

        .pagination .page-link {
            background: var(--surface2);
            border-color: var(--border);
            color: var(--text2);
            font-size: 12.5px;
            transition: all .15s;
        }

        .pagination .page-link:hover {
            background: var(--surface3);
            border-color: var(--border2);
            color: var(--text);
        }

        .pagination .page-item.active .page-link {
            background: var(--red);
            border-color: var(--red);
            color: #fff;
        }

        .pagination .page-item.disabled .page-link { color: var(--text3); }

        #content nav[role="navigation"] svg,
        #content .pagination svg {
            width: 16px !important;
            height: 16px !important;
            max-width: 16px !important;
            max-height: 16px !important;
            display: inline-block !important;
            vertical-align: middle !important;
            flex: 0 0 16px !important;
        }

        #content nav[role="navigation"] {
            max-width: 100%;
            overflow-x: auto;
        }

        #content .card-footer nav,
        #content .card-footer .pagination {
            margin: 0;
        }

        .alert-danger {
            background: var(--danger-bg);
            border-color: var(--red-border);
            color: var(--danger-text);
        }

        .alert-success {
            background: var(--success-bg);
            border-color: rgba(31,122,58,.3);
            color: var(--success-text);
        }

        .alert-warning {
            background: var(--warning-bg);
            border-color: rgba(200,134,10,.3);
            color: var(--warning-text);
        }

        .alert-info {
            background: var(--info-bg);
            border-color: rgba(45,125,210,.3);
            color: var(--info-text);
        }

        a { color: var(--red-h); transition: color .15s; }

        a:hover { color: #ff6666; }

        .text-decoration-none { color: var(--text) !important; }

        .text-decoration-none:hover { color: var(--red-h) !important; }

        .text-muted,
        .form-text,
        .page-subtitle,
        .card .small,
        .table .small,
        .list-group-item .small,
        small {
            color: var(--text2) !important;
        }

        .card .text-muted,
        .table .text-muted,
        .alert .text-muted,
        .mini-notificacoes .text-muted {
            color: var(--text2) !important;
            opacity: 1 !important;
        }

        .card strong,
        .card .fw-semibold,
        .card .fw-bold,
        .table strong {
            color: var(--text) !important;
        }

        .page-header {
            margin-bottom: 1.4rem;
        }

        .page-title {
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: #fff;
            margin: 0 0 2px;
            letter-spacing: -.01em;
        }

        .page-subtitle {
            font-size: 12px;
            color: var(--text3);
        }

        .anim-fade-up {
            opacity: 0;
            transform: translateY(12px);
            animation: fadeUp .4s cubic-bezier(.4,0,.2,1) forwards;
        }

        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }

        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.6);
            z-index: 999;
            backdrop-filter: blur(2px);
        }

        @media (max-width: 900px) {
            #sidebar {
                transform: translateX(-100%);
                box-shadow: none;
            }
            #sidebar.open {
                transform: translateX(0);
                box-shadow: 8px 0 40px rgba(0,0,0,.6);
            }
            #sidebar-overlay { display: block; opacity: 0; pointer-events: none; transition: opacity .25s; }
            #sidebar-overlay.show { opacity: 1; pointer-events: all; }
            #topbar, #content { margin-left: 0; }
            .sidebar-toggle { display: flex !important; align-items: center; }
        }

        @media (max-width: 576px) {
            #content { padding: 1rem; }
            .btn-nova-os .btn-text { display: none; }
        }

        @media print {
            #sidebar, #topbar, .no-print { display: none !important; }
            #content { margin: 0; padding: 0; }
            body { background: #fff; color: #000; }
            .card { border: 1px solid #ddd; background: #fff; }
        }

        /* Correcoes finais do layout: lateral so com ferramentas, topo fixo com marca aberta. */
        #sidebar {
            width: var(--sidebar-collapsed-w, 68px) !important;
        }

        #sidebar:hover {
            width: var(--sidebar-w, 236px) !important;
        }

        #sidebar .sidebar-brand {
            min-height: 74px !important;
            justify-content: center !important;
            padding: 1rem 0 !important;
        }

        #sidebar .sidebar-brand > div:not(.brand-icon-wrap),
        #sidebar .brand-name,
        #sidebar .brand-sub {
            display: none !important;
        }

        #topbar {
            display: flex !important;
            position: fixed !important;
            top: 0 !important;
            left: var(--sidebar-collapsed-w, 68px) !important;
            right: 0 !important;
            min-height: 72px !important;
            padding: 10px 24px !important;
            border-radius: 0 !important;
            border: 0 !important;
            border-bottom: 1px solid rgba(255,255,255,.10) !important;
            background: linear-gradient(90deg, rgba(2,5,12,.96), rgba(2,5,12,.82)) !important;
            box-shadow: none !important;
            backdrop-filter: blur(16px) !important;
        }

        #topbar .topbar-brand {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            color: var(--text) !important;
        }

        #topbar .topbar-brand-icon {
            width: 38px !important;
            height: 38px !important;
            border-radius: 50% !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
            color: #fff !important;
            background: linear-gradient(135deg, var(--red), #6f0000) !important;
        }

        #topbar .topbar-brand-name {
            display: block !important;
            font-family: 'Syne', sans-serif !important;
            font-weight: 800 !important;
            font-size: 14px !important;
            letter-spacing: .04em !important;
            line-height: 1.1 !important;
            color: var(--text) !important;
        }

        #topbar .topbar-brand-sub {
            display: block !important;
            margin-top: 1px !important;
            font-size: 9px !important;
            color: var(--text3) !important;
            text-transform: uppercase !important;
            letter-spacing: .18em !important;
        }

        #content {
            margin-left: var(--sidebar-collapsed-w, 68px) !important;
            padding-top: 72px !important;
        }

        .home-hero {
            margin-top: -72px !important;
            padding-top: 156px !important;
        }

        @media (max-width: 900px) {
            #sidebar {
                width: 100% !important;
            }

            #topbar {
                position: sticky !important;
                left: 0 !important;
                min-height: auto !important;
            }

            #content {
                margin-left: 0 !important;
                padding-top: 1rem !important;
            }
        }
    </style>

	    <link href="{{ asset('css/app.css') }}?v=30" rel="stylesheet">

	    <style>
	        @media (min-width: 901px) {
            #sidebar {
                width: var(--sidebar-collapsed-w, 68px) !important;
                z-index: 800 !important;
                padding-top: 0 !important;
                overflow: hidden !important;
                transition: width .42s cubic-bezier(.22, 1, .36, 1), box-shadow .42s ease !important;
            }

            #sidebar:hover {
                width: var(--sidebar-w, 236px) !important;
                box-shadow: 18px 0 60px rgba(0,0,0,.30) !important;
            }

            #sidebar:not(:hover) .nav-link span,
            #sidebar:not(:hover) .nav-label,
            #sidebar:not(:hover) .user-info-name,
            #sidebar:not(:hover) .user-role-badge,
            #sidebar:not(:hover) .btn-logout span {
                opacity: 0 !important;
                max-width: 0 !important;
                overflow: hidden !important;
                pointer-events: none !important;
                transform: translateX(-8px) !important;
            }

            #sidebar:not(:hover) .nav-link {
                justify-content: center !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                width: 44px !important;
                height: 44px !important;
                margin: 4px auto !important;
            }

            #sidebar:hover .nav-link {
                justify-content: flex-start !important;
                width: calc(100% - 1.4rem) !important;
                height: 40px !important;
                margin: 1px 0 !important;
                padding: 7px 1.1rem !important;
            }

            #sidebar:hover .nav-link span,
            #sidebar:hover .nav-label {
                opacity: 1 !important;
                width: auto !important;
                max-width: none !important;
                height: auto !important;
                pointer-events: auto !important;
                transform: translateX(0) !important;
                transition-delay: .08s !important;
            }

            #sidebar .nav-link span,
            #sidebar .nav-label {
                display: inline-block !important;
                white-space: nowrap !important;
                transition: opacity .26s ease, transform .32s cubic-bezier(.22, 1, .36, 1), max-width .36s cubic-bezier(.22, 1, .36, 1) !important;
            }

            #sidebar .nav-link {
                transition: width .38s cubic-bezier(.22, 1, .36, 1), height .38s cubic-bezier(.22, 1, .36, 1), margin .38s cubic-bezier(.22, 1, .36, 1), padding .38s cubic-bezier(.22, 1, .36, 1), background .18s ease, color .18s ease !important;
            }

            #sidebar .sidebar-brand {
                display: none !important;
                min-height: 0 !important;
                justify-content: center !important;
                padding: 1rem 0 !important;
            }

            #sidebar .sidebar-brand > div:not(.brand-icon-wrap),
            #sidebar .brand-name,
            #sidebar .brand-sub {
                display: none !important;
            }

            #topbar {
                left: var(--sidebar-collapsed-w, 68px) !important;
                right: 0 !important;
                top: 0 !important;
                min-height: 72px !important;
                border-radius: 0 !important;
                background: linear-gradient(90deg, rgba(2,5,12,.96), rgba(2,5,12,.82)) !important;
                z-index: 1100 !important;
            }

            #content {
                margin-left: var(--sidebar-collapsed-w, 68px) !important;
                padding-top: 72px !important;
            }

            .sidebar-logo-button,
            .layout-logo-main {
                display: inline-flex !important;
            }

            .layout-logo-main {
                position: fixed !important;
                top: 15px !important;
                left: 14px !important;
                z-index: 1200 !important;
                margin: 0 !important;
            }
        }
    </style>

    <style>
        @media (min-width: 901px) {
            #sidebar {
                width: var(--sidebar-collapsed-w, 68px) !important;
                z-index: 800 !important;
                padding-top: 72px !important;
                overflow: hidden !important;
                transition: width .46s cubic-bezier(.22, 1, .36, 1), box-shadow .46s ease, background .46s ease !important;
            }

            #sidebar:hover,
            #sidebar:focus-within {
                width: var(--sidebar-w, 236px) !important;
                box-shadow: 18px 0 54px rgba(0,0,0,.28) !important;
            }

            #sidebar .sidebar-brand.sidebar-brand-logo {
                display: flex !important;
                min-height: 72px !important;
                height: 72px !important;
                padding: 0 0 0 13px !important;
                align-items: center !important;
                justify-content: flex-start !important;
                gap: 10px !important;
                color: #fff !important;
                text-decoration: none !important;
                border-bottom: 1px solid rgba(255,255,255,.08) !important;
                flex: 0 0 72px !important;
            }

            #sidebar .sidebar-brand-text {
                display: block !important;
                min-width: 0 !important;
                opacity: 0 !important;
                max-width: 0 !important;
                overflow: hidden !important;
                transform: translateX(-8px) !important;
                transition: opacity .28s ease .08s, max-width .38s cubic-bezier(.22, 1, .36, 1), transform .38s cubic-bezier(.22, 1, .36, 1) !important;
            }

            #sidebar:hover .sidebar-brand-text,
            #sidebar:focus-within .sidebar-brand-text {
                opacity: 1 !important;
                max-width: 145px !important;
                transform: translateX(0) !important;
            }

            #sidebar .sidebar-brand-text .brand-name,
            #sidebar .sidebar-brand-text .brand-sub {
                display: block !important;
                width: auto !important;
                max-width: none !important;
                height: auto !important;
                opacity: 1 !important;
                overflow: visible !important;
                white-space: nowrap !important;
                pointer-events: auto !important;
            }

            #sidebar:not(:hover):not(:focus-within) .nav-scroll {
                align-items: center !important;
            }

            #sidebar:hover .nav-scroll,
            #sidebar:focus-within .nav-scroll {
                align-items: stretch !important;
            }

            #sidebar .nav-link {
                overflow: hidden !important;
                transition: width .42s cubic-bezier(.22, 1, .36, 1), height .42s cubic-bezier(.22, 1, .36, 1), margin .42s cubic-bezier(.22, 1, .36, 1), padding .42s cubic-bezier(.22, 1, .36, 1), background .18s ease, color .18s ease, border-color .18s ease !important;
            }

            #sidebar:not(:hover):not(:focus-within) .nav-link {
                width: 44px !important;
                height: 44px !important;
                margin: 4px auto !important;
                padding: 0 !important;
                justify-content: center !important;
            }

            #sidebar:hover .nav-link,
            #sidebar:focus-within .nav-link {
                width: 100% !important;
                min-height: 40px !important;
                height: auto !important;
                margin: 1px 0 !important;
                padding: 7px 1.1rem !important;
                justify-content: flex-start !important;
            }

            #sidebar .nav-link span {
                display: inline-block !important;
                overflow: hidden !important;
                white-space: nowrap !important;
                transition: opacity .28s ease .06s, max-width .38s cubic-bezier(.22, 1, .36, 1), transform .38s cubic-bezier(.22, 1, .36, 1) !important;
            }

            #sidebar:not(:hover):not(:focus-within) .nav-link span {
                opacity: 0 !important;
                max-width: 0 !important;
                transform: translateX(-8px) !important;
                pointer-events: none !important;
            }

            #sidebar:hover .nav-link span,
            #sidebar:focus-within .nav-link span {
                opacity: 1 !important;
                max-width: 190px !important;
                transform: translateX(0) !important;
                pointer-events: auto !important;
            }

            #sidebar .nav-label {
                display: block !important;
                overflow: hidden !important;
                white-space: nowrap !important;
                transition: opacity .28s ease, max-height .38s cubic-bezier(.22, 1, .36, 1), padding .38s cubic-bezier(.22, 1, .36, 1), transform .38s cubic-bezier(.22, 1, .36, 1) !important;
            }

            #sidebar:not(:hover):not(:focus-within) .nav-label {
                opacity: 0 !important;
                max-height: 0 !important;
                padding-top: 0 !important;
                padding-bottom: 0 !important;
                transform: translateX(-8px) !important;
                pointer-events: none !important;
            }

            #sidebar:hover .nav-label,
            #sidebar:focus-within .nav-label {
                opacity: 1 !important;
                max-height: 36px !important;
                padding: .85rem 1.2rem .25rem !important;
                transform: translateX(0) !important;
                pointer-events: auto !important;
            }

            #sidebar .sidebar-brand-logo .brand-icon-wrap {
                width: 42px !important;
                height: 42px !important;
                box-shadow: 0 14px 34px rgba(196,0,0,.34), inset 0 0 0 1px rgba(255,255,255,.16) !important;
            }
        }
    </style>

    <style>
        @media (min-width: 901px) {
            #topbar {
                left: 0 !important;
                right: 0 !important;
                top: 0 !important;
                width: 100vw !important;
                min-height: 72px !important;
                margin: 0 !important;
                padding-left: calc(var(--sidebar-collapsed-w, 68px) + 24px) !important;
                border-radius: 0 !important;
                border-left: 0 !important;
                border-top: 0 !important;
                border-right: 0 !important;
                border-bottom: 1px solid rgba(255,255,255,.08) !important;
                background: linear-gradient(90deg, rgba(2,5,12,.96), rgba(2,5,12,.86)) !important;
                z-index: 900 !important;
            }

            #sidebar {
                top: 0 !important;
                z-index: 1100 !important;
                padding-top: 0 !important;
                border-right: 1px solid rgba(255,255,255,.08) !important;
                background: linear-gradient(180deg, rgba(2,5,12,.96), rgba(2,5,12,.78)) !important;
            }

            #sidebar .sidebar-brand.sidebar-brand-logo {
                display: flex !important;
                min-height: 72px !important;
                height: 72px !important;
                border-bottom: 1px solid rgba(255,255,255,.08) !important;
            }

            #content {
                margin-left: var(--sidebar-collapsed-w, 68px) !important;
                padding-top: 72px !important;
            }
        }
    </style>

    <style>
        @media (min-width: 901px) {
            .sidebar-toggle,
            .layout-logo-main {
                display: none !important;
            }

            #sidebar {
                background: #02050c !important;
                backdrop-filter: none !important;
            }

            #sidebar::before {
                content: '' !important;
                position: absolute !important;
                inset: 0 !important;
                background: #02050c !important;
                z-index: 0 !important;
                pointer-events: none !important;
            }

            #sidebar > * {
                position: relative !important;
                z-index: 1 !important;
            }

            #sidebar .sidebar-brand.sidebar-brand-logo > .sidebar-brand-text {
                display: block !important;
                min-width: 0 !important;
                opacity: 0 !important;
                max-width: 0 !important;
                height: auto !important;
                overflow: hidden !important;
                transform: translateX(-8px) !important;
                transition: opacity .28s ease .08s, max-width .38s cubic-bezier(.22, 1, .36, 1), transform .38s cubic-bezier(.22, 1, .36, 1) !important;
            }

            #sidebar:hover .sidebar-brand.sidebar-brand-logo > .sidebar-brand-text,
            #sidebar:focus-within .sidebar-brand.sidebar-brand-logo > .sidebar-brand-text {
                opacity: 1 !important;
                max-width: 150px !important;
                transform: translateX(0) !important;
            }

            #sidebar .sidebar-brand.sidebar-brand-logo > .sidebar-brand-text > .brand-name,
            #sidebar .sidebar-brand.sidebar-brand-logo > .sidebar-brand-text > .brand-sub {
                display: block !important;
                opacity: 1 !important;
                width: auto !important;
                max-width: none !important;
                height: auto !important;
                overflow: visible !important;
                white-space: nowrap !important;
                pointer-events: auto !important;
            }
	        }
	    </style>

	    <style>
	        :root[data-theme="light"] #sidebar {
	            background: #fffaf5 !important;
	            border-right-color: rgba(31,25,20,.18) !important;
                box-shadow: 18px 0 48px rgba(66,45,25,.16) !important;
	        }

	        :root[data-theme="light"] #sidebar .brand-name,
	        :root[data-theme="light"] #sidebar .brand-sub,
	        :root[data-theme="light"] #sidebar .nav-label,
	        :root[data-theme="light"] #sidebar .nav-link,
	        :root[data-theme="light"] #sidebar .nav-link span {
	            color: #2c241e !important;
	        }

	        :root[data-theme="light"] #sidebar .nav-label {
	            color: #6c5d50 !important;
	        }

	        :root[data-theme="light"] #sidebar .nav-link i {
	            color: #6c5d50 !important;
	        }

	        :root[data-theme="light"] #sidebar .nav-link:hover {
	            background: rgba(176,0,0,.08) !important;
	            color: #17130f !important;
	        }

	        :root[data-theme="light"] #sidebar .nav-link:hover i,
	        :root[data-theme="light"] #sidebar .nav-link:hover span {
	            color: #17130f !important;
	        }

	        :root[data-theme="light"] #sidebar .nav-link.active {
	            background: rgba(176,0,0,.12) !important;
	            border-left-color: var(--red-h) !important;
	        }

	        :root[data-theme="light"] #sidebar .nav-link.active,
	        :root[data-theme="light"] #sidebar .nav-link.active i,
	        :root[data-theme="light"] #sidebar .nav-link.active span {
	            color: #8d0000 !important;
	        }

	        :root[data-theme="light"] #sidebar .nav-badge {
	            background: var(--red-h) !important;
	            color: #fff !important;
	        }

	        :root[data-theme="light"] .card,
	        :root[data-theme="light"] .modal-content,
	        :root[data-theme="light"] .mini-notificacoes .card {
	            color: var(--text) !important;
	        }

	        :root[data-theme="light"] .table,
	        :root[data-theme="light"] .table td,
	        :root[data-theme="light"] .table > :not(caption) > * > * {
	            color: var(--text) !important;
	        }

	        :root[data-theme="light"] .table th {
	            color: var(--text3) !important;
	            background: rgba(31,25,20,.045) !important;
	        }

	        :root[data-theme="light"] .home-hero h1,
	        :root[data-theme="light"] .home-hero h1::first-line {
	            color: #111 !important;
	        }

	        :root[data-theme="light"] #topbar {
	            background: linear-gradient(90deg, rgba(2,5,12,.96), rgba(2,5,12,.86)) !important;
	        }

	        :root[data-theme="light"] #topbar .breadcrumb-wrap,
	        :root[data-theme="light"] #topbar .breadcrumb-wrap .bc-current,
	        :root[data-theme="light"] #topbar .topbar-user,
	        :root[data-theme="light"] #topbar .topbar-user .user-info-name,
	        :root[data-theme="light"] #topbar .topbar-user div,
	        :root[data-theme="light"] #topbar .topbar-btn,
	        :root[data-theme="light"] #topbar .btn-logout {
	            color: rgba(255,255,255,.82) !important;
	        }

	        :root[data-theme="light"] #topbar .topbar-user:hover,
	        :root[data-theme="light"] #topbar .topbar-btn:hover {
	            color: #fff !important;
	        }

	        :root[data-theme="light"] #topbar .topbar-btn {
	            border-color: rgba(255,255,255,.14) !important;
	        }

	        :root[data-theme="light"] #topbar .topbar-btn:hover {
	            background: rgba(255,255,255,.08) !important;
	            border-color: rgba(255,255,255,.24) !important;
	        }

	        @media (max-width: 900px) {
	            :root { --topbar-h: 64px; }

	            body {
	                min-width: 0 !important;
	            }

	            #sidebar {
	                width: min(84vw, 320px) !important;
	                max-width: 320px !important;
	                height: 100dvh !important;
	                padding-top: 0 !important;
	                transform: translateX(-105%) !important;
	                z-index: 1200 !important;
	                overflow-y: auto !important;
	            }

	            #sidebar.open {
	                transform: translateX(0) !important;
	                box-shadow: 20px 0 60px rgba(0,0,0,.45) !important;
	            }

	            #sidebar .sidebar-brand.sidebar-brand-logo {
	                display: flex !important;
	                height: 64px !important;
	                min-height: 64px !important;
	                padding: 0 1rem !important;
	                justify-content: flex-start !important;
	            }

	            #sidebar .sidebar-brand-text,
	            #sidebar .brand-name,
	            #sidebar .brand-sub,
	            #sidebar .nav-link span,
	            #sidebar .nav-label {
	                display: block !important;
	                opacity: 1 !important;
	                max-width: none !important;
	                transform: none !important;
	                overflow: visible !important;
	            }

	            #sidebar .nav-link {
	                width: 100% !important;
	                min-height: 44px !important;
	                padding: 10px 1rem !important;
	                justify-content: flex-start !important;
	            }

	            #sidebar-overlay {
	                z-index: 1190 !important;
	            }

	            #topbar {
	                position: sticky !important;
	                top: 0 !important;
	                left: 0 !important;
	                right: 0 !important;
	                width: 100% !important;
	                min-height: var(--topbar-h) !important;
	                margin: 0 !important;
	                padding: 8px 12px !important;
	                gap: 8px !important;
	                background: rgba(2,5,12,.92) !important;
	                backdrop-filter: blur(16px) !important;
	                z-index: 1000 !important;
	            }

	            :root[data-theme="light"] #topbar {
	                background: rgba(255,255,255,.92) !important;
	            }

	            .sidebar-toggle {
	                display: inline-flex !important;
	                align-items: center !important;
	                justify-content: center !important;
	                width: 38px !important;
	                height: 38px !important;
	                padding: 0 !important;
	                flex: 0 0 38px !important;
	            }

	            .breadcrumb-wrap {
	                min-width: 0 !important;
	                flex: 1 !important;
	                overflow: hidden !important;
	                white-space: nowrap !important;
	            }

	            .breadcrumb-wrap > span:first-child,
	            .breadcrumb-wrap .bc-sep {
	                display: none !important;
	            }

	            .breadcrumb-wrap .bc-current {
	                overflow: hidden !important;
	                text-overflow: ellipsis !important;
	                white-space: nowrap !important;
	                font-size: 12px !important;
	            }

	            .topbar-right {
	                margin-left: 0 !important;
	                gap: 6px !important;
	                flex-shrink: 0 !important;
	            }

	            .topbar-user {
	                padding: 0 !important;
	                border: 0 !important;
	                background: transparent !important;
	            }

	            .topbar-user > div:last-child,
	            .topbar-user > div:nth-of-type(2),
	            .topbar-user .bi-chevron-down,
	            .btn-logout span,
	            .topbar-btn[title="Pesquisar"] {
	                display: none !important;
	            }

	            .btn-logout,
	            .topbar-btn {
	                width: 38px !important;
	                height: 38px !important;
	                padding: 0 !important;
	            }

	            #content {
	                margin-left: 0 !important;
	                padding: 14px 12px 24px !important;
	                min-height: calc(100dvh - var(--topbar-h)) !important;
	                max-width: 100vw !important;
	            }

	            html,
	            body {
	                max-width: 100%;
	                overflow-x: hidden;
	            }

	            #content,
	            #content *,
	            #topbar,
	            #topbar * {
	                min-width: 0;
	            }

	            #content :where(p, span, small, strong, em, label, dd, dt, td, th, a, .card, .card-body, .card-header, .alert, .badge, .btn, .form-text, .form-control, .form-select) {
	                overflow-wrap: anywhere;
	                word-break: break-word;
	            }

	            #content :where(.btn, .badge, .form-text) {
	                white-space: normal;
	            }

	            #content :where(.card, .alert, .row, [class*="col-"], img, video, iframe) {
	                max-width: 100%;
	            }

	            .home-hero {
	                margin-top: 0 !important;
	                padding: 1.2rem !important;
	                min-height: auto !important;
	            }

	            .home-hero h1,
	            .home-location h2 {
	                font-size: clamp(2rem, 14vw, 3.3rem) !important;
	                line-height: .95 !important;
	                overflow-wrap: anywhere !important;
	            }

	            .home-hero-actions,
	            .card-header,
	            .page-header,
	            .d-flex.flex-wrap {
	                gap: 8px !important;
	            }

	            .card {
	                border-radius: 10px !important;
	            }

	            .card-header,
	            .card-body {
	                padding-left: 1rem !important;
	                padding-right: 1rem !important;
	            }

                .info-block {
                    grid-template-columns: 1fr !important;
                    align-items: stretch !important;
                    padding: .9rem !important;
                }

                .info-block-actions {
                    justify-content: stretch !important;
                    min-width: 0 !important;
                }

                .info-block-actions .btn,
                .info-block-actions form,
                .info-block-actions button {
                    width: 100% !important;
                }

                .report-chart-row {
                    grid-template-columns: 1fr !important;
                    gap: .45rem !important;
                }

                .report-chart-track {
                    height: 16px !important;
                }

	            .row {
	                --bs-gutter-x: .75rem !important;
	                --bs-gutter-y: .75rem !important;
	            }

	            .table-responsive {
	                border-radius: 10px !important;
	                overflow-x: auto !important;
	                -webkit-overflow-scrolling: touch !important;
	            }

	            .table {
	                min-width: 680px !important;
	            }

	            .btn,
	            .form-control,
	            .form-select {
	                min-height: 42px !important;
	            }

	            .modal-dialog {
	                margin: .75rem !important;
	            }

	            #mini-notificacoes {
	                position: fixed !important;
	                top: calc(var(--topbar-h) + 8px) !important;
	                left: 12px !important;
	                right: 12px !important;
	                width: auto !important;
	                max-width: none !important;
	            }

	            .profile-dropdown {
	                position: fixed !important;
	                top: calc(var(--topbar-h) + 8px) !important;
	                right: 12px !important;
	                width: min(220px, calc(100vw - 24px)) !important;
	            }

	            #search-modal > div {
	                width: calc(100vw - 24px) !important;
	                max-width: none !important;
	                margin: 0 12px !important;
	            }
	        }

	        @media (max-width: 576px) {
	            html,
	            body {
	                max-width: 100%;
	                overflow-x: hidden !important;
	            }

	            :root {
	                --topbar-h: 58px;
	            }

	            #topbar {
	                min-height: var(--topbar-h) !important;
	                padding: 7px 10px !important;
	            }

	            .topbar-right {
	                gap: 5px !important;
	            }

	            .sidebar-toggle,
	            .topbar-btn,
	            .btn-logout {
	                width: 36px !important;
	                height: 36px !important;
	                flex: 0 0 36px !important;
	            }

	            .user-avatar {
	                width: 36px !important;
	                height: 36px !important;
	            }

	            #content {
	                padding: 12px 10px 22px !important;
	            }

	            .card {
	                border-radius: 9px !important;
	                margin-bottom: .85rem;
	            }

	            .card-header {
	                min-height: auto !important;
	                padding: .78rem .9rem !important;
	                font-size: .95rem !important;
	            }

	            .card-body {
	                padding: .9rem !important;
	            }

	            .card-body.p-0 {
	                padding: 0 !important;
	            }

	            .row {
	                --bs-gutter-x: .7rem !important;
	                --bs-gutter-y: .85rem !important;
	            }

	            .btn {
	                width: auto;
	                white-space: normal;
	                min-height: 38px !important;
	                padding: .5rem .7rem !important;
	                font-size: .9rem !important;
	            }

	            .d-flex.justify-content-end,
	            .text-end {
	                text-align: left !important;
	            }

	            .d-flex.justify-content-end > .btn,
	            .d-flex.justify-content-end > form,
	            .text-end > .btn,
	            .text-end > form,
	            form.text-end .btn {
	                width: 100% !important;
	            }

	            .text-end > form .btn,
	            .d-flex.justify-content-end > form .btn {
	                width: 100% !important;
	            }

	            .form-control,
	            .form-select {
	                min-height: 40px !important;
	                font-size: 16px !important;
	            }

	            .table-responsive {
	                margin: 0;
	                border-radius: 9px !important;
	            }

	            .table {
	                min-width: 560px !important;
	                font-size: .9rem !important;
	            }

	            .table th,
	            .table td {
	                padding: .72rem .75rem !important;
	                vertical-align: middle !important;
	            }

	            .badge {
	                white-space: normal;
	                line-height: 1.2;
	            }

	            .home-grid,
	            .home-metrics {
	                grid-template-columns: 1fr !important;
	            }

	            .font-mono {
	                font-size: .78rem !important;
	            }

	            .modal-dialog {
	                margin: .6rem !important;
	            }

	            .modal-content {
	                max-height: calc(100dvh - 1.2rem);
	                overflow: auto;
	            }
	        }

            /* Final mobile layer: keeps all existing pages usable on narrow screens. */
            @media (max-width: 900px) {
                html {
                    -webkit-text-size-adjust: 100%;
                }

                body {
                    width: 100%;
                    min-width: 0 !important;
                    overscroll-behavior-x: none;
                }

                body.sidebar-open {
                    overflow: hidden !important;
                }

                #sidebar {
                    position: fixed !important;
                    top: 0 !important;
                    left: 0 !important;
                    width: min(86vw, 320px) !important;
                    max-width: 320px !important;
                    height: 100dvh !important;
                    min-height: 100dvh !important;
                    transform: translateX(-105%) !important;
                    border-right: 1px solid var(--border2) !important;
                    display: flex !important;
                    flex-direction: column !important;
                    overflow: hidden !important;
                    overscroll-behavior: contain;
                    transition: transform .24s ease !important;
                    will-change: transform;
                }

                #sidebar.open {
                    transform: translateX(0) !important;
                }

                #sidebar-overlay {
                    display: block !important;
                    position: fixed !important;
                    inset: 0 !important;
                    background: rgba(0,0,0,.58) !important;
                    backdrop-filter: blur(2px);
                    opacity: 0;
                    pointer-events: none;
                    transition: opacity .22s ease;
                }

                #sidebar-overlay.show {
                    opacity: 1;
                    pointer-events: auto;
                }

                #sidebar .nav-scroll {
                    display: flex !important;
                    flex: 1 1 auto !important;
                    min-height: 0 !important;
                    flex-direction: column !important;
                    align-items: stretch !important;
                    padding: .75rem !important;
                    gap: 2px !important;
                    overflow-y: auto !important;
                    -webkit-overflow-scrolling: touch !important;
                }

                #sidebar .sidebar-brand,
                #sidebar .sidebar-brand.sidebar-brand-logo {
                    flex: 0 0 64px !important;
                    height: 64px !important;
                    min-height: 64px !important;
                    width: 100% !important;
                    padding: 0 16px !important;
                    border-radius: 0 !important;
                    justify-content: flex-start !important;
                    background-clip: padding-box !important;
                }

                #sidebar .sidebar-brand.sidebar-brand-logo {
                    background: transparent !important;
                    border-bottom: 1px solid var(--border) !important;
                }

                #sidebar .sidebar-brand.sidebar-brand-logo .brand-icon-wrap {
                    display: inline-flex !important;
                    width: 42px !important;
                    height: 42px !important;
                    flex: 0 0 42px !important;
                }

                #sidebar .sidebar-brand.sidebar-brand-logo .sidebar-brand-text {
                    display: block !important;
                }

                #sidebar .sidebar-footer {
                    display: none !important;
                }

                #sidebar .sidebar-brand::before,
                #sidebar .sidebar-brand::after {
                    content: none !important;
                    display: none !important;
                    border-radius: 0 !important;
                }

                #sidebar .nav-link {
                    display: flex !important;
                    align-items: center !important;
                    gap: 10px !important;
                    width: 100% !important;
                    min-height: 44px !important;
                    border-radius: 9px !important;
                    margin: 1px 0 !important;
                    padding: 10px 12px !important;
                    white-space: normal !important;
                    flex-shrink: 0 !important;
                }

                #sidebar .nav-link span,
                #sidebar .nav-label,
                #sidebar .brand-name,
                #sidebar .brand-sub,
                #sidebar .sidebar-brand-text {
                    display: block !important;
                    width: auto !important;
                    max-width: none !important;
                    height: auto !important;
                    opacity: 1 !important;
                    overflow: visible !important;
                    transform: none !important;
                    pointer-events: auto !important;
                }

                #topbar {
                    display: grid !important;
                    grid-template-columns: auto minmax(0, 1fr) auto !important;
                    align-items: center !important;
                    margin-left: 0 !important;
                    width: 100% !important;
                    min-height: var(--topbar-h) !important;
                    height: auto !important;
                    padding: 8px max(10px, env(safe-area-inset-left)) 8px max(10px, env(safe-area-inset-right)) !important;
                    gap: 8px !important;
                }

                .breadcrumb-wrap {
                    min-width: 0 !important;
                    max-width: 100% !important;
                }

                .topbar-right {
                    min-width: 0 !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: flex-end !important;
                    gap: 6px !important;
                }

                #content {
                    width: 100% !important;
                    max-width: 100vw !important;
                    margin-left: 0 !important;
                    padding: 14px max(12px, env(safe-area-inset-left)) calc(24px + env(safe-area-inset-bottom)) max(12px, env(safe-area-inset-right)) !important;
                    overflow-x: hidden !important;
                }

                .container,
                .container-fluid,
                .row,
                [class^="col-"],
                [class*=" col-"] {
                    min-width: 0 !important;
                }

                #content > .row,
                #content .card-body > .row {
                    max-width: 100% !important;
                    margin-left: 0 !important;
                    margin-right: 0 !important;
                }

                .card,
                .modal-content,
                .toast-at {
                    max-width: 100% !important;
                }

                .card-header {
                    align-items: flex-start !important;
                    flex-wrap: wrap !important;
                }

                .card-header .btn,
                .page-header .btn {
                    flex-shrink: 0;
                }

                .table-responsive {
                    width: 100% !important;
                    max-width: 100% !important;
                    overflow-x: auto !important;
                    -webkit-overflow-scrolling: touch !important;
                    border: 1px solid var(--border);
                }

                .table-responsive > .table {
                    margin-bottom: 0 !important;
                }

                img,
                video,
                canvas,
                iframe {
                    max-width: 100%;
                }

                .form-control,
                .form-select,
                textarea,
                input {
                    max-width: 100% !important;
                }

                .toast-container {
                    left: 12px !important;
                    right: 12px !important;
                    top: calc(var(--topbar-h) + 10px) !important;
                }

                .toast-at {
                    width: 100% !important;
                    min-width: 0 !important;
                }

                body {
                    font-size: 16px !important;
                    line-height: 1.55 !important;
                    -webkit-text-size-adjust: 100%;
                }

                .card,
                .alert,
                .form-control,
                .form-select,
                .btn,
                .table,
                .profile-dropdown-item {
                    font-size: 15px !important;
                    line-height: 1.45 !important;
                }

                .nav-link {
                    min-height: 46px !important;
                    font-size: 15px !important;
                    padding: 10px 1rem !important;
                }

                .nav-link i {
                    font-size: 18px !important;
                    width: 22px !important;
                }

                .card-body,
                .card-header,
                .card-footer {
                    padding-left: 14px !important;
                    padding-right: 14px !important;
                }

                .btn {
                    min-height: 42px !important;
                    white-space: normal !important;
                }

                .table {
                    min-width: 640px;
                }

                .page-header h1,
                .page-header h2,
                h1 {
                    font-size: 1.55rem !important;
                    line-height: 1.2 !important;
                }

                h2 { font-size: 1.35rem !important; }
                h3 { font-size: 1.2rem !important; }
            }

            @media (max-width: 576px) {
                :root {
                    --topbar-h: 58px;
                }

                #topbar {
                    grid-template-columns: 36px minmax(0, 1fr) auto !important;
                }

                .topbar-btn[title="Pesquisar"],
                .topbar-user > div:not(.user-avatar),
                .topbar-user .bi-chevron-down {
                    display: none !important;
                }

                .breadcrumb-wrap .bc-current {
                    display: block !important;
                    max-width: 100% !important;
                    overflow: hidden !important;
                    text-overflow: ellipsis !important;
                    white-space: nowrap !important;
                }

                .card-header .btn,
                .page-header .btn,
                .card-body > .btn,
                .card-body > form > .btn,
                .text-end > .btn,
                .text-end > form,
                .text-end > form .btn {
                    width: 100% !important;
                }

                .d-flex.align-items-center.justify-content-between,
                .d-flex.justify-content-between {
                    align-items: stretch !important;
                }

                .btn-group,
                .input-group {
                    width: 100%;
                }

                .input-group > .form-control,
                .input-group > .form-select {
                    min-width: 0;
                }

                .page-title {
                    font-size: 18px !important;
                    overflow-wrap: anywhere;
                }

                .home-service-strip,
                .home-grid,
                .home-metrics,
                .home-location {
                    grid-template-columns: 1fr !important;
                }

                .home-hero,
                .home-location {
                    margin-left: -10px !important;
                    margin-right: -10px !important;
                    border-radius: 0 0 12px 12px !important;
                }

                .modal-dialog {
                    max-width: calc(100vw - 20px) !important;
                }
            }

            /* Light theme contrast pass. */
            :root[data-theme="light"] body {
                background:
                    radial-gradient(circle at 78% -10%, rgba(176,0,0,.11), transparent 34%),
                    linear-gradient(135deg, #f1e8df 0%, #e7dbd1 100%) !important;
                color: #17130f !important;
            }

            :root[data-theme="light"] .card,
            :root[data-theme="light"] .modal-content,
            :root[data-theme="light"] .profile-dropdown,
            :root[data-theme="light"] #mini-notificacoes .card {
                background: #fffaf5 !important;
                border-color: rgba(31,25,20,.18) !important;
                color: #17130f !important;
                box-shadow: 0 16px 36px rgba(66,45,25,.14) !important;
            }

            :root[data-theme="light"] .card-header,
            :root[data-theme="light"] .card-footer {
                background: #f1e7de !important;
                border-color: rgba(31,25,20,.16) !important;
                color: #211a15 !important;
            }

            :root[data-theme="light"] .text-muted,
            :root[data-theme="light"] .form-text,
            :root[data-theme="light"] .page-subtitle,
            :root[data-theme="light"] small {
                color: #4f4238 !important;
            }

            :root[data-theme="light"] .fw-500,
            :root[data-theme="light"] .card strong,
            :root[data-theme="light"] .card .fw-semibold,
            :root[data-theme="light"] .card .fw-bold {
                color: #211a15 !important;
            }

            :root[data-theme="light"] .form-control,
            :root[data-theme="light"] .form-select,
            :root[data-theme="light"] .input-group-text {
                background-color: #fffdf9 !important;
                border-color: rgba(31,25,20,.22) !important;
                color: #17130f !important;
            }

            :root[data-theme="light"] .form-control[readonly],
            :root[data-theme="light"] .form-select[aria-disabled="true"] {
                background-color: #eee3d9 !important;
                border-color: rgba(31,25,20,.20) !important;
                color: #211a15 !important;
                -webkit-text-fill-color: #211a15 !important;
                opacity: 1 !important;
            }

            :root[data-theme="light"] .table th {
                background: #eaded3 !important;
                color: #55483d !important;
            }

            :root[data-theme="light"] .table td {
                color: #211a15 !important;
                border-color: rgba(31,25,20,.13) !important;
            }

            :root[data-theme="light"] .topbar-btn,
            :root[data-theme="light"] .sidebar-toggle,
            :root[data-theme="light"] .topbar-user {
                background: #fffaf5 !important;
                border-color: rgba(31,25,20,.18) !important;
                color: #3f352d !important;
            }

            :root[data-theme="light"] .topbar-btn:hover,
            :root[data-theme="light"] .sidebar-toggle:hover,
            :root[data-theme="light"] .topbar-user:hover {
                background: #f3e7dd !important;
                border-color: rgba(176,0,0,.28) !important;
                color: #17130f !important;
            }

            @media (max-width: 900px) {
                :root[data-theme="light"] #topbar {
                    background: rgba(232,218,207,.96) !important;
                    border-bottom-color: rgba(31,25,20,.16) !important;
                    box-shadow: 0 10px 24px rgba(66,45,25,.12) !important;
                }

                :root[data-theme="light"] #topbar .breadcrumb-wrap,
                :root[data-theme="light"] #topbar .bc-current,
                :root[data-theme="light"] #topbar .topbar-user,
                :root[data-theme="light"] #topbar .topbar-user div,
                :root[data-theme="light"] #topbar .topbar-user .user-info-name,
                :root[data-theme="light"] #topbar .topbar-btn {
                    color: #2c241e !important;
                }

                :root[data-theme="light"] #sidebar {
                    background: #fffaf5 !important;
                    border-right-color: rgba(31,25,20,.18) !important;
                    box-shadow: 18px 0 48px rgba(66,45,25,.22) !important;
                }

                :root[data-theme="light"] #sidebar .sidebar-brand.sidebar-brand-logo {
                    background: transparent !important;
                    border-bottom-color: rgba(31,25,20,.16) !important;
                    border-radius: 0 !important;
                    box-shadow: none !important;
                    overflow: hidden !important;
                }

                :root[data-theme="light"] #sidebar .sidebar-brand.sidebar-brand-logo .brand-name {
                    color: #211a15 !important;
                }

                :root[data-theme="light"] #sidebar .sidebar-brand.sidebar-brand-logo .brand-sub {
                    color: #6c5d50 !important;
                }

                #sidebar .sidebar-brand.sidebar-brand-logo {
                    width: 100% !important;
                    max-width: 100% !important;
                    border-radius: 0 !important;
                    box-shadow: none !important;
                    overflow: visible !important;
                }

                #sidebar .sidebar-brand.sidebar-brand-logo::before,
                #sidebar .sidebar-brand.sidebar-brand-logo::after {
                    border-radius: 0 !important;
                }

                :root[data-theme="light"] #sidebar .brand-name,
                :root[data-theme="light"] #sidebar .brand-sub,
                :root[data-theme="light"] #sidebar .nav-label,
                :root[data-theme="light"] #sidebar .nav-link,
                :root[data-theme="light"] #sidebar .nav-link span,
                :root[data-theme="light"] #sidebar .nav-link i {
                    color: #2c241e !important;
                    text-shadow: none !important;
                }

                :root[data-theme="light"] #sidebar .brand-sub,
                :root[data-theme="light"] #sidebar .nav-label {
                    color: #6c5d50 !important;
                }

                :root[data-theme="light"] #sidebar .nav-link {
                    background: transparent !important;
                    border-color: transparent !important;
                }

                :root[data-theme="light"] #sidebar .nav-link:hover {
                    background: rgba(176,0,0,.08) !important;
                    color: #17130f !important;
                }

                :root[data-theme="light"] #sidebar .nav-link.active {
                    background: rgba(176,0,0,.12) !important;
                    border-left-color: var(--red-h) !important;
                }

                :root[data-theme="light"] #sidebar .nav-link.active,
                :root[data-theme="light"] #sidebar .nav-link.active span,
                :root[data-theme="light"] #sidebar .nav-link.active i {
                    color: #8d0000 !important;
                    font-weight: 800 !important;
                }
            }

            /* Mobile sidebar hard reset: keeps the drawer usable even with long manager menus. */
            @media (max-width: 900px) {
                html body #sidebar,
                html body #sidebar:hover,
                html body #sidebar:focus-within {
                    position: fixed !important;
                    top: 0 !important;
                    left: 0 !important;
                    right: auto !important;
                    width: min(84vw, 320px) !important;
                    max-width: 320px !important;
                    height: 100dvh !important;
                    min-height: 100dvh !important;
                    display: flex !important;
                    flex-direction: column !important;
                    padding: 0 !important;
                    transform: translate3d(-105%, 0, 0) !important;
                    overflow: hidden !important;
                    box-sizing: border-box !important;
                    transition: transform .24s ease !important;
                }

                html body #sidebar.open,
                html body #sidebar.open:hover,
                html body #sidebar.open:focus-within {
                    transform: translate3d(0, 0, 0) !important;
                }

                html body #sidebar .sidebar-brand.sidebar-brand-logo,
                html body #sidebar:hover .sidebar-brand.sidebar-brand-logo,
                html body #sidebar:focus-within .sidebar-brand.sidebar-brand-logo {
                    display: flex !important;
                    width: 100% !important;
                    height: 58px !important;
                    min-height: 58px !important;
                    padding: 0 12px !important;
                    justify-content: flex-start !important;
                    gap: 10px !important;
                    overflow: hidden !important;
                }

                html body #sidebar .sidebar-brand-text,
                html body #sidebar:hover .sidebar-brand-text,
                html body #sidebar:focus-within .sidebar-brand-text,
                html body #sidebar:not(:hover):not(:focus-within) .sidebar-brand-text {
                    display: block !important;
                    opacity: 1 !important;
                    min-width: 0 !important;
                    max-width: calc(100% - 52px) !important;
                    height: auto !important;
                    overflow: hidden !important;
                    transform: none !important;
                }

                html body #sidebar .brand-name,
                html body #sidebar .brand-sub {
                    display: block !important;
                    max-width: 100% !important;
                    overflow: hidden !important;
                    text-overflow: ellipsis !important;
                    white-space: nowrap !important;
                    opacity: 1 !important;
                    transform: none !important;
                }

                html body #sidebar .nav-scroll,
                html body #sidebar:hover .nav-scroll,
                html body #sidebar:focus-within .nav-scroll,
                html body #sidebar:not(:hover):not(:focus-within) .nav-scroll {
                    display: flex !important;
                    flex: 1 1 auto !important;
                    min-height: 0 !important;
                    flex-direction: column !important;
                    align-items: stretch !important;
                    padding: 8px 9px calc(140px + env(safe-area-inset-bottom)) !important;
                    overflow-y: auto !important;
                    overflow-x: hidden !important;
                    -webkit-overflow-scrolling: touch !important;
                    scrollbar-gutter: stable !important;
                }

                html body #sidebar .sidebar-footer {
                    display: none !important;
                }

                html body #sidebar .nav-link,
                html body #sidebar:hover .nav-link,
                html body #sidebar:focus-within .nav-link,
                html body #sidebar:not(:hover):not(:focus-within) .nav-link {
                    display: flex !important;
                    align-items: center !important;
                    justify-content: flex-start !important;
                    gap: 10px !important;
                    width: 100% !important;
                    height: auto !important;
                    min-height: 38px !important;
                    margin: 1px 0 !important;
                    padding: 8px 10px !important;
                    border-radius: 8px !important;
                    box-sizing: border-box !important;
                    overflow: hidden !important;
                }

                html body #sidebar .nav-link i {
                    width: 22px !important;
                    min-width: 22px !important;
                    font-size: 16px !important;
                    text-align: center !important;
                }

                html body #sidebar .nav-link span,
                html body #sidebar:hover .nav-link span,
                html body #sidebar:focus-within .nav-link span,
                html body #sidebar:not(:hover):not(:focus-within) .nav-link span {
                    display: block !important;
                    flex: 1 1 auto !important;
                    min-width: 0 !important;
                    width: auto !important;
                    max-width: 100% !important;
                    height: auto !important;
                    opacity: 1 !important;
                    overflow: hidden !important;
                    text-overflow: ellipsis !important;
                    white-space: nowrap !important;
                    transform: none !important;
                    pointer-events: auto !important;
                    font-size: 13px !important;
                    line-height: 1.2 !important;
                    letter-spacing: 0 !important;
                }

                html body #sidebar .nav-label,
                html body #sidebar:hover .nav-label,
                html body #sidebar:focus-within .nav-label,
                html body #sidebar:not(:hover):not(:focus-within) .nav-label {
                    display: block !important;
                    width: auto !important;
                    max-width: 100% !important;
                    height: auto !important;
                    margin: 7px 0 2px !important;
                    padding: 0 10px !important;
                    opacity: 1 !important;
                    overflow: hidden !important;
                    text-overflow: ellipsis !important;
                    white-space: nowrap !important;
                    transform: none !important;
                    font-size: 9px !important;
                    line-height: 1.2 !important;
                    letter-spacing: .14em !important;
                }
            }

            @media (min-width: 901px) {
                :root[data-theme="light"] #topbar .topbar-user,
                :root[data-theme="light"] #topbar .topbar-btn,
                :root[data-theme="light"] #topbar .btn-logout {
                    background: rgba(255,255,255,.12) !important;
                    border: 1px solid rgba(255,255,255,.18) !important;
                    color: rgba(255,255,255,.88) !important;
                    box-shadow: none !important;
                }

                :root[data-theme="light"] #topbar .topbar-user .user-info-name,
                :root[data-theme="light"] #topbar .topbar-user div,
                :root[data-theme="light"] #topbar .topbar-user i,
                :root[data-theme="light"] #topbar .topbar-btn i {
                    color: rgba(255,255,255,.9) !important;
                }

                :root[data-theme="light"] #topbar .topbar-user div[style*="var(--text3)"] {
                    color: rgba(255,255,255,.62) !important;
                }

                :root[data-theme="light"] #topbar .topbar-user:hover,
                :root[data-theme="light"] #topbar .topbar-btn:hover {
                    background: rgba(255,255,255,.20) !important;
                    border-color: rgba(255,255,255,.32) !important;
                    color: #fff !important;
                }
            }

            :root[data-theme="light"] html body #sidebar,
            :root[data-theme="light"] html body #sidebar:hover,
            :root[data-theme="light"] html body #sidebar:focus-within {
                background: #fffaf5 !important;
                border-right-color: rgba(31,25,20,.18) !important;
                box-shadow: 18px 0 48px rgba(66,45,25,.16) !important;
                color: #2c241e !important;
            }

            :root[data-theme="light"] html body #sidebar::before {
                background: #fffaf5 !important;
            }

            :root[data-theme="light"] html body #sidebar .sidebar-brand.sidebar-brand-logo {
                background: transparent !important;
                border-bottom-color: rgba(31,25,20,.16) !important;
            }

            :root[data-theme="light"] html body #sidebar .brand-name,
            :root[data-theme="light"] html body #sidebar .nav-link,
            :root[data-theme="light"] html body #sidebar .nav-link span,
            :root[data-theme="light"] html body #sidebar .nav-link i {
                color: #2c241e !important;
                text-shadow: none !important;
            }

            :root[data-theme="light"] html body #sidebar .brand-sub,
            :root[data-theme="light"] html body #sidebar .nav-label {
                color: #6c5d50 !important;
                text-shadow: none !important;
            }

            :root[data-theme="light"] html body #sidebar .nav-link {
                background: transparent !important;
                border-color: transparent !important;
            }

            :root[data-theme="light"] html body #sidebar .nav-link:hover {
                background: rgba(176,0,0,.08) !important;
            }

            :root[data-theme="light"] html body #sidebar .nav-link.active {
                background: rgba(176,0,0,.12) !important;
                border-left-color: var(--red-h) !important;
            }

            :root[data-theme="light"] html body #sidebar .nav-link.active,
            :root[data-theme="light"] html body #sidebar .nav-link.active span,
            :root[data-theme="light"] html body #sidebar .nav-link.active i {
                color: #8d0000 !important;
                font-weight: 800 !important;
            }

            :root[data-theme="light"] body #sidebar,
            :root[data-theme="light"] body #sidebar:hover,
            :root[data-theme="light"] body #sidebar:focus-within {
                background: #fffaf5 !important;
                border-right-color: rgba(31,25,20,.18) !important;
                box-shadow: 18px 0 48px rgba(66,45,25,.16) !important;
                color: #2c241e !important;
            }

            :root[data-theme="light"] body #sidebar::before {
                background: #fffaf5 !important;
            }

            :root[data-theme="light"] body #sidebar .sidebar-brand,
            :root[data-theme="light"] body #sidebar .sidebar-brand.sidebar-brand-logo,
            :root[data-theme="light"] body #sidebar .sidebar-footer {
                background: transparent !important;
                border-color: rgba(31,25,20,.16) !important;
                box-shadow: none !important;
            }

            :root[data-theme="light"] body #sidebar .brand-name,
            :root[data-theme="light"] body #sidebar .nav-link,
            :root[data-theme="light"] body #sidebar .nav-link span,
            :root[data-theme="light"] body #sidebar .nav-link i,
            :root[data-theme="light"] body #sidebar .user-info-name,
            :root[data-theme="light"] body #sidebar .btn-logout {
                color: #2c241e !important;
                text-shadow: none !important;
            }

            :root[data-theme="light"] body #sidebar .brand-sub,
            :root[data-theme="light"] body #sidebar .nav-label,
            :root[data-theme="light"] body #sidebar .user-role-badge {
                color: #6c5d50 !important;
                text-shadow: none !important;
            }

            :root[data-theme="light"] body #sidebar .nav-link {
                background: transparent !important;
                border-color: transparent !important;
            }

            :root[data-theme="light"] body #sidebar .nav-link:hover {
                background: rgba(176,0,0,.08) !important;
                color: #17130f !important;
            }

            :root[data-theme="light"] body #sidebar .nav-link.active {
                background: rgba(176,0,0,.12) !important;
                border-left-color: var(--red-h) !important;
            }

            :root[data-theme="light"] body #sidebar .nav-link.active,
            :root[data-theme="light"] body #sidebar .nav-link.active span,
            :root[data-theme="light"] body #sidebar .nav-link.active i {
                color: #8d0000 !important;
                font-weight: 800 !important;
            }

            @media (min-width: 901px) {
                :root[data-theme="light"] body #topbar {
                    background: rgba(255,250,245,.94) !important;
                    border-bottom: 1px solid rgba(31,25,20,.16) !important;
                    box-shadow: 0 12px 30px rgba(66,45,25,.12) !important;
                    color: #2c241e !important;
                    backdrop-filter: blur(16px) !important;
                }

                :root[data-theme="light"] body #topbar .breadcrumb-wrap,
                :root[data-theme="light"] body #topbar .breadcrumb-wrap .bc-current,
                :root[data-theme="light"] body #topbar .topbar-user,
                :root[data-theme="light"] body #topbar .topbar-user .user-info-name,
                :root[data-theme="light"] body #topbar .topbar-user div,
                :root[data-theme="light"] body #topbar .topbar-user i,
                :root[data-theme="light"] body #topbar .topbar-btn,
                :root[data-theme="light"] body #topbar .topbar-btn i,
                :root[data-theme="light"] body #topbar .btn-logout {
                    color: #2c241e !important;
                    text-shadow: none !important;
                }

                :root[data-theme="light"] body #topbar .topbar-user div[style*="var(--text3)"] {
                    color: #6c5d50 !important;
                }

                :root[data-theme="light"] body #topbar .topbar-user,
                :root[data-theme="light"] body #topbar .topbar-btn,
                :root[data-theme="light"] body #topbar .btn-logout {
                    background: #fffaf5 !important;
                    border: 1px solid rgba(31,25,20,.18) !important;
                    box-shadow: 0 8px 20px rgba(66,45,25,.10) !important;
                }

                :root[data-theme="light"] body #topbar .topbar-user:hover,
                :root[data-theme="light"] body #topbar .topbar-btn:hover,
                :root[data-theme="light"] body #topbar .btn-logout:hover {
                    background: #f3e7dd !important;
                    border-color: rgba(176,0,0,.28) !important;
                    color: #17130f !important;
                }
            }

            @media (min-width: 901px) {
                html body #sidebar:hover .nav-scroll,
                html body #sidebar:focus-within .nav-scroll {
                    padding-top: 14px !important;
                    padding-bottom: 18px !important;
                    overflow-y: auto !important;
                    overflow-x: hidden !important;
                }

                html body #sidebar:hover .nav-label,
                html body #sidebar:focus-within .nav-label {
                    display: block !important;
                    width: auto !important;
                    max-width: 100% !important;
                    max-height: none !important;
                    min-height: 22px !important;
                    height: auto !important;
                    margin: 16px 0 7px !important;
                    padding: 0 1.2rem !important;
                    overflow: visible !important;
                    white-space: normal !important;
                    text-overflow: clip !important;
                    line-height: 1.45 !important;
                    letter-spacing: .16em !important;
                    opacity: 1 !important;
                    transform: none !important;
                }

                html body #sidebar:hover .nav-link,
                html body #sidebar:focus-within .nav-link {
                    min-height: 44px !important;
                    padding-top: 9px !important;
                    padding-bottom: 9px !important;
                    overflow: visible !important;
                }

                html body #sidebar:hover .nav-link span,
                html body #sidebar:focus-within .nav-link span {
                    line-height: 1.25 !important;
                    overflow: visible !important;
                }

                :root[data-theme="light"] html body #sidebar:hover .nav-label,
                :root[data-theme="light"] html body #sidebar:focus-within .nav-label {
                    color: #6c5d50 !important;
                }
            }

            /* Public-layout visual system applied across the authenticated app. */
            body {
                background:
                    radial-gradient(circle at 82% -12%, rgba(196,0,0,.20), transparent 32%),
                    radial-gradient(circle at 8% 0%, rgba(255,255,255,.035), transparent 26%),
                    var(--bg) !important;
            }

            :root[data-theme="light"] body {
                background:
                    radial-gradient(circle at 78% -10%, rgba(176,0,0,.12), transparent 34%),
                    radial-gradient(circle at 12% 10%, rgba(255,255,255,.85), transparent 28%),
                    linear-gradient(135deg, #F8F5EF 0%, #EFE9E1 58%, #F6F1EA 100%) !important;
            }

            #topbar {
                background: rgba(7,7,7,.88) !important;
                border-bottom: 1px solid rgba(255,255,255,.08) !important;
                box-shadow: 0 18px 48px rgba(0,0,0,.28) !important;
                backdrop-filter: blur(16px) !important;
            }

            #sidebar {
                background:
                    linear-gradient(180deg, rgba(255,255,255,.035), rgba(255,255,255,.012)),
                    rgba(15,15,15,.96) !important;
                border-right: 1px solid rgba(255,255,255,.08) !important;
                box-shadow: 18px 0 60px rgba(0,0,0,.18) !important;
                backdrop-filter: blur(14px) !important;
            }

            #sidebar .nav-link {
                border-radius: 8px !important;
                border-left: 0 !important;
                margin: 3px .7rem !important;
                color: rgba(255,255,255,.72) !important;
            }

            #sidebar .nav-link:hover,
            #sidebar .nav-link.active {
                background: rgba(196,0,0,.14) !important;
                color: #fff !important;
            }

            #sidebar .nav-link.active i,
            #sidebar .nav-link:hover i {
                color: var(--red-h) !important;
            }

            .brand-icon-wrap,
            .topbar-brand-icon,
            .user-avatar-inner,
            .info-block-icon,
            .profile-action-icon,
            .notification-icon {
                border-radius: 8px !important;
                background: linear-gradient(135deg, var(--red-h), #690000) !important;
                box-shadow: 0 14px 34px rgba(196,0,0,.22) !important;
            }

            #content {
                background: transparent !important;
            }

            #content .card,
            #content .modal-content,
            #content .profile-card,
            #content .notification-card,
            #content .info-block,
            #content .report-card,
            #content .metric-card,
            #content .os-index-card,
            #content .table-responsive {
                background:
                    linear-gradient(180deg, rgba(255,255,255,.055), rgba(255,255,255,.018)),
                    rgba(15,15,15,.96) !important;
                border: 1px solid rgba(255,255,255,.12) !important;
                border-radius: 10px !important;
                box-shadow: 0 18px 46px rgba(0,0,0,.20) !important;
            }

            #content .card-header,
            #content .card-footer,
            #content .modal-header,
            #content .modal-footer {
                background: rgba(255,255,255,.025) !important;
                border-color: rgba(255,255,255,.10) !important;
                color: var(--text) !important;
            }

            #content .card-header {
                min-height: 58px;
                font-family: 'Syne', sans-serif !important;
                font-weight: 800 !important;
                letter-spacing: 0 !important;
            }

            #content .card-header i,
            #content .page-header i,
            #content .section-title i {
                color: var(--red-h) !important;
            }

            #content .btn,
            #topbar .topbar-btn,
            #topbar .btn-logout,
            #topbar .topbar-user {
                border-radius: 8px !important;
                font-weight: 700 !important;
                box-shadow: none !important;
            }

            #content .btn-primary,
            #content .btn-danger,
            #content .btn-outline-danger:hover,
            #content .btn-outline-primary:hover {
                background: var(--red) !important;
                border-color: var(--red-h) !important;
                color: #fff !important;
                box-shadow: 0 12px 30px rgba(196,0,0,.22) !important;
            }

            #content .btn-primary:hover,
            #content .btn-danger:hover {
                background: var(--red-h) !important;
            }

            #content .btn-outline-primary,
            #content .btn-outline-danger,
            #content .btn-outline-secondary,
            #content .btn-outline-light,
            #content .btn-light {
                background: rgba(0,0,0,.16) !important;
                border-color: rgba(255,255,255,.14) !important;
                color: var(--text2) !important;
            }

            #content .btn-outline-primary:hover,
            #content .btn-outline-secondary:hover,
            #content .btn-light:hover {
                background: rgba(255,255,255,.06) !important;
                border-color: rgba(255,255,255,.22) !important;
                color: #fff !important;
            }

            #content .form-control,
            #content .form-select,
            #content textarea,
            #content .input-group-text {
                background: rgba(255,255,255,.035) !important;
                border: 1px solid rgba(255,255,255,.12) !important;
                border-radius: 8px !important;
                color: var(--text) !important;
            }

            #content .form-control:focus,
            #content .form-select:focus,
            #content textarea:focus {
                border-color: var(--red-border) !important;
                box-shadow: 0 0 0 3px rgba(196,0,0,.14) !important;
            }

            #content .form-label {
                color: var(--text2) !important;
                font-weight: 800 !important;
                letter-spacing: .06em !important;
                text-transform: uppercase !important;
                font-size: .76rem !important;
            }

            #content .table {
                --bs-table-bg: transparent !important;
                --bs-table-color: var(--text) !important;
                --bs-table-border-color: rgba(255,255,255,.08) !important;
                color: var(--text) !important;
                margin-bottom: 0 !important;
            }

            #content .table thead,
            #content .table-light {
                background: rgba(255,255,255,.045) !important;
                color: var(--text2) !important;
            }

            #content .table th {
                color: var(--text3) !important;
                font-size: .74rem !important;
                letter-spacing: .08em !important;
                text-transform: uppercase !important;
                border-bottom-color: rgba(255,255,255,.10) !important;
            }

            #content .table td {
                border-color: rgba(255,255,255,.07) !important;
                vertical-align: middle !important;
            }

            #content .table-hover tbody tr:hover {
                background: rgba(196,0,0,.08) !important;
                color: var(--text) !important;
            }

            #content .badge {
                border-radius: 999px !important;
                font-weight: 800 !important;
                letter-spacing: 0 !important;
            }

            #content .alert {
                border-radius: 10px !important;
                border: 1px solid rgba(255,255,255,.12) !important;
                background: rgba(255,255,255,.04) !important;
                color: var(--text) !important;
            }

            :root[data-theme="light"] #topbar {
                background: rgba(255,250,245,.94) !important;
                border-bottom-color: rgba(31,25,20,.16) !important;
                box-shadow: 0 12px 30px rgba(66,45,25,.12) !important;
            }

            :root[data-theme="light"] #sidebar {
                background: rgba(255,250,245,.96) !important;
                border-right-color: rgba(31,25,20,.16) !important;
                box-shadow: 18px 0 48px rgba(66,45,25,.14) !important;
            }

            :root[data-theme="light"] #content .card,
            :root[data-theme="light"] #content .modal-content,
            :root[data-theme="light"] #content .profile-card,
            :root[data-theme="light"] #content .notification-card,
            :root[data-theme="light"] #content .info-block,
            :root[data-theme="light"] #content .report-card,
            :root[data-theme="light"] #content .metric-card,
            :root[data-theme="light"] #content .os-index-card,
            :root[data-theme="light"] #content .table-responsive {
                background:
                    linear-gradient(180deg, rgba(255,255,255,.86), rgba(255,255,255,.62)),
                    #fffaf5 !important;
                border-color: rgba(31,25,20,.14) !important;
                box-shadow: 0 18px 40px rgba(66,45,25,.10) !important;
            }

            :root[data-theme="light"] #content .card-header,
            :root[data-theme="light"] #content .card-footer,
            :root[data-theme="light"] #content .modal-header,
            :root[data-theme="light"] #content .modal-footer {
                background: rgba(31,25,20,.035) !important;
                border-color: rgba(31,25,20,.12) !important;
                color: var(--text) !important;
            }

            :root[data-theme="light"] #content .form-control,
            :root[data-theme="light"] #content .form-select,
            :root[data-theme="light"] #content textarea,
            :root[data-theme="light"] #content .input-group-text {
                background: rgba(255,255,255,.78) !important;
                border-color: rgba(31,25,20,.16) !important;
                color: var(--text) !important;
            }

            :root[data-theme="light"] #content .btn-outline-primary,
            :root[data-theme="light"] #content .btn-outline-danger,
            :root[data-theme="light"] #content .btn-outline-secondary,
            :root[data-theme="light"] #content .btn-outline-light,
            :root[data-theme="light"] #content .btn-light {
                background: rgba(255,255,255,.58) !important;
                border-color: rgba(31,25,20,.18) !important;
                color: var(--text2) !important;
            }

            :root[data-theme="light"] #content .table th,
            :root[data-theme="light"] #content .table td {
                border-color: rgba(31,25,20,.10) !important;
            }

            :root[data-theme="light"] #content .table thead,
            :root[data-theme="light"] #content .table-light {
                background: rgba(31,25,20,.045) !important;
            }

            /* Global responsive polish: keeps dense admin screens readable on desktop and mobile. */
            #content,
            #content * {
                min-width: 0;
            }

            #content img,
            #content video,
            #content canvas,
            #content iframe {
                max-width: 100%;
            }

            #content :where(.card, .alert, .info-block, .notification-card, .table-responsive, .modal-content) {
                max-width: 100%;
            }

            #content :where(p, span, strong, small, label, td, th, dd, dt, .btn, .badge, .form-control, .form-select) {
                overflow-wrap: anywhere;
            }

            #content :where(.btn, .badge) {
                white-space: normal;
            }

            #content :where(.d-flex) {
                min-width: 0;
            }

            #content :where(.table-responsive) {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            @media (min-width: 901px) {
                #content {
                    max-width: 100vw;
                    overflow-x: hidden;
                }

                #content .card {
                    overflow: hidden;
                }
            }

            @media (max-width: 900px) {
                #content {
                    max-width: 100vw !important;
                    overflow-x: hidden !important;
                }

                #content :where(.card-header, .card-body, .card-footer) {
                    min-width: 0 !important;
                }

                #content :where(.card-body.p-0) {
                    overflow-x: auto !important;
                    -webkit-overflow-scrolling: touch !important;
                }

                #content :where(.card-header.d-flex, .card-body.d-flex, .card-footer.d-flex) {
                    align-items: stretch !important;
                    flex-wrap: wrap !important;
                }

                #content :where(.card-header .btn, .card-body > .btn, .card-body > form > .btn) {
                    max-width: 100%;
                }

                #content :where(.row) {
                    max-width: 100% !important;
                }

                #content :where(input, select, textarea, button) {
                    max-width: 100%;
                }
            }

            @media (max-width: 576px) {
                #content :where(.btn-group, .btn-toolbar) {
                    display: grid !important;
                    grid-template-columns: 1fr !important;
                    width: 100% !important;
                }

                #content :where(.btn-group .btn, .btn-toolbar .btn) {
                    width: 100% !important;
                    border-radius: 8px !important;
                }

                #content :where(.table) {
                    min-width: 620px;
                }

                #content :where(.modal-footer, .modal-header) {
                    gap: .5rem;
                    flex-wrap: wrap;
                }
            }

            @media (max-width: 900px) {
                :root {
                    --topbar-h: 60px;
                }

                body {
                    overflow-x: hidden !important;
                    -webkit-text-size-adjust: 100%;
                }

                #topbar {
                    min-height: var(--topbar-h) !important;
                    height: auto !important;
                    padding: .55rem .75rem !important;
                    gap: .55rem !important;
                    align-items: center !important;
                    border-bottom-color: rgba(255,255,255,.10) !important;
                }

                #topbar .topbar-brand {
                    min-width: 0 !important;
                    flex: 1 1 auto !important;
                }

                #topbar .topbar-brand-name,
                #topbar .topbar-brand-sub {
                    max-width: 36vw !important;
                    overflow: hidden !important;
                    text-overflow: ellipsis !important;
                    white-space: nowrap !important;
                }

                #topbar .topbar-right {
                    flex: 0 0 auto !important;
                    gap: .35rem !important;
                    margin-left: 0 !important;
                }

                #topbar .topbar-user {
                    min-width: 0 !important;
                    padding: .25rem !important;
                    gap: .35rem !important;
                }

                #topbar .topbar-user .user-info-name,
                #topbar .topbar-user .user-role-badge,
                #topbar .topbar-user .user-info,
                #topbar .topbar-user .bi-chevron-down {
                    display: none !important;
                }

                #topbar .topbar-btn,
                #topbar .btn-logout,
                .sidebar-toggle {
                    width: 42px !important;
                    height: 42px !important;
                    min-width: 42px !important;
                    border-radius: 12px !important;
                }

                #content {
                    padding: .9rem .75rem 1.25rem !important;
                    width: 100% !important;
                    max-width: 100vw !important;
                    overflow-x: clip !important;
                }

                #content :where(.container, .container-fluid) {
                    padding-left: 0 !important;
                    padding-right: 0 !important;
                    max-width: 100% !important;
                }

                #content :where(.row) {
                    --bs-gutter-x: .75rem;
                    --bs-gutter-y: .75rem;
                    margin-left: calc(var(--bs-gutter-x) * -.5) !important;
                    margin-right: calc(var(--bs-gutter-x) * -.5) !important;
                }

                #content :where(.card, .alert, .info-block, .notification-card, .profile-card, .report-card, .metric-card, .os-index-card, .dashboard-card, .parts-catalog-filter, .parts-xray-card) {
                    border-radius: 12px !important;
                    box-shadow: 0 12px 34px rgba(0,0,0,.20), inset 0 1px 0 rgba(255,255,255,.045) !important;
                }

                #content :where(.card-header, .card-footer, .modal-header, .modal-footer, .parts-catalog-filter-head, .parts-xray-head) {
                    min-height: auto !important;
                    padding: .85rem .9rem !important;
                    gap: .55rem !important;
                    flex-wrap: wrap !important;
                }

                #content :where(.card-body, .modal-body, .offcanvas-body) {
                    padding: .95rem !important;
                }

                #content :where(.card-header .btn, .card-footer .btn, .modal-footer .btn, .text-end .btn, .d-flex.justify-content-end > .btn) {
                    flex: 1 1 auto;
                }

                #content :where(.info-block) {
                    grid-template-columns: auto minmax(0, 1fr) !important;
                    align-items: flex-start !important;
                }

                #content :where(.info-block-actions) {
                    grid-column: 1 / -1;
                    justify-content: stretch !important;
                    width: 100%;
                }

                #content :where(.info-block-actions .btn) {
                    flex: 1 1 160px;
                }

                #content :where(.form-control, .form-select, textarea, .input-group-text) {
                    min-height: 46px !important;
                    font-size: 16px !important;
                }

                #content :where(.btn) {
                    min-height: 44px !important;
                    touch-action: manipulation;
                }

                #content :where(.table-responsive, .card-body.p-0) {
                    border-radius: 0 0 12px 12px;
                    overflow-x: auto !important;
                    -webkit-overflow-scrolling: touch !important;
                }

                #content :where(.table) {
                    min-width: 680px;
                }

                #content :where(.pagination) {
                    flex-wrap: wrap !important;
                    gap: .25rem !important;
                    justify-content: center !important;
                }

                #content :where(.pagination .page-link) {
                    min-width: 38px;
                    min-height: 38px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                }

                #content :where(.parts-xray, .services-showcase, .ecosystem, .testimonial-grid, .report-grid, .metrics-grid, .dashboard-grid) {
                    grid-template-columns: 1fr !important;
                }

                #content :where(.parts-car-wrap) {
                    min-height: 250px !important;
                    padding: .7rem !important;
                }

                #content :where(.parts-car-stage) {
                    height: 240px !important;
                }
            }

            @media (max-width: 640px) {
                #content {
                    padding: .75rem .55rem 1rem !important;
                }

                #content :where(.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12, .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12) {
                    width: 100% !important;
                    flex: 0 0 100% !important;
                    max-width: 100% !important;
                }

                #content :where(.d-flex:not(.client-name-stack):not(.topbar-right):not(.input-group)) {
                    flex-wrap: wrap !important;
                }

                #content :where(.btn, button.btn) {
                    width: 100%;
                }

                #content :where(.input-group .btn, .input-group .input-group-text) {
                    width: auto;
                }

                #content :where(.card-header, .card-footer) {
                    align-items: stretch !important;
                }

                #content :where(.card-header > *, .card-footer > *) {
                    max-width: 100%;
                }

                #content :where(.stat-card, .metric-card) {
                    min-height: auto !important;
                }

                #content :where(.modal-dialog) {
                    margin: .6rem !important;
                }
            }

            @media (max-width: 420px) {
                #topbar .topbar-brand-name,
                #topbar .topbar-brand-sub {
                    display: none !important;
                }

                #topbar .topbar-btn[title="Pesquisar"] {
                    display: none !important;
                }

                #content :where(.card-body, .modal-body, .offcanvas-body) {
                    padding: .8rem !important;
                }
            }
	    </style>

        <style>
            /* Microinteracoes compartilhadas do site. */
            #content {
                background:
                    radial-gradient(circle at 82% 0%, rgba(196, 0, 0, .10), transparent 28%),
                    radial-gradient(circle at 8% 8%, rgba(255, 255, 255, .035), transparent 24%);
            }

            #content :where(.card, .alert, .info-block, .notification-card, .profile-card, .report-card, .metric-card, .os-index-card, .dashboard-card, .parts-catalog-filter, .parts-xray-card) {
                position: relative;
                overflow: hidden;
                border: 1px solid rgba(255, 255, 255, .105) !important;
                border-radius: 14px !important;
                background:
                    linear-gradient(135deg, rgba(255, 255, 255, .060), rgba(255, 255, 255, .018) 48%, rgba(196, 0, 0, .035)),
                    var(--surface) !important;
                box-shadow:
                    0 20px 60px rgba(0, 0, 0, .22),
                    inset 0 1px 0 rgba(255, 255, 255, .055) !important;
                backdrop-filter: blur(10px);
            }

            #content :where(.card, .alert, .info-block, .notification-card, .profile-card, .report-card, .metric-card, .os-index-card, .dashboard-card, .parts-catalog-filter, .parts-xray-card)::before {
                content: '';
                position: absolute;
                inset: 0;
                pointer-events: none;
                background:
                    radial-gradient(circle at 100% 0%, rgba(196, 0, 0, .14), transparent 26%),
                    linear-gradient(90deg, rgba(255, 255, 255, .070), transparent 22%, transparent 78%, rgba(255, 255, 255, .025));
                opacity: .78;
                z-index: 0;
            }

            #content :where(.card > *, .alert > *, .info-block > *, .notification-card > *, .profile-card > *, .report-card > *, .metric-card > *, .os-index-card > *, .dashboard-card > *, .parts-catalog-filter > *, .parts-xray-card > *) {
                position: relative;
                z-index: 1;
            }

            #content :where(.card-header, .modal-header, .offcanvas-header, .parts-catalog-filter-head, .parts-xray-head) {
                min-height: 58px;
                padding: 1rem 1.25rem !important;
                border-bottom: 1px solid rgba(255, 255, 255, .095) !important;
                background:
                    linear-gradient(90deg, rgba(196, 0, 0, .115), transparent 42%),
                    rgba(255, 255, 255, .018) !important;
                color: var(--text) !important;
                font-family: 'Syne', sans-serif !important;
                font-size: clamp(.98rem, .9vw, 1.08rem) !important;
                font-weight: 850 !important;
                letter-spacing: .01em;
            }

            #content :where(.card-header, .modal-header, .offcanvas-header) > i:first-child,
            #content :where(.card-header, .modal-header, .offcanvas-header) > .bi:first-child {
                width: 34px;
                height: 34px;
                display: inline-grid;
                place-items: center;
                margin-right: .15rem;
                border-radius: 10px;
                color: #fff !important;
                background: linear-gradient(135deg, var(--red-h), #850000);
                box-shadow: 0 12px 28px rgba(196, 0, 0, .24);
            }

            #content :where(.card-body, .modal-body, .offcanvas-body) {
                padding: 1.25rem !important;
            }

            #content :where(.card-footer, .modal-footer) {
                padding: 1rem 1.25rem !important;
                border-top: 1px solid rgba(255, 255, 255, .095) !important;
                background: rgba(255, 255, 255, .018) !important;
            }

            #content :where(.card-body .card, .card-body .alert, .card-body fieldset, .card-body .border, .card-body [class*="p-"].border, .payment-card, .parts-region-active, .parts-region-chip, .parts-car-stage) {
                border-color: rgba(255, 255, 255, .115) !important;
                border-radius: 12px !important;
                background:
                    linear-gradient(145deg, rgba(255, 255, 255, .045), rgba(255, 255, 255, .014)),
                    rgba(255, 255, 255, .012) !important;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, .045);
            }

            #content :where(.info-block) {
                padding: 1.05rem 1.15rem !important;
                gap: 1rem !important;
            }

            #content :where(.info-block-icon, .parts-filter-title-icon, .parts-region-active .icon) {
                border: 1px solid rgba(255, 255, 255, .13);
                background:
                    radial-gradient(circle at 28% 22%, rgba(255, 255, 255, .26), transparent 28%),
                    linear-gradient(135deg, var(--red-h), #7d0000) !important;
                box-shadow: 0 16px 34px rgba(196, 0, 0, .24), inset 0 1px 0 rgba(255, 255, 255, .16);
            }

            #content :where(.form-label) {
                color: var(--text2) !important;
                font-size: .78rem !important;
                font-weight: 850 !important;
                letter-spacing: .035em;
                text-transform: uppercase;
            }

            #content :where(.form-label.is-required)::after {
                content: ' *';
                color: var(--red-h);
                font-weight: 900;
            }

            #content :where(.form-control, .form-select, textarea) {
                min-height: 44px;
                border-radius: 11px !important;
                border: 1px solid rgba(255, 255, 255, .13) !important;
                background:
                    linear-gradient(145deg, rgba(255, 255, 255, .050), rgba(255, 255, 255, .018)),
                    rgba(5, 5, 5, .22) !important;
                color: var(--text) !important;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, .040);
            }

            #content :where(textarea.form-control) {
                min-height: 118px;
            }

            #content :where(.form-control:hover, .form-select:hover, textarea:hover) {
                border-color: rgba(255, 255, 255, .21) !important;
            }

            #content :where(.form-control:focus, .form-select:focus, textarea:focus) {
                border-color: rgba(224, 0, 0, .68) !important;
                box-shadow: 0 0 0 4px rgba(196, 0, 0, .15), inset 0 1px 0 rgba(255, 255, 255, .055) !important;
            }

            #content :where(.input-group-text) {
                min-height: 44px;
                border-radius: 11px !important;
                border-color: rgba(255, 255, 255, .13) !important;
                background: rgba(255, 255, 255, .055) !important;
                color: var(--text2) !important;
                font-weight: 800;
            }

            #content :where(input[type="file"].form-control) {
                padding: .45rem .65rem !important;
            }

            #content :where(input[type="file"].form-control)::file-selector-button {
                margin: -.45rem .75rem -.45rem -.65rem;
                min-height: 44px;
                border: 0;
                border-right: 1px solid rgba(255, 255, 255, .12);
                background: rgba(255, 255, 255, .92);
                color: #151515;
                font-weight: 800;
                border-radius: 10px 0 0 10px;
                padding-inline: 1rem;
            }

            #content :where(.btn) {
                min-height: 42px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: .35rem;
                border-radius: 11px !important;
                padding: .58rem .92rem;
                font-weight: 850 !important;
                letter-spacing: -.01em;
            }

            #content :where(.btn-sm) {
                min-height: 34px;
                border-radius: 9px !important;
                padding: .38rem .68rem;
                font-size: .82rem;
            }

            #content :where(.btn-primary, .btn-danger) {
                border-color: rgba(255, 255, 255, .08) !important;
                background:
                    radial-gradient(circle at 20% 0%, rgba(255, 255, 255, .22), transparent 32%),
                    linear-gradient(135deg, #ff1919, #b40000 58%, #780000) !important;
                box-shadow: 0 16px 32px rgba(196, 0, 0, .24), inset 0 1px 0 rgba(255, 255, 255, .16) !important;
            }

            #content :where(.btn-success) {
                border-color: rgba(255, 255, 255, .08) !important;
                background:
                    radial-gradient(circle at 20% 0%, rgba(255, 255, 255, .18), transparent 32%),
                    linear-gradient(135deg, #22c55e, #16803d) !important;
                box-shadow: 0 16px 30px rgba(34, 197, 94, .16), inset 0 1px 0 rgba(255, 255, 255, .14) !important;
            }

            #content :where(.btn-outline-secondary, .btn-outline-primary, .btn-outline-danger, .btn-outline-success, .btn-outline-warning, .btn-light) {
                border-color: rgba(255, 255, 255, .13) !important;
                background: rgba(255, 255, 255, .035) !important;
                color: var(--text) !important;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, .045);
            }

            #content :where(.btn-outline-secondary:hover, .btn-outline-primary:hover, .btn-outline-danger:hover, .btn-outline-success:hover, .btn-outline-warning:hover, .btn-light:hover) {
                border-color: rgba(224, 0, 0, .42) !important;
                background: rgba(196, 0, 0, .12) !important;
                color: #fff !important;
            }

            #content :where(.badge) {
                min-height: 24px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 999px !important;
                padding: .28rem .62rem;
                border: 1px solid rgba(255, 255, 255, .10);
                font-weight: 850;
                letter-spacing: -.01em;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, .06);
            }

            #content :where(.table) {
                --bs-table-bg: transparent;
                --bs-table-color: var(--text);
                --bs-table-border-color: rgba(255, 255, 255, .075);
                color: var(--text) !important;
                margin-bottom: 0;
            }

            #content :where(.table thead, .table-light) {
                background:
                    linear-gradient(90deg, rgba(255, 255, 255, .060), rgba(255, 255, 255, .020)) !important;
                color: var(--text2) !important;
            }

            #content :where(.table th) {
                padding: .9rem 1rem !important;
                color: var(--text3) !important;
                font-size: .72rem;
                font-weight: 900;
                letter-spacing: .08em;
                text-transform: uppercase;
                border-bottom-color: rgba(255, 255, 255, .10) !important;
            }

            #content :where(.table td) {
                padding: .92rem 1rem !important;
                vertical-align: middle;
                border-color: rgba(255, 255, 255, .070) !important;
            }

            #content :where(.table-hover tbody tr:hover, table tbody tr:hover) {
                background: rgba(196, 0, 0, .075) !important;
            }

            #content :where(.alert) {
                padding: 1rem 1.1rem !important;
                border-left: 4px solid var(--red-h) !important;
            }

            #content :where(.alert-success) {
                border-left-color: #22c55e !important;
            }

            #content :where(.alert-warning) {
                border-left-color: #f59e0b !important;
            }

            :root[data-theme="light"] #content {
                background:
                    radial-gradient(circle at 82% 0%, rgba(176, 0, 0, .08), transparent 28%),
                    radial-gradient(circle at 10% 6%, rgba(255, 255, 255, .70), transparent 24%);
            }

            :root[data-theme="light"] #content :where(.card, .alert, .info-block, .notification-card, .profile-card, .report-card, .metric-card, .os-index-card, .dashboard-card, .parts-catalog-filter, .parts-xray-card) {
                border-color: rgba(31, 25, 20, .13) !important;
                background:
                    linear-gradient(135deg, rgba(255, 255, 255, .86), rgba(255, 255, 255, .55) 50%, rgba(176, 0, 0, .030)),
                    var(--surface) !important;
                box-shadow:
                    0 18px 46px rgba(70, 35, 15, .12),
                    inset 0 1px 0 rgba(255, 255, 255, .82) !important;
            }

            :root[data-theme="light"] #content :where(.card-header, .modal-header, .offcanvas-header, .parts-catalog-filter-head, .parts-xray-head) {
                border-bottom-color: rgba(31, 25, 20, .10) !important;
                background:
                    linear-gradient(90deg, rgba(176, 0, 0, .075), transparent 46%),
                    rgba(255, 255, 255, .46) !important;
            }

            :root[data-theme="light"] #content :where(.form-control, .form-select, textarea) {
                border-color: rgba(31, 25, 20, .16) !important;
                background: rgba(255, 255, 255, .76) !important;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, .85);
            }

            :root[data-theme="light"] #content :where(.card-body .card, .card-body .alert, .card-body fieldset, .card-body .border, .card-body [class*="p-"].border, .payment-card, .parts-region-active, .parts-region-chip, .parts-car-stage) {
                border-color: rgba(31, 25, 20, .13) !important;
                background: rgba(255, 255, 255, .48) !important;
            }

            :root[data-theme="light"] #content :where(.btn-outline-secondary, .btn-outline-primary, .btn-outline-danger, .btn-outline-success, .btn-outline-warning, .btn-light) {
                border-color: rgba(31, 25, 20, .16) !important;
                background: rgba(255, 255, 255, .62) !important;
                color: var(--text) !important;
            }

            :root[data-theme="light"] #content :where(.table th, .table td) {
                border-color: rgba(31, 25, 20, .085) !important;
            }

            .page-loader {
                position: fixed;
                inset: 0;
                z-index: 99999;
                display: grid;
                place-items: center;
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
                background:
                    radial-gradient(circle at 50% 38%, rgba(196, 0, 0, .18), transparent 28%),
                    rgba(3, 3, 3, .76);
                backdrop-filter: blur(12px);
                transition: opacity .18s ease, visibility .18s ease;
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
                border: 1px solid rgba(255, 255, 255, .14);
                border-radius: 12px;
                background: linear-gradient(145deg, rgba(24, 24, 24, .92), rgba(12, 12, 12, .92));
                box-shadow: 0 28px 80px rgba(0, 0, 0, .42);
                color: #fff;
                text-align: center;
            }

            .page-loader-mark {
                width: 54px;
                height: 54px;
                position: relative;
                overflow: hidden;
                display: grid;
                place-items: center;
                border-radius: 16px;
                background:
                    radial-gradient(circle at 22% 16%, rgba(255,255,255,.32), transparent 34%),
                    linear-gradient(135deg, #ff2424 0%, var(--red) 48%, #650000 100%);
                box-shadow: 0 18px 42px rgba(196, 0, 0, .34), inset 0 1px 0 rgba(255,255,255,.20);
                animation: loaderPulse 1.1s ease-in-out infinite;
            }

            .page-loader-mark::before {
                content: 'AT';
                position: relative;
                z-index: 1;
                font-family: 'Syne', sans-serif;
                font-size: 18px;
                font-weight: 900;
                letter-spacing: -.15em;
                color: #fff;
                transform: skewX(-8deg);
                text-shadow: 0 2px 10px rgba(0,0,0,.24);
            }

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

            .page-loader-mark i { display: none; }

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
                color: rgba(255, 255, 255, .68);
                font-size: .82rem;
            }

            .page-loader-bar {
                width: 180px;
                height: 4px;
                overflow: hidden;
                border-radius: 999px;
                background: rgba(255, 255, 255, .10);
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
                    radial-gradient(circle at 50% 38%, rgba(196, 0, 0, .14), transparent 28%),
                    rgba(248, 245, 239, .78);
            }

            :root[data-theme="light"] .page-loader-card {
                background: linear-gradient(145deg, rgba(255, 250, 245, .94), rgba(246, 240, 232, .94));
                border-color: rgba(31, 25, 20, .14);
                color: #17120f;
                box-shadow: 0 28px 80px rgba(70, 35, 15, .20);
            }

            :root[data-theme="light"] .page-loader-text span {
                color: rgba(31, 25, 20, .62);
            }

            :root[data-theme="light"] .page-loader-bar {
                background: rgba(31, 25, 20, .10);
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

            #content :where(.btn, button, .btn-primary, .btn-outline-primary, .btn-outline-secondary, .btn-outline-danger, .btn-outline-success),
            #topbar :where(.topbar-btn, .btn-logout, .topbar-user),
            #sidebar .nav-link {
                position: relative;
                overflow: hidden;
                transform: translateZ(0);
                transition:
                    transform .18s ease,
                    box-shadow .18s ease,
                    background .18s ease,
                    border-color .18s ease,
                    color .18s ease,
                    opacity .18s ease !important;
            }

            #content :where(.btn, button, .btn-primary, .btn-outline-primary, .btn-outline-secondary, .btn-outline-danger, .btn-outline-success):hover,
            #topbar :where(.topbar-btn, .btn-logout, .topbar-user):hover,
            #sidebar .nav-link:hover {
                transform: translateY(-1px);
            }

            #content :where(.btn, button, .btn-primary, .btn-outline-primary, .btn-outline-secondary, .btn-outline-danger, .btn-outline-success):active,
            #topbar :where(.topbar-btn, .btn-logout, .topbar-user):active,
            #sidebar .nav-link:active {
                transform: translateY(0) scale(.98);
            }

            #content :where(.card, .profile-card, .notification-card, .info-block, .report-card, .metric-card, .os-index-card, .dashboard-card) {
                transition:
                    transform .22s ease,
                    box-shadow .22s ease,
                    border-color .22s ease,
                    background .22s ease !important;
            }

            #content :where(.card, .profile-card, .notification-card, .info-block, .report-card, .metric-card, .os-index-card, .dashboard-card):hover {
                transform: translateY(-2px);
                border-color: rgba(196, 0, 0, .24) !important;
                box-shadow: 0 22px 56px rgba(0, 0, 0, .26) !important;
            }

            #content .table-hover tbody tr,
            #content table tbody tr {
                transition: background .18s ease, transform .18s ease, box-shadow .18s ease;
            }

            #content .table-hover tbody tr:hover,
            #content table tbody tr:hover {
                transform: translateX(2px);
            }

            .ui-ripple {
                position: absolute;
                z-index: 0;
                border-radius: 999px;
                pointer-events: none;
                transform: scale(0);
                opacity: .44;
                background: rgba(255, 255, 255, .45);
                animation: uiRipple .62s ease-out forwards;
            }

            #content .btn > :not(.ui-ripple),
            #topbar .topbar-btn > :not(.ui-ripple),
            #topbar .btn-logout > :not(.ui-ripple),
            #sidebar .nav-link > :not(.ui-ripple) {
                position: relative;
                z-index: 1;
            }

            @keyframes uiRipple {
                to {
                    transform: scale(18);
                    opacity: 0;
                }
            }

            @media (prefers-reduced-motion: reduce) {
                #content *,
                #topbar *,
                #sidebar * {
                    animation-duration: .001ms !important;
                    transition-duration: .001ms !important;
                    scroll-behavior: auto !important;
                }

                #content :where(.btn, button, .card, .profile-card, .notification-card, .info-block, .report-card, .metric-card, .os-index-card, .dashboard-card):hover,
                #topbar :where(.topbar-btn, .btn-logout, .topbar-user):hover,
                #sidebar .nav-link:hover,
                #content table tbody tr:hover {
                    transform: none !important;
                }

                .ui-ripple {
                    display: none !important;
                }

                .page-loader,
                .page-loader-mark,
                .page-loader-mark i,
                .page-loader-bar span {
                    animation: none !important;
                    transition: none !important;
                }
            }
        </style>

	    @stack('styles')
        <link href="/css/mobile-sidebar-fix.css?v=7" rel="stylesheet">
        <script src="/js/mobile-sidebar-fix.js?v=5" defer></script>
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

<div id="sidebar-overlay"></div>

<nav id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand sidebar-brand-logo" aria-label="Ir para a página inicial">
        <div class="brand-icon-wrap">
            <i class="bi bi-gear-wide-connected"></i>
        </div>
        <div class="sidebar-brand-text">
            <span class="brand-name">AutoTech</span>
            <span class="brand-sub">Oficina Pro</span>
        </div>
    </a>

    <div class="nav-scroll">
        <div class="nav-label">Principal</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i> <span>Início</span>
        </a>

        <div class="nav-label">Cadastros</div>

        @if(auth()->user()->role === 'cliente')
        <a href="{{ route('conta.veiculos') }}" class="nav-link {{ request()->routeIs('conta.veiculos') ? 'active' : '' }}">
            <i class="bi bi-car-front"></i> <span>Veículos</span>
        </a>
        @else
        <a href="{{ route('veiculos.index') }}" class="nav-link {{ request()->routeIs('veiculos.*') ? 'active' : '' }}">
            <i class="bi bi-car-front"></i> <span>Veículos</span>
        </a>
        @endif
        @if(auth()->user()->isGerente() || auth()->user()->isAtendente())
        <a href="{{ route('mecanicos.index') }}" class="nav-link {{ request()->routeIs('mecanicos.*') ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i> <span>Mecânicos</span>
        </a>
        @endif
        @if(auth()->user()->isGerente() || auth()->user()->isAtendente())
            <a href="{{ route('conta.usuarios') }}" class="nav-link {{ request()->routeIs('conta.usuarios') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i> <span>Contas</span>
            </a>
        @endif

        <div class="nav-label">Oficina</div>
        <a href="{{ route('os.index') }}" class="nav-link {{ request()->routeIs('os.index', 'os.show', 'os.edit') ? 'active' : '' }}">
            <i class="bi bi-clipboard2-check"></i> <span>Ordens de Serviço</span>
        </a>
        @if(auth()->user()->isGerente() || auth()->user()->isAtendente())
        <a href="{{ route('os.create') }}" class="nav-link {{ request()->routeIs('os.create') ? 'active' : '' }}">
            <i class="bi bi-person-workspace"></i> <span>Atendimento presencial</span>
        </a>
        @endif
        @if(auth()->user()->isGerente() || auth()->user()->isAtendente() || auth()->user()->isCliente())
        <a href="{{ route('notificacoes.index') }}" class="nav-link {{ request()->routeIs('notificacoes.*') ? 'active' : '' }}">
            <i class="bi bi-bell"></i> <span>Notificações</span>
            @php
            $nao_lidas = \App\Models\Notificacao::where('user_id', auth()->id())
                ->where('lida', false)
                ->where('status', 'pendente')
                ->count();
            @endphp
            @if($nao_lidas > 0)
            <span class="nav-badge" style="background: #e05555;">{{ $nao_lidas }}</span>
            @endif
        </a>
        @endif

        @if(auth()->user()->isGerente() || auth()->user()->isAtendente())
        <div class="nav-label">Estoque</div>
        <a href="{{ route('servicos.index') }}" class="nav-link {{ request()->routeIs('servicos.*') ? 'active' : '' }}">
            <i class="bi bi-tools"></i> <span>Serviços</span>
        </a>
        <a href="{{ route('pecas.index') }}" class="nav-link {{ request()->routeIs('pecas.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> <span>Peças</span>
        </a>
        @endif

        @if(auth()->user()->isGerente())
        <div class="nav-label">Gestão</div>
        <a href="{{ route('relatorios.index') }}" class="nav-link {{ request()->routeIs('relatorios.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> <span>Relatórios</span>
        </a>
        @endif

        <div class="nav-label">Ferramentas</div>
        <a href="{{ route('avaliacoes.index') }}" class="nav-link {{ request()->routeIs('avaliacoes.*') ? 'active' : '' }}">
            <i class="bi bi-star"></i> <span>Avaliações</span>
        </a>
        @if(auth()->user()->isCliente())
        <a href="{{ route('localizacao') }}" class="nav-link {{ request()->routeIs('localizacao') ? 'active' : '' }}">
            <i class="bi bi-geo-alt"></i> <span>Localização</span>
        </a>
        @endif
    </div>

    <div class="sidebar-footer"></div>
</nav>

	<header id="topbar">
	    <button id="sidebar-toggle" class="sidebar-toggle no-print" type="button" aria-label="Abrir menu">
	        <i class="bi bi-list"></i>
	    </button>
	    <div class="breadcrumb-wrap">
	        <span>AutoTech</span>
        <span class="bc-sep">›</span>
        <span class="bc-current">@yield('breadcrumb', 'Dashboard')</span>
    </div>
    <div class="topbar-right">
	        <div class="profile-menu-wrap no-print">
	            <button type="button" class="topbar-user" title="Meu perfil" onclick="toggleProfileMenu()">
	                <div class="user-avatar" title="{{ auth()->user()->name }}">
	                    <div class="user-avatar-inner">
                            @php
                                $userInitials = strtoupper(substr(auth()->user()->name, 0, 1)) . strtoupper(substr(explode(' ', auth()->user()->name)[1] ?? 'X', 0, 1));
                            @endphp
                            <span class="avatar-fallback-initials">{{ $userInitials }}</span>
	                        @if(auth()->user()->profilePhotoUrl())
	                            <img src="{{ auth()->user()->profilePhotoUrl() }}" alt="{{ auth()->user()->name }}" onerror="this.style.display='none';">
	                        @endif
	                    </div>
	                    <span class="status-dot {{ auth()->user()->isOnline() ? 'online' : 'offline' }}" title="{{ auth()->user()->isOnline() ? 'Online' : 'Offline' }}"></span>
	                </div>
	                <div style="flex:1;min-width:0">
	                    <div class="user-info-name">{{ auth()->user()->name }}</div>
	                    <div style="font-size:10px;color:var(--text3);">Meu perfil</div>
	                </div>
	                <i class="bi bi-chevron-down small"></i>
	            </button>

	            <div id="profile-dropdown" class="profile-dropdown">
	                <a href="{{ route('perfil.edit') }}" class="profile-dropdown-item">
	                    <i class="bi bi-person-circle"></i>
	                    <span>Conta</span>
	                </a>
	                <form method="POST" action="{{ route('logout') }}" class="m-0">
	                    @csrf
	                    <button type="submit" class="profile-dropdown-item danger">
	                        <i class="bi bi-box-arrow-left"></i>
	                        <span>Sair</span>
	                    </button>
	                </form>
	            </div>

                @php
                    $clientePerfilIncompleto = auth()->user()->isCliente()
                        && (
                            blank(auth()->user()->cliente?->cidade)
                            || blank(auth()->user()->cliente?->estado)
                            || blank(auth()->user()->cliente?->endereco)
                        );
                @endphp

                @if($clientePerfilIncompleto && !request()->routeIs('perfil.*'))
                    <div class="profile-check-nudge">
                        <div class="nudge-row">
                            <i class="bi bi-arrow-up-right nudge-arrow"></i>
                            <div>
                                <strong>Complete seu perfil</strong>
                                <span>Revise suas informacoes para continuar.</span>
                                <a href="{{ route('perfil.edit') }}">
                                    Abrir perfil <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
	        </div>
	        <button class="topbar-btn" title="Pesquisar" onclick="openSearch()">
	            <i class="bi bi-search"></i>
	        </button>
	        <button class="topbar-btn theme-toggle" title="Alternar modo claro/escuro" type="button" onclick="toggleTheme()">
	            <i class="bi bi-sun theme-dark-icon"></i>
	            <i class="bi bi-moon-stars theme-light-icon"></i>
	        </button>
	        <button class="topbar-btn no-print" title="Notificações" type="button" onclick="toggleMiniNotifs()" data-mark-read-url="{{ route('notificacoes.marcar-todas-lidas') }}">
	            <i class="bi bi-bell"></i>
                @php
                    $topbarNaoLidasCount = \App\Models\Notificacao::where('user_id', auth()->id())
                        ->where('lida', false)
                        ->where('status', 'pendente')
                        ->count();
                @endphp
                @if($topbarNaoLidasCount > 0)
	                <span class="notif-dot"></span>
                @endif
        </button>

        <div id="mini-notificacoes" class="mini-notificacoes" style="display:none; position:absolute; top:62px; right:28px; width:360px; z-index:9500;">
            <div class="card" style="background:var(--surface); border:1px solid var(--border2); border-radius:var(--radius); overflow:hidden;">
                <div class="card-header" style="background:var(--surface); border-bottom:1px solid var(--border); border-radius:0 !important;">
                    <i class="bi bi-bell-fill me-2 text-warning"></i> Notificações
                    <span style="margin-left:auto; font-family:var(--radius-sm);"></span>
                </div>
                <div class="card-body" style="padding:.6rem .8rem;">
                    @php
                        $nao_lidas = \App\Models\Notificacao::where('user_id', auth()->id())
                            ->where('lida', false)
                            ->where('status', 'pendente')
                            ->orderByDesc('created_at')
                            ->limit(5)
                            ->get();
                    @endphp

                    @if($nao_lidas->isEmpty())
                        <div class="text-center text-muted" style="padding:1rem 0; font-size:13px;">Nenhuma solicitação pendente.</div>
                    @else
                        <div style="display:flex; flex-direction:column; gap:8px;">
                            @foreach($nao_lidas as $n)
                                <a href="{{ route('os.show', $n->os) }}" class="text-decoration-none" style="border:1px solid var(--border2); border-radius:10px; padding:.6rem .7rem; background:rgba(255,255,255,.02);">
                                    <div style="display:flex; align-items:center; justify-content:space-between; gap:8px;">
                                        <div style="font-family: 'DM Mono', monospace; color:var(--text); font-size:12.5px;">OS {{ $n->os->numero }}</div>
                                        <span style="font-size:10px; color:var(--warning-text); background:var(--warning-bg); padding:2px 8px; border-radius:99px;">Pendente</span>
                                    </div>
                                    <div style="margin-top:4px; font-size:12.5px; color:var(--text2);">
                                        {{ $n->os->cliente->nome }} · {{ $n->os->veiculo->marca }} {{ $n->os->veiculo->modelo }}
                                    </div>


                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="card-footer" style="background:var(--surface); border-top:1px solid var(--border); display:flex; justify-content:flex-end;">
                    <a href="{{ route('notificacoes.index') }}" class="btn btn-sm btn-outline-secondary">Ver todas</a>
                </div>
            </div>
        </div>
    </div>
</header>

<main id="content">

    @if(session('success'))
    <div class="toast-container" id="toast-container">
        <div class="toast-at toast-success">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button onclick="dismissToast(this)" style="margin-left:auto;background:none;border:none;color:var(--text3);cursor:pointer;padding:0;font-size:14px">
                <i class="bi bi-x"></i>
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="toast-container" id="toast-container">
        <div class="toast-at toast-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>{{ session('error') }}</span>
            <button onclick="dismissToast(this)" style="margin-left:auto;background:none;border:none;color:var(--text3);cursor:pointer;padding:0;font-size:14px">
                <i class="bi bi-x"></i>
            </button>
        </div>
    </div>
    @endif

    @yield('content')

    @if (isset($errors) && $errors->any())
        <div class="toast-container" id="toast-container">
            <div class="toast-at toast-error">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>Erro ao salvar: {{ $errors->first() }}</span>
            </div>
        </div>
    @endif
</main>

<div id="search-modal" style="
    display:none; position:fixed; inset:0; z-index:9998;
    background:rgba(0,0,0,.7); backdrop-filter:blur(4px);
    align-items:flex-start; justify-content:center; padding-top:10vh;
" onclick="if(event.target===this)closeSearch()">
    <div style="
        background:var(--surface); border:1px solid var(--border2);
        border-radius:var(--radius); width:100%; max-width:520px;
        box-shadow:0 24px 64px rgba(0,0,0,.6); overflow:hidden;
    ">
        <div style="display:flex;align-items:center;padding:.85rem 1rem;gap:10px;border-bottom:1px solid var(--border)">
            <i class="bi bi-search" style="color:var(--text3);font-size:15px"></i>
            <input id="search-input" type="text" placeholder="Pesquisar OS, veículos…"

                   style="flex:1;background:none;border:none;outline:none;color:var(--text);font-family:'DM Sans',sans-serif;font-size:14px"
                   oninput="handleSearch(this.value)">
            <kbd style="background:var(--surface3);border:1px solid var(--border2);border-radius:4px;padding:2px 7px;font-size:10px;color:var(--text3)">ESC</kbd>
        </div>
        <div id="search-results" style="padding:.8rem 1rem;min-height:80px;max-height:300px;overflow-y:auto">
            <div style="color:var(--text3);font-size:12px;text-align:center;padding:1rem 0">
                <i class="bi bi-search" style="display:block;font-size:24px;margin-bottom:6px;opacity:.3"></i>
                Digite para buscar…
            </div>
        </div>
        <div style="padding:.5rem 1rem;border-top:1px solid var(--border);display:flex;gap:12px">
            <span style="font-size:10px;color:var(--text3)"><kbd style="background:var(--surface3);border:1px solid var(--border2);border-radius:3px;padding:1px 5px">↵</kbd> selecionar</span>
            <span style="font-size:10px;color:var(--text3)"><kbd style="background:var(--surface3);border:1px solid var(--border2);border-radius:3px;padding:1px 5px">ESC</kbd> fechar</span>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('autotech-theme', theme);
}

function toggleTheme() {
    const current = document.documentElement.getAttribute('data-theme') || 'dark';
    setTheme(current === 'dark' ? 'light' : 'dark');
}

function toggleSidebar() {
    const sb = document.getElementById('sidebar');
    const ov = document.getElementById('sidebar-overlay');
    sb.classList.toggle('open');
    ov.classList.toggle('show');
    document.body.classList.toggle('sidebar-open', sb.classList.contains('open'));
}

document.getElementById('sidebar-overlay').addEventListener('click', () => {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebar-overlay').classList.remove('show');
    document.body.classList.remove('sidebar-open');
});

document.querySelectorAll('#sidebar .nav-link').forEach((link) => {
    link.addEventListener('click', () => {
        if (window.matchMedia('(max-width: 900px)').matches) {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebar-overlay').classList.remove('show');
            document.body.classList.remove('sidebar-open');
        }
    });
});

function dismissToast(btn) {
    const toast = btn.closest('.toast-at');
    toast.style.animation = 'slideOutRight .25s forwards';
    setTimeout(() => toast.remove(), 250);
}

document.querySelectorAll('.toast-at').forEach(t => {
    setTimeout(() => {
        t.style.animation = 'slideOutRight .3s forwards';
        setTimeout(() => t.remove(), 300);
    }, 4500);
});

function openSearch() {
    const m = document.getElementById('search-modal');
    m.style.display = 'flex';
    setTimeout(() => document.getElementById('search-input').focus(), 50);
}

function closeSearch() {
    document.getElementById('search-modal').style.display = 'none';
    document.getElementById('search-input').value = '';
}

document.addEventListener('keydown', e => {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') { e.preventDefault(); openSearch(); }
    if (e.key === 'Escape') closeSearch();
});

function handleSearch(val) {
    const res = document.getElementById('search-results');

}

function toggleMiniNotifs() {
    const el = document.getElementById('mini-notificacoes');
    if (!el) return;
    const isHidden = el.style.display === 'none' || !el.style.display;
    el.style.display = isHidden ? 'block' : 'none';

    if (!isHidden) {
        markMiniNotifsViewed();
    }
}

function markMiniNotifsViewed() {
    const btn = document.querySelector('.topbar-btn.no-print[title="Notificações"]');
    const url = btn?.dataset.markReadUrl;
    const token = document.querySelector('meta[name="csrf-token"]')?.content;

    if (!btn || !url || btn.dataset.markingRead === '1') return;

    btn.dataset.markingRead = '1';
    btn.querySelector('.notif-dot')?.remove();

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token || '',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
    }).finally(() => {
        btn.dataset.markingRead = '0';
    });
}

function toggleProfileMenu() {
    const el = document.getElementById('profile-dropdown');
    if (!el) return;
    el.classList.toggle('show');
}

document.addEventListener('click', (e) => {
    const el = document.getElementById('mini-notificacoes');
    const btn = e.target.closest('.topbar-btn.no-print[title="Notificações"]');
    if (el && !btn && !el.contains(e.target)) {
        const wasOpen = el.style.display !== 'none' && !!el.style.display;
        el.style.display = 'none';
        if (wasOpen) {
            markMiniNotifsViewed();
        }
    }

    const profileMenu = document.getElementById('profile-dropdown');
    const profileButton = e.target.closest('.topbar-user');
    if (profileMenu && !profileButton && !profileMenu.contains(e.target)) {
        profileMenu.classList.remove('show');
    }
});

function handleSearch(val) {
    const res = document.getElementById('search-results');

    if (!val.trim()) {
        res.innerHTML = '<div style="color:var(--text3);font-size:12px;text-align:center;padding:1rem 0"><i class="bi bi-search" style="display:block;font-size:24px;margin-bottom:6px;opacity:.3"></i>Digite para buscar…</div>';
        return;
    }
    res.innerHTML = '<div style="color:var(--text3);font-size:12px;padding:.5rem 0"><i class="bi bi-arrow-repeat spin" style="margin-right:6px"></i>Buscando…</div>';
}

(function() {
    const cards = document.querySelectorAll('.stat-card, .card, .anim-fade-up');
    cards.forEach((el, i) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(14px)';
        el.style.transition = 'opacity .4s ease, transform .4s ease';
        el.style.transitionDelay = (i * 60) + 'ms';
        setTimeout(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, 80 + i * 60);
    });
})();

document.addEventListener('click', function (event) {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const target = event.target.closest('#content .btn, #content button, #topbar .topbar-btn, #topbar .btn-logout, #sidebar .nav-link');
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

(function () {
    document.querySelectorAll('#content :is(input, select, textarea)[required]').forEach((field) => {
        if (!field.id) return;
        const label = document.querySelector(`#content label[for="${CSS.escape(field.id)}"]`);
        label?.classList.add('is-required');
    });
})();

function animateCount(el) {
    const raw = el.textContent.trim();
    const isCurrency = raw.startsWith('R$');
    const num = parseFloat(raw.replace(/[^0-9,]/g, '').replace(',', '.')) || 0;
    if (num === 0) return;
    const duration = 800;
    const start = performance.now();
    const tick = (now) => {
        const p = Math.min((now - start) / duration, 1);
        const ease = 1 - Math.pow(1 - p, 3);
        const val = Math.round(num * ease);
        el.textContent = isCurrency
            ? 'R$ ' + val.toLocaleString('pt-BR', {minimumFractionDigits:2, maximumFractionDigits:2})
            : val.toString();
        if (p < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
}

document.querySelectorAll('.stat-value').forEach(animateCount);

const rippleStyle = document.createElement('style');
rippleStyle.textContent = `
  @keyframes spin { to { transform: rotate(360deg); } }
  .spin { animation: spin .7s linear infinite; display:inline-block; }
`;
document.head.appendChild(rippleStyle);
</script>

@stack('scripts')
</body>
</html>
