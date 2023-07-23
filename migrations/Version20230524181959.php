<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230524181959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, global_wallet_id INT NOT NULL, gasy_wallet_id INT DEFAULT NULL, transaction_type VARCHAR(255) NOT NULL, solde VARCHAR(255) NOT NULL, account_number VARCHAR(255) DEFAULT NULL, solde_ariary VARCHAR(255) NOT NULL, cours VARCHAR(255) NOT NULL, date DATETIME NOT NULL, being_processed TINYINT(1) NOT NULL, verified TINYINT(1) NOT NULL, transaction_done TINYINT(1) NOT NULL, policy_agreement TINYINT(1) NOT NULL, reference_manavola VARCHAR(255) NOT NULL, transaction_id VARCHAR(255) DEFAULT NULL, INDEX IDX_723705D1A76ED395 (user_id), INDEX IDX_723705D1AD244B6A (global_wallet_id), INDEX IDX_723705D1E246891F (gasy_wallet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1AD244B6A FOREIGN KEY (global_wallet_id) REFERENCES global_wallet (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1E246891F FOREIGN KEY (gasy_wallet_id) REFERENCES gasy_wallet (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1A76ED395');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1AD244B6A');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1E246891F');
        $this->addSql('DROP TABLE transaction');
    }
}
