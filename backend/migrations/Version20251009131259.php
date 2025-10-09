<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251009131259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE products RENAME INDEX idx_d34a04ad12469de2 TO IDX_B3BA5A5A12469DE2');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories DROP deleted_at');
        $this->addSql('ALTER TABLE products DROP created_at, DROP updated_at, DROP deleted_at');
        $this->addSql('ALTER TABLE products RENAME INDEX idx_b3ba5a5a12469de2 TO IDX_D34A04AD12469DE2');
    }
}
