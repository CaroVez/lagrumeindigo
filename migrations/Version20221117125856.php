<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221117125856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contract (
            id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', 
            franchise_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', 
            gym_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', 
            send_newsletter TINYINT(1) NOT NULL, 
            team_planning TINYINT(1) NOT NULL, 
            sell_drinks TINYINT(1) NOT NULL, 
            promotion TINYINT(1) NOT NULL, 
            payment_schedules TINYINT(1) NOT NULL, 
            statistics TINYINT(1) NOT NULL, 
            UNIQUE INDEX UNIQ_E98F2859523CAB89 (franchise_id), 
            UNIQUE INDEX UNIQ_E98F2859BD2F03 (gym_id), 
            PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('CREATE TABLE franchise (
            id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', 
            user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', 
            name VARCHAR(60) NOT NULL, 
            active TINYINT(1) NOT NULL, 
            UNIQUE INDEX UNIQ_66F6CE2AA76ED395 (user_id), 
            PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('CREATE TABLE gym (
            id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', 
            user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', 
            name VARCHAR(60) NOT NULL, 
            active TINYINT(1) NOT NULL, 
            address LONGTEXT NOT NULL, 
            UNIQUE INDEX UNIQ_7F27DBEDA76ED395 (user_id), 
            PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('CREATE TABLE user (
            id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', 
            email VARCHAR(180) NOT NULL, 
            roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', 
            password VARCHAR(255) NOT NULL, 
            name VARCHAR(60) NOT NULL, 
            phone VARCHAR(60) DEFAULT NULL, 
            UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), 
            PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F2859523CAB89 FOREIGN KEY (franchise_id) REFERENCES franchise (id)');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F2859BD2F03 FOREIGN KEY (gym_id) REFERENCES gym (id)');
        $this->addSql('ALTER TABLE franchise ADD CONSTRAINT FK_66F6CE2AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE gym ADD CONSTRAINT FK_7F27DBEDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contract DROP FOREIGN KEY FK_E98F2859523CAB89');
        $this->addSql('ALTER TABLE contract DROP FOREIGN KEY FK_E98F2859BD2F03');
        $this->addSql('ALTER TABLE franchise DROP FOREIGN KEY FK_66F6CE2AA76ED395');
        $this->addSql('ALTER TABLE gym DROP FOREIGN KEY FK_7F27DBEDA76ED395');
        $this->addSql('DROP TABLE contract');
        $this->addSql('DROP TABLE franchise');
        $this->addSql('DROP TABLE gym');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
