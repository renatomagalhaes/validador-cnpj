<?php

declare(strict_types=1);

namespace Unoweb\Cnpj;

/**
 * Classe para validação de CNPJ legado e alfanumérico.
 * Utiliza recursos do PHP 8.3 para maior segurança de tipos.
 */
class Cnpj
{
    /**
     * Pesos para o cálculo do primeiro dígito verificador.
     * PHP 8.3 Feature: Typed class constants provide better type safety.
     */
    private const array WEIGHTS_DV1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    /**
     * Pesos para o cálculo do segundo dígito verificador.
     */
    private const array WEIGHTS_DV2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    /**
     * Valida um CNPJ (legado ou alfanumérico).
     *
     * @param string $cnpj
     * @return bool
     */
    public static function validate(string $cnpj): bool
    {
        // Limpa formatação mantendo apenas o que é relevante (A-Z e 0-9)
        $cnpj = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $cnpj));

        if (strlen($cnpj) !== 14) {
            return false;
        }

        // Verifica se são todos caracteres iguais (inválido conforme referência)
        if (preg_match('/^([A-Z0-9])\1{13}$/', $cnpj)) {
            return false;
        }

        return self::calculateCheckDigits($cnpj);
    }

    private static function calculateCheckDigits(string $cnpj): bool
    {
        $base = substr($cnpj, 0, 12);
        
        $dv1 = self::calculateDigit($base, self::WEIGHTS_DV1);
        $dv2 = self::calculateDigit($base . $dv1, self::WEIGHTS_DV2);

        return substr($cnpj, 12, 2) === "{$dv1}{$dv2}";
    }

    private static function calculateDigit(string $input, array $weights): int
    {
        $sum = 0;
        foreach (str_split($input) as $i => $char) {
            /**
             * Conversão alfanumérica conforme regra da Receita Federal: ASCII - 48.
             * '0'-'9' (ASCII 48-57) -> valores 0-9
             * 'A'-'Z' (ASCII 65-90) -> valores 17-42
             */
            $value = ord($char) - 48;
            $sum += $value * $weights[$i];
        }

        $rest = $sum % 11;
        return $rest < 2 ? 0 : 11 - $rest;
    }
}
