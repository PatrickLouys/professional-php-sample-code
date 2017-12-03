<?php declare(strict_types=1);

namespace SocialNews\User\Domain;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

final class User
{
    private $id;
    private $nickname;
    private $passwordHash;
    private $creationDate;
    private $failedLoginAttempts;
    private $lastFailedLoginAttempt;
    private $recordedEvents = [];

    public function __construct(
        UuidInterface $id,
        string $nickname,
        string $passwordHash,
        DateTimeImmutable $creationDate,
        int $failedLoginAttempts,
        ?DateTimeImmutable $lastFailedLoginAttempt
    ) {
        $this->id = $id;
        $this->nickname = $nickname;
        $this->passwordHash = $passwordHash;
        $this->creationDate = $creationDate;
        $this->failedLoginAttempts = $failedLoginAttempts;
        $this->lastFailedLoginAttempt = $lastFailedLoginAttempt;
    }

    public static function register(string $nickname, string $password): User
    {
        return new User(
            Uuid::uuid4(),
            $nickname,
            password_hash($password, PASSWORD_DEFAULT),
            new DateTimeImmutable(),
            0,
            null
        );
    }

    public function logIn(string $password): void
    {
        if (!password_verify($password, $this->passwordHash)) {
            $this->lastFailedLoginAttempt = new DateTimeImmutable();
            $this->failedLoginAttempts++;
            return;
        }
        $this->failedLoginAttempts = 0;
        $this->lastFailedLoginAttempt = null;
        $this->recordedEvents[] = new UserWasLoggedIn();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getCreationDate(): DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function getFailedLoginAttempts(): int
    {
        return $this->failedLoginAttempts;
    }

    public function getLastFailedLoginAttempt(): ?DateTimeImmutable
    {
        return $this->lastFailedLoginAttempt;
    }

    public function getRecordedEvents(): array
    {
        return $this->recordedEvents;
    }

    public function clearRecordedEvents(): void
    {
        $this->recordedEvents = [];
    }
}