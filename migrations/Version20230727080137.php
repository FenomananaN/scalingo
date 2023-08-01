<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230727080137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cash_out_rp (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, rp VARCHAR(255) NOT NULL, rprate VARCHAR(255) NOT NULL, mgavalue VARCHAR(255) NOT NULL, date DATETIME NOT NULL, INDEX IDX_850815E5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rpmanager (id INT AUTO_INCREMENT NOT NULL, rpinitial INT NOT NULL, rrariary INT NOT NULL, pobtenu INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cash_out_rp ADD CONSTRAINT FK_850815E5A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cash_out_rp DROP FOREIGN KEY FK_850815E5A76ED395');
        $this->addSql('DROP TABLE cash_out_rp');
        $this->addSql('DROP TABLE rpmanager');
    }
}
