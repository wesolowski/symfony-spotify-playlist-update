<?php declare(strict_types=1);


namespace App\Tests\Integration\Component\EskaPlayList\Business\Model;

use App\Component\EskaPlayList\Business\Model\Update;
use App\Component\EskaPlayList\Business\Page\SongPageInterface;
use App\Component\EskaPlayList\Business\Playlist\Clear;
use App\Component\EskaPlayList\Business\Playlist\Search;
use PHPUnit\Framework\TestCase;
use SpotifyApiConnect\Application\SpotifyWebApiPhp\SpotifyWebApi;
use SpotifyApiConnect\Domain\DataTransferObject\PlaylistDataProvider;
use SpotifyApiConnect\Domain\DataTransferObject\TrackSearchRequestDataProvider;
use SpotifyApiConnect\SpotifyApiConnectFactory;

class UpdateTest extends TestCase
{

    private $songs = [];

    /**
     * @var SpotifyWebApi
     */
    private $spotifyWebApi;

    /**
     * @var PlaylistDataProvider
     */
    private $symfonyUnitPlayList;

    protected function setUp()
    {
        $spotifyApiConnectFactory = new SpotifyApiConnectFactory();
        $spotifyApiAuth = $spotifyApiConnectFactory->createSpotifyApiAuth();

        $this->spotifyWebApi = $spotifyApiConnectFactory->createSpotifyWebApi(
            $spotifyApiAuth->getAccessByRefreshToken(getenv('REFRESH_TOKEN'))
        );

        $this->symfonyUnitPlayList = $this->spotifyWebApi->getUserPlaylistByName(
            'UnitTestSymfony'
        );

        parent::setUp();
    }

    public function testUpdatePlayListAADBDA()
    {
        $this->songs = [];
        $songOne = new TrackSearchRequestDataProvider();
        $songOne->setArtist('Sting');
        $songOne->setTrack('Englishman In New York');
        $this->songs[] = $songOne;

        $songTwo = new TrackSearchRequestDataProvider();
        $songTwo->setArtist('U2');
        $songTwo->setTrack('I Still Haven\'t Found What I\'m Looking For');
        $this->songs[] = $songTwo;

        $songNotFound = new TrackSearchRequestDataProvider();
        $songNotFound->setArtist('unit-test not found');
        $songNotFound->setTrack('aaaa bbb cccc unit test');
        $this->songs[] = $songNotFound;

        $songFoundInConfig = new TrackSearchRequestDataProvider();
        $songFoundInConfig->setArtist('unit-test config found');
        $songFoundInConfig->setTrack('config found test');
        $this->songs[] = $songFoundInConfig;

        $update = $this->getUpdateClass();

        $update->updatePlayList();

        $playList = $this->spotifyWebApi->getPlaylistTracks($this->symfonyUnitPlayList->getId());

        $this->assertCount(3, $playList->getItems());

        $this->assertSame($songOne->getTrack(), $playList->getItems()[0]->getTrack()->getName());
        $this->assertSame($songOne->getArtist(), $playList->getItems()[0]->getTrack()->getArtists()[0]->getName());
        $this->assertSame($songTwo->getTrack(), $playList->getItems()[1]->getTrack()->getName());
        $this->assertSame($songTwo->getArtist(), $playList->getItems()[1]->getTrack()->getArtists()[0]->getName());

        $this->assertSame('Inner Smile', $playList->getItems()[2]->getTrack()->getName());
        $this->assertSame('Texas', $playList->getItems()[2]->getTrack()->getArtists()[0]->getName());
    }


    public function testUpdatePlayListSecond()
    {
        $playList = $this->spotifyWebApi->getPlaylistTracks($this->symfonyUnitPlayList->getId());
        $this->assertCount(3, $playList->getItems());

        $this->songs = [];
        $song = new TrackSearchRequestDataProvider();
        $song->setArtist('Michael Jackson');
        $song->setTrack('Billie Jean');
        $this->songs[] = $song;

        $update = $this->getUpdateClass();

        $update->updatePlayList();

        $playList = $this->spotifyWebApi->getPlaylistTracks($this->symfonyUnitPlayList->getId());

        $this->assertCount(1, $playList->getItems());

        $this->assertSame($song->getTrack(), $playList->getItems()[0]->getTrack()->getName());
        $this->assertSame($song->getArtist(), $playList->getItems()[0]->getTrack()->getArtists()[0]->getName());
    }

    /**
     * @return Update
     * @throws \SpotifyApiConnect\Domain\Exception\PlaylistNotFound
     */
    protected function getUpdateClass(): Update
    {
        $songPage = $this->createMock(SongPageInterface::class);
        $songPage->method('getList')->willReturn(
            $this->songs
        );

        $update = new Update(
            $this->spotifyWebApi,
            $songPage,
            new Clear(
                $this->spotifyWebApi,
                $this->symfonyUnitPlayList
            ),
            new Search(
                $this->spotifyWebApi,
                __DIR__
            ),
            $this->symfonyUnitPlayList
        );
        return $update;
    }

}