<?php

declare(strict_types=1);

namespace FullDecent\Cents;

use InvalidArgumentException;

final class Cents
{
    /**
     * Add or subtract two amounts written as decimal strings.
     *
     * Each amount is digits, a dot, and exactly two digits. The operator is
     * `+` or `-`, separated by whitespace. The result is the same shape.
     */
    public static function evaluate(string $input): string
    {
        if (preg_match('/[^\d.+\- \t]/', $input) === 1) {
            throw new InvalidArgumentException('input must be amounts, spaces, + and -');
        }

        $tokens = preg_split('/[ \t]+/', trim($input), -1, PREG_SPLIT_NO_EMPTY);
        if ($tokens === false || count($tokens) !== 3) {
            throw new InvalidArgumentException('input must be an amount, an operator, and an amount');
        }

        [$left, $operator, $right] = $tokens;
        if ($operator !== '+' && $operator !== '-') {
            throw new InvalidArgumentException('operator must be + or -');
        }

        $result = $operator === '+'
            ? self::parse($left) + self::parse($right)
            : self::parse($left) - self::parse($right);

        return self::format($result);
    }

    private static function parse(string $amount): int
    {
        if (preg_match('/^(\d{1,12})\.(\d{2})$/', $amount, $matches) !== 1) {
            throw new InvalidArgumentException('amount must be 1 to 12 digits, a dot, and two digits');
        }

        $dollars = $matches[1];
        if (strlen($dollars) > 1 && str_starts_with($dollars, '0')) {
            throw new InvalidArgumentException('amount must not use a leading zero');
        }

        return ((int) $dollars) * 100 + (int) $matches[2];
    }

    private static function format(int $cents): string
    {
        $sign = $cents < 0 ? '-' : '';
        // PHP's % keeps the sign of the dividend, so format the absolute value.
        $absolute = $cents < 0 ? -$cents : $cents;

        return sprintf('%s%d.%02d', $sign, intdiv($absolute, 100), $absolute % 100);
    }
}
