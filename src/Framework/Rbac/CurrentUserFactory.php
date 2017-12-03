<?php declare(strict_types=1);

namespace SocialNews\Framework\Rbac;

interface CurrentUserFactory
{
    public function create(): User;
}