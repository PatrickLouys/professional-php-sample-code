<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

final class Migration201706040802
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function migrate(): void
    {
        $schema = new Schema();
        $this->createUsersTable($schema);

        $queries = $schema->toSql($this->connection->getDatabasePlatform());
        foreach ($queries as $query) {
            $this->connection->executeQuery($query);
        }
    }

    private function createUsersTable(Schema $schema): void
    {
        $table = $schema->createTable('users');
        $table->addColumn('id', Type::GUID);
        $table->addColumn('nickname', Type::STRING);
        $table->addColumn('password_hash', TYPE::STRING);
        $table->addColumn('creation_date', TYPE::DATETIME);
        $table->addColumn('failed_login_attempts', TYPE::INTEGER, [
            'default' => 0,
        ]);
        $table->addColumn('last_failed_login_attempt', TYPE::DATETIME, [
            'notnull' => false,
        ]);
    }
}