<?php

declare(strict_types=1);

namespace Hubertinio\SyliusKeyValuePlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250220205017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Unique index for key and collection';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_BBEADE3A8A90ABA9 ON hubertinio_key_value');
        $this->addSql('CREATE UNIQUE INDEX key_collection ON hubertinio_key_value (`key`, collection)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX key_collection ON hubertinio_key_value');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BBEADE3A8A90ABA9 ON hubertinio_key_value (`key`)');
    }
}
