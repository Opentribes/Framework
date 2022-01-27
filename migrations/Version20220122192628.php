<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220122192628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $sql = <<<SQL
CREATE TABLE `ot_buildings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `slot` varchar(255) NOT NULL,
  `level` int(11) NOT NULL DEFAULT 1,
  `status` ENUM('upgrading', 'downgrading', 'default') NOT NULL DEFAULT 'default',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4
SQL;

       $this->addSql($sql);
    }

    public function down(Schema $schema): void
    {
      $sql = <<<SQL
DROP TABLE `ot_buildings`
SQL;
        $this->addSql($sql);
    }
}
