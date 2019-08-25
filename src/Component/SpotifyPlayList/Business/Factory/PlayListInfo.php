<?php declare(strict_types=1);


namespace App\Component\SpotifyPlayList\Business\Factory;

use SpotifyApiConnect\Application\SpotifyWebApiInterface;
use SpotifyApiConnect\Domain\DataTransferObject\PlaylistDataProvider;
use SpotifyApiConnect\Domain\Model\Config;

class PlayListInfo
{
    /**
     * @param SpotifyWebApiInterface $spotifyWebApi
     * @param Config $config
     * @param string $playListName
     * @return PlaylistDataProvider
     * @throws \SpotifyApiConnect\Domain\Exception\PlaylistNotFound
     */
    public static function createPlayListInfo(
        SpotifyWebApiInterface $spotifyWebApi,
        string $playListName
    ): PlaylistDataProvider
    {
        return $spotifyWebApi->getUserPlaylistByName(
            $playListName
        );
    }
}