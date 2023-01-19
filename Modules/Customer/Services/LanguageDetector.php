<?php

declare(strict_types=1);

namespace Modules\Customer\Services;

use LanguageDetection\Language as Detector;
use LanguageDetection\LanguageResult;
use Modules\Language\Models\Language;

final class LanguageDetector
{
    private readonly array $whiteList;

    public function __construct(private readonly Detector $detector)
    {
        $this->whiteList = Language::pluck('code')->toArray();
    }

    private function detect(string $text): LanguageResult
    {
        return $this->detector->detect($text);
    }

    public function detectBest(string $text): string
    {
        return (string) $this->detect($text)->whitelist(...$this->whiteList);
    }
}
