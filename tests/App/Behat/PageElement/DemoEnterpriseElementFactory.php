<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Tests\App\Behat\PageElement;

use EzSystems\Behat\Browser\Context\BrowserContext;
use EzSystems\Behat\Browser\Element\Element;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\BlockPreview\BannerPreview;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\BlockPreview\CollectionPreview;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\BlockPreview\ContentListPreview;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\BlockPreview\ContentSchedulerPreview;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\BlockPreview\EmbedPreview;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\BlockPreview\GalleryPreview;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\BlockPreview\RSSPreview;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\BlockPreview\VideoPreview;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\Blocks\BannerBlock;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\Blocks\CodeBlock;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\Blocks\CollectionBlock;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\Blocks\ContentListBlock;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\Blocks\ContentSchedulerBlock;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\Blocks\EmbedBlock;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\Blocks\GalleryBlock;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\Blocks\VideoBlock;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\EnterpriseElementFactory;
use Tests\App\Behat\PageElement\BlockPreview\DemoBannerPreview;
use Tests\App\Behat\PageElement\BlockPreview\DemoCollectionPreview;
use Tests\App\Behat\PageElement\BlockPreview\DemoContentListPreview;
use Tests\App\Behat\PageElement\BlockPreview\DemoContentSchedulerPreview;
use Tests\App\Behat\PageElement\BlockPreview\DemoEmbedPreview;
use Tests\App\Behat\PageElement\BlockPreview\DemoGalleryPreview;
use Tests\App\Behat\PageElement\BlockPreview\DemoRSSPreview;
use Tests\App\Behat\PageElement\BlockPreview\DemoVideoPreview;
use Tests\App\Behat\PageElement\BlockPreview\HeroPreview;
use Tests\App\Behat\PageElement\Blocks\DemoBannerBlock;
use Tests\App\Behat\PageElement\Blocks\DemoCodeBlock;
use Tests\App\Behat\PageElement\Blocks\DemoCollectionBlock;
use Tests\App\Behat\PageElement\Blocks\DemoContentListBlock;
use Tests\App\Behat\PageElement\Blocks\DemoContentSchedulerBlock;
use Tests\App\Behat\PageElement\Blocks\DemoEmbedBlock;
use Tests\App\Behat\PageElement\Blocks\DemoGalleryBlock;
use Tests\App\Behat\PageElement\Blocks\DemoVideoBlock;
use Tests\App\Behat\PageElement\Blocks\HeroBlock;

class DemoEnterpriseElementFactory extends EnterpriseElementFactory
{
    /**
     * @param null[]|string[] ...$parameters
     *
     * @return \EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminList|\EzSystems\EzPlatformAdminUi\Behat\PageElement\AdminUpdateForm|\EzSystems\EzPlatformAdminUi\Behat\PageElement\Breadcrumb|\EzSystems\EzPlatformAdminUi\Behat\PageElement\ContentField|\EzSystems\EzPlatformAdminUi\Behat\PageElement\ContentTypePicker|\EzSystems\EzPlatformAdminUi\Behat\PageElement\ContentUpdateForm|\EzSystems\EzPlatformAdminUi\Behat\PageElement\DateAndTimePopup|\EzSystems\EzPlatformAdminUi\Behat\PageElement\Dialog|\EzSystems\EzPlatformAdminUi\Behat\PageElement\DraftConflictDialog|Element|\EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields\DefaultFieldElement|\EzSystems\EzPlatformAdminUi\Behat\PageElement\LanguagePicker|\EzSystems\EzPlatformAdminUi\Behat\PageElement\LeftMenu|\EzSystems\EzPlatformAdminUi\Behat\PageElement\NavLinkTabs|\EzSystems\EzPlatformAdminUi\Behat\PageElement\Notification|\EzSystems\EzPlatformAdminUi\Behat\PageElement\PreviewNav|\EzSystems\EzPlatformAdminUi\Behat\PageElement\RightMenu|\EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\ContentRelationTable|\EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\DashboardTable|\EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\DoubleHeaderTable|\EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\LinkedListTable|\EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\SimpleListTable|\EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\SimpleTable|\EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\SubItemsTable|\EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\SystemInfoTable|\EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\TrashTable|\EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables\VerticalOrientedTable|\EzSystems\EzPlatformAdminUi\Behat\PageElement\UniversalDiscoveryWidget|\EzSystems\EzPlatformAdminUi\Behat\PageElement\UpperMenu|CollectionPreview|ContentSchedulerPreview|VideoPreview|Timeline
     */
    public static function createElement(BrowserContext $context, string $elementName, ?string ...$parameters): Element
    {
        switch ($elementName) {
            case ContentListBlock::ELEMENT_NAME:
                return new DemoContentListBlock($context);
            case BannerBlock::ELEMENT_NAME:
                return new DemoBannerBlock($context);
            case EmbedBlock::ELEMENT_NAME:
                return new DemoEmbedBlock($context);
            case GalleryBlock::ELEMENT_NAME:
                return new DemoGalleryBlock($context);
            case CodeBlock::ELEMENT_NAME:
                return new DemoCodeBlock($context);
            case VideoBlock::ELEMENT_NAME:
                return new DemoVideoBlock($context);
            case CollectionBlock::ELEMENT_NAME:
                return new DemoCollectionBlock($context);
            case ContentSchedulerBlock::ELEMENT_NAME:
                return new DemoContentSchedulerBlock($context);
            case HeroBlock::ELEMENT_NAME:
                return new HeroBlock($context);
            case HeroPreview::ELEMENT_NAME:
                return new HeroPreview($context);
            case ContentListPreview::ELEMENT_NAME:
                return new DemoContentListPreview($context);
            case BannerPreview::ELEMENT_NAME:
                return new DemoBannerPreview($context);
            case EmbedPreview::ELEMENT_NAME:
                return new DemoEmbedPreview($context);
            case GalleryPreview::ELEMENT_NAME:
                return new DemoGalleryPreview($context);
            case RSSPreview::ELEMENT_NAME:
                return new DemoRSSPreview($context);
            case VideoPreview::ELEMENT_NAME:
                return new DemoVideoPreview($context);
            case CollectionPreview::ELEMENT_NAME:
                return new DemoCollectionPreview($context);
            case ContentSchedulerPreview::ELEMENT_NAME:
                return new DemoContentSchedulerPreview($context);
            default:
                return parent::createElement($context, $elementName, ...$parameters);
        }
    }

    public static function getPreviewType(string $elementType): string
    {
        switch ($elementType) {
            case HeroBlock::ELEMENT_NAME:
                return HeroPreview::ELEMENT_NAME;
            default:
                return parent::getPreviewType($elementType);
        }
    }
}
