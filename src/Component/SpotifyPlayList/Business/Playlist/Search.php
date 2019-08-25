<?php declare(strict_types=1);


namespace App\Component\SpotifyPlayList\Business\Playlist;


use SpotifyApiConnect\Application\SpotifyWebApiInterface;
use SpotifyApiConnect\Domain\DataTransferObject\TrackSearchRequestDataProvider;

class Search implements SearchInterface
{
    public const SONGS_MAPPING = 'songs_mapping.php';

    /**
     * @var SpotifyWebApiInterface
     */
    private $spotifyWebApi;

    /**
     * @var string
     */
    private $kernelProjectDir;

    /**
     * @var array
     */
    private $mapping;

    /**
     * @param SpotifyWebApiInterface $spotifyWebApi
     * @param string $kernelProjectDir
     */
    public function __construct(
        SpotifyWebApiInterface $spotifyWebApi,
        string $kernelProjectDir
    )
    {
        $this->spotifyWebApi = $spotifyWebApi;
        $this->kernelProjectDir = $kernelProjectDir;
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

    /**
     * @param TrackSearchRequestDataProvider $trackSearchRequestDataProvider
     * @return string
     */
    private function isSongInConfig(TrackSearchRequestDataProvider $trackSearchRequestDataProvider): string
    {
        $spotifySongId = '';
        $mapping = $this->getMapping();
        foreach ($mapping as $songId => $songInfos) {
            foreach ($songInfos as $songInfo) {
                if ($songInfo[0] === $trackSearchRequestDataProvider->getArtist() &&
                    $songInfo[1] === $trackSearchRequestDataProvider->getTrack()
                ) {
                    return $songId;
                }
            }
        }

        return $spotifySongId;
    }

    private function getMapping(): array
    {
        if ($this->mapping === null) {
            $this->mapping = [];
            $mappingFile = $this->kernelProjectDir . '/' . self::SONGS_MAPPING;
            if (file_exists($mappingFile)) {
                $this->mapping = require $mappingFile;
            }
        }

        return $this->mapping;
    }
}