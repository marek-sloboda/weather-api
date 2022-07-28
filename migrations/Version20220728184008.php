<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220728184008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE weather_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE weather (id INT NOT NULL, location_id INT NOT NULL, temperature INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4CD0D36E64D218E ON weather (location_id)');
        $this->addSql('ALTER TABLE weather ADD CONSTRAINT FK_4CD0D36E64D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE weather_id_seq CASCADE');
        $this->addSql('DROP TABLE weather');
    }
}
