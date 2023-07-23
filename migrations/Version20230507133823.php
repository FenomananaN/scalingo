<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230507133823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE retrait_cours DROP FOREIGN KEY FK_83C1FB6D712520F3');
        $this->addSql('DROP TABLE retrait_cours');
        $this->addSql('ALTER TABLE wallet DROP cours_depot');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE retrait_cours (id INT AUTO_INCREMENT NOT NULL, wallet_id INT NOT NULL, cours INT NOT NULL, plage_a INT NOT NULL, plage_b DOUBLE PRECISION NOT NULL, INDEX IDX_83C1FB6D712520F3 (wallet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE retrait_cours ADD CONSTRAINT FK_83C1FB6D712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE wallet ADD cours_depot INT NOT NULL');
    }
}
