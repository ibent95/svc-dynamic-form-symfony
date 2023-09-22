<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230922040553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication_meta CHANGE id_publication_form id_form BIGINT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE publication_meta ADD CONSTRAINT FK_DC0A95F8ADB1C68A FOREIGN KEY (id_form_version) REFERENCES publication_form_version (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication_meta ADD CONSTRAINT FK_DC0A95F8D9A8E14D FOREIGN KEY (id_form) REFERENCES publication_form (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_DC0A95F8D9A8E14D ON publication_meta (id_form)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication_meta DROP FOREIGN KEY FK_DC0A95F8ADB1C68A');
        $this->addSql('ALTER TABLE publication_meta DROP FOREIGN KEY FK_DC0A95F8D9A8E14D');
        $this->addSql('DROP INDEX IDX_DC0A95F8D9A8E14D ON publication_meta');
        $this->addSql('ALTER TABLE publication_meta CHANGE id_form id_publication_form BIGINT UNSIGNED NOT NULL');
    }
}
