<?php declare(strict_types=1);

namespace SocialNews\Submission\Infrastructure;

use Doctrine\DBAL\Connection;
use SocialNews\Submission\Domain\Submission;
use SocialNews\Submission\Domain\SubmissionRepository;

final class DbalSubmissionRepository implements SubmissionRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function add(Submission $submission): void
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->insert('submissions');
        $qb->values([
            'id' => $qb->createNamedParameter($submission->getId()->toString()),
            'title' => $qb->createNamedParameter($submission->getTitle()),
            'url' => $qb->createNamedParameter($submission->getUrl()),
            'creation_date' => $qb->createNamedParameter(
                $submission->getCreationDate(),
                'datetime'
            ),
            'author_user_id' => $qb->createNamedParameter(
                $submission->getAuthorId()->toString()
            ),
        ]);

        $qb->execute();
    }
}