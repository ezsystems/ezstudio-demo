<?php

/**
 * File containing the ContentExtension class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\Twig;

use eZ\Publish\API\Repository\Values\Content\Content;
use Psr\Log\LoggerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Twig content extension for eZ Publish Demo specific usage.
 */
class ContentDescriptionExtension extends AbstractExtension
{
    /** @var \Psr\Log\LoggerInterface */
    protected $logger;

    /**
     * @param \Psr\Log\LoggerInterface|null $logger
     */
    public function __construct(
        LoggerInterface $logger = null
    ) {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function getFunctions(): array
    {
        return array(
            new TwigFunction(
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
    public function getName(): string
    {
        return 'app.content';
    }

    /**
     * Returns description field Identifier or first Richtext field.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return null|string
     */
    public function getDescriptionOrFirstRichtextFieldName(Content $content): ?string
    {
        if ($field = $content->getField('description')) {
            return $field->fieldDefIdentifier;
        }

        foreach ($content->getFieldsByLanguage() as $field) {
            $fieldTypeIdentifier = $content->getContentType()->getFieldDefinition($field->fieldDefIdentifier);

            if ($fieldTypeIdentifier !== 'ezrichtext') {
                continue;
            }

            return $field->fieldDefIdentifier;
        }

        return null;
    }
}
