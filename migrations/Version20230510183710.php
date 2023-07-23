<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230510183710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE retrait (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, gasy_wallet_id INT NOT NULL, global_wallet_id INT NOT NULL, solde_demmande VARCHAR(255) NOT NULL, total_to_receive VARCHAR(255) NOT NULL, cours VARCHAR(255) NOT NULL, policy_agreement TINYINT(1) NOT NULL, being_processed TINYINT(1) NOT NULL, verified TINYINT(1) NOT NULL, boolean VARCHAR(255) DEFAULT NULL, transac_done TINYINT(1) NOT NULL, INDEX IDX_D9846A51A76ED395 (user_id), INDEX IDX_D9846A51E246891F (gasy_wallet_id), INDEX IDX_D9846A51AD244B6A (global_wallet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE retrait ADD CONSTRAINT FK_D9846A51A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE retrait ADD CONSTRAINT FK_D9846A51E246891F FOREIGN KEY (gasy_wallet_id) REFERENCES gasy_wallet (id)');
        $this->addSql('ALTER TABLE retrait ADD CONSTRAINT FK_D9846A51AD244B6A FOREIGN KEY (global_wallet_id) REFERENCES global_wallet (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE retrait DROP FOREIGN KEY FK_D9846A51A76ED395');
        $this->addSql('ALTER TABLE retrait DROP FOREIGN KEY FK_D9846A51E246891F');
        $this->addSql('ALTER TABLE retrait DROP FOREIGN KEY FK_D9846A51AD244B6A');
        $this->addSql('DROP TABLE retrait');
    }
}
