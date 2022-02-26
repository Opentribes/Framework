<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220107211007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial Tables for Sulu CMS';
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function up(Schema $schema): void
    {
        $sql = $this->createTables();
        $sql .= $this->getMixedTables();
        $sql .= $this->getCoTables();
        $sql .= $this->getMeTables();
        $sql .= $this->getSeTables();
        $sql .= <<<SQL
   ALTER TABLE ca_keywords CHANGE idUsersCreator idUsersCreator INT DEFAULT NULL, CHANGE idUsersChanger idUsersChanger INT DEFAULT NULL;
     ALTER TABLE ca_categories CHANGE category_key category_key VARCHAR(191) DEFAULT NULL, CHANGE idCategoriesParent idCategoriesParent INT DEFAULT NULL, CHANGE idUsersCreator idUsersCreator INT DEFAULT NULL, CHANGE idUsersChanger idUsersChanger INT DEFAULT NULL;
     ALTER TABLE ca_category_meta CHANGE locale locale VARCHAR(5) DEFAULT NULL;
     ALTER TABLE ca_category_translations CHANGE idUsersCreator idUsersCreator INT DEFAULT NULL, CHANGE idUsersChanger idUsersChanger INT DEFAULT NULL;
     ALTER TABLE ta_tags CHANGE idUsersCreator idUsersCreator INT DEFAULT NULL, CHANGE idUsersChanger idUsersChanger INT DEFAULT NULL;
     ALTER TABLE we_analytics CHANGE content content JSON NOT NULL;
     ALTER TABLE ro_routes CHANGE target_id target_id INT DEFAULT NULL, CHANGE idUsersCreator idUsersCreator INT DEFAULT NULL, CHANGE idUsersChanger idUsersChanger INT DEFAULT NULL;
     ALTER TABLE phpcr_nodes CHANGE sort_order sort_order INT DEFAULT NULL;
     ALTER TABLE phpcr_type_nodes CHANGE primary_item primary_item VARCHAR(255) DEFAULT NULL;
     ALTER TABLE phpcr_type_props CHANGE default_value default_value VARCHAR(255) DEFAULT NULL;
     ALTER TABLE phpcr_type_childs CHANGE default_type default_type VARCHAR(255) DEFAULT NULL;

SQL;
        $this->addSql($sql);
    }
    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS com_blacklist_item');
        $this->addSql('DROP TABLE IF EXISTS com_blacklist_user');
        $this->addSql('DROP TABLE IF EXISTS com_email_token');
    }
    private function getCoTables(): string
    {
        return <<<SQL
     ALTER TABLE co_contacts CHANGE avatar avatar INT DEFAULT NULL, CHANGE middleName middleName VARCHAR(60) DEFAULT NULL, CHANGE birthday birthday DATE DEFAULT NULL, CHANGE salutation salutation VARCHAR(255) DEFAULT NULL, CHANGE formOfAddress formOfAddress INT DEFAULT NULL, CHANGE newsletter newsletter TINYINT(1) DEFAULT NULL, CHANGE gender gender VARCHAR(1) DEFAULT NULL, CHANGE mainEmail mainEmail VARCHAR(255) DEFAULT NULL, CHANGE mainPhone mainPhone VARCHAR(255) DEFAULT NULL, CHANGE mainFax mainFax VARCHAR(255) DEFAULT NULL, CHANGE mainUrl mainUrl VARCHAR(255) DEFAULT NULL, CHANGE idTitles idTitles INT DEFAULT NULL, CHANGE idUsersCreator idUsersCreator INT DEFAULT NULL, CHANGE idUsersChanger idUsersChanger INT DEFAULT NULL;
     ALTER TABLE co_bank_account CHANGE bankName bankName VARCHAR(150) DEFAULT NULL, CHANGE bic bic VARCHAR(100) DEFAULT NULL;
     ALTER TABLE co_account_contacts CHANGE idPositions idPositions INT DEFAULT NULL;
     ALTER TABLE co_addresses CHANGE title title VARCHAR(250) DEFAULT NULL, CHANGE primaryAddress primaryAddress TINYINT(1) DEFAULT NULL, CHANGE deliveryAddress deliveryAddress TINYINT(1) DEFAULT NULL, CHANGE billingAddress billingAddress TINYINT(1) DEFAULT NULL, CHANGE street street VARCHAR(150) DEFAULT NULL, CHANGE number number VARCHAR(60) DEFAULT NULL, CHANGE addition addition VARCHAR(60) DEFAULT NULL, CHANGE zip zip VARCHAR(60) DEFAULT NULL, CHANGE city city VARCHAR(60) DEFAULT NULL, CHANGE state state VARCHAR(60) DEFAULT NULL, CHANGE countryCode countryCode VARCHAR(5) DEFAULT NULL, CHANGE postboxNumber postboxNumber VARCHAR(100) DEFAULT NULL, CHANGE postboxPostcode postboxPostcode VARCHAR(100) DEFAULT NULL, CHANGE postboxCity postboxCity VARCHAR(100) DEFAULT NULL, CHANGE latitude latitude DOUBLE PRECISION DEFAULT NULL, CHANGE longitude longitude DOUBLE PRECISION DEFAULT NULL;
     ALTER TABLE co_accounts CHANGE logo logo INT DEFAULT NULL, CHANGE externalId externalId VARCHAR(255) DEFAULT NULL, CHANGE number number VARCHAR(255) DEFAULT NULL, CHANGE corporation corporation VARCHAR(255) DEFAULT NULL, CHANGE uid uid VARCHAR(50) DEFAULT NULL, CHANGE registerNumber registerNumber VARCHAR(255) DEFAULT NULL, CHANGE placeOfJurisdiction placeOfJurisdiction VARCHAR(255) DEFAULT NULL, CHANGE mainEmail mainEmail VARCHAR(255) DEFAULT NULL, CHANGE mainPhone mainPhone VARCHAR(255) DEFAULT NULL, CHANGE mainFax mainFax VARCHAR(255) DEFAULT NULL, CHANGE mainUrl mainUrl VARCHAR(255) DEFAULT NULL, CHANGE idContactsMain idContactsMain INT DEFAULT NULL, CHANGE idAccountsParent idAccountsParent INT DEFAULT NULL, CHANGE idUsersCreator idUsersCreator INT DEFAULT NULL, CHANGE idUsersChanger idUsersChanger INT DEFAULT NULL;
 
SQL;
    }
    private function getSeTables(): string
    {
        return <<<SQL
   ALTER TABLE se_user_roles CHANGE idUsers idUsers INT DEFAULT NULL, CHANGE idRoles idRoles INT DEFAULT NULL;
     ALTER TABLE se_access_controls CHANGE entityIdInteger entityIdInteger INT DEFAULT NULL;
     ALTER TABLE se_roles CHANGE role_key role_key VARCHAR(60) DEFAULT NULL, CHANGE idSecurityTypes idSecurityTypes INT DEFAULT NULL, CHANGE idUsersCreator idUsersCreator INT DEFAULT NULL, CHANGE idUsersChanger idUsersChanger INT DEFAULT NULL;
     ALTER TABLE se_users CHANGE lastLogin lastLogin DATETIME DEFAULT NULL, CHANGE confirmationKey confirmationKey VARCHAR(128) DEFAULT NULL, CHANGE passwordResetToken passwordResetToken VARCHAR(80) DEFAULT NULL, CHANGE passwordResetTokenExpiresAt passwordResetTokenExpiresAt DATETIME DEFAULT NULL, CHANGE privateKey privateKey VARCHAR(2000) DEFAULT NULL, CHANGE apiKey apiKey CHAR(36) DEFAULT NULL COMMENT '(DC2Type:guid)', CHANGE email email VARCHAR(191) DEFAULT NULL, CHANGE idUsersCreator idUsersCreator INT DEFAULT NULL, CHANGE idUsersChanger idUsersChanger INT DEFAULT NULL;
     ALTER TABLE se_permissions CHANGE module module VARCHAR(60) DEFAULT NULL, CHANGE idRoles idRoles INT DEFAULT NULL;
     ALTER TABLE se_user_groups CHANGE idGroups idGroups INT DEFAULT NULL, CHANGE idUsers idUsers INT DEFAULT NULL;
     ALTER TABLE se_groups CHANGE idGroupsParent idGroupsParent INT DEFAULT NULL, CHANGE idUsersCreator idUsersCreator INT DEFAULT NULL, CHANGE idUsersChanger idUsersChanger INT DEFAULT NULL;
     ALTER TABLE se_role_settings CHANGE value value JSON NOT NULL, CHANGE roleId roleId INT DEFAULT NULL;
   
SQL;
    }
    private function createTables(): string
    {
        return <<<SQL
   CREATE TABLE IF NOT EXISTS com_blacklist_item (id INT AUTO_INCREMENT NOT NULL, pattern VARCHAR(191) NOT NULL, regexpPattern VARCHAR(191) NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_23CBBF74A3BCFC8E (pattern), INDEX IDX_23CBBF743D4A32EB (regexpPattern), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
     CREATE TABLE IF NOT EXISTS com_blacklist_user (id INT AUTO_INCREMENT NOT NULL, token VARCHAR(191) DEFAULT NULL, type INT NOT NULL, webspaceKey VARCHAR(255) NOT NULL, userId INT DEFAULT NULL, UNIQUE INDEX UNIQ_B1434C235F37A13B (token), UNIQUE INDEX UNIQ_B1434C2364B64DCC (userId), INDEX IDX_B1434C235F37A13B (token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
     CREATE TABLE IF NOT EXISTS com_email_token (id INT AUTO_INCREMENT NOT NULL, token VARCHAR(191) NOT NULL, userId INT DEFAULT NULL, UNIQUE INDEX UNIQ_F01439A75F37A13B (token), UNIQUE INDEX UNIQ_F01439A764B64DCC (userId), INDEX IDX_F01439A75F37A13B (token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
  
SQL;
    }
    private function getMixedTables(): string
    {
        return <<<SQL
    ALTER TABLE com_blacklist_user ADD CONSTRAINT FK_B1434C2364B64DCC FOREIGN KEY (userId) REFERENCES se_users (id) ON DELETE CASCADE;
     ALTER TABLE com_email_token ADD CONSTRAINT FK_F01439A764B64DCC FOREIGN KEY (userId) REFERENCES se_users (id) ON DELETE CASCADE;
     ALTER TABLE tr_trash_items CHANGE restoreData restoreData JSON NOT NULL, CHANGE restoreType restoreType VARCHAR(191) DEFAULT NULL, CHANGE restoreOptions restoreOptions JSON NOT NULL, CHANGE resourceSecurityContext resourceSecurityContext VARCHAR(191) DEFAULT NULL, CHANGE resourceSecurityObjectType resourceSecurityObjectType VARCHAR(191) DEFAULT NULL, CHANGE resourceSecurityObjectId resourceSecurityObjectId VARCHAR(191) DEFAULT NULL, CHANGE defaultLocale defaultLocale VARCHAR(191) DEFAULT NULL, CHANGE userId userId INT DEFAULT NULL;
     ALTER TABLE tr_trash_item_translations CHANGE locale locale VARCHAR(191) DEFAULT NULL;
     ALTER TABLE ac_activities CHANGE context context JSON NOT NULL, CHANGE batch batch VARCHAR(191) DEFAULT NULL, CHANGE payload payload JSON DEFAULT NULL, CHANGE resourceLocale resourceLocale VARCHAR(191) DEFAULT NULL, CHANGE resourceWebspaceKey resourceWebspaceKey VARCHAR(191) DEFAULT NULL, CHANGE resourceTitle resourceTitle VARCHAR(191) DEFAULT NULL, CHANGE resourceTitleLocale resourceTitleLocale VARCHAR(191) DEFAULT NULL, CHANGE resourceSecurityContext resourceSecurityContext VARCHAR(191) DEFAULT NULL, CHANGE resourceSecurityObjectType resourceSecurityObjectType VARCHAR(191) DEFAULT NULL, CHANGE resourceSecurityObjectId resourceSecurityObjectId VARCHAR(191) DEFAULT NULL, CHANGE userId userId INT DEFAULT NULL;
     ALTER TABLE pr_preview_links CHANGE options options JSON NOT NULL, CHANGE lastVisit lastVisit DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)';
     
SQL;
    }
    private function getMeTables(): string
    {
        return <<<SQL
     ALTER TABLE me_collections CHANGE style style VARCHAR(255) DEFAULT NULL, CHANGE collection_key collection_key VARCHAR(191) DEFAULT NULL, CHANGE idCollectionsMetaDefault idCollectionsMetaDefault INT DEFAULT NULL, CHANGE idCollectionsParent idCollectionsParent INT DEFAULT NULL, CHANGE idUsersCreator idUsersCreator INT DEFAULT NULL, CHANGE idUsersChanger idUsersChanger INT DEFAULT NULL;
     ALTER TABLE me_file_versions CHANGE mimeType mimeType VARCHAR(191) DEFAULT NULL, CHANGE properties properties VARCHAR(1000) DEFAULT NULL, CHANGE focusPointX focusPointX INT DEFAULT NULL, CHANGE focusPointY focusPointY INT DEFAULT NULL, CHANGE idFileVersionsMetaDefault idFileVersionsMetaDefault INT DEFAULT NULL, CHANGE idFiles idFiles INT DEFAULT NULL, CHANGE idUsersCreator idUsersCreator INT DEFAULT NULL, CHANGE idUsersChanger idUsersChanger INT DEFAULT NULL;
     ALTER TABLE me_file_version_publish_languages CHANGE idFileVersions idFileVersions INT DEFAULT NULL;
     ALTER TABLE me_files CHANGE idUsersCreator idUsersCreator INT DEFAULT NULL, CHANGE idUsersChanger idUsersChanger INT DEFAULT NULL;
     ALTER TABLE me_file_version_content_languages CHANGE idFileVersions idFileVersions INT DEFAULT NULL;
     ALTER TABLE me_collection_types CHANGE collection_type_key collection_type_key VARCHAR(191) DEFAULT NULL;
     ALTER TABLE me_media CHANGE idPreviewImage idPreviewImage INT DEFAULT NULL, CHANGE idUsersCreator idUsersCreator INT DEFAULT NULL, CHANGE idUsersChanger idUsersChanger INT DEFAULT NULL;
SQL;
    }
}
