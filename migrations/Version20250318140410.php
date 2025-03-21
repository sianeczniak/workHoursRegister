<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250318140410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE worktimes DROP FOREIGN KEY FK_B9312FAF8C03F15C');
        $this->addSql('ALTER TABLE employee CHANGE uuid uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE worktimes CHANGE employee_id employee_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE worktimes ADD CONSTRAINT FK_B9312FAF8C03F15C FOREIGN KEY (employee_id) REFERENCES employee(uuid) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE worktimes DROP FOREIGN KEY FK_B9312FAF8C03F15C');
        $this->addSql('ALTER TABLE employee CHANGE uuid uuid INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE worktimes CHANGE employee_id employee_id INT NOT NULL');
        $this->addSql('ALTER TABLE worktimes ADD CONSTRAINT FK_B9312FAF8C03F15C FOREIGN KEY (employee_id) REFERENCES employee(uuid) ON DELETE CASCADE');
    }
}
