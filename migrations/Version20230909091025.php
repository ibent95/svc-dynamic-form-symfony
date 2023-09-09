<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230909091025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication_meta_backup DROP FOREIGN KEY FK_DC0A95F8B72EAA8E');
        $this->addSql('DROP TABLE publication_meta_backup');
        $this->addSql('ALTER TABLE publication CHANGE created_user created_user VARCHAR(50) DEFAULT \'system\', CHANGE updated_user updated_user VARCHAR(50) DEFAULT \'system\'');
        $this->addSql('ALTER TABLE publication_meta DROP FOREIGN KEY FK_7E278A9AB72EAA8E');
        $this->addSql('ALTER TABLE publication_meta DROP FOREIGN KEY FK_7E278A9AADB1C68A');
        $this->addSql('DROP INDEX idx_7e278a9ab72eaa8e ON publication_meta');
        $this->addSql('CREATE INDEX IDX_DC0A95F8B72EAA8E ON publication_meta (id_publication)');
        $this->addSql('DROP INDEX idx_7e278a9aadb1c68a ON publication_meta');
        $this->addSql('CREATE INDEX IDX_DC0A95F8ADB1C68A ON publication_meta (id_form_version)');
        $this->addSql('ALTER TABLE publication_meta ADD CONSTRAINT FK_7E278A9AB72EAA8E FOREIGN KEY (id_publication) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE publication_meta ADD CONSTRAINT FK_7E278A9AADB1C68A FOREIGN KEY (id_form_version) REFERENCES publication_form_version (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE publication_meta_backup (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, id_publication BIGINT UNSIGNED NOT NULL, field_name VARCHAR(150) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, field_value VARCHAR(1000) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, flag_active TINYINT(1) DEFAULT 1 NOT NULL, created_user VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_user VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, updated_at DATETIME NOT NULL, uuid CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\', INDEX IDX_DC0A95F8B72EAA8E (id_publication), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE publication_meta_backup ADD CONSTRAINT FK_DC0A95F8B72EAA8E FOREIGN KEY (id_publication) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE publication CHANGE created_user created_user VARCHAR(50) DEFAULT NULL, CHANGE updated_user updated_user VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE publication_meta DROP FOREIGN KEY FK_DC0A95F8B72EAA8E');
        $this->addSql('ALTER TABLE publication_meta DROP FOREIGN KEY FK_DC0A95F8ADB1C68A');
        $this->addSql('DROP INDEX idx_dc0a95f8b72eaa8e ON publication_meta');
        $this->addSql('CREATE INDEX IDX_7E278A9AB72EAA8E ON publication_meta (id_publication)');
        $this->addSql('DROP INDEX idx_dc0a95f8adb1c68a ON publication_meta');
        $this->addSql('CREATE INDEX IDX_7E278A9AADB1C68A ON publication_meta (id_form_version)');
        $this->addSql('ALTER TABLE publication_meta ADD CONSTRAINT FK_DC0A95F8B72EAA8E FOREIGN KEY (id_publication) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE publication_meta ADD CONSTRAINT FK_DC0A95F8ADB1C68A FOREIGN KEY (id_form_version) REFERENCES publication_form_version (id)');
    }
}
