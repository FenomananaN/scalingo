<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230807043538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cash_out_rp (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, rp VARCHAR(255) NOT NULL, rprate VARCHAR(255) NOT NULL, mgavalue VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, being_processed TINYINT(1) NOT NULL, verified TINYINT(1) NOT NULL, cashout_successed TINYINT(1) DEFAULT NULL, cashout_failed TINYINT(1) DEFAULT NULL, cashout_at DATETIME NOT NULL, INDEX IDX_850815E567B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cash_out_rp ADD CONSTRAINT FK_850815E567B3B43D FOREIGN KEY (users_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cash_out_rp DROP FOREIGN KEY FK_850815E567B3B43D');
        $this->addSql('DROP TABLE cash_out_rp');
    }
}
