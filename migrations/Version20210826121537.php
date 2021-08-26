<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210826121537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create notification table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE notification (
                id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
                owner_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\',
                message VARCHAR(255) NOT NULL, INDEX IDX_BF5476CA7E3C61F9 (owner_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE notification');
    }
}
