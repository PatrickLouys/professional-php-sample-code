<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Connection;

final class Migration201704151205
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function migrate(): void
    {
        $schema = new Schema();
        $this->createSubmissionsTable($schema);
        $queries = $schema->toSql($this->connection->getDatabasePlatform());
        foreach ($queries as $query) {
            $this->connection->executeQuery($query);
        }
    }

    private function createSubmissionsTable(Schema $schema): void
    {
        $table = $schema->createTable('submissions');
        $table->addColumn('id', Type::GUID);
        $table->addColumn('title', Type::STRING);
        $table->addColumn('url', Type::STRING);
        $table->addColumn('creation_date', Type::DATETIME);
        $table->addColumn('author_user_id', Type::GUID);
    }
}