<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230521213018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE human_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE pet_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE human (id INT NOT NULL, uuid UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE human_pet (human_id INT NOT NULL, pet_id INT NOT NULL, PRIMARY KEY(human_id, pet_id))');
        $this->addSql('CREATE INDEX IDX_62B4CD738ABD4580 ON human_pet (human_id)');
        $this->addSql('CREATE INDEX IDX_62B4CD73966F7FB6 ON human_pet (pet_id)');
        $this->addSql('CREATE TABLE pet (id INT NOT NULL, uuid UUID NOT NULL, name VARCHAR(255) NOT NULL, date_of_birth DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN pet.date_of_birth IS \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE human_pet ADD CONSTRAINT FK_62B4CD738ABD4580 FOREIGN KEY (human_id) REFERENCES human (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE human_pet ADD CONSTRAINT FK_62B4CD73966F7FB6 FOREIGN KEY (pet_id) REFERENCES pet (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE human_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE pet_id_seq CASCADE');
        $this->addSql('ALTER TABLE human_pet DROP CONSTRAINT FK_62B4CD738ABD4580');
        $this->addSql('ALTER TABLE human_pet DROP CONSTRAINT FK_62B4CD73966F7FB6');
        $this->addSql('DROP TABLE human');
        $this->addSql('DROP TABLE human_pet');
        $this->addSql('DROP TABLE pet');
    }
}
