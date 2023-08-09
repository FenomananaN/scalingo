<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230806171115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE affiliated (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, commision VARCHAR(255) NOT NULL, mvx_id VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_935FEF4867B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE affiliated_level (id INT AUTO_INCREMENT NOT NULL, affiliated_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_3566E1DD3AFABA9D (affiliated_id), INDEX IDX_3566E1DD67B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE depot_cours (id INT AUTO_INCREMENT NOT NULL, wallet_id INT NOT NULL, cours_max INT NOT NULL, cours_min INT NOT NULL, montant_mrmax INT NOT NULL, UNIQUE INDEX UNIQ_9B3BDF3B712520F3 (wallet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gasy_wallet (id INT AUTO_INCREMENT NOT NULL, gasy_wallet_name VARCHAR(255) NOT NULL, reserve VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, logo_main VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE global_wallet (id INT AUTO_INCREMENT NOT NULL, main_wallet_id INT NOT NULL, wallet_id INT NOT NULL, reserve VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, frais_depot_charged TINYINT(1) NOT NULL, frais_depot DOUBLE PRECISION NOT NULL, frais_wallet DOUBLE PRECISION NOT NULL, frais_blockchain DOUBLE PRECISION NOT NULL, INDEX IDX_93F9874F51938751 (main_wallet_id), INDEX IDX_93F9874F712520F3 (wallet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_wallet (id INT AUTO_INCREMENT NOT NULL, main_wallet_name VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE old_phone_number (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, phone_number VARCHAR(255) NOT NULL, INDEX IDX_F784D5AD67B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE retrait_cours (id INT AUTO_INCREMENT NOT NULL, wallet_id INT NOT NULL, cours_max INT NOT NULL, cours_min INT NOT NULL, montant_mrmax INT NOT NULL, UNIQUE INDEX UNIQ_83C1FB6D712520F3 (wallet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rpmanager (id INT AUTO_INCREMENT NOT NULL, rpinitial INT NOT NULL, rrariary INT NOT NULL, pobtenue INT NOT NULL, rprate VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, global_wallet_id INT NOT NULL, gasy_wallet_id INT DEFAULT NULL, transaction_type VARCHAR(255) NOT NULL, solde VARCHAR(255) NOT NULL, account_number VARCHAR(255) NOT NULL, solde_ariary VARCHAR(255) NOT NULL, cours VARCHAR(255) NOT NULL, transaction_at DATETIME NOT NULL, being_processed TINYINT(1) DEFAULT NULL, verified TINYINT(1) DEFAULT NULL, transaction_done TINYINT(1) DEFAULT NULL, failed TINYINT(1) DEFAULT NULL, policy_agreement TINYINT(1) NOT NULL, reference_manavola VARCHAR(255) DEFAULT NULL, transaction_id VARCHAR(255) DEFAULT NULL, rpobtenue VARCHAR(255) NOT NULL, INDEX IDX_723705D167B3B43D (users_id), INDEX IDX_723705D1AD244B6A (global_wallet_id), INDEX IDX_723705D1E246891F (gasy_wallet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, fullname VARCHAR(255) DEFAULT NULL, telma VARCHAR(255) DEFAULT NULL, orange VARCHAR(255) DEFAULT NULL, airtel VARCHAR(255) DEFAULT NULL, current_rp VARCHAR(255) NOT NULL, verified_status TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, verification_pending TINYINT(1) DEFAULT NULL, verification_failed TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_verified_info (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, numero_cin VARCHAR(255) NOT NULL, verso_photo VARCHAR(255) NOT NULL, selfie_avec_cin VARCHAR(255) NOT NULL, verified_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_3A1E4CFA67B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wallet (id INT AUTO_INCREMENT NOT NULL, wallet_name VARCHAR(255) NOT NULL, currency VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, logo_main VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE affiliated ADD CONSTRAINT FK_935FEF4867B3B43D FOREIGN KEY (users_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE affiliated_level ADD CONSTRAINT FK_3566E1DD3AFABA9D FOREIGN KEY (affiliated_id) REFERENCES affiliated (id)');
        $this->addSql('ALTER TABLE affiliated_level ADD CONSTRAINT FK_3566E1DD67B3B43D FOREIGN KEY (users_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE depot_cours ADD CONSTRAINT FK_9B3BDF3B712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE global_wallet ADD CONSTRAINT FK_93F9874F51938751 FOREIGN KEY (main_wallet_id) REFERENCES main_wallet (id)');
        $this->addSql('ALTER TABLE global_wallet ADD CONSTRAINT FK_93F9874F712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE old_phone_number ADD CONSTRAINT FK_F784D5AD67B3B43D FOREIGN KEY (users_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE retrait_cours ADD CONSTRAINT FK_83C1FB6D712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D167B3B43D FOREIGN KEY (users_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1AD244B6A FOREIGN KEY (global_wallet_id) REFERENCES global_wallet (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1E246891F FOREIGN KEY (gasy_wallet_id) REFERENCES gasy_wallet (id)');
        $this->addSql('ALTER TABLE user_verified_info ADD CONSTRAINT FK_3A1E4CFA67B3B43D FOREIGN KEY (users_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE affiliated DROP FOREIGN KEY FK_935FEF4867B3B43D');
        $this->addSql('ALTER TABLE affiliated_level DROP FOREIGN KEY FK_3566E1DD3AFABA9D');
        $this->addSql('ALTER TABLE affiliated_level DROP FOREIGN KEY FK_3566E1DD67B3B43D');
        $this->addSql('ALTER TABLE depot_cours DROP FOREIGN KEY FK_9B3BDF3B712520F3');
        $this->addSql('ALTER TABLE global_wallet DROP FOREIGN KEY FK_93F9874F51938751');
        $this->addSql('ALTER TABLE global_wallet DROP FOREIGN KEY FK_93F9874F712520F3');
        $this->addSql('ALTER TABLE old_phone_number DROP FOREIGN KEY FK_F784D5AD67B3B43D');
        $this->addSql('ALTER TABLE retrait_cours DROP FOREIGN KEY FK_83C1FB6D712520F3');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D167B3B43D');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1AD244B6A');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1E246891F');
        $this->addSql('ALTER TABLE user_verified_info DROP FOREIGN KEY FK_3A1E4CFA67B3B43D');
        $this->addSql('DROP TABLE affiliated');
        $this->addSql('DROP TABLE affiliated_level');
        $this->addSql('DROP TABLE depot_cours');
        $this->addSql('DROP TABLE gasy_wallet');
        $this->addSql('DROP TABLE global_wallet');
        $this->addSql('DROP TABLE main_wallet');
        $this->addSql('DROP TABLE old_phone_number');
        $this->addSql('DROP TABLE retrait_cours');
        $this->addSql('DROP TABLE rpmanager');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_verified_info');
        $this->addSql('DROP TABLE wallet');
    }
}
