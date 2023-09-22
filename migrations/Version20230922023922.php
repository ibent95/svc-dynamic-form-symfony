<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230922023922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication_meta DROP FOREIGN KEY FK_DC0A95F8ADB1C68A');
        $this->addSql('ALTER TABLE publication_meta ADD id_publication_form BIGINT UNSIGNED NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication_meta DROP id_publication_form');
        $this->addSql('ALTER TABLE publication_meta ADD CONSTRAINT FK_DC0A95F8ADB1C68A FOREIGN KEY (id_form_version) REFERENCES publication_form_version (id) ON DELETE CASCADE');
    }
}
