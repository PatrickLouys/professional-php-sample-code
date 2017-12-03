<?php declare(strict_types=1);

namespace SocialNews\FrontPage\Application;

final class Submission
{
    private $url;
    private $title;
    private $author;

    public function __construct(string $url, string $title, string $author)
    {
        $this->url = $url;
        $this->title = $title;
        $this->author = $author;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }
}