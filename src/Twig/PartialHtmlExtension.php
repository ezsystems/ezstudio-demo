<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Twig;

use App\Helper\PartialHtmlRenderer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Twig helper for partial html renderer.
 */
final class PartialHtmlExtension extends AbstractExtension
{
    /** @var \App\Helper\PartialHtmlRenderer */
    private $htmlRenderer;

    public function __construct(
        PartialHtmlRenderer $htmlRenderer
    ) {
        $this->htmlRenderer = $htmlRenderer;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName(): string
    {
        return 'partial_html_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('renderPartialHtml', [$this, 'renderPartialHtml'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Allows to display certain number of paragraphs.
     */
    public function renderPartialHtml(string $document, int $numberOfDisplayedElements = 2): string
    {
        return $this->htmlRenderer->renderElements($document, $numberOfDisplayedElements);
    }
}
