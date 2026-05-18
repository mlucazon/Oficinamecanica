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
