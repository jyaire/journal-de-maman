<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200329111531 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE likes CHANGE datelike datelike DATETIME NOT NULL');
        $this->addSql('ALTER TABLE commentaires ADD com LONGTEXT NOT NULL, CHANGE datecom datecom DATETIME NOT NULL');
        $this->addSql('ALTER TABLE articles CHANGE ajoutdate ajoutdate DATETIME DEFAULT NULL, CHANGE modifdate modifdate DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE articles CHANGE ajoutdate ajoutdate DATETIME DEFAULT NULL, CHANGE modifdate modifdate DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaires DROP com, CHANGE datecom datecom DATETIME NOT NULL');
        $this->addSql('ALTER TABLE likes CHANGE datelike datelike DATETIME NOT NULL');
    }
}
