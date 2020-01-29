<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200129093243 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE articles ADD auteur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD316860BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_BFDD316860BB6FE6 ON articles (auteur_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD316860BB6FE6');
        $this->addSql('DROP INDEX IDX_BFDD316860BB6FE6 ON articles');
        $this->addSql('ALTER TABLE articles DROP auteur_id');
    }
}
