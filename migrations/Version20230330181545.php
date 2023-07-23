<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230330181545 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE depot (id INT AUTO_INCREMENT NOT NULL, wallet_id INT NOT NULL, gasy_wallet_id INT NOT NULL, user_id_id INT NOT NULL, solde_demande VARCHAR(255) NOT NULL, numero_compte VARCHAR(255) NOT NULL, total_to_paid VARCHAR(255) NOT NULL, date DATETIME NOT NULL, confirmed_demand TINYINT(1) NOT NULL, manual_verification TINYINT(1) NOT NULL, cours VARCHAR(255) NOT NULL, reference_manavola VARCHAR(255) NOT NULL, INDEX IDX_47948BBC712520F3 (wallet_id), INDEX IDX_47948BBCE246891F (gasy_wallet_id), INDEX IDX_47948BBC9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gasy_wallet (id INT AUTO_INCREMENT NOT NULL, gasy_wallet_name VARCHAR(255) NOT NULL, reserve VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, fullname VARCHAR(255) DEFAULT NULL, telma VARCHAR(255) DEFAULT NULL, orange VARCHAR(255) DEFAULT NULL, airtel VARCHAR(255) DEFAULT NULL, verified_status TINYINT(1) NOT NULL, fidelity_pt INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wallet (id INT AUTO_INCREMENT NOT NULL, wallet_name VARCHAR(255) NOT NULL, reserve VARCHAR(255) NOT NULL, currency VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBC712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBCE246891F FOREIGN KEY (gasy_wallet_id) REFERENCES gasy_wallet (id)');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBC9D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBC712520F3');
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBCE246891F');
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBC9D86650F');
        $this->addSql('DROP TABLE depot');
        $this->addSql('DROP TABLE gasy_wallet');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE wallet');
    }
}
