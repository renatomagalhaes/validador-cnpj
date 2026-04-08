<?php

declare(strict_types=1);

namespace Unoweb\Cnpj\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Unoweb\Cnpj\Cnpj;

class CnpjTest extends TestCase
{
    #[DataProvider('legacyCnpjProvider')]
    public function testLegacyCnpj(string $cnpj, bool $expected): void
    {
        $this->assertEquals($expected, Cnpj::validate($cnpj));
    }

    #[DataProvider('alphanumericCnpjProvider')]
    public function testAlphanumericCnpj(string $cnpj, bool $expected): void
    {
        $this->assertEquals($expected, Cnpj::validate($cnpj));
    }

    #[DataProvider('invalidCnpjProvider')]
    public function testInvalidCnpj(string $cnpj, bool $expected): void
    {
        $this->assertEquals($expected, Cnpj::validate($cnpj));
    }

    public static function legacyCnpjProvider(): iterable
    {
        yield 'Legacy 1' => ['11.222.333/0001-81', true];
        yield 'Legacy 2' => ['00.000.000/0001-91', true];
        yield 'Legacy 3' => ['10.870.455/0001-11', true];
        yield 'Legacy 4' => ['58.118.141/0001-93', true];
        yield 'Legacy 5' => ['34.463.398/0001-14', true];
        yield 'Legacy 6' => ['98.572.441/0001-62', true];
        yield 'Legacy 7' => ['37.149.094/0001-75', true];
        yield 'Legacy 8' => ['22.071.972/0001-00', true];
        yield 'Legacy 9' => ['03.896.310/0001-24', true];
        yield 'Legacy 10' => ['13.961.060/0001-12', true];
        yield 'Legacy 11' => ['59.880.709/0001-71', true];
        yield 'Legacy 12' => ['33.079.124/0001-63', true];
        yield 'Legacy 13' => ['91.210.224/0001-83', true];
        yield 'Legacy 14' => ['28.896.281/0001-21', true];
        yield 'Legacy 15' => ['43.271.804/0001-38', true];
        yield 'Legacy 16' => ['43.271.804/4018-09', true];
        yield 'Legacy 17' => ['43.271.804/4894-63', true];
        yield 'Legacy 18' => ['43.271.804/1875-30', true];
        yield 'Legacy 19' => ['50.668.802/0001-42', true];
        yield 'Legacy 20' => ['72.907.570/0001-70', true];
        yield 'Legacy 21' => ['38.676.509/0001-21', true];
        yield 'Legacy 22' => ['38.676.509/7338-97', true];
        yield 'Legacy 23' => ['38.676.509/5347-70', true];
        yield 'Legacy 24' => ['77.807.303/0001-45', true];
        yield 'Legacy 25' => ['22.675.743/0001-02', true];
        yield 'Legacy 26' => ['09.333.505/0001-42', true];
        yield 'Legacy 27' => ['09.333.505/7140-05', true];
        yield 'Legacy 28' => ['82.833.601/0001-40', true];
    }

    public static function alphanumericCnpjProvider(): iterable
    {
        yield 'Alphanumeric 1' => ['JR.32M.XJG/0001-69', true];
        yield 'Alphanumeric 2' => ['R4.10W.DKL/0001-87', true];
        yield 'Alphanumeric 3' => ['W5.8P6.ME9/0001-08', true];
        yield 'Alphanumeric 4' => ['VT.6J1.0DR/0001-00', true];
        yield 'Alphanumeric 5' => ['HW.AYK.RVY/0001-36', true];
        yield 'Alphanumeric 6' => ['C6.9XH.YHK/0001-93', true];
        yield 'Alphanumeric 7' => ['40.S47.CJM/0001-90', true];
        yield 'Alphanumeric 8' => ['ZZ.AR3.K3N/0001-39', true];
        yield 'Alphanumeric 9' => ['ZZ.AR3.K3N/BSN0-43', true];
        yield 'Alphanumeric 10' => ['ZZ.AR3.K3N/8CLT-82', true];
        yield 'Alphanumeric 11' => ['ZZ.AR3.K3N/0C4H-19', true];
        yield 'Alphanumeric 12' => ['9P.NYB.525/0001-10', true];
        yield 'Alphanumeric 13' => ['BN.XL1.NK5/0001-22', true];
        yield 'Alphanumeric 14' => ['48.34Z.W24/0001-45', true];
        yield 'Alphanumeric 15' => ['80.5GB.GSD/0001-00', true];
        yield 'Alphanumeric 16' => ['80.5GB.GSD/YE60-37', true];
        yield 'Alphanumeric 17' => ['80.5GB.GSD/ADKG-76', true];
        yield 'Alphanumeric 18' => ['60.2G2.TD5/0001-18', true];
        yield 'Alphanumeric 19' => ['3Y.HC2.TNV/0001-08', true];
        yield 'Alphanumeric 20' => ['3Y.HC2.TNV/B4Z8-69', true];
        yield 'Alphanumeric 21' => ['1B.VAS.688/0001-04', true];
    }

    public static function invalidCnpjProvider(): iterable
    {
        yield 'Legacy DV error' => ['11.222.333/0001-82', false];
        yield 'Alphanumeric DV error' => ['12.ABC.345/01AB-78', false];
        yield 'Repetitive numeric characters' => ['111.111.111/1111-11', false];
        yield 'Repetitive letters' => ['AA.AAA.AAA/AAAA-AA', false];
        yield 'Length shorter' => ['04.252.327/0001-9', false];
        yield 'Length longer' => ['11.222.333/0001-811', false];
        yield 'Empty string' => ['', false];
        yield 'Only whitespaces' => ['   ', false];
        yield 'Normalization Case' => ['jr.32m.xjg/0001-69', true];
    }
}
