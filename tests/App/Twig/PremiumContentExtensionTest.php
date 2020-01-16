<?php

namespace Tests\App\Twig;

use App\Helper\UserGroupHelper;
use App\PremiumContent\HtmlRenderer;
use App\Twig\PremiumContentExtension;
use PHPUnit\Framework\TestCase;

class PremiumContentExtensionTest extends TestCase
{
    public function testHasAccessToPremiumContent()
    {
        $userGroups = $this->createMock(UserGroupHelper::class);

        $userGroupsLocationIds = [24];

        $userGroups->expects($this->once())
            ->method('isCurrentUserInOneOfTheGroups')
            ->with($userGroupsLocationIds)
            ->willReturn(true);

        $subject = new PremiumContentExtension(
            $this->createMock(HtmlRenderer::class),
            $userGroups,
            $userGroupsLocationIds
        );

        $result = $subject->hasAccessToPremiumContent();

        $this->assertTrue($result);
    }

    public function testHasAccessToPremiumContentWithWrongIds()
    {
        $userGroups = $this->createMock(UserGroupHelper::class);

        $userGroupsLocationIds = [36];

        $userGroups->expects($this->once())
            ->method('isCurrentUserInOneOfTheGroups')
            ->with($userGroupsLocationIds)
            ->willReturn(false);

        $subject = new PremiumContentExtension(
            $this->createMock(HtmlRenderer::class),
            $userGroups,
            $userGroupsLocationIds
        );

        $result = $subject->hasAccessToPremiumContent();

        $this->assertFalse($result);
    }

    public function testPreviewPremiumContent()
    {
        $htmlRenderer = $this->createMock(HtmlRenderer::class);

        $htmlDocument = '<b>rendered text</b>';

        $htmlRenderer->expects($this->once())
            ->method('renderElements')
            ->with($htmlDocument, 5)
            ->willReturn($htmlDocument);

        $subject = new PremiumContentExtension(
            $htmlRenderer,
            $this->createMock(UserGroupHelper::class),
            []
        );

        $result = $subject->previewPremiumContent($htmlDocument, 5);

        $this->assertEquals($htmlDocument, $result);
    }
}
