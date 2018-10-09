<?php

/**
 * File containing the ContentExtension class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace AppBundle\Twig;

use eZ\Publish\Core\Helper\FieldHelper;
use eZ\Publish\API\Repository\Values\Content\Content;
use Psr\Log\LoggerInterface;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Twig content extension for eZ Publish Demo specific usage.
 */
class ContentDescriptionExtension extends Twig_Extension
{
    /**
     * @var \eZ\Publish\Core\Helper\FieldHelper
     */
    protected $fieldHelper;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param \eZ\Publish\Core\Helper\FieldHelper $fieldHelper
     * @param \Psr\Log\LoggerInterface|null $logger
     */
    public function __construct(
        FieldHelper $fieldHelper,
        LoggerInterface $logger = null
    ) {
        $this->fieldHelper = $fieldHelper;
        $this->logger = $logger;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction(
                'ez_get_description_or_first_richtext_field_name',
                array($this, 'getDescriptionOrFirstRichtextFieldName')
            ),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'app.content';
    }

    /**
     * Returns description field Identifier or first Richtext field.
     *
     * @param Content $content
     *
     * @return null|string
     */
    public function getDescriptionOrFirstRichtextFieldName(Content $content): ?string
    {
        if ($field = $content->getField('description')) {
            return $field->fieldDefIdentifier;
        }

        foreach ($content->getFieldsByLanguage() as $field) {
            $fieldTypeIdentifier = $this->fieldHelper->getFieldDefinition(
                $content->contentInfo,
                $field->fieldDefIdentifier
            )->fieldTypeIdentifier;

            if ($fieldTypeIdentifier !== 'ezrichtext') {
                continue;
            }

            return $field->fieldDefIdentifier;
        }

        return null;
    }
}
