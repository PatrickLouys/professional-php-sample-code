<?php declare(strict_types=1);

namespace SocialNews\FrontPage\Application;

interface SubmissionsQuery
{
    /** @return Submission[] */
    public function execute(): array;
}