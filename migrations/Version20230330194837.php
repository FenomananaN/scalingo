<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230330194837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE affiliated_level (id INT AUTO_INCREMENT NOT NULL, affiliated_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3566E1DD3AFABA9D (affiliated_id), INDEX IDX_3566E1DDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE affiliated_level ADD CONSTRAINT FK_3566E1DD3AFABA9D FOREIGN KEY (affiliated_id) REFERENCES affiliated (id)');
        $this->addSql('ALTER TABLE affiliated_level ADD CONSTRAINT FK_3566E1DDA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE affiliated_level DROP FOREIGN KEY FK_3566E1DD3AFABA9D');
        $this->addSql('ALTER TABLE affiliated_level DROP FOREIGN KEY FK_3566E1DDA76ED395');
        $this->addSql('DROP TABLE affiliated_level');
    }
}
