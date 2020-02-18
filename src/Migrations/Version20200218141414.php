<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200218141414 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE articles ADD ajouteur_id INT DEFAULT NULL, ADD modifieur_id INT DEFAULT NULL, ADD auteur VARCHAR(255) DEFAULT NULL, ADD ajoutdate DATETIME DEFAULT NULL, ADD modifdate DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD31687268CC77 FOREIGN KEY (ajouteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD31681F5F7FEB FOREIGN KEY (modifieur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_BFDD31687268CC77 ON articles (ajouteur_id)');
        $this->addSql('CREATE INDEX IDX_BFDD31681F5F7FEB ON articles (modifieur_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD31687268CC77');
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD31681F5F7FEB');
        $this->addSql('DROP INDEX IDX_BFDD31687268CC77 ON articles');
        $this->addSql('DROP INDEX IDX_BFDD31681F5F7FEB ON articles');
        $this->addSql('ALTER TABLE articles DROP ajouteur_id, DROP modifieur_id, DROP auteur, DROP ajoutdate, DROP modifdate');
    }
}
