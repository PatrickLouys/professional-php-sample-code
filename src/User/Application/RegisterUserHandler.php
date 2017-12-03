<?php declare(strict_types=1);

namespace SocialNews\User\Application;

use SocialNews\User\Domain\UserRepository;
use SocialNews\User\Domain\User;

final class RegisterUserHandler
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(RegisterUser $command): void
    {
        $user = User::register(
            $command->getNickname(),
            $command->getPassword()
        );
        $this->userRepository->add($user);
    }
}