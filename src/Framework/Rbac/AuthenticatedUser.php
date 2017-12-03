<?php declare(strict_types=1);

namespace SocialNews\Framework\Rbac;

use Ramsey\Uuid\UuidInterface;

final class AuthenticatedUser implements User
{
    private $id;
    private $roles = [];

    /**
     * @param Role[] $roles
     */
    public function __construct(UuidInterface $id, array $roles)
    {
        $this->id = $id;
        $this->roles = $roles;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function hasPermission(Permission $permission): bool
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }
}