<?php declare(strict_types=1);

namespace SocialNews\User\Infrastructure;

use Doctrine\DBAL\Connection;
use SocialNews\User\Application\NicknameTakenQuery;

final class DbalNicknameTakenQuery implements NicknameTakenQuery
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function execute(string $nickname): bool
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('count(*)');
        $qb->from('users');
        $qb->where("nickname = {$qb->createNamedParameter($nickname)}");
        $qb->execute();

        $stmt = $qb->execute();
        return (bool)$stmt->fetchColumn();
    }
}