<?php declare(strict_types=1);

use Auryn\Injector;
use SocialNews\Framework\Rendering\TemplateRenderer;
use SocialNews\Framework\Rendering\TwigTemplateRendererFactory;
use SocialNews\Framework\Rendering\TemplateDirectory;
use Doctrine\DBAL\Connection;
use SocialNews\Framework\Dbal\ConnectionFactory;
use SocialNews\Framework\Dbal\DatabaseUrl;
use SocialNews\FrontPage\Infrastructure\DbalSubmissionsQuery;
use SocialNews\FrontPage\Application\SubmissionsQuery;
use SocialNews\Framework\Csrf\TokenStorage;
use SocialNews\Framework\Csrf\SymfonySessionTokenStorage;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use SocialNews\Submission\Domain\SubmissionRepository;
use SocialNews\Submission\Infrastructure\DbalSubmissionRepository;
use SocialNews\User\Domain\UserRepository;
use SocialNews\User\Infrastructure\DbalUserRepository;
use SocialNews\User\Application\NicknameTakenQuery;
use SocialNews\User\Infrastructure\DbalNicknameTakenQuery;
use SocialNews\Framework\Rbac\User;
use SocialNews\Framework\Rbac\SymfonySessionCurrentUserFactory;

$injector = new Injector();

$injector->delegate(
    TemplateRenderer::class,
    function () use ($injector): TemplateRenderer {
        $factory = $injector->make(TwigTemplateRendererFactory::class);
        return $factory->create();
    }
);

$injector->define(TemplateDirectory::class, [':rootDirectory' => ROOT_DIR]);

$injector->define(
    DatabaseUrl::class,
    [':url' => 'sqlite:///' . ROOT_DIR . '/storage/db.sqlite3']
);

$injector->delegate(Connection::class, function () use ($injector): Connection {
    $factory = $injector->make(ConnectionFactory::class);
    return $factory->create();
});
$injector->share(Connection::class);

$injector->alias(SubmissionsQuery::class, DbalSubmissionsQuery::class);
$injector->share(SubmissionsQuery::class);

$injector->alias(TokenStorage::class, SymfonySessionTokenStorage::class);

$injector->alias(SessionInterface::class, Session::class);

$injector->alias(SubmissionRepository::class, DbalSubmissionRepository::class);

$injector->alias(UserRepository::class, DbalUserRepository::class);

$injector->alias(NicknameTakenQuery::class, DbalNicknameTakenQuery::class);

$injector->delegate(User::class, function () use ($injector): User {
    $factory = $injector->make(SymfonySessionCurrentUserFactory::class);
    return $factory->create();
});

return $injector;