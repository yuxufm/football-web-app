<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230509223404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE player_transfers (id INT AUTO_INCREMENT NOT NULL, player_id INT NOT NULL, buying_team_id INT NOT NULL, selling_team_id INT NOT NULL, transfer_amount INT NOT NULL, transfer_date DATETIME NOT NULL, INDEX IDX_20260C2199E6F5DF (player_id), INDEX IDX_20260C214683D018 (buying_team_id), INDEX IDX_20260C21952E86F3 (selling_team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE players (id INT AUTO_INCREMENT NOT NULL, team_id INT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, transfer_fee INT NOT NULL, is_open_for_transfer TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_264E43A6296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teams (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, money_balance INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE player_transfers ADD CONSTRAINT FK_20260C2199E6F5DF FOREIGN KEY (player_id) REFERENCES players (id)');
        $this->addSql('ALTER TABLE player_transfers ADD CONSTRAINT FK_20260C214683D018 FOREIGN KEY (buying_team_id) REFERENCES teams (id)');
        $this->addSql('ALTER TABLE player_transfers ADD CONSTRAINT FK_20260C21952E86F3 FOREIGN KEY (selling_team_id) REFERENCES teams (id)');
        $this->addSql('ALTER TABLE players ADD CONSTRAINT FK_264E43A6296CD8AE FOREIGN KEY (team_id) REFERENCES teams (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player_transfers DROP FOREIGN KEY FK_20260C2199E6F5DF');
        $this->addSql('ALTER TABLE player_transfers DROP FOREIGN KEY FK_20260C214683D018');
        $this->addSql('ALTER TABLE player_transfers DROP FOREIGN KEY FK_20260C21952E86F3');
        $this->addSql('ALTER TABLE players DROP FOREIGN KEY FK_264E43A6296CD8AE');
        $this->addSql('DROP TABLE player_transfers');
        $this->addSql('DROP TABLE players');
        $this->addSql('DROP TABLE teams');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
