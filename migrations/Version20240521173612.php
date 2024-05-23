<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240521173612 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bloc ADD code_trouve VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE epreuve ADD code_trouve VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE filiere ADD code_trouve VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE matiere ADD code_trouve VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE unite ADD code_trouve VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bloc DROP code_trouve');
        $this->addSql('ALTER TABLE epreuve DROP code_trouve');
        $this->addSql('ALTER TABLE filiere DROP code_trouve');
        $this->addSql('ALTER TABLE matiere DROP code_trouve');
        $this->addSql('ALTER TABLE unite DROP code_trouve');
    }
}
