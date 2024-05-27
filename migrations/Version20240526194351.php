<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240526194351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annee_universitaire (annee INT NOT NULL, PRIMARY KEY(annee)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bac (idbac INT AUTO_INCREMENT NOT NULL, typebac VARCHAR(20) NOT NULL, libele VARCHAR(20) DEFAULT NULL, PRIMARY KEY(idbac)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bloc (codebloc VARCHAR(20) NOT NULL, filiere VARCHAR(20) DEFAULT NULL, element VARCHAR(20) DEFAULT NULL, nombloc VARCHAR(60) NOT NULL, noteplancher INT DEFAULT NULL, code_trouve VARCHAR(255) DEFAULT NULL, INDEX IDX_C778955A2ED05D9E (filiere), UNIQUE INDEX UNIQ_C778955A41405E39 (element), PRIMARY KEY(codebloc)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE choix (specialite VARCHAR(10) NOT NULL, etudiant VARCHAR(8) NOT NULL, enterminale TINYINT(1) NOT NULL, INDEX IDX_4F488091E7D6FCC1 (specialite), INDEX IDX_4F488091717E22E3 (etudiant), PRIMARY KEY(specialite, etudiant)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE codes (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, nature VARCHAR(10) DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE element (codeelt VARCHAR(20) NOT NULL, type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(codeelt)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE epreuve (codeepreuve VARCHAR(20) NOT NULL, matiere VARCHAR(20) DEFAULT NULL, element VARCHAR(20) DEFAULT NULL, numchance INT NOT NULL, annee INT DEFAULT NULL, typeepreuve VARCHAR(20) NOT NULL, salle VARCHAR(20) DEFAULT NULL, duree VARCHAR(255) DEFAULT NULL, pourcentage INT DEFAULT NULL, code_trouve VARCHAR(255) DEFAULT NULL, INDEX IDX_D6ADE47F9014574A (matiere), UNIQUE INDEX UNIQ_D6ADE47F41405E39 (element), PRIMARY KEY(codeepreuve)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudiant (numetd VARCHAR(8) NOT NULL, codegrp VARCHAR(50) DEFAULT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, email VARCHAR(60) NOT NULL, sexe VARCHAR(1) NOT NULL, adresse VARCHAR(70) DEFAULT NULL, tel VARCHAR(40) DEFAULT NULL, filiere VARCHAR(40) DEFAULT NULL, datnaiss DATE DEFAULT NULL, depnaiss VARCHAR(40) DEFAULT NULL, villnaiss VARCHAR(40) DEFAULT NULL, paysnaiss VARCHAR(40) DEFAULT NULL, nationalite VARCHAR(50) DEFAULT NULL, sports VARCHAR(80) DEFAULT NULL, handicape VARCHAR(80) DEFAULT NULL, derdiplome VARCHAR(50) DEFAULT NULL, dateinsc DATE DEFAULT NULL, registre VARCHAR(30) DEFAULT NULL, statut VARCHAR(30) DEFAULT NULL, hide TINYINT(1) DEFAULT 0, UNIQUE INDEX UNIQ_717E22E3E7927C74 (email), INDEX IDX_717E22E3BE4E55D8 (codegrp), PRIMARY KEY(numetd)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudsup (formation VARCHAR(20) NOT NULL, etudiant VARCHAR(8) NOT NULL, anneedeb INT DEFAULT NULL, INDEX IDX_5DDD686404021BF (formation), INDEX IDX_5DDD686717E22E3 (etudiant), PRIMARY KEY(formation, etudiant)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filiere (codefiliere VARCHAR(20) NOT NULL, element VARCHAR(20) DEFAULT NULL, nomfiliere VARCHAR(30) NOT NULL, respfiliere VARCHAR(50) DEFAULT NULL, niv_lmd VARCHAR(3) DEFAULT NULL, code_trouve VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_2ED05D9E41405E39 (element), PRIMARY KEY(codefiliere)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation_ant (codef VARCHAR(20) NOT NULL, nomf VARCHAR(50) NOT NULL, etablissement VARCHAR(80) DEFAULT NULL, diplome VARCHAR(10) DEFAULT NULL, PRIMARY KEY(codef)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation_int (id INT AUTO_INCREMENT NOT NULL, etudiant VARCHAR(8) NOT NULL, filiere VARCHAR(20) NOT NULL, annee_id INT DEFAULT NULL, INDEX IDX_A301BFC5717E22E3 (etudiant), INDEX IDX_A301BFC52ED05D9E (filiere), INDEX IDX_A301BFC5543EC5F0 (annee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe (codegrp VARCHAR(50) NOT NULL, nomgrp VARCHAR(50) NOT NULL, nbetds INT NOT NULL, capacite INT NOT NULL, PRIMARY KEY(codegrp)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (codemat VARCHAR(20) NOT NULL, unite VARCHAR(20) DEFAULT NULL, element VARCHAR(20) DEFAULT NULL, nommat VARCHAR(40) NOT NULL, periode VARCHAR(3) NOT NULL, code_trouve VARCHAR(255) DEFAULT NULL, INDEX IDX_9014574A1D64C118 (unite), UNIQUE INDEX UNIQ_9014574A41405E39 (element), PRIMARY KEY(codemat)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (anneeuniversitaire INT NOT NULL, etudiant VARCHAR(8) NOT NULL, element VARCHAR(20) NOT NULL, note DOUBLE PRECISION DEFAULT NULL, INDEX IDX_CFBDFA1469D43CC0 (anneeuniversitaire), INDEX IDX_CFBDFA14717E22E3 (etudiant), INDEX IDX_CFBDFA1441405E39 (element), PRIMARY KEY(anneeuniversitaire, etudiant, element)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resultatbac (bac INT NOT NULL, etudiant VARCHAR(8) NOT NULL, anneebac INT NOT NULL, mention VARCHAR(20) DEFAULT NULL, moyennebac DOUBLE PRECISION DEFAULT NULL, etabbac VARCHAR(50) DEFAULT NULL, depbac VARCHAR(50) DEFAULT NULL, INDEX IDX_A83D80341C4FAC58 (bac), INDEX IDX_A83D8034717E22E3 (etudiant), PRIMARY KEY(bac, etudiant)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialite (codespe VARCHAR(10) NOT NULL, nomspe VARCHAR(50) NOT NULL, PRIMARY KEY(codespe)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unite (codeunite VARCHAR(20) NOT NULL, bloc VARCHAR(20) DEFAULT NULL, element VARCHAR(20) DEFAULT NULL, nomunite VARCHAR(60) NOT NULL, coeficient INT DEFAULT NULL, respunite VARCHAR(50) DEFAULT NULL, code_trouve VARCHAR(255) DEFAULT NULL, INDEX IDX_1D64C118C778955A (bloc), UNIQUE INDEX UNIQ_1D64C11841405E39 (element), PRIMARY KEY(codeunite)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, validation TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bloc ADD CONSTRAINT FK_C778955A2ED05D9E FOREIGN KEY (filiere) REFERENCES filiere (codefiliere)');
        $this->addSql('ALTER TABLE bloc ADD CONSTRAINT FK_C778955A41405E39 FOREIGN KEY (element) REFERENCES element (codeelt)');
        $this->addSql('ALTER TABLE choix ADD CONSTRAINT FK_4F488091E7D6FCC1 FOREIGN KEY (specialite) REFERENCES specialite (codespe)');
        $this->addSql('ALTER TABLE choix ADD CONSTRAINT FK_4F488091717E22E3 FOREIGN KEY (etudiant) REFERENCES etudiant (numetd)');
        $this->addSql('ALTER TABLE epreuve ADD CONSTRAINT FK_D6ADE47F9014574A FOREIGN KEY (matiere) REFERENCES matiere (codemat)');
        $this->addSql('ALTER TABLE epreuve ADD CONSTRAINT FK_D6ADE47F41405E39 FOREIGN KEY (element) REFERENCES element (codeelt)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3BE4E55D8 FOREIGN KEY (codegrp) REFERENCES groupe (codegrp) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE etudsup ADD CONSTRAINT FK_5DDD686404021BF FOREIGN KEY (formation) REFERENCES formation_ant (codef)');
        $this->addSql('ALTER TABLE etudsup ADD CONSTRAINT FK_5DDD686717E22E3 FOREIGN KEY (etudiant) REFERENCES etudiant (numetd)');
        $this->addSql('ALTER TABLE filiere ADD CONSTRAINT FK_2ED05D9E41405E39 FOREIGN KEY (element) REFERENCES element (codeelt)');
        $this->addSql('ALTER TABLE formation_int ADD CONSTRAINT FK_A301BFC5717E22E3 FOREIGN KEY (etudiant) REFERENCES etudiant (numetd)');
        $this->addSql('ALTER TABLE formation_int ADD CONSTRAINT FK_A301BFC52ED05D9E FOREIGN KEY (filiere) REFERENCES filiere (codefiliere)');
        $this->addSql('ALTER TABLE formation_int ADD CONSTRAINT FK_A301BFC5543EC5F0 FOREIGN KEY (annee_id) REFERENCES annee_universitaire (annee)');
        $this->addSql('ALTER TABLE matiere ADD CONSTRAINT FK_9014574A1D64C118 FOREIGN KEY (unite) REFERENCES unite (codeunite)');
        $this->addSql('ALTER TABLE matiere ADD CONSTRAINT FK_9014574A41405E39 FOREIGN KEY (element) REFERENCES element (codeelt)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA1469D43CC0 FOREIGN KEY (anneeuniversitaire) REFERENCES annee_universitaire (annee)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14717E22E3 FOREIGN KEY (etudiant) REFERENCES etudiant (numetd)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA1441405E39 FOREIGN KEY (element) REFERENCES element (codeelt)');
        $this->addSql('ALTER TABLE resultatbac ADD CONSTRAINT FK_A83D80341C4FAC58 FOREIGN KEY (bac) REFERENCES bac (idbac)');
        $this->addSql('ALTER TABLE resultatbac ADD CONSTRAINT FK_A83D8034717E22E3 FOREIGN KEY (etudiant) REFERENCES etudiant (numetd)');
        $this->addSql('ALTER TABLE unite ADD CONSTRAINT FK_1D64C118C778955A FOREIGN KEY (bloc) REFERENCES bloc (codebloc)');
        $this->addSql('ALTER TABLE unite ADD CONSTRAINT FK_1D64C11841405E39 FOREIGN KEY (element) REFERENCES element (codeelt)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bloc DROP FOREIGN KEY FK_C778955A2ED05D9E');
        $this->addSql('ALTER TABLE bloc DROP FOREIGN KEY FK_C778955A41405E39');
        $this->addSql('ALTER TABLE choix DROP FOREIGN KEY FK_4F488091E7D6FCC1');
        $this->addSql('ALTER TABLE choix DROP FOREIGN KEY FK_4F488091717E22E3');
        $this->addSql('ALTER TABLE epreuve DROP FOREIGN KEY FK_D6ADE47F9014574A');
        $this->addSql('ALTER TABLE epreuve DROP FOREIGN KEY FK_D6ADE47F41405E39');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3BE4E55D8');
        $this->addSql('ALTER TABLE etudsup DROP FOREIGN KEY FK_5DDD686404021BF');
        $this->addSql('ALTER TABLE etudsup DROP FOREIGN KEY FK_5DDD686717E22E3');
        $this->addSql('ALTER TABLE filiere DROP FOREIGN KEY FK_2ED05D9E41405E39');
        $this->addSql('ALTER TABLE formation_int DROP FOREIGN KEY FK_A301BFC5717E22E3');
        $this->addSql('ALTER TABLE formation_int DROP FOREIGN KEY FK_A301BFC52ED05D9E');
        $this->addSql('ALTER TABLE formation_int DROP FOREIGN KEY FK_A301BFC5543EC5F0');
        $this->addSql('ALTER TABLE matiere DROP FOREIGN KEY FK_9014574A1D64C118');
        $this->addSql('ALTER TABLE matiere DROP FOREIGN KEY FK_9014574A41405E39');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA1469D43CC0');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14717E22E3');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA1441405E39');
        $this->addSql('ALTER TABLE resultatbac DROP FOREIGN KEY FK_A83D80341C4FAC58');
        $this->addSql('ALTER TABLE resultatbac DROP FOREIGN KEY FK_A83D8034717E22E3');
        $this->addSql('ALTER TABLE unite DROP FOREIGN KEY FK_1D64C118C778955A');
        $this->addSql('ALTER TABLE unite DROP FOREIGN KEY FK_1D64C11841405E39');
        $this->addSql('DROP TABLE annee_universitaire');
        $this->addSql('DROP TABLE bac');
        $this->addSql('DROP TABLE bloc');
        $this->addSql('DROP TABLE choix');
        $this->addSql('DROP TABLE codes');
        $this->addSql('DROP TABLE element');
        $this->addSql('DROP TABLE epreuve');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE etudsup');
        $this->addSql('DROP TABLE filiere');
        $this->addSql('DROP TABLE formation_ant');
        $this->addSql('DROP TABLE formation_int');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE resultatbac');
        $this->addSql('DROP TABLE specialite');
        $this->addSql('DROP TABLE unite');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
