<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240908104852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dynamic_form_taxonomy (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, dynamic_form_taxonomy VARCHAR(255) NOT NULL, dynamic_form_taxonomy_code VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, flag_active TINYINT(1) NOT NULL, create_user VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, update_user VARCHAR(50) NOT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dynamic_form_taxonomy_term (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, dynamic_form_taxonomy_id BIGINT UNSIGNED DEFAULT NULL, dynamic_form_taxonomy_term VARCHAR(255) NOT NULL, dynamic_form_taxonomy_term_code VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, flag_active TINYINT(1) NOT NULL, create_user VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, update_user VARCHAR(50) NOT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_C5C708A7D90F5A18 (dynamic_form_taxonomy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dynamic_form_taxonomy_term ADD CONSTRAINT FK_C5C708A7D90F5A18 FOREIGN KEY (dynamic_form_taxonomy_id) REFERENCES dynamic_form_taxonomy (id)');
        $this->addSql('ALTER TABLE dynamic_form_field_type CHANGE dynamic_form_field_dependency_parent_configs dynamic_form_field_dependency_parent_configs JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE dynamic_form_field_dependency_child_configs dynamic_form_field_dependency_child_configs JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dynamic_form_taxonomy_term DROP FOREIGN KEY FK_C5C708A7D90F5A18');
        $this->addSql('DROP TABLE dynamic_form_taxonomy');
        $this->addSql('DROP TABLE dynamic_form_taxonomy_term');
        $this->addSql('ALTER TABLE dynamic_form_field_type CHANGE dynamic_form_field_dependency_parent_configs dynamic_form_field_dependency_parent_configs JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE dynamic_form_field_dependency_child_configs dynamic_form_field_dependency_child_configs JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
    }
}
