<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220430043557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication DROP INDEX UNIQ_AF3C6779A7D25F7B, ADD INDEX IDX_AF3C6779A7D25F7B (publication_general_type_id)');
        $this->addSql('ALTER TABLE publication DROP INDEX UNIQ_AF3C6779CDC55AAF, ADD INDEX IDX_AF3C6779CDC55AAF (publication_type_id)');
        $this->addSql('ALTER TABLE publication DROP INDEX UNIQ_AF3C677951C5150D, ADD INDEX IDX_AF3C677951C5150D (publication_form_version_id)');
        $this->addSql('ALTER TABLE publication DROP INDEX UNIQ_AF3C677977B4062A, ADD INDEX IDX_AF3C677977B4062A (publication_status_id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779A7D25F7B FOREIGN KEY (publication_general_type_id) REFERENCES publication_general_type (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779CDC55AAF FOREIGN KEY (publication_type_id) REFERENCES publication_type (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C677951C5150D FOREIGN KEY (publication_form_version_id) REFERENCES publication_form_version (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C677977B4062A FOREIGN KEY (publication_status_id) REFERENCES publication_status (id)');
        $this->addSql('ALTER TABLE publication_form ADD field_configs LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD order_position INT DEFAULT NULL, CHANGE validation_config validation_config LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE publication_form_version ADD CONSTRAINT FK_CFA3AA3DCDC55AAF FOREIGN KEY (publication_type_id) REFERENCES publication_type (id)');
        $this->addSql('ALTER TABLE publication_meta ADD CONSTRAINT FK_DC0A95F838B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE publication_type ADD CONSTRAINT FK_8726D6E4A7D25F7B FOREIGN KEY (publication_general_type_id) REFERENCES publication_general_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication DROP INDEX IDX_AF3C6779A7D25F7B, ADD UNIQUE INDEX UNIQ_AF3C6779A7D25F7B (publication_general_type_id)');
        $this->addSql('ALTER TABLE publication DROP INDEX IDX_AF3C6779CDC55AAF, ADD UNIQUE INDEX UNIQ_AF3C6779CDC55AAF (publication_type_id)');
        $this->addSql('ALTER TABLE publication DROP INDEX IDX_AF3C677951C5150D, ADD UNIQUE INDEX UNIQ_AF3C677951C5150D (publication_form_version_id)');
        $this->addSql('ALTER TABLE publication DROP INDEX IDX_AF3C677977B4062A, ADD UNIQUE INDEX UNIQ_AF3C677977B4062A (publication_status_id)');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779A7D25F7B');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779CDC55AAF');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C677951C5150D');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C677977B4062A');
        $this->addSql('ALTER TABLE publication CHANGE title title VARCHAR(500) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE created_user created_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE updated_user updated_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE uuid uuid CHAR(36) NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE publication_form DROP field_configs, DROP order_position, CHANGE field_label field_label VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE field_type field_type VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE field_name field_name VARCHAR(100) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE field_id field_id VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE field_class field_class VARCHAR(100) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE field_placeholder field_placeholder VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE field_options field_options VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE validation_config validation_config VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE error_message error_message VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE dependency_child dependency_child LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE dependency_parent dependency_parent LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE created_user created_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE updated_user updated_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE uuid uuid CHAR(36) NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE publication_form_version DROP FOREIGN KEY FK_CFA3AA3DCDC55AAF');
        $this->addSql('ALTER TABLE publication_form_version CHANGE publication_form_version_name publication_form_version_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE publication_form_version_code publication_form_version_code VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE created_user created_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE updated_user updated_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE uuid uuid CHAR(36) NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE publication_general_type CHANGE publication_general_type_name publication_general_type_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE publication_general_type_code publication_general_type_code VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE created_user created_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE updated_user updated_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE uuid uuid CHAR(36) NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE publication_meta DROP FOREIGN KEY FK_DC0A95F838B217A7');
        $this->addSql('ALTER TABLE publication_meta CHANGE field_name field_name VARCHAR(150) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE field_value field_value VARCHAR(1000) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE created_user created_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE updated_user updated_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE uuid uuid CHAR(36) NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE publication_status CHANGE publication_status_name publication_status_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE publication_status_code publication_status_code VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE created_user created_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE updated_user updated_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE uuid uuid CHAR(36) NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE publication_type DROP FOREIGN KEY FK_8726D6E4A7D25F7B');
        $this->addSql('ALTER TABLE publication_type CHANGE publication_type_name publication_type_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE publication_type_code publication_type_code VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE created_user created_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE updated_user updated_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE uuid uuid CHAR(36) NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
    }
}
