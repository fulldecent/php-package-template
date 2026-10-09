<?php

declare(strict_types=1);

namespace FullDecent\Cents\Tests;

use FullDecent\Cents\Cents;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CentsTest extends TestCase
{
    public function testAddsTwoAmounts(): void
    {
        self::assertSame('2.25', Cents::evaluate('1.50 + 0.75'));
    }

    public function testAddsTenthsThatFloatsMisadd(): void
    {
        self::assertSame('0.30', Cents::evaluate('0.10 + 0.20'));
    }

    public function testSubtracts(): void
    {
        self::assertSame('0.25', Cents::evaluate('1.00 - 0.75'));
    }

    public function testSubtractsBelowZero(): void
    {
        self::assertSame('-0.50', Cents::evaluate('1.00 - 1.50'));
    }

    public function testSubtractsToZero(): void
    {
        self::assertSame('0.00', Cents::evaluate('0.50 - 0.50'));
    }

    public function testAcceptsExtraWhitespace(): void
    {
        self::assertSame('2.00', Cents::evaluate("  1.00 \t + \t 1.00  "));
    }

    public function testRejectsTheWrongCharacters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('amounts, spaces, + and -');
        Cents::evaluate('1.00 * 2.00');
    }

    public function testRejectsAnEmptyString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('an amount, an operator, and an amount');
        Cents::evaluate('');
    }

    public function testRejectsAMissingOperand(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('an amount, an operator, and an amount');
        Cents::evaluate('1.00 +');
    }

    public function testRejectsOneDecimalPlace(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('1 to 12 digits, a dot, and two digits');
        Cents::evaluate('1.5 + 0.25');
    }

    public function testRejectsALeadingZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('leading zero');
        Cents::evaluate('01.00 + 0.25');
    }

    public function testRejectsTooManyDollars(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('1 to 12 digits, a dot, and two digits');
        Cents::evaluate('1000000000000.00 + 0.01');
    }
}
