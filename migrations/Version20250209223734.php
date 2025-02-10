<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250209223734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rendez_vous (id INT AUTO_INCREMENT NOT NULL, patient_id_id INT NOT NULL, doctor_id_id INT NOT NULL, date_rendez_vous DATE NOT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_65E8AA0AEA724598 (patient_id_id), INDEX IDX_65E8AA0A32B07E31 (doctor_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0AEA724598 FOREIGN KEY (patient_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A32B07E31 FOREIGN KEY (doctor_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ordonnance ADD patient_id_id INT NOT NULL, ADD doctor_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ordonnance ADD CONSTRAINT FK_924B326CEA724598 FOREIGN KEY (patient_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ordonnance ADD CONSTRAINT FK_924B326C32B07E31 FOREIGN KEY (doctor_id_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_924B326CEA724598 ON ordonnance (patient_id_id)');
        $this->addSql('CREATE INDEX IDX_924B326C32B07E31 ON ordonnance (doctor_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0AEA724598');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A32B07E31');
        $this->addSql('DROP TABLE rendez_vous');
        $this->addSql('ALTER TABLE ordonnance DROP FOREIGN KEY FK_924B326CEA724598');
        $this->addSql('ALTER TABLE ordonnance DROP FOREIGN KEY FK_924B326C32B07E31');
        $this->addSql('DROP INDEX UNIQ_924B326CEA724598 ON ordonnance');
        $this->addSql('DROP INDEX IDX_924B326C32B07E31 ON ordonnance');
        $this->addSql('ALTER TABLE ordonnance DROP patient_id_id, DROP doctor_id_id');
    }
}
