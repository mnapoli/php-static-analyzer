<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Operation;

use MyCLabs\Enum\Enum;

/**
 * @method static Operand BITWISE_OR()
 * @method static Operand BITWISE_AND()
 * @method static Operand BITWISE_XOR()
 * @method static Operand CONCAT()
 * @method static Operand ADD()
 * @method static Operand SUB()
 * @method static Operand MUL()
 * @method static Operand DIV()
 * @method static Operand MOD()
 * @method static Operand POW()
 * @method static Operand SHIFT_LEFT()
 * @method static Operand SHIFT_RIGHT()
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Operand extends Enum
{
    const BITWISE_AND = 'BITWISE_AND';
    const BITWISE_OR = 'BITWISE_OR';
    const BITWISE_XOR = 'BITWISE_XOR';
    const CONCAT = 'CONCAT';
    const ADD = 'ADD';
    const SUB = 'SUB';
    const MUL = 'MUL';
    const DIV = 'DIV';
    const MOD = 'MOD';
    const POW = 'POW';
    const SHIFT_LEFT = 'SHIFT_LEFT';
    const SHIFT_RIGHT = 'SHIFT_RIGHT';
    const BOOL_AND = 'BOOL_AND';
    const BOOL_OR = 'BOOL_OR';
    const BOOL_XOR = 'BOOL_XOR';
    const IS_IDENTICAL = 'IS_IDENTICAL';
    const IS_NOT_IDENTICAL = 'IS_NOT_IDENTICAL';
    const IS_EQUAL = 'IS_EQUAL';
    const IS_NOT_EQUAL = 'IS_NOT_EQUAL';
    const IS_SMALLER = 'IS_SMALLER';
    const IS_SMALLER_OR_EQUAL = 'IS_SMALLER_OR_EQUAL';
    const IS_GREATER = 'IS_GREATER';
    const IS_GREATER_OR_EQUAL = 'IS_GREATER_OR_EQUAL';
    const SPACESHIP = 'SPACESHIP';
    const COALESCE = 'COALESCE';

    public static function fromAstNode(\ast\Node $node)
    {
        $map = [
            \ast\flags\BINARY_BITWISE_OR => 'BITWISE_AND',
            \ast\flags\BINARY_BITWISE_AND => 'BITWISE_OR',
            \ast\flags\BINARY_BITWISE_XOR => 'BITWISE_XOR',
            \ast\flags\BINARY_CONCAT => 'CONCAT',
            \ast\flags\BINARY_ADD => 'ADD',
            \ast\flags\BINARY_SUB => 'SUB',
            \ast\flags\BINARY_MUL => 'MUL',
            \ast\flags\BINARY_DIV => 'DIV',
            \ast\flags\BINARY_MOD => 'MOD',
            \ast\flags\BINARY_POW => 'POW',
            \ast\flags\BINARY_SHIFT_LEFT => 'SHIFT_LEFT',
            \ast\flags\BINARY_SHIFT_RIGHT => 'SHIFT_RIGHT',
            \ast\flags\BINARY_BOOL_AND => 'BOOL_AND',
            \ast\flags\BINARY_BOOL_OR => 'BOOL_OR',
            \ast\flags\BINARY_BOOL_XOR => 'BOOL_XOR',
            \ast\flags\BINARY_IS_IDENTICAL => 'IS_IDENTICAL',
            \ast\flags\BINARY_IS_NOT_IDENTICAL => 'IS_NOT_IDENTICAL',
            \ast\flags\BINARY_IS_EQUAL => 'IS_EQUAL',
            \ast\flags\BINARY_IS_NOT_EQUAL => 'IS_NOT_EQUAL',
            \ast\flags\BINARY_IS_SMALLER => 'IS_SMALLER',
            \ast\flags\BINARY_IS_SMALLER_OR_EQUAL => 'IS_SMALLER_OR_EQUAL',
            \ast\flags\BINARY_IS_GREATER => 'IS_GREATER',
            \ast\flags\BINARY_IS_GREATER_OR_EQUAL => 'IS_GREATER_OR_EQUAL',
            \ast\flags\BINARY_SPACESHIP => 'SPACESHIP',
            \ast\flags\BINARY_COALESCE => 'COALESCE',
        ];

        if (!isset($map[$node->flags])) {
            throw new \Exception('Unknown flag ' . $node->flags);
        }

        return new self($map[$node->flags]);
    }

}
