<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230402172148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE global_wallet (id INT AUTO_INCREMENT NOT NULL, main_wallet_id INT NOT NULL, wallet_id INT NOT NULL, reserve VARCHAR(255) NOT NULL, INDEX IDX_93F9874F51938751 (main_wallet_id), INDEX IDX_93F9874F712520F3 (wallet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE global_wallet ADD CONSTRAINT FK_93F9874F51938751 FOREIGN KEY (main_wallet_id) REFERENCES main_wallet (id)');
        $this->addSql('ALTER TABLE global_wallet ADD CONSTRAINT FK_93F9874F712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE global_wallet DROP FOREIGN KEY FK_93F9874F51938751');
        $this->addSql('ALTER TABLE global_wallet DROP FOREIGN KEY FK_93F9874F712520F3');
        $this->addSql('DROP TABLE global_wallet');
    }
}
