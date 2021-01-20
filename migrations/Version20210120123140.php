<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210120123140 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lesson_tag (lesson_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_71FC1160CDF80196 (lesson_id), INDEX IDX_71FC1160BAD26311 (tag_id), PRIMARY KEY(lesson_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lesson_tag ADD CONSTRAINT FK_71FC1160CDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lesson_tag ADD CONSTRAINT FK_71FC1160BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD lesson_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CCDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id)');
        $this->addSql('CREATE INDEX IDX_9474526CCDF80196 ON comment (lesson_id)');
        $this->addSql('ALTER TABLE lesson CHANGE content content MEDIUMTEXT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE lesson_tag');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CCDF80196');
        $this->addSql('DROP INDEX IDX_9474526CCDF80196 ON comment');
        $this->addSql('ALTER TABLE comment DROP lesson_id');
        $this->addSql('ALTER TABLE lesson CHANGE content content MEDIUMTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
