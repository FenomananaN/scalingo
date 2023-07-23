<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230411142331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours_depot DROP FOREIGN KEY FK_77F2026A712520F3');
        $this->addSql('DROP TABLE cours_depot');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cours_depot (id INT AUTO_INCREMENT NOT NULL, wallet_id INT DEFAULT NULL, cours INT NOT NULL, plage_a INT NOT NULL, plage_b INT NOT NULL, INDEX IDX_77F2026A712520F3 (wallet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cours_depot ADD CONSTRAINT FK_77F2026A712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id)');
    }
}
