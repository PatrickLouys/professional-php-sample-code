<?php declare(strict_types=1);

namespace SocialNews\FrontPage\Infrastructure;

use Doctrine\DBAL\Connection;

use SocialNews\FrontPage\Application\Submission;
use SocialNews\FrontPage\Application\SubmissionsQuery;

final class DbalSubmissionsQuery implements SubmissionsQuery
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function execute(): array
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->addSelect('submissions.title');
        $qb->addSelect('submissions.url');
        $qb->addSelect('authors.nickname');
        $qb->from('submissions');
        $qb->join(
            'submissions',
            'users',
            'authors',
            'submissions.author_user_id = authors.id'
        );
        $qb->orderBy('submissions.creation_date', 'DESC');

        $stmt = $qb->execute();
        $rows = $stmt->fetchAll();

        $submissions = [];
        foreach ($rows as $row) {
            $submissions[] = new Submission($row['url'], $row['title'], $row['nickname']);
        }
        return $submissions;
    }
}