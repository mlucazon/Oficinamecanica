@extends('layouts.app')
@section('title', 'Início')
@section('breadcrumb', 'Início')

@section('content')
@php
    $user = auth()->user();
    $primeiroNome = explode(' ', trim($user->name))[0] ?? $user->name;
    $ordensAtivas = $stats['os_abertas'] + $stats['os_em_execucao'] + $stats['os_aguardando'];
    $totalOperacao = max($ordensAtivas + $stats['os_finalizadas_mes'], 1);
    $percentExecucao = min(100, round(($stats['os_em_execucao'] / $totalOperacao) * 100));
    $percentAguardando = min(100, round(($stats['os_aguardando'] / $totalOperacao) * 100));
    $heroTitulo = 'SOMOS APAIXONADOS EM SERVIR, VOCÊ';
    $heroTexto = 'Acompanhe solicitações, aprovações e o andamento dos serviços com uma visão clara, rápida e feita para o ritmo da oficina.';
@endphp

<section class="discount-home-banner">
    <div class="discount-home-content">
        <span class="discount-home-kicker">DESCONTO DE BOAS-VINDAS</span>
        <h2>Cliente novo ganha 30% OFF na primeira OS</h2>
        <p>O desconto e aplicado automaticamente no primeiro orcamento aprovado para voce cuidar do seu veiculo pagando menos.</p>
    </div>
    @if($user->isCliente())
        <a href="{{ route('os.create') }}" class="btn btn-light">
            <i class="bi bi-plus-lg me-1"></i>Usar meu desconto
        </a>
    @endif
</section>

<section class="home-hero">
    <div class="home-hero-copy">
        <span class="home-kicker">OFICINA MECÂNICA • AUTOTECH</span>
        <h1>{{ $heroTitulo }}</h1>
        <p>{{ $heroTexto }}</p>
        <div class="home-hero-actions">
            @if($user->isCliente())
                <a href="{{ route('os.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Solicitar OS
                </a>
                <a href="{{ route('conta.veiculos') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-car-front me-1"></i>Meus veículos
                </a>
            @else
                <a href="{{ route('os.index') }}" class="btn btn-primary">
                    <i class="bi bi-clipboard2-check me-1"></i>Ver ordens
                </a>
                @if($user->isGerente())
                    <a href="{{ route('relatorios.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-bar-chart-line me-1"></i>Relatórios
                    </a>
                @endif
            @endif
        </div>
    </div>

    <div class="home-hero-panel">
        <div class="home-panel-top">
            <span>Operação de hoje</span>
            <i class="bi bi-activity"></i>
        </div>
        <div class="home-main-number">{{ $ordensAtivas }}</div>
        <div class="home-main-label">ordens ativas</div>
        <div class="home-progress-list">
            <div>
                <span>Em diagnóstico/execução</span>
                <strong>{{ $stats['os_em_execucao'] }}</strong>
            </div>
            <div class="home-progress">
                <span style="width: {{ $percentExecucao }}%"></span>
            </div>
            <div>
                <span>Aguardando aprovação</span>
                <strong>{{ $stats['os_aguardando'] }}</strong>
            </div>
            <div class="home-progress home-progress-warning">
                <span style="width: {{ $percentAguardando }}%"></span>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    .discount-home-banner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.25rem;
        padding: clamp(1.1rem, 3vw, 2rem);
        border: 1px solid rgba(255, 255, 255, .16);
        border-radius: 8px;
        background:
            linear-gradient(135deg, rgba(213, 0, 0, .96), rgba(109, 0, 0, .94)),
            radial-gradient(circle at 88% 20%, rgba(255, 255, 255, .20), transparent 34%);
        box-shadow: 0 18px 40px rgba(0, 0, 0, .22);
        color: #fff;
    }

    .discount-home-kicker {
        display: block;
        margin-bottom: .35rem;
        font-size: .78rem;
        font-weight: 800;
        letter-spacing: .18em;
        text-transform: uppercase;
        opacity: .88;
    }

    .discount-home-banner h2 {
        margin: 0;
        font-size: clamp(2rem, 6vw, 4.4rem);
        line-height: .96;
        font-weight: 900;
    }

    .discount-home-banner p {
        max-width: 760px;
        margin: .7rem 0 0;
        color: rgba(255, 255, 255, .86);
        font-size: clamp(.98rem, 2vw, 1.12rem);
    }

    .discount-home-banner .btn {
        flex: 0 0 auto;
        color: #b00000;
        font-weight: 800;
        border: 0;
    }

    :root[data-theme="light"] .discount-home-banner {
        border-color: rgba(140, 0, 0, .18);
        box-shadow: 0 12px 30px rgba(120, 0, 0, .12);
    }

    @media (max-width: 700px) {
        .discount-home-banner {
            align-items: stretch;
            flex-direction: column;
        }

        .discount-home-banner .btn {
            width: 100%;
        }
    }
</style>
@endpush
