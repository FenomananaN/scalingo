<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230402171602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE main_wallet_wallet DROP FOREIGN KEY FK_7E6CBEC6712520F3');
        $this->addSql('ALTER TABLE main_wallet_wallet DROP FOREIGN KEY FK_7E6CBEC651938751');
        $this->addSql('DROP TABLE main_wallet_wallet');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE main_wallet_wallet (main_wallet_id INT NOT NULL, wallet_id INT NOT NULL, INDEX IDX_7E6CBEC651938751 (main_wallet_id), INDEX IDX_7E6CBEC6712520F3 (wallet_id), PRIMARY KEY(main_wallet_id, wallet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE main_wallet_wallet ADD CONSTRAINT FK_7E6CBEC6712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE main_wallet_wallet ADD CONSTRAINT FK_7E6CBEC651938751 FOREIGN KEY (main_wallet_id) REFERENCES main_wallet (id) ON DELETE CASCADE');
    }
}
