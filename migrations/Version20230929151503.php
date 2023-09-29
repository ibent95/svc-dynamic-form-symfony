<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230929151503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication_form CHANGE id id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE publication_meta ADD CONSTRAINT FK_DC0A95F8D9A8E14D FOREIGN KEY (id_form) REFERENCES publication_form (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_DC0A95F8D9A8E14D ON publication_meta (id_form)');
        $this->addSql('ALTER TABLE temporary_file_upload CHANGE id id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE id_parrent_service id_parrent_service BIGINT UNSIGNED DEFAULT NULL, CHANGE create_user create_user VARCHAR(50) DEFAULT \'system\' NOT NULL, CHANGE update_user update_user VARCHAR(50) DEFAULT \'system\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE temporary_file_upload CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE id_parrent_service id_parrent_service BIGINT NOT NULL, CHANGE create_user create_user VARCHAR(50) NOT NULL, CHANGE update_user update_user VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE publication_meta DROP FOREIGN KEY FK_DC0A95F8D9A8E14D');
        $this->addSql('DROP INDEX IDX_DC0A95F8D9A8E14D ON publication_meta');
        $this->addSql('ALTER TABLE publication_form CHANGE id id BIGINT UNSIGNED NOT NULL');
    }
}
