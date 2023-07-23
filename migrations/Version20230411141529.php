<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230411141529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depot ADD global_wallet_id INT NOT NULL');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBCAD244B6A FOREIGN KEY (global_wallet_id) REFERENCES global_wallet (id)');
        $this->addSql('CREATE INDEX IDX_47948BBCAD244B6A ON depot (global_wallet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBCAD244B6A');
        $this->addSql('DROP INDEX IDX_47948BBCAD244B6A ON depot');
        $this->addSql('ALTER TABLE depot DROP global_wallet_id');
    }
}
