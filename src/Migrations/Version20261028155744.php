<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261028155744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE oeuvre (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, datecreation DATE DEFAULT NULL, description CLOB DEFAULT NULL, datedestruction DATE DEFAULT NULL, lieu_id INTEGER DEFAULT NULL, CONSTRAINT FK_35FE2EFE6AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_35FE2EFE6AB213CC ON oeuvre (lieu_id)');
        $this->addSql('CREATE TABLE oeuvre_artiste (oeuvre_id INTEGER NOT NULL, artiste_id INTEGER NOT NULL, PRIMARY KEY (oeuvre_id, artiste_id), CONSTRAINT FK_DB75DF1488194DE8 FOREIGN KEY (oeuvre_id) REFERENCES oeuvre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_DB75DF1421D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_DB75DF1488194DE8 ON oeuvre_artiste (oeuvre_id)');
        $this->addSql('CREATE INDEX IDX_DB75DF1421D25844 ON oeuvre_artiste (artiste_id)');
        $this->addSql('CREATE TABLE oeuvre_technique (oeuvre_id INTEGER NOT NULL, technique_id INTEGER NOT NULL, PRIMARY KEY (oeuvre_id, technique_id), CONSTRAINT FK_CEAB50CA88194DE8 FOREIGN KEY (oeuvre_id) REFERENCES oeuvre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CEAB50CA1F8ACB26 FOREIGN KEY (technique_id) REFERENCES technique (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_CEAB50CA88194DE8 ON oeuvre_technique (oeuvre_id)');
        $this->addSql('CREATE INDEX IDX_CEAB50CA1F8ACB26 ON oeuvre_technique (technique_id)');
        $this->addSql('CREATE TABLE oeuvre_oeuvre (oeuvre_source INTEGER NOT NULL, oeuvre_target INTEGER NOT NULL, PRIMARY KEY (oeuvre_source, oeuvre_target), CONSTRAINT FK_F5DB0A109FAF5B9D FOREIGN KEY (oeuvre_source) REFERENCES oeuvre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F5DB0A10864A0B12 FOREIGN KEY (oeuvre_target) REFERENCES oeuvre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_F5DB0A109FAF5B9D ON oeuvre_oeuvre (oeuvre_source)');
        $this->addSql('CREATE INDEX IDX_F5DB0A10864A0B12 ON oeuvre_oeuvre (oeuvre_target)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE oeuvre');
        $this->addSql('DROP TABLE oeuvre_artiste');
        $this->addSql('DROP TABLE oeuvre_technique');
        $this->addSql('DROP TABLE oeuvre_oeuvre');
    }
}
