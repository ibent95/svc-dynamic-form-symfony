<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220412143559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE publication_form (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, publication_form_version_id BIGINT UNSIGNED DEFAULT NULL, form_version_id BIGINT NOT NULL, form_parent_id BIGINT DEFAULT NULL, field_label VARCHAR(255) DEFAULT NULL, field_type VARCHAR(100) NOT NULL, field_name VARCHAR(100) DEFAULT NULL, field_id VARCHAR(100) NOT NULL, field_class VARCHAR(100) DEFAULT NULL, field_placeholder VARCHAR(255) DEFAULT NULL, field_options VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, error_message VARCHAR(255) DEFAULT NULL, validation_config VARCHAR(255) DEFAULT NULL, dependency_child LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', dependency_parent LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', flag_required TINYINT(1) DEFAULT 0 NOT NULL, flag_active TINYINT(1) DEFAULT 1 NOT NULL, created_user VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, updated_user VARCHAR(50) DEFAULT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_59707C8251C5150D (publication_form_version_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE publication_form ADD CONSTRAINT FK_59707C8251C5150D FOREIGN KEY (publication_form_version_id) REFERENCES publication_form_version (id)');
        $this->addSql('DROP TABLE form');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE form (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, publication_form_version_id BIGINT UNSIGNED DEFAULT NULL, form_version_id BIGINT NOT NULL, form_parent_id BIGINT DEFAULT NULL, field_label VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, field_type VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, field_name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, field_id VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, field_class VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, field_placeholder VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, field_options VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, error_message VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, validation_config VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, dependency_child LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', dependency_parent LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', flag_required TINYINT(1) DEFAULT 0 NOT NULL, flag_active TINYINT(1) DEFAULT 1 NOT NULL, created_user VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_user VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, updated_at DATETIME NOT NULL, uuid CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\', INDEX IDX_5288FD4F51C5150D (publication_form_version_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE form ADD CONSTRAINT FK_5288FD4F51C5150D FOREIGN KEY (publication_form_version_id) REFERENCES publication_form_version (id)');
        $this->addSql('DROP TABLE publication_form');
        $this->addSql('ALTER TABLE publication CHANGE title title VARCHAR(500) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE created_user created_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE updated_user updated_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE uuid uuid CHAR(36) NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE publication_form_version CHANGE publication_form_version_name publication_form_version_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE publication_form_version_code publication_form_version_code VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE created_user created_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE updated_user updated_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE uuid uuid CHAR(36) NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE publication_general_type CHANGE publication_general_type_name publication_general_type_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE publication_general_type_code publication_general_type_code VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE created_user created_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE updated_user updated_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE uuid uuid CHAR(36) NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE publication_meta CHANGE field_name field_name VARCHAR(150) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE field_value field_value VARCHAR(1000) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE created_user created_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE updated_user updated_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE uuid uuid CHAR(36) NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE publication_status CHANGE publication_status_name publication_status_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE publication_status_code publication_status_code VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE created_user created_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE updated_user updated_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE uuid uuid CHAR(36) NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE publication_type CHANGE publication_type_name publication_type_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE publication_type_code publication_type_code VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE created_user created_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE updated_user updated_user VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE uuid uuid CHAR(36) NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
    }
}
