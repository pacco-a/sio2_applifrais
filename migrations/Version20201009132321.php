<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201009132321 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE entree_frais_forfait (id INT AUTO_INCREMENT NOT NULL, fiche_frais_id INT NOT NULL, frais_forfait_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_7CE28761D94F5755 (fiche_frais_id), INDEX IDX_7CE287617B70375E (frais_forfait_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entree_frais_hors_forfait (id INT AUTO_INCREMENT NOT NULL, fiche_frais_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, date DATE NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_C77DD24D94F5755 (fiche_frais_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche_frais (id INT AUTO_INCREMENT NOT NULL, id_visisteur_id INT NOT NULL, id_etat_id INT NOT NULL, month INT NOT NULL, nb_proofs INT NOT NULL, valid_amount DOUBLE PRECISION NOT NULL, update_date DATE NOT NULL, INDEX IDX_5FC0A6A75E81D1F8 (id_visisteur_id), INDEX IDX_5FC0A6A7D3C32F8F (id_etat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frais_forfait (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entree_frais_forfait ADD CONSTRAINT FK_7CE28761D94F5755 FOREIGN KEY (fiche_frais_id) REFERENCES fiche_frais (id)');
        $this->addSql('ALTER TABLE entree_frais_forfait ADD CONSTRAINT FK_7CE287617B70375E FOREIGN KEY (frais_forfait_id) REFERENCES frais_forfait (id)');
        $this->addSql('ALTER TABLE entree_frais_hors_forfait ADD CONSTRAINT FK_C77DD24D94F5755 FOREIGN KEY (fiche_frais_id) REFERENCES fiche_frais (id)');
        $this->addSql('ALTER TABLE fiche_frais ADD CONSTRAINT FK_5FC0A6A75E81D1F8 FOREIGN KEY (id_visisteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE fiche_frais ADD CONSTRAINT FK_5FC0A6A7D3C32F8F FOREIGN KEY (id_etat_id) REFERENCES etat (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche_frais DROP FOREIGN KEY FK_5FC0A6A7D3C32F8F');
        $this->addSql('ALTER TABLE entree_frais_forfait DROP FOREIGN KEY FK_7CE28761D94F5755');
        $this->addSql('ALTER TABLE entree_frais_hors_forfait DROP FOREIGN KEY FK_C77DD24D94F5755');
        $this->addSql('ALTER TABLE entree_frais_forfait DROP FOREIGN KEY FK_7CE287617B70375E');
        $this->addSql('DROP TABLE entree_frais_forfait');
        $this->addSql('DROP TABLE entree_frais_hors_forfait');
        $this->addSql('DROP TABLE etat');
        $this->addSql('DROP TABLE fiche_frais');
        $this->addSql('DROP TABLE frais_forfait');
    }
}
