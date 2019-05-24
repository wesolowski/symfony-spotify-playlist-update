<?php declare(strict_types=1);


namespace App\Component\EskaPlayList\Business\Playlist;


use SpotifyApiConnect\Application\SpotifyWebApiInterface;

class Info
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


}