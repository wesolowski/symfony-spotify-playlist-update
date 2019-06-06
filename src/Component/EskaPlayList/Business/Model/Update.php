<?php declare(strict_types=1);

namespace App\Component\EskaPlayList\Business\Model;

use App\Component\EskaPlayList\Business\Page\SongPageInterface;
use App\Component\EskaPlayList\Business\Playlist\ClearInterface;
use App\Component\EskaPlayList\Business\Playlist\SearchInterface;
use App\Component\EskaPlayList\Persistence\DataTransferObject\SongResult;
use SpotifyApiConnect\Application\SpotifyWebApiInterface;
use SpotifyApiConnect\Domain\DataTransferObject\PlaylistDataProvider;

class Update implements UpdateInterface
{
    /**
     * @var SpotifyWebApiInterface
     */
    private $spotifyWebApi;

    /**
     * @var SongPageInterface
     */
    private $songPage;

    /**
     * @var ClearInterface
     */
    private $clear;

    /**
     * @var SearchInterface
     */
    private $search;

    /**
     * @var PlaylistDataProvider
     */
    private $playlistDataProvider;

    /**
     * @param SpotifyWebApiInterface $spotifyWebApi
     * @param SongPageInterface $songPage
     * @param ClearInterface $clear
     * @param SearchInterface $search
     * @param PlaylistDataProvider $playlistDataProvider
     */
    public function __construct(
        SpotifyWebApiInterface $spotifyWebApi,
        SongPageInterface $songPage,
        ClearInterface $clear,
        SearchInterface $search,
        PlaylistDataProvider $playlistDataProvider
    )
    {
        $this->spotifyWebApi = $spotifyWebApi;
        $this->songPage = $songPage;
        $this->clear = $clear;
        $this->search = $search;
        $this->playlistDataProvider = $playlistDataProvider;
    }


    public function updatePlayList()
    {
        $this->clear->deleteAllSong(
            $this->playlistDataProvider
        );
        $trackSearchRequestDataProviderList = $this->songPage->getList();
        $trackIds = [];

        $findResult = new SongResult();

        foreach ($trackSearchRequestDataProviderList as $trackSearchRequestDataProvider) {
            $trackId = $this->search->searchSongs($trackSearchRequestDataProvider);
            if (!empty($trackId)) {
                $findResult->addFindSongs($trackSearchRequestDataProvider);
                $trackIds[] = $trackId;
            } else {
                $findResult->addNotFoundSongs($trackSearchRequestDataProvider);
            }
        }

        $this->spotifyWebApi->addPlaylistTracks(
            $this->playlistDataProvider->getId(),
            $trackIds
        );

        dump($findResult->getNotFoundSongs());
    }
}