<?php declare(strict_types=1);


namespace App\Tests\Integration\Component\SpotifyPlayList\Business\Model;

use App\Component\SpotifyPlayList\Business\Model\Update;
use App\Component\SpotifyPlayList\Business\Page\SongPageInterface;
use App\Component\SpotifyPlayList\Business\Playlist\Clear;
use App\Component\SpotifyPlayList\Business\Playlist\Search;
use PHPUnit\Framework\TestCase;
use SpotifyApiConnect\Application\SpotifyWebApiPhp\SpotifyWebApi;
use SpotifyApiConnect\Domain\DataTransferObject\PlaylistDataProvider;
use SpotifyApiConnect\Domain\DataTransferObject\TrackSearchRequestDataProvider;
use SpotifyApiConnect\SpotifyApiConnectFactory;

class UpdateTest extends TestCase
{

    private $songs = [];

    /**
     * @var SongPageInterface
     */
    private $songPage;

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
            $spotifyApiAuth->getAccessByRefreshToken($_SERVER['REFRESH_TOKEN'])
        );

        $this->symfonyUnitPlayList = $this->spotifyWebApi->getUserPlaylistByName(
            'UnitTestSymfony'
        );

        $this->songPage = $this->createMock(SongPageInterface::class);
        $this->songPage->method('getSpotifyPlaylistName')->willReturn(
            'UnitTestSymfony'
        );

        parent::setUp();
    }

    public function testUpdatePlayList()
    {
        $songs = [];
        $songOne = new TrackSearchRequestDataProvider();
        $songOne->setArtist('Sting');
        $songOne->setTrack('Englishman In New York');
        $songs[] = $songOne;

        $songTwo = new TrackSearchRequestDataProvider();
        $songTwo->setArtist('U2');
        $songTwo->setTrack('I Still Haven\'t Found What I\'m Looking For');
        $songs[] = $songTwo;

        $songNotFound = new TrackSearchRequestDataProvider();
        $songNotFound->setArtist('unit-test not found');
        $songNotFound->setTrack('aaaa bbb cccc unit test');
        $songs[] = $songNotFound;

        $songFoundInConfig = new TrackSearchRequestDataProvider();
        $songFoundInConfig->setArtist('unit-test config found');
        $songFoundInConfig->setTrack('config found test');
        $songs[] = $songFoundInConfig;

        $update = $this->getUpdateClass();

        $this->songPage->method('getList')->willReturn(
            $songs
        );

        $update->updatePlayList(
            $this->songPage
        );

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

        $songs = [];
        $song = new TrackSearchRequestDataProvider();
        $song->setArtist('Michael Jackson');
        $song->setTrack('Billie Jean');
        $songs[] = $song;

        $update = $this->getUpdateClass();

        $this->songPage->method('getList')->willReturn(
            $songs
        );

        $update->updatePlayList(
            $this->songPage
        );

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
        return new Update(
            $this->spotifyWebApi,
            new Clear(
                $this->spotifyWebApi,
                $this->symfonyUnitPlayList
            ),
            new Search(
                $this->spotifyWebApi,
                __DIR__
            )
        );
    }

}