<?php

namespace App\Support;

class PixBrCode
{
    public static function gerar(
        string $chave,
        float $valor,
        string $nomeRecebedor = 'AUTOTECH PRO',
        string $cidade = 'FORTALEZA',
        string $txid = '***',
        string $descricao = ''
    ): string {
        $chave = preg_replace('/\D+/', '', $chave) ?: $chave;
        $nomeRecebedor = self::normalizar($nomeRecebedor, 25);
        $cidade = self::normalizar($cidade, 15);
        $txid = self::normalizarTxid($txid);
        $descricao = self::normalizar($descricao, 72);

        $merchantAccount = self::campo('00', 'BR.GOV.BCB.PIX')
            . self::campo('01', $chave);

        if ($descricao !== '') {
            $merchantAccount .= self::campo('02', $descricao);
        }

        $payload = self::campo('00', '01')
            . self::campo('01', '12')
            . self::campo('26', $merchantAccount)
            . self::campo('52', '0000')
            . self::campo('53', '986')
            . self::campo('54', number_format(max($valor, 0), 2, '.', ''))
            . self::campo('58', 'BR')
            . self::campo('59', $nomeRecebedor)
            . self::campo('60', $cidade)
            . self::campo('62', self::campo('05', $txid))
            . '6304';

        return $payload . self::crc16($payload);
    }

    private static function campo(string $id, string $valor): string
    {
        return $id . str_pad((string) strlen($valor), 2, '0', STR_PAD_LEFT) . $valor;
    }

    private static function normalizar(string $valor, int $limite): string
    {
        $valor = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $valor) ?: $valor;
        $valor = strtoupper(preg_replace('/[^A-Z0-9 .\-]/i', '', $valor) ?? '');
        $valor = trim(preg_replace('/\s+/', ' ', $valor) ?? '');

        return substr($valor, 0, $limite);
    }

    private static function normalizarTxid(string $valor): string
    {
        $valor = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $valor) ?? '');

        return substr($valor ?: '***', 0, 25);
    }

    private static function crc16(string $payload): string
    {
        $crc = 0xFFFF;

        for ($i = 0, $len = strlen($payload); $i < $len; $i++) {
            $crc ^= ord($payload[$i]) << 8;

            for ($bit = 0; $bit < 8; $bit++) {
                $crc = ($crc & 0x8000)
                    ? (($crc << 1) ^ 0x1021)
                    : ($crc << 1);
                $crc &= 0xFFFF;
            }
        }

        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }
}
