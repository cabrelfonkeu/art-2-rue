<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261029090401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE oeuvre_oeuvre');
        $this->addSql('CREATE TEMPORARY TABLE __temp__oeuvre AS SELECT id, titre, datecreation, description, datedestruction, lieu_id FROM oeuvre');
        $this->addSql('DROP TABLE oeuvre');
        $this->addSql('CREATE TABLE oeuvre (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, datecreation DATE DEFAULT NULL, description CLOB DEFAULT NULL, datedestruction DATE DEFAULT NULL, lieu_id INTEGER DEFAULT NULL, oeuvre_recouverte_id INTEGER DEFAULT NULL, CONSTRAINT FK_35FE2EFE6AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_35FE2EFEEAFC6390 FOREIGN KEY (oeuvre_recouverte_id) REFERENCES oeuvre (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO oeuvre (id, titre, datecreation, description, datedestruction, lieu_id) SELECT id, titre, datecreation, description, datedestruction, lieu_id FROM __temp__oeuvre');
        $this->addSql('DROP TABLE __temp__oeuvre');
        $this->addSql('CREATE INDEX IDX_35FE2EFE6AB213CC ON oeuvre (lieu_id)');
        $this->addSql('CREATE INDEX IDX_35FE2EFEEAFC6390 ON oeuvre (oeuvre_recouverte_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE oeuvre_oeuvre (oeuvre_source INTEGER NOT NULL, oeuvre_target INTEGER NOT NULL, PRIMARY KEY (oeuvre_source, oeuvre_target), CONSTRAINT FK_F5DB0A109FAF5B9D FOREIGN KEY (oeuvre_source) REFERENCES oeuvre (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F5DB0A10864A0B12 FOREIGN KEY (oeuvre_target) REFERENCES oeuvre (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_F5DB0A10864A0B12 ON oeuvre_oeuvre (oeuvre_target)');
        $this->addSql('CREATE INDEX IDX_F5DB0A109FAF5B9D ON oeuvre_oeuvre (oeuvre_source)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__oeuvre AS SELECT id, titre, datecreation, description, datedestruction, lieu_id FROM oeuvre');
        $this->addSql('DROP TABLE oeuvre');
        $this->addSql('CREATE TABLE oeuvre (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, datecreation DATE DEFAULT NULL, description CLOB DEFAULT NULL, datedestruction DATE DEFAULT NULL, lieu_id INTEGER DEFAULT NULL, CONSTRAINT FK_35FE2EFE6AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO oeuvre (id, titre, datecreation, description, datedestruction, lieu_id) SELECT id, titre, datecreation, description, datedestruction, lieu_id FROM __temp__oeuvre');
        $this->addSql('DROP TABLE __temp__oeuvre');
        $this->addSql('CREATE INDEX IDX_35FE2EFE6AB213CC ON oeuvre (lieu_id)');
    }
}
