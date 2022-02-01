<?php

declare(strict_types=1);

namespace App\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use OpenTribes\Core\Enum\BuildStatus;

/**
 * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
 */
final class BuildStatusType extends Type
{
    public const NAME = 'BuildStatus';

    /**
     * {@inheritdoc}
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $values = array_map(static function (\UnitEnum $enumValue) {
            return sprintf("'%s'", $enumValue->value);
        }, BuildStatus::cases());
        return 'ENUM('.implode(',', $values).')';
    }

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return BuildStatus::from($value);
    }

    /**
     * {@inheritdoc}
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value->value;
    }
}
