<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210204131738 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE journaux ADD couleur VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE articles CHANGE ajoutdate ajoutdate DATETIME DEFAULT NULL, CHANGE modifdate modifdate DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaires CHANGE datecom datecom DATETIME NOT NULL');
        $this->addSql('ALTER TABLE documents CHANGE date date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE likes CHANGE datelike datelike DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE articles CHANGE ajoutdate ajoutdate DATETIME DEFAULT NULL, CHANGE modifdate modifdate DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaires CHANGE datecom datecom DATETIME NOT NULL');
        $this->addSql('ALTER TABLE documents CHANGE date date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE journaux DROP couleur');
        $this->addSql('ALTER TABLE likes CHANGE datelike datelike DATETIME NOT NULL');
    }
}
