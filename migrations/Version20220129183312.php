<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220129183312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'update buildings with location';
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function up(Schema $schema): void
    {
        $sql = <<<SQL
ALTER TABLE `ot_buildings`
ADD `location_x` INT(10) DEFAULT 0 NOT NULL,
ADD `location_y` INT(10) DEFAULT 0 NOT NULL,
ADD `username` VARCHAR(255) DEFAULT '' NOT NULL
SQL;

        $this->addSql($sql);
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function down(Schema $schema): void
    {
        $sql = <<<SQL
ALTER TABLE  `ot_buildings`
DROP COLUMN `location_x`,
DROP COLUMN `location_y`,
DROP COLUMN `username`
SQL;
        $this->addSql($sql);
    }
}
