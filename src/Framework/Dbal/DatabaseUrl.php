<?php declare(strict_types=1);

namespace SocialNews\Framework\Dbal;

final class DatabaseUrl
{
    private $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function toString(): string
    {
        return $this->url;
    }
}