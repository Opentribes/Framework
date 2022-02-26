<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220225215136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create City ';
    }

    public function up(Schema $schema): void
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS`ot_city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `location_x` int(11) NOT NULL,
  `location_y` int(11) NOT NULL,
  `user_id` int(11) NULL, 
  PRIMARY KEY (`id`),
  CONSTRAINT `UNQ_location` UNIQUE (`location_x`,`location_y`),
   CONSTRAINT `FK_user` FOREIGN KEY (`user_id`)
   REFERENCES `se_users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4
SQL;

        $this->addSql($sql);

        $sql = <<<SQL
ALTER TABLE `ot_buildings` RENAME `ot_building`
SQL;
        $this->addSql($sql);

        $sql = <<<SQL
TRUNCATE TABLE `ot_building`
SQL;
        $this->addSql($sql);
        $sql = <<<SQL
ALTER TABLE `ot_building`
DROP COLUMN `location_x`,
DROP COLUMN `location_y`,
DROP COLUMN `username`,
ADD COLUMN `city_id` int(11) NOT NULL,
ADD CONSTRAINT `FK_city` FOREIGN KEY (`city_id`) REFERENCES `ot_city` (`id`) ON UPDATE CASCADE ON DELETE CASCADE, 
ADD CONSTRAINT `UNQ_clot_in_city` UNIQUE (`slot`,`city_id`)
SQL;
        $this->addSql($sql);
    }

    public function down(Schema $schema): void
    {
        $sql = <<<SQL
DROP TABLE IF EXISTS `ot_city`
SQL;

        $this->addSql($sql);

        $sql = <<<SQL
ALTER TABLE `ot_building` RENAME `ot_buildings`
SQL;

        $this->addSql($sql);

        $sql = <<<SQL
ALTER TABLE `ot_buildings`
DROP KEY `UNQ_clot_in_city`
DROP FOREIGN KEY `city_id`
DROP COLUMN `city_id`,
ADD `location_x` INT(10) DEFAULT 0 NOT NULL,
ADD `location_y` INT(10) DEFAULT 0 NOT NULL,
ADD `username` VARCHAR(255) DEFAULT '' NOT NULL
SQL;

        $this->addSql($sql);
    }
}
