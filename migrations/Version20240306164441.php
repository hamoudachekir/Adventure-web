<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306164441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE host_service (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(2000) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hebergement DROP FOREIGN KEY FK_4852DD9CAE632A35');
        $this->addSql('DROP INDEX IDX_4852DD9CAE632A35 ON hebergement');
        $this->addSql('ALTER TABLE hebergement ADD host_service_id INT NOT NULL, ADD prix_adulte DOUBLE PRECISION NOT NULL, ADD prix_jeune DOUBLE PRECISION NOT NULL, ADD prix_enfant DOUBLE PRECISION NOT NULL, ADD adresse VARCHAR(255) NOT NULL, ADD image VARCHAR(255) NOT NULL, DROP commodite_id, DROP prix_adul, DROP prix_enf, DROP etat, CHANGE description description VARCHAR(2000) NOT NULL');
        $this->addSql('ALTER TABLE hebergement ADD CONSTRAINT FK_4852DD9C45CFF3EF FOREIGN KEY (host_service_id) REFERENCES host_service (id)');
        $this->addSql('CREATE INDEX IDX_4852DD9C45CFF3EF ON hebergement (host_service_id)');
        $this->addSql('ALTER TABLE reservation_hebergement ADD hebergement_id INT DEFAULT NULL, ADD nbr_adulte INT NOT NULL, ADD nbr_jeune INT NOT NULL, ADD nbr_enfant INT NOT NULL, CHANGE date_deb date_deb VARCHAR(255) NOT NULL, CHANGE date_fin date_fin VARCHAR(255) NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE reservation_hebergement ADD CONSTRAINT FK_843E00C023BB0F66 FOREIGN KEY (hebergement_id) REFERENCES hebergement (id)');
        $this->addSql('CREATE INDEX IDX_843E00C023BB0F66 ON reservation_hebergement (hebergement_id)');
        $this->addSql('ALTER TABLE type_hebergement CHANGE description description VARCHAR(2000) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hebergement DROP FOREIGN KEY FK_4852DD9C45CFF3EF');
        $this->addSql('DROP TABLE host_service');
        $this->addSql('DROP INDEX IDX_4852DD9C45CFF3EF ON hebergement');
        $this->addSql('ALTER TABLE hebergement ADD commodite_id INT DEFAULT NULL, ADD prix_adul DOUBLE PRECISION NOT NULL, ADD prix_enf DOUBLE PRECISION NOT NULL, ADD etat TINYINT(1) NOT NULL, DROP host_service_id, DROP prix_adulte, DROP prix_jeune, DROP prix_enfant, DROP adresse, DROP image, CHANGE description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE hebergement ADD CONSTRAINT FK_4852DD9CAE632A35 FOREIGN KEY (commodite_id) REFERENCES commodite (id)');
        $this->addSql('CREATE INDEX IDX_4852DD9CAE632A35 ON hebergement (commodite_id)');
        $this->addSql('ALTER TABLE reservation_hebergement DROP FOREIGN KEY FK_843E00C023BB0F66');
        $this->addSql('DROP INDEX IDX_843E00C023BB0F66 ON reservation_hebergement');
        $this->addSql('ALTER TABLE reservation_hebergement DROP hebergement_id, DROP nbr_adulte, DROP nbr_jeune, DROP nbr_enfant, CHANGE date_deb date_deb DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE date_fin date_fin DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE type_hebergement CHANGE description description VARCHAR(255) NOT NULL');
    }
}
