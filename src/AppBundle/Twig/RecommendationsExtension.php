<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace AppBundle\Twig;

use Twig_Extension;
use Twig_SimpleFunction;
use eZ\Publish\Core\MVC\ConfigResolverInterface as ConfigResolver;

/**
 * Recommendations Twig helper for additional integration with RecommendationBundle.
 */
class RecommendationsExtension extends Twig_Extension
{
    /** var \eZ\Publish\Core\MVC\ConfigResolverInterface */
    private $configResolver;

    /**
     * @param \eZ\Publish\Core\MVC\ConfigResolverInterface $configResolver
     */
    public function __construct(ConfigResolver $configResolver)
    {
        $this->configResolver = $configResolver;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string the extension name
     */
    public function getName()
    {
        return 'recommendations_extension';
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('ez_reco_enabled', [$this, 'isRecommendationsEnabled']),
        ];
    }

    /**
     * Checks if YooChoose license key is provided.
     *
     * @return bool
     */
    public function isRecommendationsEnabled()
    {
        if ($this->configResolver->hasParameter('yoochoose.license_key', 'ez_recommendation') &&
            !empty($this->configResolver->getParameter('yoochoose.license_key', 'ez_recommendation'))) {
            return true;
        }

        return false;
    }
}
