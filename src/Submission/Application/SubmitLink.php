<?php declare(strict_types=1);

namespace SocialNews\Submission\Application;

use Ramsey\Uuid\UuidInterface;

final class SubmitLink
{
    private $authorId;
    private $url;
    private $title;

    public function __construct(UuidInterface $authorId, string $url, string $title)
    {
        $this->authorId = $authorId;
        $this->url = $url;
        $this->title = $title;
    }

    public function getAuthorId(): UuidInterface
    {
        return $this->authorId;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}