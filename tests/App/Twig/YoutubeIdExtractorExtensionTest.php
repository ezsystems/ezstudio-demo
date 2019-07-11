<?php

namespace Tests\App\Twig;

use App\Twig\YoutubeIdExtractorExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

class YoutubeIdExtractorExtensionTest extends TestCase
{
    public function youtubeUrls()
    {
        return [
            ['http://www.youtube.com/watch?v=Z1xNWm6dHp4', 'Z1xNWm6dHp4'],
            ['http://youtu.be/Z1xNWm6dHp4', 'Z1xNWm6dHp4'],
            ['https://youtu.be/Z1xNWm6dHp4', 'Z1xNWm6dHp4'],
            ['https://youtu.be/Z1xNWm6dHp4?t=4s', 'Z1xNWm6dHp4'],
            ['http://www.youtube.com/v/Z1xNWm6dHp4?fs=1&hl=en_US', 'Z1xNWm6dHp4'],
            ['http://www.youtube.com/watch?v=Z1xNWm6dHp4', 'Z1xNWm6dHp4'],
            ['http://www.youtube.com/ytscreeningroom?v=NRHVzbJVx8I', 'NRHVzbJVx8I'],
            ['http://www.youtube.com/watch?v=MsRua-Tdhf8&feature=g-vrec', 'MsRua-Tdhf8'],
            ['http://www.youtube.com/watch?v=MsRua-Tdhf8&feature=youtu.be', 'MsRua-Tdhf8'],
            ['http://www.youtube-nocookie.com/watch?v=WNlN4U4Qbwc', 'WNlN4U4Qbwc'],
            ['http://www.youtube.com/embed/WNlN4U4Qbwc', 'WNlN4U4Qbwc'],
            ['https://www.youtube.com/embed/WNlN4U4Qbwc', 'WNlN4U4Qbwc'],
            ['https://www.youtube.com/watch?v=WNlN4U4Qbwc&feature=youtu.be', 'WNlN4U4Qbwc'],
            ['https://www.youtube.com/watch?v=WNlN4U4Qbwc', 'WNlN4U4Qbwc'],
            ['m.youtube.com/watch?v=bfs_6KeqTzU', 'bfs_6KeqTzU'],
            ['youtube.com/watch?v=Z1xNWm6dHp4', 'Z1xNWm6dHp4'],
            ['badstring', null],
            ['http://www.youtube.com/', null],
            ['//something/', null],
        ];
    }

    /**
     * @param $input
     * @param $expected
     * @dataProvider youtubeUrls
     */
    public function testExtractId($input, $expected)
    {
        $subject = new YoutubeIdExtractorExtension();
        $result = $subject->extractId($input);

        $this->assertEquals($expected, $result);
    }

    public function testGetName()
    {
        $subject = new YoutubeIdExtractorExtension();
        $result = $subject->getName();

        $this->assertIsString($result);
        $this->assertEquals('app.youtube_extract_id', $result);
    }

    public function testGetFunctions()
    {
        $subject = new YoutubeIdExtractorExtension();

        /** @var TwigFunction[] $result */
        $result = $subject->getFunctions();

        $this->assertIsArray($result);
        $this->assertInstanceOf(TwigFunction::class, $result[0]);
        $this->assertEquals('app_youtube_extract_id', $result[0]->getName());
    }
}
