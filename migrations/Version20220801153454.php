<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220801153454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE publication (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, publication_general_type_id BIGINT UNSIGNED NOT NULL, publication_type_id BIGINT UNSIGNED NOT NULL, publication_form_version_id BIGINT UNSIGNED NOT NULL, publication_status_id BIGINT UNSIGNED NOT NULL, title VARCHAR(500) DEFAULT NULL, id_publication_general_type BIGINT NOT NULL, id_publication_type BIGINT NOT NULL, id_publication_form_version BIGINT NOT NULL, id_publication_status BIGINT NOT NULL, publication_date DATETIME DEFAULT NULL, flag_active TINYINT(1) DEFAULT 1 NOT NULL, created_user VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, updated_user VARCHAR(50) DEFAULT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_AF3C6779A7D25F7B (publication_general_type_id), INDEX IDX_AF3C6779CDC55AAF (publication_type_id), INDEX IDX_AF3C677951C5150D (publication_form_version_id), INDEX IDX_AF3C677977B4062A (publication_status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication_form (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, publication_form_version_id BIGINT UNSIGNED DEFAULT NULL, form_version_id BIGINT NOT NULL, form_parent_id BIGINT DEFAULT NULL, field_label VARCHAR(255) DEFAULT NULL, field_type VARCHAR(100) NOT NULL, field_name VARCHAR(100) DEFAULT NULL, field_id VARCHAR(100) NOT NULL, field_class VARCHAR(100) DEFAULT NULL, field_placeholder VARCHAR(255) DEFAULT NULL, field_options VARCHAR(255) DEFAULT NULL, field_config LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', description VARCHAR(255) DEFAULT NULL, order_position INT DEFAULT NULL, validation_config LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', error_message VARCHAR(255) DEFAULT NULL, dependency_child LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', dependency_parent LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', flag_required TINYINT(1) DEFAULT 0 NOT NULL, flag_active TINYINT(1) DEFAULT 1 NOT NULL, created_user VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, updated_user VARCHAR(50) DEFAULT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_59707C8251C5150D (publication_form_version_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication_form_version (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, publication_type_id BIGINT UNSIGNED DEFAULT NULL, publication_form_version_name VARCHAR(255) NOT NULL, publication_form_version_code VARCHAR(50) NOT NULL, grid_system LONGTEXT DEFAULT \'{"type":"no_grid_system","cols":12,"config":{}}\' COMMENT \'(DC2Type:json)\', flag_active TINYINT(1) DEFAULT 1 NOT NULL, created_user VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, updated_user VARCHAR(50) DEFAULT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_CFA3AA3DCDC55AAF (publication_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication_general_type (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, publication_general_type_name VARCHAR(255) NOT NULL, publication_general_type_code VARCHAR(100) NOT NULL, flag_active TINYINT(1) DEFAULT 1 NOT NULL, created_user VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, updated_user VARCHAR(50) DEFAULT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication_meta (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, publication_id BIGINT UNSIGNED DEFAULT NULL, id_publication BIGINT NOT NULL, field_name VARCHAR(150) NOT NULL, field_value VARCHAR(1000) DEFAULT NULL, flag_active TINYINT(1) DEFAULT 1 NOT NULL, created_user VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, updated_user VARCHAR(50) DEFAULT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_DC0A95F838B217A7 (publication_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication_status (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, publication_status_name VARCHAR(255) NOT NULL, publication_status_code VARCHAR(50) NOT NULL, flag_active TINYINT(1) DEFAULT 1 NOT NULL, created_user VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, updated_user VARCHAR(50) DEFAULT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication_type (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, publication_general_type_id BIGINT UNSIGNED DEFAULT NULL, publication_type_name VARCHAR(255) NOT NULL, publication_type_code VARCHAR(50) NOT NULL, id_publication_general_type BIGINT NOT NULL, flag_active TINYINT(1) DEFAULT 1 NOT NULL, created_user VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, updated_user VARCHAR(50) DEFAULT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_8726D6E4A7D25F7B (publication_general_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779A7D25F7B FOREIGN KEY (publication_general_type_id) REFERENCES publication_general_type (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779CDC55AAF FOREIGN KEY (publication_type_id) REFERENCES publication_type (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C677951C5150D FOREIGN KEY (publication_form_version_id) REFERENCES publication_form_version (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C677977B4062A FOREIGN KEY (publication_status_id) REFERENCES publication_status (id)');
        $this->addSql('ALTER TABLE publication_form ADD CONSTRAINT FK_59707C8251C5150D FOREIGN KEY (publication_form_version_id) REFERENCES publication_form_version (id)');
        $this->addSql('ALTER TABLE publication_form_version ADD CONSTRAINT FK_CFA3AA3DCDC55AAF FOREIGN KEY (publication_type_id) REFERENCES publication_type (id)');
        $this->addSql('ALTER TABLE publication_meta ADD CONSTRAINT FK_DC0A95F838B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE publication_type ADD CONSTRAINT FK_8726D6E4A7D25F7B FOREIGN KEY (publication_general_type_id) REFERENCES publication_general_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication_meta DROP FOREIGN KEY FK_DC0A95F838B217A7');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C677951C5150D');
        $this->addSql('ALTER TABLE publication_form DROP FOREIGN KEY FK_59707C8251C5150D');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779A7D25F7B');
        $this->addSql('ALTER TABLE publication_type DROP FOREIGN KEY FK_8726D6E4A7D25F7B');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C677977B4062A');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779CDC55AAF');
        $this->addSql('ALTER TABLE publication_form_version DROP FOREIGN KEY FK_CFA3AA3DCDC55AAF');
        $this->addSql('DROP TABLE publication');
        $this->addSql('DROP TABLE publication_form');
        $this->addSql('DROP TABLE publication_form_version');
        $this->addSql('DROP TABLE publication_general_type');
        $this->addSql('DROP TABLE publication_meta');
        $this->addSql('DROP TABLE publication_status');
        $this->addSql('DROP TABLE publication_type');
    }
}
