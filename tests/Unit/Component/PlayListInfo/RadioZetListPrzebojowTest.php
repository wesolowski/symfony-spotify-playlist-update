<?php declare(strict_types=1);


namespace App\Tests\Unit\Component\PlayListInfo;


use App\Component\PlayListInfo\RadioZetListPrzebojow;
use App\Component\SpotifyPlayList\Business\Page\HtmlInterface;
use PHPUnit\Framework\TestCase;

class RadioZetListPrzebojowTest extends TestCase
{
    public function testSongs()
    {
        $htmlMock = $this->createMock(HtmlInterface::class);
        $htmlMock->method('get')
            ->willReturn(file_get_contents(__DIR__ . '/html/RadioZET_ListaPrzebojów.html'));

        $songPage = new RadioZetListPrzebojow($htmlMock);
        $songInfo = $songPage->getList();

        $this->assertCount(4, $songInfo);

        $this->assertSame('Senorita', $songInfo[0]->getTrack());
        $this->assertSame('Shawn Mendes', $songInfo[0]->getArtist());

        $this->assertSame('Górą Ty', $songInfo[1]->getTrack());
        $this->assertSame('Golec uOrkiestra', $songInfo[1]->getArtist());

        $this->assertSame('Old Town Road (Diplo remix)', $songInfo[2]->getTrack());
        $this->assertSame('Lil Nas X', $songInfo[2]->getArtist());

        $this->assertSame('Rescue Me', $songInfo[3]->getTrack());
        $this->assertSame('OneRepublic', $songInfo[3]->getArtist());
    }
}