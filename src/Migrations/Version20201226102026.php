<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201226102026 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE documents (id INT AUTO_INCREMENT NOT NULL, poster_id INT NOT NULL, article_id INT NOT NULL, file VARCHAR(255) NOT NULL, date DATETIME NOT NULL, INDEX IDX_A2B072885BB66C05 (poster_id), INDEX IDX_A2B072887294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B072885BB66C05 FOREIGN KEY (poster_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B072887294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE articles CHANGE ajoutdate ajoutdate DATETIME DEFAULT NULL, CHANGE modifdate modifdate DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaires CHANGE datecom datecom DATETIME NOT NULL');
        $this->addSql('ALTER TABLE likes CHANGE datelike datelike DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE documents');
        $this->addSql('ALTER TABLE articles CHANGE ajoutdate ajoutdate DATETIME DEFAULT NULL, CHANGE modifdate modifdate DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaires CHANGE datecom datecom DATETIME NOT NULL');
        $this->addSql('ALTER TABLE likes CHANGE datelike datelike DATETIME NOT NULL');
    }
}
