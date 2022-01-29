<?php
declare(strict_types=1);

namespace App\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use OpenTribes\Core\Enum\BuildStatus;

final class BuildStatusType extends Type
{
    public const NAME = 'BuildStatus';
    private BuildStatus $enum;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform):string
    {
        $values = array_map(function (\UnitEnum $enumValue){
            return sprintf("'%s'",$enumValue->value);
        },BuildStatus::cases());
       return "ENUM(".implode(",",$values).")";
    }

    public function getName():string
    {
        return self::NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return BuildStatus::from($value);
    }

    /**
     * @param BuildStatus $value
     * @param AbstractPlatform $platform
     * @return mixed|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->value;
    }
}