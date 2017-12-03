<?php declare(strict_types=1);

namespace SocialNews\Submission\Domain;

interface SubmissionRepository
{
    public function add(Submission $submission): void;
}