<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230909143732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE publication (id BIGINT UNSIGNED NOT NULL, id_publication_general_type BIGINT UNSIGNED DEFAULT NULL, id_publication_type BIGINT UNSIGNED DEFAULT NULL, id_publication_form_version BIGINT UNSIGNED DEFAULT NULL, id_publication_status BIGINT UNSIGNED DEFAULT NULL, title VARCHAR(500) DEFAULT NULL, publication_date DATETIME DEFAULT NULL, flag_active TINYINT(1) DEFAULT 1 NOT NULL, create_user VARCHAR(50) DEFAULT \'system\' NOT NULL, created_at DATETIME NOT NULL, update_user VARCHAR(50) DEFAULT \'system\' NOT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_AF3C67795ECB64EE (id_publication_general_type), INDEX IDX_AF3C677964AC8335 (id_publication_type), INDEX IDX_AF3C6779A4A6901E (id_publication_form_version), INDEX IDX_AF3C67791CC9C6FE (id_publication_status), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication_form (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, id_form_version BIGINT UNSIGNED DEFAULT NULL, id_form_parent BIGINT DEFAULT NULL, field_label VARCHAR(255) DEFAULT NULL, field_type VARCHAR(100) NOT NULL, field_name VARCHAR(100) DEFAULT NULL, field_id VARCHAR(100) NOT NULL, field_class VARCHAR(100) DEFAULT NULL, field_placeholder VARCHAR(255) DEFAULT NULL, field_options VARCHAR(255) DEFAULT NULL, field_configs LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', description VARCHAR(255) DEFAULT NULL, order_position INT DEFAULT NULL, validation_configs LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', error_message VARCHAR(255) DEFAULT NULL, dependency_child LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', dependency_parent LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', flag_required TINYINT(1) DEFAULT 0 NOT NULL, flag_field_form_type TINYINT(1) DEFAULT 0 NOT NULL, flag_field_title TINYINT(1) DEFAULT 0 NOT NULL, flag_field_publish_date TINYINT(1) DEFAULT 0 NOT NULL, flag_active TINYINT(1) DEFAULT 1 NOT NULL, create_user VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, update_user VARCHAR(50) DEFAULT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_59707C82ADB1C68A (id_form_version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication_form_version (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, id_publication_type BIGINT UNSIGNED NOT NULL, publication_form_version_name VARCHAR(255) NOT NULL, publication_form_version_code VARCHAR(50) NOT NULL, grid_system LONGTEXT DEFAULT \'{"type":"no_grid_system","cols":12,"config":{}}\' COMMENT \'(DC2Type:json)\', flag_active TINYINT(1) DEFAULT 1 NOT NULL, create_user VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, update_user VARCHAR(50) DEFAULT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_CFA3AA3D64AC8335 (id_publication_type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication_general_type (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, publication_general_type_name VARCHAR(255) NOT NULL, publication_general_type_code VARCHAR(100) NOT NULL, flag_active TINYINT(1) DEFAULT 1 NOT NULL, create_user VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, update_user VARCHAR(50) DEFAULT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication_meta (id BIGINT UNSIGNED NOT NULL, id_publication BIGINT UNSIGNED NOT NULL, id_form_version BIGINT UNSIGNED DEFAULT NULL, id_form_parent BIGINT UNSIGNED DEFAULT NULL, field_label VARCHAR(255) DEFAULT NULL, field_type VARCHAR(100) NOT NULL, field_name VARCHAR(100) DEFAULT NULL, field_id VARCHAR(100) NOT NULL, field_class VARCHAR(100) DEFAULT NULL, field_placeholder VARCHAR(255) DEFAULT NULL, field_options VARCHAR(255) DEFAULT NULL, field_configs LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', description VARCHAR(255) DEFAULT NULL, order_position INT DEFAULT NULL, validation_configs LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', error_message VARCHAR(255) DEFAULT NULL, dependency_child LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', dependency_parent LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', flag_required TINYINT(1) DEFAULT 0 NOT NULL, flag_field_form_type TINYINT(1) DEFAULT 0 NOT NULL, flag_field_title TINYINT(1) DEFAULT 0 NOT NULL, flag_field_publish_date TINYINT(1) DEFAULT 0 NOT NULL, value TEXT DEFAULT NULL, other_value LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', flag_active TINYINT(1) DEFAULT 1 NOT NULL, create_user VARCHAR(50) DEFAULT \'system\' NOT NULL, created_at DATETIME NOT NULL, update_user VARCHAR(50) DEFAULT \'system\' NOT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_DC0A95F8B72EAA8E (id_publication), INDEX IDX_DC0A95F8ADB1C68A (id_form_version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication_status (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, publication_status_name VARCHAR(255) NOT NULL, publication_status_code VARCHAR(50) NOT NULL, flag_active TINYINT(1) DEFAULT 1 NOT NULL, create_user VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, update_user VARCHAR(50) DEFAULT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication_type (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, id_publication_general_type BIGINT UNSIGNED NOT NULL, publication_type_name VARCHAR(255) NOT NULL, publication_type_code VARCHAR(50) NOT NULL, flag_active TINYINT(1) DEFAULT 1 NOT NULL, create_user VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, update_user VARCHAR(50) DEFAULT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_8726D6E45ECB64EE (id_publication_general_type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C67795ECB64EE FOREIGN KEY (id_publication_general_type) REFERENCES publication_general_type (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C677964AC8335 FOREIGN KEY (id_publication_type) REFERENCES publication_type (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779A4A6901E FOREIGN KEY (id_publication_form_version) REFERENCES publication_form_version (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C67791CC9C6FE FOREIGN KEY (id_publication_status) REFERENCES publication_status (id)');
        $this->addSql('ALTER TABLE publication_form ADD CONSTRAINT FK_59707C82ADB1C68A FOREIGN KEY (id_form_version) REFERENCES publication_form_version (id)');
        $this->addSql('ALTER TABLE publication_form_version ADD CONSTRAINT FK_CFA3AA3D64AC8335 FOREIGN KEY (id_publication_type) REFERENCES publication_type (id)');
        $this->addSql('ALTER TABLE publication_meta ADD CONSTRAINT FK_DC0A95F8B72EAA8E FOREIGN KEY (id_publication) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE publication_meta ADD CONSTRAINT FK_DC0A95F8ADB1C68A FOREIGN KEY (id_form_version) REFERENCES publication_form_version (id)');
        $this->addSql('ALTER TABLE publication_type ADD CONSTRAINT FK_8726D6E45ECB64EE FOREIGN KEY (id_publication_general_type) REFERENCES publication_general_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C67795ECB64EE');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C677964AC8335');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779A4A6901E');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C67791CC9C6FE');
        $this->addSql('ALTER TABLE publication_form DROP FOREIGN KEY FK_59707C82ADB1C68A');
        $this->addSql('ALTER TABLE publication_form_version DROP FOREIGN KEY FK_CFA3AA3D64AC8335');
        $this->addSql('ALTER TABLE publication_meta DROP FOREIGN KEY FK_DC0A95F8B72EAA8E');
        $this->addSql('ALTER TABLE publication_meta DROP FOREIGN KEY FK_DC0A95F8ADB1C68A');
        $this->addSql('ALTER TABLE publication_type DROP FOREIGN KEY FK_8726D6E45ECB64EE');
        $this->addSql('DROP TABLE publication');
        $this->addSql('DROP TABLE publication_form');
        $this->addSql('DROP TABLE publication_form_version');
        $this->addSql('DROP TABLE publication_general_type');
        $this->addSql('DROP TABLE publication_meta');
        $this->addSql('DROP TABLE publication_status');
        $this->addSql('DROP TABLE publication_type');
    }
}
