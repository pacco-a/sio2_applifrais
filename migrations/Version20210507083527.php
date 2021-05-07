<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210507083527 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche_frais ADD validateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fiche_frais ADD CONSTRAINT FK_5FC0A6A7E57AEF2F FOREIGN KEY (validateur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5FC0A6A7E57AEF2F ON fiche_frais (validateur_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche_frais DROP FOREIGN KEY FK_5FC0A6A7E57AEF2F');
        $this->addSql('DROP INDEX IDX_5FC0A6A7E57AEF2F ON fiche_frais');
        $this->addSql('ALTER TABLE fiche_frais DROP validateur_id');
    }
}
