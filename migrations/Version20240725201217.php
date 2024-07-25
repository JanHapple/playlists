<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240725201217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D782112D5E237E06 ON playlist (name)');
        $this->addSql('ALTER TABLE song ADD min_duration INT NOT NULL, ADD sec_duration INT NOT NULL, DROP duration');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_D782112D5E237E06 ON playlist');
        $this->addSql('ALTER TABLE song ADD duration TIME NOT NULL, DROP min_duration, DROP sec_duration');
    }
}
