<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250314111404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employee (uuid INT AUTO_INCREMENT NOT NULL, fullname VARCHAR(100) NOT NULL, PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE worktimes (id INT AUTO_INCREMENT NOT NULL, employee_id INT DEFAULT NULL, time_start DATETIME NOT NULL, time_end DATETIME NOT NULL, INDEX IDX_B9312FAF8C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE worktimes ADD CONSTRAINT FK_B9312FAF8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (uuid) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE worktimes DROP FOREIGN KEY FK_B9312FAF8C03F15C');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE worktimes');
    }
}
