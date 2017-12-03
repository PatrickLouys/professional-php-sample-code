<?php declare(strict_types=1);

namespace SocialNews\User\Presentation;

use SocialNews\Framework\Csrf\StoredTokenValidator;
use SocialNews\User\Application\NicknameTakenQuery;
use SocialNews\User\Application\RegisterUser;
use SocialNews\Framework\Csrf\Token;

final class RegisterUserForm
{
    private $storedTokenValidator;
    private $token;
    private $nickname;
    private $password;
    private $nicknameTakenQuery;

    public function __construct(
        StoredTokenValidator $storedTokenValidator,
        NicknameTakenQuery $nicknameTakenQuery,
        string $token,
        string $nickname,
        string $password
    ) {
        $this->storedTokenValidator = $storedTokenValidator;
        $this->token = $token;
        $this->nickname = $nickname;
        $this->password = $password;
        $this->nicknameTakenQuery = $nicknameTakenQuery;
    }

    public function hasValidationErrors(): bool
    {
        return (count($this->getValidationErrors()) > 0);
    }

    /**
     * @return string[]
     */
    public function getValidationErrors(): array
    {
        $errors = [];

        if (!$this->storedTokenValidator->validate(
            'registration',
            new Token($this->token)
        )) {
            $errors[] = 'Invalid token';
        }

        if (strlen($this->nickname) < 3 || strlen($this->nickname) > 20) {
            $errors[] = 'Nickname must be between 3 and 20 characters';
        }

        if (!ctype_alnum($this->nickname)) {
            $errors[] = 'Nickname can only consist of letters and numbers';
        }

        if ($this->nicknameTakenQuery->execute($this->nickname)) {
            $errors[] = 'This nickname is already being used';
        }

        if (strlen($this->password) < 8) {
            $errors[] = 'Password must be at least 8 characters';
        }

        return $errors;
    }

    public function toCommand(): RegisterUser
    {
        return new RegisterUser($this->nickname, $this->password);
    }
}