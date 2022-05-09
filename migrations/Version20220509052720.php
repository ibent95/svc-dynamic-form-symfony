<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220509052720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication_form_version CHANGE grid_system grid_system LONGTEXT DEFAULT \'[\'\'type\'\':\'\'no_grid_system\'\',\'\'cols\'\':12,\'\'config\'\':[]]\' COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication_form_version CHANGE grid_system grid_system LONGTEXT DEFAULT \'\'\'[\\\\\'\'type\\\\\'\':\\\\\'\'no_grid_system\\\\\'\',\\\\\'\'cols\\\\\'\':12,\\\\\'\'config\\\\\'\':[]]\'\'\' COMMENT \'(DC2Type:json)\'');
    }
}
