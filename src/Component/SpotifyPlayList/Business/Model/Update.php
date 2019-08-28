<?php declare(strict_types=1);

namespace App\Component\SpotifyPlayList\Business\Model;

use App\Component\SpotifyPlayList\Business\Factory\PlayListInfo;
use App\Component\SpotifyPlayList\Business\Page\SongPageInterface;
use App\Component\SpotifyPlayList\Business\Playlist\ClearInterface;
use App\Component\SpotifyPlayList\Business\Playlist\SearchInterface;
use App\Component\SpotifyPlayList\Persistence\DataTransferObject\SongResult;
use SpotifyApiConnect\Application\SpotifyWebApiInterface;

class Update implements UpdateInterface
{
    /**
     * @var SpotifyWebApiInterface
     */
    private $spotifyWebApi;

    /**
     * @var ClearInterface
     */
    private $clear;

    /**
     * @var SearchInterface
     */
    private $search;

    /**
     * @param SpotifyWebApiInterface $spotifyWebApi
     * @param ClearInterface $clear
     * @param SearchInterface $search
     */
    public function __construct(
        SpotifyWebApiInterface $spotifyWebApi,
        ClearInterface $clear,
        SearchInterface $search
    )
    {
        $this->spotifyWebApi = $spotifyWebApi;
        $this->clear = $clear;
        $this->search = $search;
    }


    public function updatePlayList(SongPageInterface $songPageInfo)
    {
        $playlistDataProvider = PlayListInfo::createPlayListInfo(
            $this->spotifyWebApi,
            $songPageInfo->getSpotifyPlaylistName()
        );

        $this->clear->deleteAllSong($playlistDataProvider);

        $trackSearchRequestDataProviderList = $songPageInfo->getList();
        $trackIds = [];

        $findResult = new SongResult();

        foreach ($trackSearchRequestDataProviderList as $trackSearchRequestDataProvider) {
            $trackId = $this->search->searchSongs($trackSearchRequestDataProvider);
            if (!empty($trackId)) {
                dump($trackSearchRequestDataProvider->getArtist() . ' | ' . $trackSearchRequestDataProvider->getTrack());
                $findResult->addFindSongs($trackSearchRequestDataProvider);
                $trackIds[] = $trackId;
            } else {
                $findResult->addNotFoundSongs($trackSearchRequestDataProvider);
            }

            if (!empty($trackIds) && count($trackIds) % 100 === 0) {
                dump(__FILE__ .':'. __LINE__);
                $this->spotifyWebApi->addPlaylistTracks(
                    $playlistDataProvider->getId(),
                    $trackIds
                );
                $trackIds = [];
            }

        }

        if (!empty($trackIds)) {
            $this->spotifyWebApi->addPlaylistTracks(
                $playlistDataProvider->getId(),
                $trackIds
            );
        }

        dump($findResult->getNotFoundSongs());
    }
}