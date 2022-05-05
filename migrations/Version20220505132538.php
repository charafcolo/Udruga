<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505132538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD association_member_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D170DA8B FOREIGN KEY (association_member_id) REFERENCES association (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649D170DA8B ON user (association_member_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D170DA8B');
        $this->addSql('DROP INDEX IDX_8D93D649D170DA8B ON user');
        $this->addSql('ALTER TABLE user DROP association_member_id');
    }
}
