<?php declare(strict_types=1);


namespace App\Component\EskaPlayList\Business\Factory;


use SpotifyApiConnect\Application\SpotifyWebApiInterface;
use SpotifyApiConnect\Domain\DataTransferObject\PlaylistDataProvider;
use SpotifyApiConnect\Domain\Model\Config;

class PlayListInfo
{
    /**
     * @var SpotifyWebApiInterface
     */
    private $spotifyWebApi;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param SpotifyWebApiInterface $spotifyWebApi
     * @param Config $config
     */
    public function __construct(SpotifyWebApiInterface $spotifyWebApi, Config $config)
    {
        $this->spotifyWebApi = $spotifyWebApi;
        $this->config = $config;
    }

    /**
     * @param string $playListName
     * @return \SpotifyApiConnect\Domain\DataTransferObject\PlaylistDataProvider
     * @throws \SpotifyApiConnect\Domain\Exception\PlaylistNotFound
     */
    public function createPlayListInfo(string $playListName) : PlaylistDataProvider
    {
        return $this->spotifyWebApi->getUserPlaylistByName(
            $this->config->getClientId(),
            $playListName
        );
    }
}