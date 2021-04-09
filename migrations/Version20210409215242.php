<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210409215242 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create initial file data structure';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE file (id VARCHAR(36) NOT NULL, checksum CHAR(40) NOT NULL, mimetype VARCHAR(255) NOT NULL, exif JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8C9F3610DE6FDF9A ON file (checksum)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE file');
    }
}
