<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\PlayListInfo\EskaGoraca20;

use App\Component\SpotifyPlayList\Business\Page\HtmlInterface;
use App\Component\SpotifyPlayList\Business\Page\SongPage;
use PHPUnit\Framework\TestCase;

class SongsTest extends TestCase
{
    public function testSongs()
    {
        $htmlMock = $this->createMock(HtmlInterface::class);
        $htmlMock->method('get')
            ->willReturn(file_get_contents(__DIR__ . '/eska.html'));

        $songPage = new SongPage($htmlMock);
        $songInfo = $songPage->getList();

        $this->assertCount(5, $songInfo);

        $this->assertSame('SOS', $songInfo[0]->getTrack());
        $this->assertSame('Avicii', $songInfo[0]->getArtist());

        $this->assertSame('Freed From Desire', $songInfo[1]->getTrack());
        $this->assertSame('Drenchill', $songInfo[1]->getArtist());

        $this->assertSame('Bad Liar', $songInfo[2]->getTrack());
        $this->assertSame('Imagine Dragons', $songInfo[2]->getArtist());

        $this->assertSame('Giant', $songInfo[3]->getTrack());
        $this->assertSame('Calvin Harris', $songInfo[3]->getArtist());

        $this->assertSame('La Libertad', $songInfo[4]->getTrack());
        $this->assertSame('Alvaro Soler', $songInfo[4]->getArtist());
    }
}