<?php declare(strict_types=1);


namespace App\Component\EskaPlayList\Business\Playlist;


use SpotifyApiConnect\Application\SpotifyWebApiInterface;
use SpotifyApiConnect\Domain\DataTransferObject\TrackSearchRequestDataProvider;

class Search implements SearchInterface
{
    /**
     * @var SpotifyWebApiInterface
     */
    private $spotifyWebApi;

    /**
     * @param SpotifyWebApiInterface $spotifyWebApi
     */
    public function __construct(SpotifyWebApiInterface $spotifyWebApi)
    {
        $this->spotifyWebApi = $spotifyWebApi;
    }

    /**
     * @param TrackSearchRequestDataProvider $trackSearchRequestDataProvider
     * @return string
     */
    public function searchSongs(TrackSearchRequestDataProvider $trackSearchRequestDataProvider): string
    {
        $spotifySongId = $this->isSongInConfig($trackSearchRequestDataProvider);
        if (empty($spotifySongId)) {
            $tracksSearchDataProvider = $this->spotifyWebApi->searchTrack($trackSearchRequestDataProvider);
            if ($tracksSearchDataProvider->getTotal() > 0) {
                $spotifySongId = $tracksSearchDataProvider->getItems()[0]->getId();
            }
        }

        return $spotifySongId;
    }

    private function isSongInConfig($trackSearchRequestDataProvider): string
    {
        $spotifySongId = '';
        //config

        return $spotifySongId;
    }
}