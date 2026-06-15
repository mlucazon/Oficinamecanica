<?php

namespace App\Support;

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

    }
}
