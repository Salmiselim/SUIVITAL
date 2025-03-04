<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250303055309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A32B07E31');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0AEA724598');
        $this->addSql('DROP INDEX IDX_65E8AA0AEA724598 ON rendez_vous');
        $this->addSql('DROP INDEX IDX_65E8AA0A32B07E31 ON rendez_vous');
        $this->addSql('ALTER TABLE rendez_vous ADD patient_id INT NOT NULL, ADD doctor_id INT NOT NULL, DROP patient_id_id, DROP doctor_id_id, CHANGE date_rendez_vous date_rendez_vous DATETIME NOT NULL');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A6B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A87F4FB17 FOREIGN KEY (doctor_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0A6B899279 ON rendez_vous (patient_id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0A87F4FB17 ON rendez_vous (doctor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A6B899279');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A87F4FB17');
        $this->addSql('DROP INDEX IDX_65E8AA0A6B899279 ON rendez_vous');
        $this->addSql('DROP INDEX IDX_65E8AA0A87F4FB17 ON rendez_vous');
        $this->addSql('ALTER TABLE rendez_vous ADD patient_id_id INT NOT NULL, ADD doctor_id_id INT NOT NULL, DROP patient_id, DROP doctor_id, CHANGE date_rendez_vous date_rendez_vous DATE NOT NULL');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A32B07E31 FOREIGN KEY (doctor_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0AEA724598 FOREIGN KEY (patient_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0AEA724598 ON rendez_vous (patient_id_id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0A32B07E31 ON rendez_vous (doctor_id_id)');
    }
}
