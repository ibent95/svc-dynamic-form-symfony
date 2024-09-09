<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240907063837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE try01');
        $this->addSql('ALTER TABLE dynamic_form_field_type ADD dynamic_form_field_dependency_parent_configs JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD dynamic_form_field_dependency_child_configs JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'; ALTER TABLE dynamic_form_app.dynamic_form_field_type CHANGE dynamic_form_field_dependency_parent_configs dynamic_form_field_dependency_parent_configs longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL NULL COMMENT \'(DC2Type:json)\' AFTER dynamic_form_field_validation_configs; ALTER TABLE dynamic_form_app.dynamic_form_field_type CHANGE dynamic_form_field_dependency_child_configs dynamic_form_field_dependency_child_configs longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL NULL COMMENT \'(DC2Type:json)\' AFTER dynamic_form_field_dependency_parent_configs;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE try01 (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, uuid CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE dynamic_form_field_type DROP dynamic_form_field_dependency_parent_configs, DROP dynamic_form_field_dependency_child_configs');
    }
}
