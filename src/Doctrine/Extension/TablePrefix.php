<?php

declare(strict_types=1);

namespace App\Doctrine\Extension;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

final class TablePrefix
{
    public function __construct(private string $prefix)
    {
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $classMetadata = $eventArgs->getClassMetadata();
        if (! str_starts_with($classMetadata->getTableName(), $this->prefix)) {
            return;
        }

        if ($classMetadata->isRootEntity() && ! $classMetadata->namespace) {
            $classMetadata->name = $this->getNameFromTableName($classMetadata->getTableName());
        }
    }

    private function getNameFromTableName(string $tableName): string
    {
        $pattern = sprintf('~^%s(.*)~', $this->prefix);
        $className = preg_replace($pattern, '$1', $tableName);

        if (str_ends_with($className, 's')) {
            $className = substr($className, 0, -1);
        }
        return ucfirst($className);
    }
}
