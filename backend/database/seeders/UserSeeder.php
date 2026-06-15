<?php

namespace Database\Seeders;

use App\Models\Mecanico;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'email' => 'joao@autotech.com',
                'name' => 'Joao Atendente',
                'role' => 'atendente',
            ],
            [
                'email' => 'antonio@autotech.com',
                'name' => 'Antonio Gerente',
                'role' => 'gerente',
            ],
            [
                'email' => 'cliente@autotech.com',
                'name' => 'Pedro Cliente',
                'role' => 'cliente',
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('12345678'),
                    'role' => $data['role'],
                ]
            );
        }

        $mecanicos = [
            ['nome' => 'Carlos Mecanico', 'cpf' => '000.000.000-01', 'telefone' => '(00) 00000-0001'],
            ['nome' => 'Jose Mecanico', 'cpf' => '000.000.000-02', 'telefone' => '(00) 00000-0002'],
        ];

        foreach ($mecanicos as $mecanico) {
            Mecanico::updateOrCreate(
                ['cpf' => $mecanico['cpf']],
                $mecanico + ['user_id' => null, 'ativo' => true]
            );
        }

        $this->command->info('Usuarios e mecanicos criados/atualizados com sucesso!');
        $this->command->line('');
        $this->command->line('Credenciais de acesso:');
        $this->command->line('  Atendente: joao@autotech.com / 12345678');
        $this->command->line('  Gerente:   antonio@autotech.com / 12345678');
        $this->command->line('  Cliente:   cliente@autotech.com / 12345678');
    }
}
