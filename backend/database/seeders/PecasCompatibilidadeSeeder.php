<?php

namespace Database\Seeders;

use App\Models\MarcasVeiculos;
use App\Models\ModelosVeiculos;
use App\Models\Peca;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PecasCompatibilidadeSeeder extends Seeder
{
    public function run(): void
    {
        $catalogo = [
            'Fiat' => ['Uno', 'Strada', 'Toro', 'Argo', 'Cronos', 'Mobi', 'Palio'],
            'Volkswagen' => ['Gol', 'Polo', 'Virtus', 'Nivus', 'T-Cross', 'Fox', 'Saveiro'],
            'Chevrolet' => ['Onix', 'Tracker', 'S10', 'Cruze', 'Spin', 'Prisma'],
            'Honda' => ['Civic', 'City', 'HR-V', 'Fit', 'WR-V'],
            'Ford' => ['Ka', 'EcoSport', 'Ranger', 'Fusion', 'Focus', 'Fiesta'],
            'Toyota' => ['Corolla', 'Yaris', 'Hilux', 'SW4', 'Etios', 'Corolla Cross'],
            'Renault' => ['Kwid', 'Sandero', 'Logan', 'Duster', 'Captur', 'Oroch'],
            'Nissan' => ['Versa', 'Kicks', 'March', 'Sentra', 'Frontier'],
            'Hyundai' => ['HB20', 'HB20S', 'Creta', 'Tucson', 'i30'],
            'Citroen' => ['C3', 'C3 Picasso', 'C4 Cactus', 'Aircross', 'C4 Lounge'],
            'Peugeot' => ['208', '2008', '308', '3008', 'Partner'],
            'Chery' => ['Arrizo 5', 'Arrizo 6', 'Arrizo 8', 'Tiggo 5X', 'Tiggo 7'],
            'Audi' => ['A3', 'A4', 'Q3', 'Q5', 'Q7'],
            'BMW' => ['320i', '330i', 'X1', 'X3', 'X5'],
        ];

        $familias = [
            ['nome' => 'Filtro de oleo', 'codigo' => 'FO', 'fabricante' => 'Mann', 'custo' => 22, 'venda' => 48, 'min' => 8, 'estoque' => 30],
            ['nome' => 'Filtro de ar do motor', 'codigo' => 'FA', 'fabricante' => 'Tecfil', 'custo' => 24, 'venda' => 52, 'min' => 8, 'estoque' => 28],
            ['nome' => 'Filtro de combustivel', 'codigo' => 'FC', 'fabricante' => 'Fram', 'custo' => 28, 'venda' => 62, 'min' => 6, 'estoque' => 22],
            ['nome' => 'Jogo de velas de ignicao', 'codigo' => 'VI', 'fabricante' => 'NGK', 'custo' => 58, 'venda' => 118, 'min' => 5, 'estoque' => 18],
            ['nome' => 'Correia dentada', 'codigo' => 'CD', 'fabricante' => 'Gates', 'custo' => 72, 'venda' => 156, 'min' => 4, 'estoque' => 14],
            ['nome' => 'Kit correia auxiliar', 'codigo' => 'KA', 'fabricante' => 'Contitech', 'custo' => 96, 'venda' => 210, 'min' => 3, 'estoque' => 12],
            ['nome' => 'Pastilha de freio dianteira', 'codigo' => 'PFD', 'fabricante' => 'Bosch', 'custo' => 64, 'venda' => 139, 'min' => 5, 'estoque' => 20],
            ['nome' => 'Disco de freio ventilado', 'codigo' => 'DFV', 'fabricante' => 'Fremax', 'custo' => 88, 'venda' => 188, 'min' => 4, 'estoque' => 16],
            ['nome' => 'Amortecedor dianteiro', 'codigo' => 'AMD', 'fabricante' => 'Monroe', 'custo' => 150, 'venda' => 310, 'min' => 3, 'estoque' => 10],
            ['nome' => 'Amortecedor traseiro', 'codigo' => 'AMT', 'fabricante' => 'Cofap', 'custo' => 135, 'venda' => 285, 'min' => 3, 'estoque' => 11],
            ['nome' => 'Bieleta da suspensao', 'codigo' => 'BIE', 'fabricante' => 'Axios', 'custo' => 32, 'venda' => 79, 'min' => 6, 'estoque' => 24],
            ['nome' => 'Bucha da bandeja', 'codigo' => 'BUB', 'fabricante' => 'Axios', 'custo' => 24, 'venda' => 58, 'min' => 6, 'estoque' => 26],
            ['nome' => 'Rolamento de roda dianteiro', 'codigo' => 'RRD', 'fabricante' => 'SKF', 'custo' => 82, 'venda' => 174, 'min' => 4, 'estoque' => 15],
            ['nome' => 'Sensor ABS', 'codigo' => 'ABS', 'fabricante' => 'Bosch', 'custo' => 74, 'venda' => 168, 'min' => 3, 'estoque' => 9],
            ['nome' => 'Bateria 60Ah', 'codigo' => 'BAT', 'fabricante' => 'Moura', 'custo' => 260, 'venda' => 520, 'min' => 2, 'estoque' => 8],
            ['nome' => 'Lampada farol H7', 'codigo' => 'LMP', 'fabricante' => 'Osram', 'custo' => 19, 'venda' => 45, 'min' => 10, 'estoque' => 36],
            ['nome' => 'Kit embreagem', 'codigo' => 'EMB', 'fabricante' => 'Luk', 'custo' => 320, 'venda' => 690, 'min' => 2, 'estoque' => 6],
            ['nome' => 'Junta homocinetica', 'codigo' => 'HOM', 'fabricante' => 'Spicer', 'custo' => 120, 'venda' => 260, 'min' => 3, 'estoque' => 9],
            ['nome' => 'Retrovisor externo', 'codigo' => 'RET', 'fabricante' => 'Metagal', 'custo' => 110, 'venda' => 245, 'min' => 2, 'estoque' => 7],
            ['nome' => 'Filtro de cabine', 'codigo' => 'CAB', 'fabricante' => 'Tecfil', 'custo' => 26, 'venda' => 62, 'min' => 8, 'estoque' => 30],
        ];

        foreach ($catalogo as $marcaNome => $modelos) {
            $marca = MarcasVeiculos::updateOrCreate(['nome' => $marcaNome], ['nome' => $marcaNome]);

            foreach ($modelos as $modeloNome) {
                $modelo = ModelosVeiculos::updateOrCreate(
                    ['marca_id' => $marca->id, 'nome' => $modeloNome],
                    ['marca_id' => $marca->id, 'nome' => $modeloNome]
                );

                foreach ($familias as $familia) {
                    $codigo = $this->codigo($marcaNome, $modeloNome, $familia['codigo']);

                    Peca::updateOrCreate(
                        ['codigo' => $codigo],
                        [
                            'nome' => $familia['nome'] . ' ' . $marcaNome . ' ' . $modeloNome,
                            'fabricante' => $familia['fabricante'],
                            'marca_veiculo_id' => $marca->id,
                            'modelo_veiculo_id' => $modelo->id,
                            'preco_custo' => $familia['custo'],
                            'preco_venda' => $familia['venda'],
                            'estoque' => $familia['estoque'],
                            'estoque_minimo' => $familia['min'],
                            'unidade' => str_starts_with($familia['nome'], 'Jogo') || str_starts_with($familia['nome'], 'Kit') ? 'jogo' : 'un',
                            'ativo' => true,
                        ]
                    );
                }
            }
        }

        foreach (['Fluido de freio DOT4', 'Oleo 5W30 sintetico', 'Aditivo radiador', 'Limpa contato eletrico', 'Graxa homocinetica'] as $index => $nome) {
            Peca::updateOrCreate(
                ['codigo' => 'UNI-' . str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'nome' => $nome . ' universal',
                    'fabricante' => ['Bosch', 'Mobil', 'Radiex', 'Wurth', 'Vonder'][$index],
                    'marca_veiculo_id' => null,
                    'modelo_veiculo_id' => null,
                    'preco_custo' => [18, 31, 22, 16, 24][$index],
                    'preco_venda' => [42, 68, 49, 36, 54][$index],
                    'estoque' => [40, 55, 32, 26, 20][$index],
                    'estoque_minimo' => [10, 12, 8, 6, 5][$index],
                    'unidade' => 'un',
                    'ativo' => true,
                ]
            );
        }
    }

    private function codigo(string $marca, string $modelo, string $familia): string
    {
        $marca = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', Str::ascii($marca)), 0, 3));
        $modelo = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', Str::ascii($modelo)), 0, 6));

        return "{$familia}-{$marca}-{$modelo}";
    }
}
