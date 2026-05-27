<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AutoTech Pro') — AutoTech</title>

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
            --text2:        #BBBBBB;
            --text3:        #888888;
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
            width: 36px; height: 36px;
            background: var(--red);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }

        .brand-icon-wrap::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.15) 0%, transparent 60%);
        }

        .brand-icon-wrap i { font-size: 17px; color: #fff; position: relative; z-index: 1; }

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

	    <link href="{{ asset('css/app.css') }}?v=25" rel="stylesheet">

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
	            background: linear-gradient(180deg, #050914, #0A0D16) !important;
	            border-right-color: rgba(255,255,255,.10) !important;
	        }

	        :root[data-theme="light"] #sidebar .brand-name,
	        :root[data-theme="light"] #sidebar .brand-sub,
	        :root[data-theme="light"] #sidebar .nav-label,
	        :root[data-theme="light"] #sidebar .nav-link,
	        :root[data-theme="light"] #sidebar .nav-link span {
	            color: rgba(255,255,255,.72) !important;
	        }

	        :root[data-theme="light"] #sidebar .nav-label {
	            color: rgba(255,255,255,.54) !important;
	        }

	        :root[data-theme="light"] #sidebar .nav-link i {
	            color: rgba(255,255,255,.58) !important;
	        }

	        :root[data-theme="light"] #sidebar .nav-link:hover {
	            background: rgba(255,255,255,.08) !important;
	            color: #fff !important;
	        }

	        :root[data-theme="light"] #sidebar .nav-link:hover i,
	        :root[data-theme="light"] #sidebar .nav-link:hover span {
	            color: #fff !important;
	        }

	        :root[data-theme="light"] #sidebar .nav-link.active {
	            background: rgba(176,0,0,.22) !important;
	            border-left-color: var(--red-h) !important;
	        }

	        :root[data-theme="light"] #sidebar .nav-link.active,
	        :root[data-theme="light"] #sidebar .nav-link.active i,
	        :root[data-theme="light"] #sidebar .nav-link.active span {
	            color: #fff !important;
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
                    width: min(82vw, 300px) !important;
                    max-width: calc(100vw - 48px) !important;
                    height: 100dvh !important;
                    min-height: 100dvh !important;
                    padding: 0 !important;
                    transform: translate3d(-105%, 0, 0) !important;
                    overflow: hidden !important;
                    box-sizing: border-box !important;
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
                    height: 62px !important;
                    min-height: 62px !important;
                    padding: 0 14px !important;
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
                    padding: 10px !important;
                    overflow-y: auto !important;
                    overflow-x: hidden !important;
                    -webkit-overflow-scrolling: touch !important;
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
                    min-height: 42px !important;
                    margin: 2px 0 !important;
                    padding: 9px 12px !important;
                    border-radius: 9px !important;
                    box-sizing: border-box !important;
                    overflow: hidden !important;
                }

                html body #sidebar .nav-link i {
                    width: 22px !important;
                    min-width: 22px !important;
                    font-size: 18px !important;
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
                    font-size: 14px !important;
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
                    margin: 8px 0 3px !important;
                    padding: 0 12px !important;
                    opacity: 1 !important;
                    overflow: hidden !important;
                    text-overflow: ellipsis !important;
                    white-space: nowrap !important;
                    transform: none !important;
                    font-size: 10px !important;
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
	    </style>

	    @stack('styles')
        @verbatim
        <style id="mobile-sidebar-final-fix">
            @media (max-width: 900px) {
                body #sidebar,
                body #sidebar:hover,
                body #sidebar:focus-within {
                    position: fixed !important;
                    inset: 0 auto 0 0 !important;
                    width: 300px !important;
                    max-width: 84vw !important;
                    min-width: 0 !important;
                    height: 100dvh !important;
                    min-height: 100dvh !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    display: flex !important;
                    flex-direction: column !important;
                    transform: translate3d(-105%, 0, 0) !important;
                    overflow: hidden !important;
                    box-sizing: border-box !important;
                    z-index: 1200 !important;
                }

                body #sidebar.open,
                body #sidebar.open:hover,
                body #sidebar.open:focus-within {
                    transform: translate3d(0, 0, 0) !important;
                }

                body #sidebar .sidebar-brand,
                body #sidebar .sidebar-brand.sidebar-brand-logo {
                    width: 100% !important;
                    min-width: 0 !important;
                    max-width: 100% !important;
                    height: 62px !important;
                    min-height: 62px !important;
                    padding: 0 12px !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: flex-start !important;
                    gap: 10px !important;
                    overflow: hidden !important;
                    transform: none !important;
                }

                body #sidebar .nav-scroll {
                    width: 100% !important;
                    min-width: 0 !important;
                    display: flex !important;
                    flex: 1 1 auto !important;
                    min-height: 0 !important;
                    flex-direction: column !important;
                    align-items: stretch !important;
                    padding: 10px !important;
                    overflow-y: auto !important;
                    overflow-x: hidden !important;
                }

                body #sidebar .nav-link,
                body #sidebar:hover .nav-link,
                body #sidebar:focus-within .nav-link,
                body #sidebar:not(:hover):not(:focus-within) .nav-link {
                    width: 100% !important;
                    min-width: 0 !important;
                    max-width: 100% !important;
                    height: auto !important;
                    min-height: 42px !important;
                    margin: 2px 0 !important;
                    padding: 9px 10px !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: flex-start !important;
                    gap: 10px !important;
                    overflow: hidden !important;
                    box-sizing: border-box !important;
                }

                body #sidebar .nav-link i {
                    width: 22px !important;
                    min-width: 22px !important;
                    flex: 0 0 22px !important;
                }

                body #sidebar .nav-link span,
                body #sidebar .nav-label,
                body #sidebar .brand-name,
                body #sidebar .brand-sub,
                body #sidebar .sidebar-brand-text {
                    opacity: 1 !important;
                    transform: none !important;
                    pointer-events: auto !important;
                }

                body #sidebar .nav-link span {
                    display: block !important;
                    flex: 1 1 auto !important;
                    min-width: 0 !important;
                    max-width: 100% !important;
                    overflow: hidden !important;
                    text-overflow: ellipsis !important;
                    white-space: nowrap !important;
                    font-size: 14px !important;
                    letter-spacing: 0 !important;
                }

                body #sidebar .nav-label {
                    display: block !important;
                    margin: 9px 0 4px !important;
                    padding: 0 10px !important;
                    overflow: hidden !important;
                    text-overflow: ellipsis !important;
                    white-space: nowrap !important;
                    font-size: 10px !important;
                    letter-spacing: .12em !important;
                }
            }
        </style>
        @endverbatim
</head>
<body>

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
        @elseif(in_array(auth()->user()->role, ['gerente', 'atendente', 'mecanico'], true))
        <a href="{{ route('conta.clientes') }}" class="nav-link {{ request()->routeIs('conta.clientes') ? 'active' : '' }}">
            <i class="bi bi-people"></i> <span>Clientes</span>
        </a>
        @else
        <a href="{{ route('veiculos.index') }}" class="nav-link {{ request()->routeIs('veiculos.*') ? 'active' : '' }}">
            <i class="bi bi-car-front"></i> <span>Veículos</span>
        </a>
        @endif
        @if(auth()->user()->isGerente() || auth()->user()->isAtendente() || auth()->user()->isMecanico())
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
        <a href="{{ route('os.index') }}" class="nav-link {{ request()->routeIs('os.*') ? 'active' : '' }}">
            <i class="bi bi-clipboard2-check"></i> <span>Ordens de Serviço</span>
        </a>
        <a href="{{ route('garantias.index') }}" class="nav-link {{ request()->routeIs('garantias.*') ? 'active' : '' }}">
            <i class="bi bi-shield-check"></i> <span>Garantias</span>
        </a>

        @if(auth()->user()->isGerente() || auth()->user()->isAtendente() || auth()->user()->isMecanico() || auth()->user()->isCliente())
        <a href="{{ route('notificacoes.index') }}" class="nav-link {{ request()->routeIs('notificacoes.*') ? 'active' : '' }}">
            <i class="bi bi-bell"></i> <span>Notificações</span>
            @php
            $nao_lidas = \App\Models\Notificacao::where('user_id', auth()->id())
                ->where('lida', false)
                ->where('status', 'pendente')
                ->count();
            if (auth()->user()->isMecanico()) {
                $nao_lidas += \App\Models\Peca::whereColumn('estoque', '<=', 'estoque_minimo')->count();
            }
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
	    <button class="sidebar-toggle no-print" type="button" onclick="toggleSidebar()" aria-label="Abrir menu">
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
	        </div>
	        <button class="topbar-btn" title="Pesquisar" onclick="openSearch()">
	            <i class="bi bi-search"></i>
	        </button>
	        <button class="topbar-btn theme-toggle" title="Alternar modo claro/escuro" type="button" onclick="toggleTheme()">
	            <i class="bi bi-sun theme-dark-icon"></i>
	            <i class="bi bi-moon-stars theme-light-icon"></i>
	        </button>
	        <button class="topbar-btn no-print" title="Notificações" type="button" onclick="toggleMiniNotifs()">
	            <i class="bi bi-bell"></i>
	            <span class="notif-dot"></span>
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

    @if ($errors && $errors->any())
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

function normalizeMobileSidebar() {
    const sb = document.getElementById('sidebar');
    if (!sb) return;

    const isMobile = window.matchMedia('(max-width: 900px)').matches;
    const navScroll = sb.querySelector('.nav-scroll');
    const brand = sb.querySelector('.sidebar-brand');

    if (!isMobile) {
        [sb, navScroll, brand, ...sb.querySelectorAll('.nav-link, .nav-link span, .nav-link i, .nav-label, .sidebar-brand-text, .brand-name, .brand-sub')]
            .filter(Boolean)
            .forEach((el) => el.removeAttribute('style'));
        return;
    }

    Object.assign(sb.style, {
        position: 'fixed',
        top: '0',
        left: '0',
        right: 'auto',
        width: 'min(300px, 84vw)',
        maxWidth: '84vw',
        height: '100dvh',
        minHeight: '100dvh',
        margin: '0',
        padding: '0',
        display: 'flex',
        flexDirection: 'column',
        overflow: 'hidden',
        boxSizing: 'border-box',
        zIndex: '1200',
        transform: sb.classList.contains('open') ? 'translate3d(0, 0, 0)' : 'translate3d(-105%, 0, 0)'
    });

    if (brand) {
        Object.assign(brand.style, {
            width: '100%',
            maxWidth: '100%',
            height: '62px',
            minHeight: '62px',
            padding: '0 12px',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'flex-start',
            gap: '10px',
            overflow: 'hidden',
            transform: 'none'
        });
    }

    if (navScroll) {
        Object.assign(navScroll.style, {
            width: '100%',
            display: 'flex',
            flex: '1 1 auto',
            minHeight: '0',
            flexDirection: 'column',
            alignItems: 'stretch',
            padding: '10px',
            overflowY: 'auto',
            overflowX: 'hidden'
        });
    }

    sb.querySelectorAll('.nav-link').forEach((link) => {
        Object.assign(link.style, {
            width: '100%',
            maxWidth: '100%',
            minHeight: '42px',
            height: 'auto',
            margin: '2px 0',
            padding: '9px 10px',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'flex-start',
            gap: '10px',
            overflow: 'hidden',
            boxSizing: 'border-box'
        });
    });

    sb.querySelectorAll('.nav-link i').forEach((icon) => {
        Object.assign(icon.style, {
            width: '22px',
            minWidth: '22px',
            flex: '0 0 22px',
            textAlign: 'center'
        });
    });

    sb.querySelectorAll('.nav-link span').forEach((span) => {
        Object.assign(span.style, {
            display: 'block',
            flex: '1 1 auto',
            minWidth: '0',
            maxWidth: '100%',
            opacity: '1',
            overflow: 'hidden',
            textOverflow: 'ellipsis',
            whiteSpace: 'nowrap',
            transform: 'none',
            pointerEvents: 'auto',
            fontSize: '14px',
            letterSpacing: '0'
        });
    });

    sb.querySelectorAll('.nav-label').forEach((label) => {
        Object.assign(label.style, {
            display: 'block',
            margin: '9px 0 4px',
            padding: '0 10px',
            opacity: '1',
            overflow: 'hidden',
            textOverflow: 'ellipsis',
            whiteSpace: 'nowrap',
            transform: 'none',
            fontSize: '10px',
            letterSpacing: '.12em'
        });
    });
}

function toggleSidebar() {
    const sb = document.getElementById('sidebar');
    const ov = document.getElementById('sidebar-overlay');
    sb.classList.toggle('open');
    ov.classList.toggle('show');
    document.body.classList.toggle('sidebar-open', sb.classList.contains('open'));
    normalizeMobileSidebar();
}

document.getElementById('sidebar-overlay').addEventListener('click', () => {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebar-overlay').classList.remove('show');
    document.body.classList.remove('sidebar-open');
    normalizeMobileSidebar();
});

document.querySelectorAll('#sidebar .nav-link').forEach((link) => {
    link.addEventListener('click', () => {
        if (window.matchMedia('(max-width: 900px)').matches) {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebar-overlay').classList.remove('show');
            document.body.classList.remove('sidebar-open');
            normalizeMobileSidebar();
        }
    });
});

window.addEventListener('resize', normalizeMobileSidebar);
document.addEventListener('DOMContentLoaded', normalizeMobileSidebar);
normalizeMobileSidebar();

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
        el.style.display = 'none';
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

document.querySelectorAll('.btn-nova-os, .btn-primary, .btn-outline-primary').forEach(btn => {
    btn.style.position = 'relative';
    btn.style.overflow = 'hidden';
    btn.addEventListener('click', function(e) {
        const rect = this.getBoundingClientRect();
        const r = document.createElement('span');
        r.style.cssText = `
            position:absolute;border-radius:50%;
            width:6px;height:6px;
            background:rgba(255,255,255,.35);
            top:${e.clientY - rect.top - 3}px;
            left:${e.clientX - rect.left - 3}px;
            transform:scale(0);
            animation:ripple .5s linear;
            pointer-events:none;
        `;
        this.appendChild(r);
        setTimeout(() => r.remove(), 500);
    });
});

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
  @keyframes ripple { to { transform: scale(30); opacity: 0; } }
  @keyframes spin { to { transform: rotate(360deg); } }
  .spin { animation: spin .7s linear infinite; display:inline-block; }
`;
document.head.appendChild(rippleStyle);
</script>

@stack('scripts')
</body>
</html>
