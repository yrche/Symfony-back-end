<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250207224142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog_likes (blog_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(blog_id, user_id))');
        $this->addSql('CREATE INDEX IDX_B9250DB5DAE07E97 ON blog_likes (blog_id)');
        $this->addSql('CREATE INDEX IDX_B9250DB5A76ED395 ON blog_likes (user_id)');
        $this->addSql('ALTER TABLE blog_likes ADD CONSTRAINT FK_B9250DB5DAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE blog_likes ADD CONSTRAINT FK_B9250DB5A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE blog ADD likes_count INT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE blog_likes DROP CONSTRAINT FK_B9250DB5DAE07E97');
        $this->addSql('ALTER TABLE blog_likes DROP CONSTRAINT FK_B9250DB5A76ED395');
        $this->addSql('DROP TABLE blog_likes');
        $this->addSql('ALTER TABLE blog DROP likes_count');
    }
}
