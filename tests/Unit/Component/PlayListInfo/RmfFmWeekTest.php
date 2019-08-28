<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\PlayListInfo\EskaGoraca20;

use App\Component\PlayListInfo\EskaGoraca20;
use App\Component\PlayListInfo\RmfFmWeek;
use App\Component\SpotifyPlayList\Business\Page\HtmlInterface;
use PHPUnit\Framework\TestCase;

class RmfFmWeekTest extends TestCase
{
    public function testSongs()
    {
        $htmlMock = $this->createMock(HtmlInterface::class);
        $htmlMock->method('get')
            ->willReturn(file_get_contents(__DIR__ . '/html/rmf_fm_week.html'));

        $songPage = new RmfFmWeek($htmlMock);
        $songInfo = $songPage->getList();

        $this->assertCount(5, $songInfo);

        $this->assertSame('I follow rivers (magician remix)', $songInfo[0]->getTrack());
        $this->assertSame('Lykke Li', $songInfo[0]->getArtist());

        $this->assertSame('One day - reckoning song (wankelmut remix)', $songInfo[1]->getTrack());
        $this->assertSame('Asaf Avidan, The Mojos', $songInfo[1]->getArtist());

        $this->assertSame('Con calma', $songInfo[2]->getTrack());
        $this->assertSame('Daddy Yankee, snow', $songInfo[2]->getArtist());

        $this->assertSame('Jackie chan', $songInfo[3]->getTrack());
        $this->assertSame('Tiësto, Dzeko, Post Malone, Preme', $songInfo[3]->getArtist());

        $this->assertSame('Wystarczę ja', $songInfo[4]->getTrack());
        $this->assertSame('Paweł Domagała', $songInfo[4]->getArtist());
    }
}