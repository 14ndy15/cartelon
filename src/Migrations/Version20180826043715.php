<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180826043715 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, date_time DATETIME DEFAULT NULL, place VARCHAR(255) DEFAULT NULL, image VARCHAR(500) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(500) NOT NULL, text LONGTEXT DEFAULT NULL, image VARCHAR(500) DEFAULT NULL, file VARCHAR(500) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poster (id INT AUTO_INCREMENT NOT NULL, associate_event_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(500) DEFAULT NULL, author VARCHAR(255) NOT NULL, image VARCHAR(500) NOT NULL, INDEX IDX_2D710CF2B292A8BD (associate_event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poster_event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, date DATE DEFAULT NULL, position INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slide (id INT AUTO_INCREMENT NOT NULL, background_image VARCHAR(255) NOT NULL, poster VARCHAR(500) DEFAULT NULL, position INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE poster ADD CONSTRAINT FK_2D710CF2B292A8BD FOREIGN KEY (associate_event_id) REFERENCES poster_event (id)');
        $this->addSql('ALTER TABLE video ADD position INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE poster DROP FOREIGN KEY FK_2D710CF2B292A8BD');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE poster');
        $this->addSql('DROP TABLE poster_event');
        $this->addSql('DROP TABLE slide');
        $this->addSql('ALTER TABLE video DROP position');
    }
}
