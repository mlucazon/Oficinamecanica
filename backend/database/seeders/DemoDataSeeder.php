<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\ItemOs;
use App\Models\Mecanico;
use App\Models\Notificacao;
use App\Models\OrdemServico;
use App\Models\Peca;
use App\Models\Servico;
use App\Models\User;
use App\Models\Veiculo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $atendente = User::firstOrCreate(
            ['email' => 'joao@autotech.com'],
            ['name' => 'Joao Atendente', 'password' => Hash::make('12345678'), 'role' => 'atendente']
        );

        $clientes = [
            [
                'user' => ['name' => 'Pedro Cliente', 'email' => 'cliente@autotech.com'],
                'cpf' => '000.000.000-03',
                'telefone' => '(85) 99999-0003',
                'endereco' => 'Rua das Oficinas, 120',
                'cidade' => 'Fortaleza',
                'estado' => 'CE',
                'veiculos' => [
                    ['placa' => 'ABC1231', 'marca' => 'Citroen', 'modelo' => 'C3 Picasso', 'ano' => 2014, 'cor' => 'Prata', 'km_atual' => 87400],
                    ['placa' => 'QWE4R56', 'marca' => 'Chevrolet', 'modelo' => 'Onix', 'ano' => 2020, 'cor' => 'Branco', 'km_atual' => 42800],
                ],
            ],
            [
                'user' => ['name' => 'Maria Oliveira', 'email' => 'maria@cliente.com'],
                'cpf' => '111.222.333-44',
                'telefone' => '(85) 98888-1111',
                'endereco' => 'Avenida Central, 455',
                'cidade' => 'Fortaleza',
                'estado' => 'CE',
                'veiculos' => [
                    ['placa' => 'MOL1A22', 'marca' => 'Honda', 'modelo' => 'Civic', 'ano' => 2018, 'cor' => 'Cinza', 'km_atual' => 67300],
                ],
            ],
            [
                'user' => ['name' => 'Fabio Levi', 'email' => 'fabio@cliente.com'],
                'cpf' => '222.333.444-55',
                'telefone' => '(85) 97777-2222',
                'endereco' => 'Rua Norte, 88',
                'cidade' => 'Caucaia',
                'estado' => 'CE',
                'veiculos' => [
                    ['placa' => 'FSD9H34', 'marca' => 'Chevrolet', 'modelo' => 'Onix', 'ano' => 2019, 'cor' => 'Preto', 'km_atual' => 51200],
                ],
            ],
            [
                'user' => ['name' => 'Alessandro Lima Maia', 'email' => 'alessandro@cliente.com'],
                'cpf' => '333.444.555-66',
                'telefone' => '(85) 96666-3333',
                'endereco' => 'Rua Sul, 310',
                'cidade' => 'Maracanau',
                'estado' => 'CE',
                'veiculos' => [
                    ['placa' => 'ARR1Z08', 'marca' => 'Chery', 'modelo' => 'Arrizo 8', 'ano' => 2023, 'cor' => 'Azul', 'km_atual' => 12300],
                ],
            ],
        ];

        $clientesCriados = [];
        foreach ($clientes as $dadosCliente) {
            $user = User::updateOrCreate(
                ['email' => $dadosCliente['user']['email']],
                [
                    'name' => $dadosCliente['user']['name'],
                    'password' => Hash::make('12345678'),
                    'role' => 'cliente',
                ]
            );

            $cliente = Cliente::updateOrCreate(
                ['cpf' => $dadosCliente['cpf']],
                [
                    'user_id' => $user->id,
                    'nome' => $dadosCliente['user']['name'],
                    'telefone' => $dadosCliente['telefone'],
                    'email' => $dadosCliente['user']['email'],
                    'endereco' => $dadosCliente['endereco'],
                    'cidade' => $dadosCliente['cidade'],
                    'estado' => $dadosCliente['estado'],
                ]
            );

            foreach ($dadosCliente['veiculos'] as $dadosVeiculo) {
                Veiculo::updateOrCreate(
                    ['placa' => $dadosVeiculo['placa']],
                    $dadosVeiculo + ['cliente_id' => $cliente->id]
                );
            }

            $clientesCriados[$dadosCliente['user']['email']] = $cliente->fresh('veiculos');
        }

        $mecanicos = collect([
            ['nome' => 'Carlos Mecanico', 'cpf' => '000.000.000-01', 'telefone' => '(85) 98888-0001', 'especialidade' => 'Mecanica geral'],
            ['nome' => 'Jose Mecanico', 'cpf' => '000.000.000-02', 'telefone' => '(85) 98888-0002', 'especialidade' => 'Eletrica automotiva'],
            ['nome' => 'Ana Paula Mecanica', 'cpf' => '000.000.000-04', 'telefone' => '(85) 98888-0004', 'especialidade' => 'Suspensao e freios'],
            ['nome' => 'Rafael Costa', 'cpf' => '000.000.000-05', 'telefone' => '(85) 98888-0005', 'especialidade' => 'Motor e injecao'],
        ])->map(fn ($m) => Mecanico::updateOrCreate(['cpf' => $m['cpf']], $m + ['ativo' => true]));

        $servicos = Servico::orderBy('id')->get();
        $pecas = Peca::orderBy('id')->get();

        $ordens = [
            ['numero' => 'OS-20260502-0001', 'cliente' => 'cliente@autotech.com', 'veiculo' => 'ABC1231', 'mecanico' => 0, 'status' => 'finalizada', 'sintomas' => 'Barulho ao frear e pedal vibrando.', 'diagnostico' => 'Pastilhas gastas e discos com desgaste.', 'km' => 86120, 'aprovado' => true, 'aprovacao' => '2026-05-02 11:10:00', 'conclusao' => '2026-05-03 15:20:00'],
            ['numero' => 'OS-20260505-0002', 'cliente' => 'maria@cliente.com', 'veiculo' => 'MOL1A22', 'mecanico' => 1, 'status' => 'finalizada', 'sintomas' => 'Luz da injecao acesa.', 'diagnostico' => 'Sensor e limpeza de corpo de borboleta.', 'km' => 66400, 'aprovado' => true, 'aprovacao' => '2026-05-05 10:30:00', 'conclusao' => '2026-05-06 12:40:00'],
            ['numero' => 'OS-20260510-0003', 'cliente' => 'fabio@cliente.com', 'veiculo' => 'FSD9H34', 'mecanico' => 2, 'status' => 'finalizada', 'sintomas' => 'Revisao de 50 mil km.', 'diagnostico' => 'Troca de oleo, filtros e revisao preventiva.', 'km' => 50100, 'aprovado' => true, 'aprovacao' => '2026-05-10 09:25:00', 'conclusao' => '2026-05-10 17:50:00'],
            ['numero' => 'OS-20260516-0004', 'cliente' => 'alessandro@cliente.com', 'veiculo' => 'ARR1Z08', 'mecanico' => 3, 'status' => 'finalizada', 'sintomas' => 'Ar-condicionado com cheiro forte.', 'diagnostico' => 'Higienizacao e troca de filtro.', 'km' => 11980, 'aprovado' => true, 'aprovacao' => '2026-05-16 14:00:00', 'conclusao' => '2026-05-17 09:30:00'],
            ['numero' => 'OS-20260601-0001', 'cliente' => 'cliente@autotech.com', 'veiculo' => 'QWE4R56', 'mecanico' => 0, 'status' => 'em_diagnostico', 'sintomas' => 'Motor falhando em marcha lenta.', 'diagnostico' => 'Teste de ignicao e alimentacao em andamento.', 'km' => 42800, 'aprovado' => false],
            ['numero' => 'OS-20260603-0002', 'cliente' => 'maria@cliente.com', 'veiculo' => 'MOL1A22', 'mecanico' => 1, 'status' => 'aguardando_aprovacao', 'sintomas' => 'Volante puxando para direita.', 'diagnostico' => 'Necessario alinhamento, balanceamento e revisao de pneus.', 'km' => 67300, 'aprovado' => false],
            ['numero' => 'OS-20260604-0003', 'cliente' => 'fabio@cliente.com', 'veiculo' => 'FSD9H34', 'mecanico' => 2, 'status' => 'em_execucao', 'sintomas' => 'Suspensao batendo em rua irregular.', 'diagnostico' => 'Amortecedor dianteiro com folga.', 'km' => 51200, 'aprovado' => true, 'aprovacao' => '2026-06-04 13:10:00'],
            ['numero' => 'OS-20260605-0004', 'cliente' => 'alessandro@cliente.com', 'veiculo' => 'ARR1Z08', 'mecanico' => 3, 'status' => 'aguardando_finalizacao', 'sintomas' => 'Cliente solicitou revisao preventiva.', 'diagnostico' => 'Revisao concluida, aguardando fechamento.', 'km' => 12300, 'aprovado' => true, 'aprovacao' => '2026-06-05 10:10:00'],
            ['numero' => 'OS-20260607-0005', 'cliente' => 'cliente@autotech.com', 'veiculo' => 'ABC1231', 'mecanico' => null, 'status' => 'aguardando_aceitacao', 'sintomas' => 'Cliente relata vazamento de oleo.', 'diagnostico' => null, 'km' => 87400, 'aprovado' => false],
        ];

        foreach ($ordens as $index => $dadosOs) {
            $cliente = $clientesCriados[$dadosOs['cliente']];
            $veiculo = Veiculo::where('placa', $dadosOs['veiculo'])->first();
            $mecanico = is_null($dadosOs['mecanico']) ? null : $mecanicos[$dadosOs['mecanico']] ?? null;

            $os = OrdemServico::updateOrCreate(
                ['numero' => $dadosOs['numero']],
                [
                    'cliente_id' => $cliente->id,
                    'veiculo_id' => $veiculo->id,
                    'mecanico_id' => $mecanico?->id,
                    'status' => $dadosOs['status'],
                    'sintomas' => $dadosOs['sintomas'],
                    'diagnostico' => $dadosOs['diagnostico'],
                    'observacoes' => 'Dados de demonstracao para testes do sistema.',
                    'km_entrada' => $dadosOs['km'],
                    'aprovado_cliente' => $dadosOs['aprovado'],
                    'data_aprovacao' => $dadosOs['aprovacao'] ?? null,
                    'data_conclusao' => $dadosOs['conclusao'] ?? null,
                    'data_previsao' => now()->addDays(3)->toDateString(),
                ]
            );

            $servico = $servicos[$index % max($servicos->count(), 1)];
            ItemOs::updateOrCreate(
                ['os_id' => $os->id, 'tipo' => 'servico', 'descricao' => $servico->nome],
                [
                    'servico_id' => $servico->id,
                    'peca_id' => null,
                    'quantidade' => 1,
                    'valor_unitario' => $servico->valor_mao_obra,
                ]
            );

            if ($index % 2 === 0 && $pecas->count()) {
                $peca = $pecas[$index % $pecas->count()];
                ItemOs::updateOrCreate(
                    ['os_id' => $os->id, 'tipo' => 'peca', 'descricao' => $peca->nome],
                    [
                        'servico_id' => null,
                        'peca_id' => $peca->id,
                        'quantidade' => $index % 3 + 1,
                        'valor_unitario' => $peca->preco_venda,
                    ]
                );
            }

            $os->recalcularTotais();
        }

        $solicitacao = OrdemServico::where('numero', 'OS-20260607-0005')->first();
        if ($solicitacao) {
            Notificacao::updateOrCreate(
                ['user_id' => $atendente->id, 'os_id' => $solicitacao->id, 'tipo' => 'solicitacao_os'],
                [
                    'status' => 'pendente',
                    'lida' => false,
                    'mensagem' => 'Nova OS ' . $solicitacao->numero . ' aguardando avaliacao do atendimento.',
                ]
            );
        }
    }
}
