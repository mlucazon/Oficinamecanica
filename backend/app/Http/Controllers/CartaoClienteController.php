<?php

namespace App\Http\Controllers;

use App\Models\CartaoCliente;
use App\Support\CartoesClienteSchema;
use Illuminate\Http\Request;

class CartaoClienteController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->isCliente(), 403);

        CartoesClienteSchema::ensure();

        $cartoes = auth()->user()
            ->cartoes()
            ->latest()
            ->get();

        return view('cartoes.index', compact('cartoes'));
    }

    public function create()
    {
        abort_unless(auth()->user()->isCliente(), 403);

        CartoesClienteSchema::ensure();

        return view('cartoes.create');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->isCliente(), 403);

        CartoesClienteSchema::ensure();

        $data = $request->validate([
            'tipo_cartao' => 'required|in:debito,credito',
            'cartao_numero' => 'required|string|max:24',
            'cartao_nome' => 'required|string|max:120',
            'cartao_validade' => ['required', 'regex:/^\d{2}\/\d{2}$/'],
            'cartao_cvv' => ['required', 'regex:/^\d{3,4}$/'],
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

        if ($request->input('redirect_to') === 'perfil') {
            return redirect()->route('perfil.edit')->with('success', 'Cartao salvo no seu perfil.');
        }

        return back()->with('success', 'Cartao salvo no seu perfil.');
    }

    public function destroy(CartaoCliente $cartao)
    {
        abort_unless(auth()->user()->isCliente(), 403);
        abort_unless($cartao->user_id === auth()->id(), 403);

        $cartao->delete();

        return redirect()
            ->route('cartoes.index')
            ->with('success', 'Cartao removido com sucesso.');
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
