<?php declare(strict_types=1);


namespace App\Component\EskaPlayList\Business\Playlist;


use SpotifyApiConnect\Application\SpotifyWebApiInterface;
use SpotifyApiConnect\Domain\DataTransferObject\TrackSearchRequestDataProvider;

class Search
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
     * @param TrackSearchRequestDataProvider[] $trackSearchRequestDataProviderList
     * @return array
     */
    public function searchSongs(array $trackSearchRequestDataProviderList): array
    {
        $tractIds = [];
        foreach ($trackSearchRequestDataProviderList as $trackSearchRequestDataProvider) {
            $spotifySongId = $this->isSongInConfig($trackSearchRequestDataProvider);
            if (empty($spotifySongId)) {
                $tracksSearchDataProvider = $this->spotifyWebApi->searchTrack($trackSearchRequestDataProvider);
                if ($tracksSearchDataProvider->getTotal() > 0) {
                    $spotifySongId = $tracksSearchDataProvider->getItems()[0]->getId();
                }
            }

            if(!empty($spotifySongId)) {
                $tractIds[] = $spotifySongId;
            }

        }

        return $tractIds;
    }

    private function isSongInConfig($trackSearchRequestDataProvider): string
    {
        $spotifySongId = '';
        //config

        return $spotifySongId;
    }
}