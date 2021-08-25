<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210825092913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create forum tables.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE post (
                id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
                thread_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\',
                author_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\',
                message TEXT NOT NULL,
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                INDEX IDX_5A8A6C8DE2904019 (thread_id), INDEX IDX_5A8A6C8DF675F31B (author_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE thread (
                id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
                author_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\',
                title VARCHAR(64) NOT NULL,
                text TEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                INDEX IDX_31204C83F675F31B (author_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DE2904019 FOREIGN KEY (thread_id) REFERENCES thread (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C83F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DE2904019');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE thread');
    }
}
