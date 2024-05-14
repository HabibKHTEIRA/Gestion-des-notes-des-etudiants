<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240514202602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE formation_int (id INT AUTO_INCREMENT NOT NULL, etudiant VARCHAR(8) NOT NULL, filiere VARCHAR(20) NOT NULL, annee_id INT DEFAULT NULL, INDEX IDX_A301BFC5717E22E3 (etudiant), INDEX IDX_A301BFC52ED05D9E (filiere), INDEX IDX_A301BFC5543EC5F0 (annee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE formation_int ADD CONSTRAINT FK_A301BFC5717E22E3 FOREIGN KEY (etudiant) REFERENCES etudiant (numetd)');
        $this->addSql('ALTER TABLE formation_int ADD CONSTRAINT FK_A301BFC52ED05D9E FOREIGN KEY (filiere) REFERENCES filiere (codefiliere)');
        $this->addSql('ALTER TABLE formation_int ADD CONSTRAINT FK_A301BFC5543EC5F0 FOREIGN KEY (annee_id) REFERENCES annee_universitaire (annee)');
        $this->addSql('ALTER TABLE element ADD type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE filiere ADD niv_lmd VARCHAR(3) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation_int DROP FOREIGN KEY FK_A301BFC5717E22E3');
        $this->addSql('ALTER TABLE formation_int DROP FOREIGN KEY FK_A301BFC52ED05D9E');
        $this->addSql('ALTER TABLE formation_int DROP FOREIGN KEY FK_A301BFC5543EC5F0');
        $this->addSql('DROP TABLE formation_int');
        $this->addSql('ALTER TABLE element DROP type');
        $this->addSql('ALTER TABLE filiere DROP niv_lmd');
    }
}
