<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211134932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medicament ADD duration INT NOT NULL, DROP start_date, DROP end_date');
        $this->addSql('ALTER TABLE ordonnance DROP FOREIGN KEY FK_924B326C32B07E31');
        $this->addSql('ALTER TABLE ordonnance DROP FOREIGN KEY FK_924B326CEA724598');
        $this->addSql('DROP INDEX UNIQ_924B326CEA724598 ON ordonnance');
        $this->addSql('DROP INDEX IDX_924B326C32B07E31 ON ordonnance');
        $this->addSql('ALTER TABLE ordonnance ADD doctor_id INT NOT NULL, ADD description LONGTEXT DEFAULT NULL, DROP doctor_id_id, CHANGE patient_id_id patient_id INT NOT NULL');
        $this->addSql('ALTER TABLE ordonnance ADD CONSTRAINT FK_924B326C6B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ordonnance ADD CONSTRAINT FK_924B326C87F4FB17 FOREIGN KEY (doctor_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_924B326C6B899279 ON ordonnance (patient_id)');
        $this->addSql('CREATE INDEX IDX_924B326C87F4FB17 ON ordonnance (doctor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medicament ADD start_date DATETIME NOT NULL, ADD end_date DATETIME NOT NULL, DROP duration');
        $this->addSql('ALTER TABLE ordonnance DROP FOREIGN KEY FK_924B326C6B899279');
        $this->addSql('ALTER TABLE ordonnance DROP FOREIGN KEY FK_924B326C87F4FB17');
        $this->addSql('DROP INDEX IDX_924B326C6B899279 ON ordonnance');
        $this->addSql('DROP INDEX IDX_924B326C87F4FB17 ON ordonnance');
        $this->addSql('ALTER TABLE ordonnance ADD patient_id_id INT NOT NULL, ADD doctor_id_id INT DEFAULT NULL, DROP patient_id, DROP doctor_id, DROP description');
        $this->addSql('ALTER TABLE ordonnance ADD CONSTRAINT FK_924B326C32B07E31 FOREIGN KEY (doctor_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ordonnance ADD CONSTRAINT FK_924B326CEA724598 FOREIGN KEY (patient_id_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_924B326CEA724598 ON ordonnance (patient_id_id)');
        $this->addSql('CREATE INDEX IDX_924B326C32B07E31 ON ordonnance (doctor_id_id)');
    }
}
