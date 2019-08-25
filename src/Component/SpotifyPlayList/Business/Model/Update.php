<?php declare(strict_types=1);

namespace App\Component\SpotifyPlayList\Business\Model;

use App\Component\SpotifyPlayList\Business\Page\SongPageInterface;
use App\Component\SpotifyPlayList\Business\Playlist\ClearInterface;
use App\Component\SpotifyPlayList\Business\Playlist\SearchInterface;
use App\Component\SpotifyPlayList\Persistence\DataTransferObject\SongResult;
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

            if(count($trackIds) % 100 === 0) {
                $this->spotifyWebApi->addPlaylistTracks(
                    $this->playlistDataProvider->getId(),
                    $trackIds
                );
                $trackIds = [];
            }

        }

        if(!empty($trackIds)) {
            $this->spotifyWebApi->addPlaylistTracks(
                $this->playlistDataProvider->getId(),
                $trackIds
            );
        }


        dump($findResult->getNotFoundSongs());
    }
}