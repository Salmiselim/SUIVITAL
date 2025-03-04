<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250302004304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Removed duplicate foreign key constraint
   
    }

    public function down(Schema $schema): void
    {
        // Only drop the index, since the FK is already handled elsewhere
        $this->addSql('DROP INDEX IDX_5FB6DEC72D6BA2D9 ON reponse');
    }
}
