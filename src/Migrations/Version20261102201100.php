<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261102201100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__photo AS SELECT id, dateprisevue, nomfichier, etatoeuvre, oeuvre_id, benevole_id FROM photo');
        $this->addSql('DROP TABLE photo');
        $this->addSql('CREATE TABLE photo (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, dateprisevue DATETIME NOT NULL, nomfichier VARCHAR(255) NOT NULL, etatoeuvre VARCHAR(255) DEFAULT NULL, oeuvre_id INTEGER NOT NULL, utilisateur_id INTEGER NOT NULL, CONSTRAINT FK_14B7841888194DE8 FOREIGN KEY (oeuvre_id) REFERENCES oeuvre (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_14B78418FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO photo (id, dateprisevue, nomfichier, etatoeuvre, oeuvre_id, utilisateur_id) SELECT id, dateprisevue, nomfichier, etatoeuvre, oeuvre_id, benevole_id FROM __temp__photo');
        $this->addSql('DROP TABLE __temp__photo');
        $this->addSql('CREATE INDEX IDX_14B7841888194DE8 ON photo (oeuvre_id)');
        $this->addSql('CREATE INDEX IDX_14B78418FB88E14F ON photo (utilisateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__photo AS SELECT id, dateprisevue, nomfichier, etatoeuvre, oeuvre_id, utilisateur_id FROM photo');
        $this->addSql('DROP TABLE photo');
        $this->addSql('CREATE TABLE photo (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, dateprisevue DATETIME NOT NULL, nomfichier VARCHAR(255) NOT NULL, etatoeuvre VARCHAR(255) DEFAULT NULL, oeuvre_id INTEGER NOT NULL, benevole_id INTEGER NOT NULL, CONSTRAINT FK_14B7841888194DE8 FOREIGN KEY (oeuvre_id) REFERENCES oeuvre (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_14B78418E77B7C09 FOREIGN KEY (benevole_id) REFERENCES benevole (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO photo (id, dateprisevue, nomfichier, etatoeuvre, oeuvre_id, benevole_id) SELECT id, dateprisevue, nomfichier, etatoeuvre, oeuvre_id, utilisateur_id FROM __temp__photo');
        $this->addSql('DROP TABLE __temp__photo');
        $this->addSql('CREATE INDEX IDX_14B7841888194DE8 ON photo (oeuvre_id)');
        $this->addSql('CREATE INDEX IDX_14B78418E77B7C09 ON photo (benevole_id)');
    }
}
