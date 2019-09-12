<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190911221216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE link ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, DROP createdAt, DROP updatedAt');
        $this->addSql('ALTER TABLE tag ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, DROP createdAt, DROP updatedAt');
        $this->addSql('ALTER TABLE counter ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, DROP createdAt, DROP updatedAt');
        $this->addSql('ALTER TABLE users ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, DROP createdAt, DROP updatedAt, CHANGE firstname first_name VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE counter ADD createdAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE link ADD createdAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE tag ADD createdAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE users ADD createdAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at, CHANGE first_name firstName VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci');
    }
}
