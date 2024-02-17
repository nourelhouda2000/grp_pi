<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240211162128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sante (id INT AUTO_INCREMENT NOT NULL, maladie VARCHAR(255) NOT NULL, medicament VARCHAR(255) NOT NULL, calories VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE santé (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE analyses ADD santeid_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE analyses ADD CONSTRAINT FK_AC86883C89F57218 FOREIGN KEY (santeid_id) REFERENCES sante (id)');
        $this->addSql('CREATE INDEX IDX_AC86883C89F57218 ON analyses (santeid_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sante');
        $this->addSql('DROP TABLE santé');
        $this->addSql('ALTER TABLE analyses DROP FOREIGN KEY FK_AC86883C89F57218');
        $this->addSql('DROP INDEX IDX_AC86883C89F57218 ON analyses');
        $this->addSql('ALTER TABLE analyses DROP santeid_id');
    }
}
