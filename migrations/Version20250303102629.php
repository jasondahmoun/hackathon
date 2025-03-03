<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250303102629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE affectation (id INT AUTO_INCREMENT NOT NULL, emplye_id INT DEFAULT NULL, chantier_id INT DEFAULT NULL, date_affectation DATETIME NOT NULL, INDEX IDX_F4DD61D34977E388 (emplye_id), INDEX IDX_F4DD61D3D0C0049D (chantier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE besoin (id INT AUTO_INCREMENT NOT NULL, chantier_id INT DEFAULT NULL, type_poste VARCHAR(50) NOT NULL, nombre_requis INT NOT NULL, INDEX IDX_8118E811D0C0049D (chantier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chantier (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, adresse VARCHAR(255) NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chantier_employee (chantier_id INT NOT NULL, employee_id INT NOT NULL, INDEX IDX_969EA066D0C0049D (chantier_id), INDEX IDX_969EA0668C03F15C (employee_id), PRIMARY KEY(chantier_id, employee_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competence (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, chantier_actuel_id INT DEFAULT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL, role VARCHAR(100) NOT NULL, disponibilite TINYINT(1) NOT NULL, INDEX IDX_5D9F75A1C54B439 (chantier_actuel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, employe_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D6491B65292 (employe_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D34977E388 FOREIGN KEY (emplye_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D3D0C0049D FOREIGN KEY (chantier_id) REFERENCES chantier (id)');
        $this->addSql('ALTER TABLE besoin ADD CONSTRAINT FK_8118E811D0C0049D FOREIGN KEY (chantier_id) REFERENCES chantier (id)');
        $this->addSql('ALTER TABLE chantier_employee ADD CONSTRAINT FK_969EA066D0C0049D FOREIGN KEY (chantier_id) REFERENCES chantier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chantier_employee ADD CONSTRAINT FK_969EA0668C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1C54B439 FOREIGN KEY (chantier_actuel_id) REFERENCES chantier (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491B65292 FOREIGN KEY (employe_id) REFERENCES employee (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D34977E388');
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D3D0C0049D');
        $this->addSql('ALTER TABLE besoin DROP FOREIGN KEY FK_8118E811D0C0049D');
        $this->addSql('ALTER TABLE chantier_employee DROP FOREIGN KEY FK_969EA066D0C0049D');
        $this->addSql('ALTER TABLE chantier_employee DROP FOREIGN KEY FK_969EA0668C03F15C');
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A1C54B439');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491B65292');
        $this->addSql('DROP TABLE affectation');
        $this->addSql('DROP TABLE besoin');
        $this->addSql('DROP TABLE chantier');
        $this->addSql('DROP TABLE chantier_employee');
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
