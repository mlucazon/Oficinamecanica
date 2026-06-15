<?php

namespace Database\Seeders;

use App\Models\Cidade;
use App\Models\Estado;
use Illuminate\Database\Seeder;

class EstadoCidadeSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            'AC' => ['Acre', ['Rio Branco', 'Cruzeiro do Sul', 'Sena Madureira', 'Tarauaca', 'Feijo', 'Brasileia', 'Xapuri']],
            'AL' => ['Alagoas', ['Maceio', 'Arapiraca', 'Rio Largo', 'Palmeira dos Indios', 'Uniao dos Palmares', 'Penedo', 'Coruripe', 'Delmiro Gouveia']],
            'AP' => ['Amapa', ['Macapa', 'Santana', 'Laranjal do Jari', 'Oiapoque', 'Mazagao', 'Porto Grande', 'Tartarugalzinho']],
            'AM' => ['Amazonas', ['Manaus', 'Parintins', 'Itacoatiara', 'Manacapuru', 'Coari', 'Tefe', 'Tabatinga', 'Maues', 'Humaita', 'Iranduba']],
            'BA' => ['Bahia', ['Salvador', 'Feira de Santana', 'Vitoria da Conquista', 'Camacari', 'Juazeiro', 'Itabuna', 'Lauro de Freitas', 'Ilheus', 'Jequie', 'Teixeira de Freitas', 'Barreiras', 'Porto Seguro', 'Alagoinhas', 'Simoes Filho']],
            'CE' => ['Ceara', ['Fortaleza', 'Caucaia', 'Juazeiro do Norte', 'Maracanau', 'Sobral', 'Crato', 'Itapipoca', 'Maranguape', 'Iguatu', 'Quixada', 'Caninde', 'Aquiraz', 'Pacatuba', 'Russas', 'Aracati', 'Tiangua', 'Crateus', 'Cascavel', 'Horizonte', 'Pacajus', 'Camocim', 'Acopiara', 'Morada Nova', 'Limoeiro do Norte', 'Taua', 'Ico', 'Boa Viagem', 'Eusebio', 'Barbalha', 'Trairi']],
            'DF' => ['Distrito Federal', ['Brasilia', 'Ceilandia', 'Taguatinga', 'Samambaia', 'Planaltina', 'Gama', 'Sobradinho', 'Guara', 'Recanto das Emas', 'Santa Maria']],
            'ES' => ['Espirito Santo', ['Vitoria', 'Vila Velha', 'Serra', 'Cariacica', 'Linhares', 'Cachoeiro de Itapemirim', 'Colatina', 'Guarapari', 'Sao Mateus', 'Aracruz']],
            'GO' => ['Goias', ['Goiania', 'Aparecida de Goiania', 'Anapolis', 'Rio Verde', 'Aguas Lindas de Goias', 'Luziania', 'Valparaiso de Goias', 'Trindade', 'Formosa', 'Novo Gama', 'Catalao', 'Itumbiara']],
            'MA' => ['Maranhao', ['Sao Luis', 'Imperatriz', 'Sao Jose de Ribamar', 'Timon', 'Caxias', 'Codo', 'Paco do Lumiar', 'Acailandia', 'Bacabal', 'Balsas']],
            'MT' => ['Mato Grosso', ['Cuiaba', 'Varzea Grande', 'Rondonopolis', 'Sinop', 'Tangara da Serra', 'Caceres', 'Sorriso', 'Lucas do Rio Verde', 'Primavera do Leste', 'Barra do Garcas']],
            'MS' => ['Mato Grosso do Sul', ['Campo Grande', 'Dourados', 'Tres Lagoas', 'Corumba', 'Ponta Pora', 'Navirai', 'Nova Andradina', 'Aquidauana', 'Maracaju', 'Sidrolandia']],
            'MG' => ['Minas Gerais', ['Belo Horizonte', 'Uberlandia', 'Contagem', 'Juiz de Fora', 'Betim', 'Montes Claros', 'Ribeirao das Neves', 'Uberaba', 'Governador Valadares', 'Ipatinga', 'Sete Lagoas', 'Divinopolis', 'Santa Luzia', 'Ibirite', 'Pocos de Caldas', 'Patos de Minas']],
            'PA' => ['Para', ['Belem', 'Ananindeua', 'Santarem', 'Maraba', 'Parauapebas', 'Castanhal', 'Abaetetuba', 'Cameta', 'Marituba', 'Braganca', 'Altamira', 'Tucurui']],
            'PB' => ['Paraiba', ['Joao Pessoa', 'Campina Grande', 'Santa Rita', 'Patos', 'Bayeux', 'Sousa', 'Cajazeiras', 'Cabedelo', 'Guarabira', 'Sape']],
            'PR' => ['Parana', ['Curitiba', 'Londrina', 'Maringa', 'Ponta Grossa', 'Cascavel', 'Sao Jose dos Pinhais', 'Foz do Iguacu', 'Colombo', 'Guarapuava', 'Paranagua', 'Araucaria', 'Toledo', 'Apucarana', 'Pinhais']],
            'PE' => ['Pernambuco', ['Recife', 'Jaboatao dos Guararapes', 'Olinda', 'Caruaru', 'Petrolina', 'Paulista', 'Cabo de Santo Agostinho', 'Camaragibe', 'Garanhuns', 'Vitoria de Santo Antao', 'Igarassu', 'Sao Lourenco da Mata']],
            'PI' => ['Piaui', ['Teresina', 'Parnaiba', 'Picos', 'Piripiri', 'Floriano', 'Campo Maior', 'Barras', 'Uniao', 'Altos', 'Pedro II']],
            'RJ' => ['Rio de Janeiro', ['Rio de Janeiro', 'Sao Goncalo', 'Duque de Caxias', 'Nova Iguacu', 'Niteroi', 'Belford Roxo', 'Campos dos Goytacazes', 'Sao Joao de Meriti', 'Petropolis', 'Volta Redonda', 'Macae', 'Mage', 'Itaborai', 'Cabo Frio', 'Angra dos Reis']],
            'RN' => ['Rio Grande do Norte', ['Natal', 'Mossoro', 'Parnamirim', 'Sao Goncalo do Amarante', 'Macaiba', 'Ceara-Mirim', 'Caico', 'Assu', 'Currais Novos', 'Santa Cruz']],
            'RS' => ['Rio Grande do Sul', ['Porto Alegre', 'Caxias do Sul', 'Pelotas', 'Canoas', 'Santa Maria', 'Gravatai', 'Viamao', 'Novo Hamburgo', 'Sao Leopoldo', 'Rio Grande', 'Alvorada', 'Passo Fundo', 'Sapucaia do Sul']],
            'RO' => ['Rondonia', ['Porto Velho', 'Ji-Parana', 'Ariquemes', 'Vilhena', 'Cacoal', 'Rolim de Moura', 'Jaru', 'Guajara-Mirim', 'Ouro Preto do Oeste', 'Pimenta Bueno']],
            'RR' => ['Roraima', ['Boa Vista', 'Rorainopolis', 'Caracarai', 'Alto Alegre', 'Mucajai', 'Canta', 'Pacaraima', 'Amajari']],
            'SC' => ['Santa Catarina', ['Florianopolis', 'Joinville', 'Blumenau', 'Sao Jose', 'Chapeco', 'Itajai', 'Criciuma', 'Jaragua do Sul', 'Palhoca', 'Lages', 'Balneario Camboriu', 'Brusque']],
            'SP' => ['Sao Paulo', ['Sao Paulo', 'Guarulhos', 'Campinas', 'Sao Bernardo do Campo', 'Santo Andre', 'Osasco', 'Sao Jose dos Campos', 'Ribeirao Preto', 'Sorocaba', 'Maua', 'Sao Jose do Rio Preto', 'Santos', 'Mogi das Cruzes', 'Diadema', 'Jundiai', 'Piracicaba', 'Carapicuiba', 'Bauru', 'Itaquaquecetuba', 'Sao Vicente']],
            'SE' => ['Sergipe', ['Aracaju', 'Nossa Senhora do Socorro', 'Lagarto', 'Itabaiana', 'Sao Cristovao', 'Estancia', 'Tobias Barreto', 'Itabaianinha', 'Simao Dias', 'Propria']],
            'TO' => ['Tocantins', ['Palmas', 'Araguaina', 'Gurupi', 'Porto Nacional', 'Paraiso do Tocantins', 'Colinas do Tocantins', 'Guarai', 'Tocantinopolis', 'Miracema do Tocantins', 'Dianopolis']],
        ];

        foreach ($estados as $uf => [$nome, $cidades]) {
            $estado = Estado::updateOrCreate(['uf' => $uf], ['nome' => $nome]);

            foreach ($cidades as $cidade) {
                Cidade::updateOrCreate(
                    ['estado_id' => $estado->id, 'nome' => $cidade],
                    ['estado_id' => $estado->id, 'nome' => $cidade]
                );
            }
        }
    }
}
