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
        $this->addSql('ALTER TABLE dynamic_form_field_type ADD dynamic_form_field_dependency_parent_configs JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD dynamic_form_field_dependency_child_configs JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'; ALTER TABLE dynamic_form_app.dynamic_form_field_type CHANGE dynamic_form_field_dependency_parent_configs dynamic_form_field_dependency_parent_configs longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL NULL COMMENT \'(DC2Type:json)\' AFTER dynamic_form_field_validation_configs; ALTER TABLE dynamic_form_app.dynamic_form_field_type CHANGE dynamic_form_field_dependency_child_configs dynamic_form_field_dependency_child_configs longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL NULL COMMENT \'(DC2Type:json)\' AFTER dynamic_form_field_dependency_parent_configs;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dynamic_form_field_type DROP dynamic_form_field_dependency_parent_configs, DROP dynamic_form_field_dependency_child_configs');
    }
}
