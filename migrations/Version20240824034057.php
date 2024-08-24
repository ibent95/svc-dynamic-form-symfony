<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240824034057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dynamic_form_field_options (id INT AUTO_INCREMENT NOT NULL, dynamic_form_field_options_type VARCHAR(15) NOT NULL, dynamic_form_field_options_code VARCHAR(200) NOT NULL, dynamic_form_field_options VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, flag_active TINYINT(1) NOT NULL, create_user VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, update_user VARCHAR(50) NOT NULL, updated_at DATETIME NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dynamic_form_field_type (id INT AUTO_INCREMENT NOT NULL, dynamic_form_field_type_code VARCHAR(255) NOT NULL, dynamic_form_field_type VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, flag_active TINYINT(1) NOT NULL, create_user VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, update_user VARCHAR(50) NOT NULL, updated_at DATETIME NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE dynamic_form_field_options');
        $this->addSql('DROP TABLE dynamic_form_field_type');
    }
}
