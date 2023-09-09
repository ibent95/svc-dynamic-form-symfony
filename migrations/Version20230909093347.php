<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230909093347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication ADD create_user VARCHAR(50) DEFAULT \'system\', ADD update_user VARCHAR(50) DEFAULT \'system\', DROP created_user, DROP updated_user');
        $this->addSql('ALTER TABLE publication_form ADD create_user VARCHAR(50) DEFAULT NULL, ADD update_user VARCHAR(50) DEFAULT NULL, DROP created_user, DROP updated_user');
        $this->addSql('ALTER TABLE publication_form_version ADD create_user VARCHAR(50) DEFAULT NULL, ADD update_user VARCHAR(50) DEFAULT NULL, DROP created_user, DROP updated_user');
        $this->addSql('ALTER TABLE publication_general_type ADD create_user VARCHAR(50) DEFAULT NULL, ADD update_user VARCHAR(50) DEFAULT NULL, DROP created_user, DROP updated_user');
        $this->addSql('ALTER TABLE publication_meta ADD create_user VARCHAR(50) DEFAULT \'system\', ADD update_user VARCHAR(50) DEFAULT \'system\', DROP created_user, DROP updated_user');
        $this->addSql('ALTER TABLE publication_status ADD create_user VARCHAR(50) DEFAULT NULL, ADD update_user VARCHAR(50) DEFAULT NULL, DROP created_user, DROP updated_user');
        $this->addSql('ALTER TABLE publication_type ADD create_user VARCHAR(50) DEFAULT NULL, ADD update_user VARCHAR(50) DEFAULT NULL, DROP created_user, DROP updated_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication ADD created_user VARCHAR(50) DEFAULT \'system\', ADD updated_user VARCHAR(50) DEFAULT \'system\', DROP create_user, DROP update_user');
        $this->addSql('ALTER TABLE publication_general_type ADD created_user VARCHAR(50) DEFAULT NULL, ADD updated_user VARCHAR(50) DEFAULT NULL, DROP create_user, DROP update_user');
        $this->addSql('ALTER TABLE publication_meta ADD created_user VARCHAR(50) DEFAULT NULL, ADD updated_user VARCHAR(50) DEFAULT NULL, DROP create_user, DROP update_user');
        $this->addSql('ALTER TABLE publication_form_version ADD created_user VARCHAR(50) DEFAULT NULL, ADD updated_user VARCHAR(50) DEFAULT NULL, DROP create_user, DROP update_user');
        $this->addSql('ALTER TABLE publication_type ADD created_user VARCHAR(50) DEFAULT NULL, ADD updated_user VARCHAR(50) DEFAULT NULL, DROP create_user, DROP update_user');
        $this->addSql('ALTER TABLE publication_form ADD created_user VARCHAR(50) DEFAULT NULL, ADD updated_user VARCHAR(50) DEFAULT NULL, DROP create_user, DROP update_user');
        $this->addSql('ALTER TABLE publication_status ADD created_user VARCHAR(50) DEFAULT NULL, ADD updated_user VARCHAR(50) DEFAULT NULL, DROP create_user, DROP update_user');
    }
}
