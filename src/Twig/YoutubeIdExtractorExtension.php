<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Twig helper for extract video id from youtube url.
 */
class YoutubeIdExtractorExtension extends AbstractExtension
{
    /**
     * Returns the name of the extension.
     *
     * @return string the extension name
     */
    public function getName(): string
    {
        return 'app.youtube_extract_id';
    }

    /**
     * @inheritdoc
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('app_youtube_extract_id', [$this, 'extractId']),
        ];
    }

    /**
     * Returns youtube video id.
     *
     * @param string $string
     *
     * @return string|null
     */
    public function extractId(string $string): ?string
    {
        $regexp = '/(?:https?:)?(?:\/\/)?(?:[0-9A-Z-]+\.)?(?:youtu\.be\/|youtube(?:-nocookie)?\.com\S*?[^\w\s-])'
                . '(?P<id>[\w-]{11})(?=[^\w-]|$)(?![?=&+%\w.-]*(?:[\'"][^<>]*>|<\/a>))[?=&+%\w.-]*/i';

        preg_match($regexp, $string, $matches);

        return $matches['id'] ?? null;
    }
}
