<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\Twig;

use eZ\Publish\Core\MVC\ConfigResolverInterface as ConfigResolver;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Recommendations Twig helper for additional integration with RecommendationBundle.
 */
class RecommendationsExtension extends AbstractExtension
{
    /** var \eZ\Publish\Core\MVC\ConfigResolverInterface */
    private $configResolver;

    public function __construct(ConfigResolver $configResolver)
    {
        $this->configResolver = $configResolver;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string the extension name
     */
    public function getName(): string
    {
        return 'recommendations_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('ez_reco_enabled', [$this, 'isRecommendationsEnabled']),
        ];
    }

    /**
     * Checks if YooChoose license key is provided.
     */
    public function isRecommendationsEnabled(): bool
    {
        if ($this->configResolver->hasParameter('yoochoose.license_key', 'ez_recommendation') &&
            !empty($this->configResolver->getParameter('yoochoose.license_key', 'ez_recommendation'))) {
            return true;
        }

        return false;
    }
}
