<?php

namespace App\Support;

use App\Models\Mecanico;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultUserAccounts
{
    private const PASSWORD = '12345678';

    private const USERS = [
        'joao@autotech.com' => [
            'name' => 'Joao Atendente',
            'role' => 'atendente',
        ],
        'antonio@autotech.com' => [
            'name' => 'Antonio Gerente',
            'role' => 'gerente',
        ],
        'mecanico@autotech.com' => [
            'name' => 'Carlos Mecanico',
            'role' => 'mecanico',
            'cpf' => '000.000.000-01',
            'telefone' => '(00) 00000-0001',
        ],
        'jose@autotech.com' => [
            'name' => 'Jose Mecanico',
            'role' => 'mecanico',
            'cpf' => '000.000.000-02',
            'telefone' => '(00) 00000-0002',
        ],
        'cliente@autotech.com' => [
            'name' => 'Pedro Cliente',
            'role' => 'cliente',
        ],
    ];

    public static function ensureForEmail(string $email): void
    {
        $email = strtolower($email);

        if (! isset(self::USERS[$email])) {
            return;
        }

        $data = self::USERS[$email];

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $data['name'],
                'password' => Hash::make(self::PASSWORD),
                'role' => $data['role'],
            ]
        );

        if ($data['role'] === 'mecanico') {
            Mecanico::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nome' => $data['name'],
                    'cpf' => $data['cpf'],
                    'telefone' => $data['telefone'],
                    'ativo' => true,
                ]
            );
        }
    }
}
