<?php
declare(strict_types=1);

namespace App\DoctrineType;

use OpenTribes\Core\Enum\BuildStatus;

final class BuildStatusType extends AbstractEnumType
{
    public const NAME = 'BuildStatusType';
    public static function getEnumsClass(): string
    {
        return BuildStatus::class;
    }

    public function getName():string
    {
       return self::NAME;
    }
}