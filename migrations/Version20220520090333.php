<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220520090333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB98F5EA509');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB98F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF338F5EA509');
        $this->addSql('ALTER TABLE student CHANGE image image_path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF338F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE students_grades DROP FOREIGN KEY FK_803F4472591CC992');
        $this->addSql('ALTER TABLE students_grades DROP FOREIGN KEY FK_803F4472CB944F1A');
        $this->addSql('ALTER TABLE students_grades DROP FOREIGN KEY FK_803F44728F5EA509');
        $this->addSql('ALTER TABLE students_grades ADD CONSTRAINT FK_803F4472591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE students_grades ADD CONSTRAINT FK_803F4472CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE students_grades ADD CONSTRAINT FK_803F44728F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB98F5EA509');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB98F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF338F5EA509');
        $this->addSql('ALTER TABLE student CHANGE image_path image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF338F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE students_grades DROP FOREIGN KEY FK_803F4472CB944F1A');
        $this->addSql('ALTER TABLE students_grades DROP FOREIGN KEY FK_803F4472591CC992');
        $this->addSql('ALTER TABLE students_grades DROP FOREIGN KEY FK_803F44728F5EA509');
        $this->addSql('ALTER TABLE students_grades ADD CONSTRAINT FK_803F4472CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE students_grades ADD CONSTRAINT FK_803F4472591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE students_grades ADD CONSTRAINT FK_803F44728F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE');
    }
}
