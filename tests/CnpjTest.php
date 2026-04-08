<?php

declare(strict_types=1);

namespace Unoweb\Cnpj\Tests;

use Unoweb\Cnpj\Cnpj;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CnpjTest extends TestCase
{
    #[DataProvider('legacyCnpjProvider')]
    public function testLegacyCnpjValidation(string $cnpj, bool $expected): void
    {
        $this->assertEquals($expected, Cnpj::validate($cnpj));
    }

    public static function legacyCnpjProvider(): array
    {
        return [
            'Valid BB' => ['00.000.000/0001-91', true],
            'Valid BB unformatted' => ['00000000000191', true],
            'Valid Petrobras' => ['33.000.167/0001-01', true],
            'Valid Google' => ['06.990.590/0001-23', true],
            'Invalid repetitive' => ['11.111.111/1111-11', false],
            'Invalid length short' => ['1234567890123', false],
            'Invalid length long' => ['123456789012345', false],
            'Invalid check digits' => ['00.000.000/0001-92', false],
            'Empty' => ['', false],
            // Alfanuméricos
            'Alphanumeric 1' => ['JR.32M.XJG/0001-69', true],
            'Alphanumeric 2' => ['R4.10W.DKL/0001-87', true],
            'Alphanumeric DV error second digit' => ['12.ABC.345/01AB-78', false],
            'Repetitive alphanumeric characters' => ['AAAAAAAAAAAAAA', false],
            'Invalid length shorter' => ['12ABC34501AB7', false],
            'Invalid length longer' => ['12ABC34501AB777', false],
            'Empty string' => ['', false],
            'Only spaces' => ['   ', false],
            'Alphanumeric 3' => ['W5.8P6.ME9/0001-08', true],
            'Alphanumeric 4' => ['VT.6J1.0DR/0001-00', true],
            'Alphanumeric 5' => ['HW.AYK.RVY/0001-36', true],
            'Alphanumeric 6' => ['74.ZPP.E04/0001-30', true],
            'Alphanumeric 7' => ['8Y.3M1.CS8/0001-05', true],
            'Alphanumeric 8' => ['DH.CR1.NH3/0001-15', true],
            'Alphanumeric 9' => ['YH.TLG.0BZ/0001-40', true],
            'Alphanumeric 10' => ['S5.41D.RPV/0001-75', true],
        ];
    }
}
