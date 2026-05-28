<?php

namespace App\Http\Controllers;

use App\Models\CartaoCliente;
use Illuminate\Http\Request;

class CartaoClienteController extends Controller
{
    public function store(Request $request)
    {
        abort_unless(auth()->user()->isCliente(), 403);

        $data = $request->validate([
            'tipo_cartao' => 'required|in:debito,credito',
            'cartao_numero' => 'required|string|max:24',
            'cartao_nome' => 'required|string|max:120',
            'cartao_validade' => ['required', 'regex:/^\d{2}\/\d{2}$/'],
        ]);

        $numero = preg_replace('/\D+/', '', $data['cartao_numero']);

        if (strlen($numero) < 12) {
            return back()->withErrors(['cartao_numero' => 'Informe um numero de cartao valido.']);
        }

        $final = substr($numero, -4);

        CartaoCliente::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'final' => $final,
                'validade' => $data['cartao_validade'],
            ],
            [
                'tipo' => $data['tipo_cartao'],
                'bandeira' => $this->detectarBandeira($numero),
                'titular' => $data['cartao_nome'],
            ]
        );

        return back()->with('success', 'Cartao salvo no seu perfil.');
    }

    private function detectarBandeira(string $numero): string
    {
        if (str_starts_with($numero, '4')) {
            return 'Visa';
        }

        if (preg_match('/^5[1-5]/', $numero) || preg_match('/^2(2[2-9]|[3-6]|7[01]|720)/', $numero)) {
            return 'Mastercard';
        }

        if (preg_match('/^3[47]/', $numero)) {
            return 'American Express';
        }

        if (preg_match('/^(36|38|30[0-5])/', $numero)) {
            return 'Diners Club';
        }

        if (preg_match('/^(4011|4312|4389|4514|4576|5041|5067|509|6277|6362|6363|650|6516|6550)/', $numero)) {
            return 'Elo';
        }

        return 'Cartao';
    }
}
