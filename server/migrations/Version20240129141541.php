<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240129141541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adherent (id_adherent VARCHAR(50) NOT NULL, date_adhesion DATE NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, date_naiss DATE NOT NULL, email VARCHAR(50) NOT NULL, adresse_postale VARCHAR(80) NOT NULL, num_tel VARCHAR(10) NOT NULL, photo_link VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, id_role VARCHAR(50) NOT NULL, idRole VARCHAR(50) DEFAULT NULL, INDEX IDX_90D3F0602494D4F4 (idRole), PRIMARY KEY(id_adherent)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appartenir (id_livre VARCHAR(50) NOT NULL, id_cat VARCHAR(50) NOT NULL, idLivre VARCHAR(50) DEFAULT NULL, idCat VARCHAR(50) DEFAULT NULL, INDEX IDX_A2A0D90CBBA70C84 (idLivre), INDEX IDX_A2A0D90CBF165E2F (idCat), PRIMARY KEY(id_livre)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auteur (id_aut VARCHAR(50) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, date_naiss DATE DEFAULT NULL, date_deces DATE DEFAULT NULL, nationalite VARCHAR(50) DEFAULT NULL, photo_link VARCHAR(150) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id_aut)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id_cat VARCHAR(50) NOT NULL, nom VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id_cat)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ecrire (id_livre VARCHAR(50) NOT NULL, id_aut VARCHAR(50) NOT NULL, idLivre VARCHAR(50) DEFAULT NULL, idAut VARCHAR(50) DEFAULT NULL, INDEX IDX_918824CCBBA70C84 (idLivre), INDEX IDX_918824CC923C5D14 (idAut), PRIMARY KEY(id_livre)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emprunt (id_emp VARCHAR(50) NOT NULL, date_emprunt DATE NOT NULL, date_retour DATE NOT NULL, date_retour_limite DATE NOT NULL, id_livre VARCHAR(50) NOT NULL, id_adherent VARCHAR(50) NOT NULL, idLivre VARCHAR(50) DEFAULT NULL, idAdherent VARCHAR(50) DEFAULT NULL, INDEX IDX_364071D7BBA70C84 (idLivre), INDEX IDX_364071D7C370DA3B (idAdherent), PRIMARY KEY(id_emp)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livre (id_livre VARCHAR(50) NOT NULL, titre VARCHAR(100) NOT NULL, date_sortie DATE NOT NULL, langue VARCHAR(50) NOT NULL, couverture_link VARCHAR(150) NOT NULL, PRIMARY KEY(id_livre)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id_resa VARCHAR(50) NOT NULL, date_resa DATE NOT NULL, id_livre VARCHAR(50) NOT NULL, id_adherant VARCHAR(50) NOT NULL, idLivre VARCHAR(50) DEFAULT NULL, idAdherent VARCHAR(50) DEFAULT NULL, INDEX IDX_42C84955BBA70C84 (idLivre), INDEX IDX_42C84955C370DA3B (idAdherent), PRIMARY KEY(id_resa)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id_role VARCHAR(50) NOT NULL, name VARCHAR(50) NOT NULL, permission_rang INT NOT NULL, PRIMARY KEY(id_role)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F0602494D4F4 FOREIGN KEY (idRole) REFERENCES role (id_role)');
        $this->addSql('ALTER TABLE appartenir ADD CONSTRAINT FK_A2A0D90CBBA70C84 FOREIGN KEY (idLivre) REFERENCES livre (id_livre)');
        $this->addSql('ALTER TABLE appartenir ADD CONSTRAINT FK_A2A0D90CBF165E2F FOREIGN KEY (idCat) REFERENCES categorie (id_cat)');
        $this->addSql('ALTER TABLE ecrire ADD CONSTRAINT FK_918824CCBBA70C84 FOREIGN KEY (idLivre) REFERENCES livre (id_livre)');
        $this->addSql('ALTER TABLE ecrire ADD CONSTRAINT FK_918824CC923C5D14 FOREIGN KEY (idAut) REFERENCES auteur (id_aut)');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D7BBA70C84 FOREIGN KEY (idLivre) REFERENCES livre (id_livre)');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D7C370DA3B FOREIGN KEY (idAdherent) REFERENCES adherent (id_adherent)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955BBA70C84 FOREIGN KEY (idLivre) REFERENCES livre (id_livre)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955C370DA3B FOREIGN KEY (idAdherent) REFERENCES adherent (id_adherent)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F0602494D4F4');
        $this->addSql('ALTER TABLE appartenir DROP FOREIGN KEY FK_A2A0D90CBBA70C84');
        $this->addSql('ALTER TABLE appartenir DROP FOREIGN KEY FK_A2A0D90CBF165E2F');
        $this->addSql('ALTER TABLE ecrire DROP FOREIGN KEY FK_918824CCBBA70C84');
        $this->addSql('ALTER TABLE ecrire DROP FOREIGN KEY FK_918824CC923C5D14');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D7BBA70C84');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D7C370DA3B');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955BBA70C84');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955C370DA3B');
        $this->addSql('DROP TABLE adherent');
        $this->addSql('DROP TABLE appartenir');
        $this->addSql('DROP TABLE auteur');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE ecrire');
        $this->addSql('DROP TABLE emprunt');
        $this->addSql('DROP TABLE livre');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE role');
    }
}
