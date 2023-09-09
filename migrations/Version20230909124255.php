<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230909124255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication CHANGE create_user create_user VARCHAR(50) DEFAULT \'system\' NOT NULL, CHANGE update_user update_user VARCHAR(50) DEFAULT \'system\' NOT NULL');
        $this->addSql('ALTER TABLE publication_meta CHANGE create_user create_user VARCHAR(50) DEFAULT \'system\' NOT NULL, CHANGE update_user update_user VARCHAR(50) DEFAULT \'system\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication CHANGE create_user create_user VARCHAR(50) DEFAULT \'system\', CHANGE update_user update_user VARCHAR(50) DEFAULT \'system\'');
        $this->addSql('ALTER TABLE publication_meta CHANGE create_user create_user VARCHAR(50) DEFAULT \'system\', CHANGE update_user update_user VARCHAR(50) DEFAULT \'system\'');
    }
}
