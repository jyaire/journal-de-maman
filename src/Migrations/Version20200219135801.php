<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200219135801 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE likes (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, auteurlike_id INT NOT NULL, datelike DATETIME NOT NULL, INDEX IDX_49CA4E7D7294869C (article_id), INDEX IDX_49CA4E7D557B68C3 (auteurlike_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaires (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, auteurcom_id INT NOT NULL, datecom DATETIME NOT NULL, INDEX IDX_D9BEC0C47294869C (article_id), INDEX IDX_D9BEC0C4F40809D2 (auteurcom_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D7294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D557B68C3 FOREIGN KEY (auteurlike_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C47294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4F40809D2 FOREIGN KEY (auteurcom_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE articles CHANGE ajoutdate ajoutdate DATETIME DEFAULT NULL, CHANGE modifdate modifdate DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE likes');
        $this->addSql('DROP TABLE commentaires');
        $this->addSql('ALTER TABLE articles CHANGE ajoutdate ajoutdate DATETIME DEFAULT NULL, CHANGE modifdate modifdate DATETIME DEFAULT NULL');
    }
}
