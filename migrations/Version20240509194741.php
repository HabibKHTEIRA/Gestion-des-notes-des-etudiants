<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240509194741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bac (idbac INT AUTO_INCREMENT NOT NULL, typebac VARCHAR(20) NOT NULL, libele VARCHAR(20) DEFAULT NULL, PRIMARY KEY(idbac)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE choix (specialite VARCHAR(10) NOT NULL, etudiant VARCHAR(8) NOT NULL, enterminale TINYINT(1) NOT NULL, INDEX IDX_4F488091E7D6FCC1 (specialite), INDEX IDX_4F488091717E22E3 (etudiant), PRIMARY KEY(specialite, etudiant)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudsup (formation VARCHAR(20) NOT NULL, etudiant VARCHAR(8) NOT NULL, anneedeb INT DEFAULT NULL, INDEX IDX_5DDD686404021BF (formation), INDEX IDX_5DDD686717E22E3 (etudiant), PRIMARY KEY(formation, etudiant)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation_ant (codef VARCHAR(20) NOT NULL, nomf VARCHAR(50) NOT NULL, etablissement VARCHAR(80) DEFAULT NULL, diplome VARCHAR(10) DEFAULT NULL, PRIMARY KEY(codef)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe (codegrp VARCHAR(50) NOT NULL, nomgrp VARCHAR(50) NOT NULL, nbetds INT NOT NULL, capacite INT NOT NULL, PRIMARY KEY(codegrp)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resultatbac (bac INT NOT NULL, etudiant VARCHAR(8) NOT NULL, anneebac INT NOT NULL, mention VARCHAR(20) DEFAULT NULL, moyennebac DOUBLE PRECISION DEFAULT NULL, etabbac VARCHAR(50) DEFAULT NULL, depbac VARCHAR(50) DEFAULT NULL, INDEX IDX_A83D80341C4FAC58 (bac), INDEX IDX_A83D8034717E22E3 (etudiant), PRIMARY KEY(bac, etudiant)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialite (codespe VARCHAR(10) NOT NULL, nomspe VARCHAR(50) NOT NULL, PRIMARY KEY(codespe)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE choix ADD CONSTRAINT FK_4F488091E7D6FCC1 FOREIGN KEY (specialite) REFERENCES specialite (codespe)');
        $this->addSql('ALTER TABLE choix ADD CONSTRAINT FK_4F488091717E22E3 FOREIGN KEY (etudiant) REFERENCES etudiant (numetd)');
        $this->addSql('ALTER TABLE etudsup ADD CONSTRAINT FK_5DDD686404021BF FOREIGN KEY (formation) REFERENCES formation_ant (codef)');
        $this->addSql('ALTER TABLE etudsup ADD CONSTRAINT FK_5DDD686717E22E3 FOREIGN KEY (etudiant) REFERENCES etudiant (numetd)');
        $this->addSql('ALTER TABLE resultatbac ADD CONSTRAINT FK_A83D80341C4FAC58 FOREIGN KEY (bac) REFERENCES bac (idbac)');
        $this->addSql('ALTER TABLE resultatbac ADD CONSTRAINT FK_A83D8034717E22E3 FOREIGN KEY (etudiant) REFERENCES etudiant (numetd)');
        $this->addSql('ALTER TABLE etudiant ADD codegrp VARCHAR(50) DEFAULT NULL, CHANGE email email VARCHAR(60) NOT NULL, CHANGE sexe sexe VARCHAR(1) NOT NULL');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3BE4E55D8 FOREIGN KEY (codegrp) REFERENCES groupe (codegrp) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_717E22E3BE4E55D8 ON etudiant (codegrp)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3BE4E55D8');
        $this->addSql('ALTER TABLE choix DROP FOREIGN KEY FK_4F488091E7D6FCC1');
        $this->addSql('ALTER TABLE choix DROP FOREIGN KEY FK_4F488091717E22E3');
        $this->addSql('ALTER TABLE etudsup DROP FOREIGN KEY FK_5DDD686404021BF');
        $this->addSql('ALTER TABLE etudsup DROP FOREIGN KEY FK_5DDD686717E22E3');
        $this->addSql('ALTER TABLE resultatbac DROP FOREIGN KEY FK_A83D80341C4FAC58');
        $this->addSql('ALTER TABLE resultatbac DROP FOREIGN KEY FK_A83D8034717E22E3');
        $this->addSql('DROP TABLE bac');
        $this->addSql('DROP TABLE choix');
        $this->addSql('DROP TABLE etudsup');
        $this->addSql('DROP TABLE formation_ant');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE resultatbac');
        $this->addSql('DROP TABLE specialite');
        $this->addSql('DROP INDEX IDX_717E22E3BE4E55D8 ON etudiant');
        $this->addSql('ALTER TABLE etudiant DROP codegrp, CHANGE email email VARCHAR(60) DEFAULT NULL, CHANGE sexe sexe VARCHAR(1) DEFAULT NULL');
    }
}
