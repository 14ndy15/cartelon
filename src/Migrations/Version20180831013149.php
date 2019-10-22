<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180831013149 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE image_gallery (id INT AUTO_INCREMENT NOT NULL, associate_event_id INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, path VARCHAR(255) NOT NULL, INDEX IDX_D23B2FE6B292A8BD (associate_event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image_gallery ADD CONSTRAINT FK_D23B2FE6B292A8BD FOREIGN KEY (associate_event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event ADD path VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE image_gallery');
        $this->addSql('ALTER TABLE event DROP path');
    }
}
