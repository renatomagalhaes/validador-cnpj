# Validador de CNPJ (Legado e Alfanumérico)

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.3-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Maintenance](https://img.shields.io/badge/Maintained%20by-Unoweb-orange.svg)](https://github.com/renatomagalhaes)

Biblioteca PHP pura, sem dependências, para validar e tipar o novo **CNPJ Alfanumérico** da Receita Federal utilizando os recursos mais modernos do **PHP 8.3+**, com suporte ao formato legado (numérico) e ao novo formato alfanumérico previsto para entrar em vigor em **julho de 2026**.

---

## Sobre a Mudança

Com o crescimento acelerado de novas empresas no Brasil, o formato puramente numérico do CNPJ estava próximo do seu limite técnico. Em resposta, a Receita Federal publicou a **Instrução Normativa RFB nº 2.229/2024**, introduzindo o suporte a caracteres alfanuméricos (letras de A a Z e números de 0 a 9).

Os CNPJs numéricos atuais **continuarão válidos**. O novo formato será atribuído apenas para **novas inscrições a partir de julho de 2026**.

### Cronograma de Implantação
| Data | Evento |
| :--- | :--- |
| **15/10/2024** | Publicação da Instrução Normativa RFB nº 2.229 |
| **Julho de 2026** | Início das emissões do novo formato alfanumérico |

---

## Estrutura do CNPJ Alfanumérico

Um CNPJ tem sempre **14 caracteres** divididos em três partes principais:

```text
X X . X X X . X X X / X X X X - D D
└───────────────────┘ └──────┘ └──┘
    Raiz (8 chars)    Ordem   Dígitos
                     (4 chars) Verificadores
```

| Parte | Posições | Conteúdo | Exemplo |
| :--- | :--- | :--- | :--- |
| **Raiz** | 1 a 8 | Alfanumérico (A–Z, 0–9) | `12ABC345` |
| **Ordem** | 9 a 12 | Alfanumérico (A–Z, 0–9) | `01AB` |
| **Dígitos Verificadores** | 13 e 14 | **Sempre Numéricos (0-9)** | `77` |

---

## Como os Dígitos Verificadores são calculados

O algoritmo segue o padrão **Módulo 11**, adaptado pela Receita Federal para aceitar caracteres alfanuméricos.

### 1. Conversão de Caracteres
Cada caractere da base (posições 1 a 12) é convertido para um valor numérico subtraindo **48** do seu código ASCII:

- **Dígitos '0'–'9'** → valores **0 a 9**
  - Ex: `'5'` (ASCII 53) $53 - 48 =$ **5**
- **Letras 'A'–'Z'** → valores **17 a 42**
  - Ex: `'A'` (ASCII 65) $65 - 48 =$ **17**
  - Ex: `'B'` (ASCII 66) $66 - 48 =$ **18**
  - Ex: `'Z'` (ASCII 90) $90 - 48 =$ **42**

### 2. Cálculo do 1º Dígito Verificador (DV1)
Multiplicamos os 12 primeiros caracteres pelos pesos abaixo e somamos os resultados:

| Posição | 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | 10 | 11 | 12 |
| :--- | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| **Peso** | 5 | 4 | 3 | 2 | 9 | 8 | 7 | 6 | 5 | 4 | 3 | 2 |

1. Calcule o resto da divisão da soma por 11.
2. Se o resto for **0** ou **1** → o dígito é **0**.
3. Caso contrário → o dígito é **11 - resto**.

### 3. Cálculo do 2º Dígito Verificador (DV2)
Repetimos o processo usando os **12 primeiros caracteres + o DV1** calculado:

| Posição | 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | 10 | 11 | 12 | 13 |
| :--- | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| **Peso** | 6 | 5 | 4 | 3 | 2 | 9 | 8 | 7 | 6 | 5 | 4 | 3 | 2 |

---

## 🛠️ Como Usar

### Instalação
```bash
composer require renatomagalhaes/validador-cnpj
```

### Exemplo de Uso
```php
use Unoweb\Cnpj\Cnpj;

// Validação simples (Retorna bool)
Cnpj::validate('JR.32M.XJG/0001-69'); // true
Cnpj::validate('00.000.000/0001-91'); // true (Legado)

// O validador normaliza a entrada automaticamente (uppercase e sem máscara)
Cnpj::validate('jr32mxjg000169');     // true
```

---

## 🏗️ Integração com Laravel

Esta biblioteca é 100% compatível com as versões mais recentes do Laravel (**10.x e 11.x+**), desde que o ambiente utilize **PHP 8.3**.

### Criando uma Custom Rule
Você pode integrar a validação facilmente criando uma Rule customizada:

```php
// app/Rules/CnpjRule.php
namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Unoweb\Cnpj\Cnpj;

class CnpjRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!Cnpj::validate((string) $value)) {
            $fail('O campo :attribute não é um CNPJ válido.');
        }
    }
}
```

E utilize-a em seu Controller ou FormRequest:
```php
$request->validate([
    'documento' => ['required', new CnpjRule()],
]);
```

---

## 🌍 Compatibilidade Ampla (Framework Agnostic)

Esta biblioteca foi desenhada para ser utilizada em qualquer ecossistema PHP.

- **Hyperf / Swoole:** Por não possuir estado interno e ser 100% CPU-bound, é totalmente segura para ambientes de alta performance e corrotinas.
- **PHP Puro / Legado:** Se o seu projeto utiliza Composer, basta realizar o `require` e utilizar a classe normalmente através do autoloader padrão (`vendor/autoload.php`).
- **Outros Frameworks:** Compatível com Symfony, Slim, Zend/Laminas e CakePHP.

---

## 🧪 Desenvolvimento e Testes

A biblioteca utiliza PHPUnit para garantir a integridade dos cálculos.

```bash
# Rodar os testes via Docker
make test
```

---

## 🔗 Referências Oficiais
- [Receita Federal - Perguntas e Respostas sobre CNPJ Alfanumérico](https://www.gov.br/receitafederal/pt-br/acesso-a-informacao/acoes-e-programas/programas-e-atividades/cnpj-alfanumerico)
- [Instrução Normativa RFB nº 2.229/2024](https://www.gov.br/receitafederal/pt-br/acesso-a-informacao/legislacao)

---

Desenvolvido e mantido por **[Unoweb](https://github.com/renatomagalhaes)**.
