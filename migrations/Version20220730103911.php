<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220730103911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location ADD lat DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE location ADD lon DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE location ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE location ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE weather ADD temperature2 INT NOT NULL');
        $this->addSql('ALTER TABLE weather ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE weather ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE weather RENAME COLUMN temperature TO temperature1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE location DROP lat');
        $this->addSql('ALTER TABLE location DROP lon');
        $this->addSql('ALTER TABLE location DROP created_at');
        $this->addSql('ALTER TABLE location DROP updated_at');
        $this->addSql('ALTER TABLE weather ADD temperature INT NOT NULL');
        $this->addSql('ALTER TABLE weather DROP temperature1');
        $this->addSql('ALTER TABLE weather DROP temperature2');
        $this->addSql('ALTER TABLE weather DROP created_at');
        $this->addSql('ALTER TABLE weather DROP updated_at');
    }
}
