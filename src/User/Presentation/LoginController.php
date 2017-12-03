<?php declare(strict_types=1);

namespace SocialNews\User\Presentation;

use SocialNews\Framework\Csrf\StoredTokenValidator;
use SocialNews\Framework\Rendering\TemplateRenderer;
use SocialNews\User\Application\LogInHandler;
use Symfony\Component\HttpFoundation\Response;
use SocialNews\Framework\Csrf\Token;
use SocialNews\User\Application\LogIn;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

final class LoginController
{
    private $templateRenderer;
    private $storedTokenValidator;
    private $session;
    private $logInHandler;

    public function __construct(
        TemplateRenderer $templateRenderer,
        StoredTokenValidator $storedTokenValidator,
        Session $session,
        LogInHandler $logInHandler
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->storedTokenValidator = $storedTokenValidator;
        $this->session = $session;
        $this->logInHandler = $logInHandler;
    }

    public function show(): Response
    {
        $content = $this->templateRenderer->render('Login.html.twig');
        return new Response($content);
    }

    public function logIn(Request $request): Response
    {
        $this->session->remove('userId');
        
        if (!$this->storedTokenValidator->validate(
            'login',
            new Token((string)$request->get('token'))
        )) {
            $this->session->getFlashBag()->add('errors', 'Invalid token');
            return new RedirectResponse('/login');
        }

        $this->logInHandler->handle(new LogIn(
            (string)$request->get('nickname'),
            (string)$request->get('password')
        ));

        if ($this->session->get('userId') === null) {
            $this->session->getFlashBag()->add('errors', 'Invalid username or password');
            return new RedirectResponse('/login');
        }

        $this->session->getFlashBag()->add('success', 'You were logged in.');
        return new RedirectResponse('/');
    }
}