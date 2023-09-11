<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230911083309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C67791CC9C6FE');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779A4A6901E');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C67795ECB64EE');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C677964AC8335');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C67791CC9C6FE FOREIGN KEY (id_publication_status) REFERENCES publication_status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779A4A6901E FOREIGN KEY (id_publication_form_version) REFERENCES publication_form_version (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C67795ECB64EE FOREIGN KEY (id_publication_general_type) REFERENCES publication_general_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C677964AC8335 FOREIGN KEY (id_publication_type) REFERENCES publication_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication_form DROP FOREIGN KEY FK_59707C82ADB1C68A');
        $this->addSql('ALTER TABLE publication_form ADD CONSTRAINT FK_59707C82ADB1C68A FOREIGN KEY (id_form_version) REFERENCES publication_form_version (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication_form_version DROP FOREIGN KEY FK_CFA3AA3D64AC8335');
        $this->addSql('ALTER TABLE publication_form_version ADD CONSTRAINT FK_CFA3AA3D64AC8335 FOREIGN KEY (id_publication_type) REFERENCES publication_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication_meta DROP FOREIGN KEY FK_DC0A95F8ADB1C68A');
        $this->addSql('ALTER TABLE publication_meta DROP FOREIGN KEY FK_DC0A95F8B72EAA8E');
        $this->addSql('ALTER TABLE publication_meta CHANGE id_form_version id_form_version BIGINT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE publication_meta ADD CONSTRAINT FK_DC0A95F8ADB1C68A FOREIGN KEY (id_form_version) REFERENCES publication_form_version (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication_meta ADD CONSTRAINT FK_DC0A95F8B72EAA8E FOREIGN KEY (id_publication) REFERENCES publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication_type DROP FOREIGN KEY FK_8726D6E45ECB64EE');
        $this->addSql('ALTER TABLE publication_type ADD CONSTRAINT FK_8726D6E45ECB64EE FOREIGN KEY (id_publication_general_type) REFERENCES publication_general_type (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C67795ECB64EE');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C677964AC8335');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779A4A6901E');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C67791CC9C6FE');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C67795ECB64EE FOREIGN KEY (id_publication_general_type) REFERENCES publication_general_type (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C677964AC8335 FOREIGN KEY (id_publication_type) REFERENCES publication_type (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779A4A6901E FOREIGN KEY (id_publication_form_version) REFERENCES publication_form_version (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C67791CC9C6FE FOREIGN KEY (id_publication_status) REFERENCES publication_status (id)');
        $this->addSql('ALTER TABLE publication_meta DROP FOREIGN KEY FK_DC0A95F8B72EAA8E');
        $this->addSql('ALTER TABLE publication_meta DROP FOREIGN KEY FK_DC0A95F8ADB1C68A');
        $this->addSql('ALTER TABLE publication_meta CHANGE id_form_version id_form_version BIGINT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE publication_meta ADD CONSTRAINT FK_DC0A95F8B72EAA8E FOREIGN KEY (id_publication) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE publication_meta ADD CONSTRAINT FK_DC0A95F8ADB1C68A FOREIGN KEY (id_form_version) REFERENCES publication_form_version (id)');
        $this->addSql('ALTER TABLE publication_form_version DROP FOREIGN KEY FK_CFA3AA3D64AC8335');
        $this->addSql('ALTER TABLE publication_form_version ADD CONSTRAINT FK_CFA3AA3D64AC8335 FOREIGN KEY (id_publication_type) REFERENCES publication_type (id)');
        $this->addSql('ALTER TABLE publication_type DROP FOREIGN KEY FK_8726D6E45ECB64EE');
        $this->addSql('ALTER TABLE publication_type ADD CONSTRAINT FK_8726D6E45ECB64EE FOREIGN KEY (id_publication_general_type) REFERENCES publication_general_type (id)');
        $this->addSql('ALTER TABLE publication_form DROP FOREIGN KEY FK_59707C82ADB1C68A');
        $this->addSql('ALTER TABLE publication_form ADD CONSTRAINT FK_59707C82ADB1C68A FOREIGN KEY (id_form_version) REFERENCES publication_form_version (id)');
    }
}
