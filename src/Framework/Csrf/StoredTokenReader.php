<?php declare(strict_types=1);

namespace SocialNews\Framework\Csrf;

final class StoredTokenReader
{
    private $tokenStorage;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function read(string $key): Token
    {
        $token = $this->tokenStorage->retrieve($key);

        if ($token !== null) {
            return $token;
        }

        $token = Token::generate();
        $this->tokenStorage->store($key, $token);

        return $token;
    }
}