<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Visibility;

use MyCLabs\Enum\Enum;

/**
 * @method static Visibility PUBLIC()
 * @method static Visibility PROTECTED()
 * @method static Visibility PRIVATE()
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Visibility extends Enum
{
    const PUBLIC = 0;
    const PROTECTED = 1;
    const PRIVATE = 2;

    public static function fromFlags(int $flags)
    {
        if ($flags & \ast\flags\MODIFIER_PRIVATE) {
            return Visibility::PRIVATE();
        } elseif ($flags & \ast\flags\MODIFIER_PROTECTED) {
            return Visibility::PROTECTED();
        } elseif ($flags & \ast\flags\MODIFIER_PUBLIC) {
            return Visibility::PUBLIC();
        }
        throw new \Exception('Unknown visibility');
    }
}
