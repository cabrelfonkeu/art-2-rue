<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261028160204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE photo (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, dateprisevue DATETIME NOT NULL, nomfichier VARCHAR(255) NOT NULL, etatoeuvre VARCHAR(255) DEFAULT NULL, oeuvre_id INTEGER NOT NULL, benevole_id INTEGER NOT NULL, CONSTRAINT FK_14B7841888194DE8 FOREIGN KEY (oeuvre_id) REFERENCES oeuvre (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_14B78418E77B7C09 FOREIGN KEY (benevole_id) REFERENCES benevole (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_14B7841888194DE8 ON photo (oeuvre_id)');
        $this->addSql('CREATE INDEX IDX_14B78418E77B7C09 ON photo (benevole_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE photo');
    }
}
