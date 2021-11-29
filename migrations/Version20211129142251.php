<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211129142251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media ADD is_main TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE trick CHANGE date_creation date_creation VARCHAR(255) NOT NULL, CHANGE date_update date_update VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media DROP is_main');
        $this->addSql('ALTER TABLE trick CHANGE date_creation date_creation DATETIME NOT NULL, CHANGE date_update date_update DATETIME NOT NULL');
    }
}
