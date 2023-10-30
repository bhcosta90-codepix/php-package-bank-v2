<?php

declare(strict_types=1);

use CodePix\Bank\ValueObject\Document;
use Costa\Entity\Contracts\ValueObjectInterface;

use Costa\Entity\Exceptions\EntityException;

use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertIsString;

describe("Document Unit Test", function () {
    test("pass CPF valid", function () {
        $document = new Document("327.053.030-73");
        assertInstanceOf(ValueObjectInterface::class, $document);
        assertIsString((string) $document);

        $document = new Document("32705303073");
        assertInstanceOf(ValueObjectInterface::class, $document);
        assertIsString((string) $document);

        $document = Document::make("32705303073");
        assertInstanceOf(ValueObjectInterface::class, $document);
        assertIsString((string) $document);

    });

    test("pass CNPJ valid", function () {
        $document = new Document("97.002.686/0001-91");
        assertInstanceOf(ValueObjectInterface::class, $document);
        assertIsString((string) $document);

        $document = new Document("97002686000191");
        assertInstanceOf(ValueObjectInterface::class, $document);
        assertIsString((string) $document);

        $document = Document::make("97002686000191");
        assertInstanceOf(ValueObjectInterface::class, $document);
        assertIsString((string) $document);
    });

    test("exception CPF invalid", function () {
        expect(fn() => new Document("327.053.030-72"))->toThrow(new EntityException("CPF Invalid"));
    });

    test("exception CPF invalid when pass value less than 11", function () {
        expect(fn() => new Document("327053030"))->toThrow(new EntityException("CPF Invalid"));
    });

    test("exception CNPJ invalid", function () {
        expect(fn() => new Document("97.002.686/0001-97"))->toThrow(new EntityException("CNPJ Invalid"));
    });

    test("exception CNPJ invalid when is cnpj is 10", function () {
          expect(fn() => new Document("00000000000010"))->toThrow(new EntityException("CNPJ Invalid"));
    });

    test("exception CNPJ invalid when pass value less than 14", function () {
        expect(fn() => new Document("970026860001"))->toThrow(new EntityException("CNPJ Invalid"));
    });
});