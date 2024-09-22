<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240910160559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication_form CHANGE id_form_parent id_form_parent BIGINT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE publication_form ADD CONSTRAINT FK_59707C8227727916 FOREIGN KEY (id_form_parent) REFERENCES publication_form (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_59707C82D17F50A6 ON publication_form (uuid)');
        $this->addSql('CREATE INDEX IDX_59707C8227727916 ON publication_form (id_form_parent)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication_form DROP FOREIGN KEY FK_59707C8227727916');
        $this->addSql('DROP INDEX UNIQ_59707C82D17F50A6 ON publication_form');
        $this->addSql('DROP INDEX IDX_59707C8227727916 ON publication_form');
        $this->addSql('ALTER TABLE publication_form CHANGE id_form_parent id_form_parent BIGINT DEFAULT NULL');
    }
}
