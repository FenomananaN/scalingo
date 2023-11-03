<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231030182057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comission (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, comission_from_id INT NOT NULL, mgavalue VARCHAR(255) NOT NULL, comission VARCHAR(255) NOT NULL, comission_at DATETIME NOT NULL, INDEX IDX_8727369AA76ED395 (user_id), INDEX IDX_8727369A601A592B (comission_from_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comission_cash_out (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, mgavalue VARCHAR(255) NOT NULL, numero VARCHAR(255) DEFAULT NULL, cash_out_at DATETIME NOT NULL, being_processed TINYINT(1) NOT NULL, verified TINYINT(1) NOT NULL, success TINYINT(1) NOT NULL, failed TINYINT(1) NOT NULL, INDEX IDX_DCC023B4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comission ADD CONSTRAINT FK_8727369AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comission ADD CONSTRAINT FK_8727369A601A592B FOREIGN KEY (comission_from_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comission_cash_out ADD CONSTRAINT FK_DCC023B4A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user ADD current_comission VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comission DROP FOREIGN KEY FK_8727369AA76ED395');
        $this->addSql('ALTER TABLE comission DROP FOREIGN KEY FK_8727369A601A592B');
        $this->addSql('ALTER TABLE comission_cash_out DROP FOREIGN KEY FK_DCC023B4A76ED395');
        $this->addSql('DROP TABLE comission');
        $this->addSql('DROP TABLE comission_cash_out');
        $this->addSql('ALTER TABLE `user` DROP current_comission');
    }
}
