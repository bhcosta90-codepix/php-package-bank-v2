<?php

declare(strict_types=1);

namespace CodePix\Bank\ValueObject;

use Costa\Entity\Contracts\ValueObjectInterface;
use Costa\Entity\Exceptions\EntityException;

class Document implements ValueObjectInterface
{
    /**
     * @throws EntityException
     */
    public function __construct(protected string $document)
    {
        $this->document = preg_replace('/[^0-9]/', '', $this->document);

        $validated = strlen($this->document) <= 11
            ? $this->validateCPF()
            : $this->validateCNPJ();

        if (!$validated) {
            $type = strlen($this->document) <= 11 ? "CPF" : "CNPJ";
            throw new EntityException("{$type} Invalid");
        }
    }

    /**
     * @throws EntityException
     */
    public static function make(string $document): self
    {
        return new self($document);
    }

    public function __toString(): string
    {
        return $this->document;
    }

    private function validateCPF(): bool
    {
        $cpf = $this->document;

        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    private function validateCNPJ(): bool
    {
        $cnpj = $this->document;

        $cnpj = preg_replace('/[^0-9]/', '', (string)$cnpj);

        // Valida tamanho
        if (strlen($cnpj) != 14) {
            return false;
        }

        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $rest = $soma % 11;

        if ($cnpj[12] != ($rest < 2 ? 0 : 11 - $rest)) {
            return false;
        }

        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $rest =  (float) $soma % 11;

        return $cnpj[13] == ($rest < 2 ? 0 : 11 - $rest);
    }
}