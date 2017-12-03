<?php declare(strict_types=1);

namespace SocialNews\FrontPage\Presentation;

use SocialNews\Framework\Rendering\TemplateRenderer;
use SocialNews\FrontPage\Application\SubmissionsQuery;
use Symfony\Component\HttpFoundation\Response;

final class FrontPageController
{
    private $templateRenderer;
    private $submissionsQuery;

    public function __construct(
        TemplateRenderer $templateRenderer,
        SubmissionsQuery $submissionsQuery
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->submissionsQuery = $submissionsQuery;
    }

    public function show(): Response
    {
        $content = $this->templateRenderer->render('FrontPage.html.twig', [
            'submissions' => $this->submissionsQuery->execute(),
        ]);
        return new Response($content);
    }
}