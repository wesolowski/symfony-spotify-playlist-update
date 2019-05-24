<?php declare(strict_types=1);


namespace App\Component\EskaPlayList\Business\Playlist;


use SpotifyApiConnect\Application\SpotifyWebApiInterface;

class Clear
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

    public function deleteAllSong()
    {
        $this->spotifyWebApi->getPlaylistTracks();
        $playlistSongs = $api->getUserPlaylistTracks($config['user'],$playlistId )->items;
        $songToDelete = [];
        foreach ($playlistInfo->tracks->items as $song) {
            $songToDelete[]['id'] = $song->track->id;
        }
        if(!empty($songToDelete)) {
            $api->deleteUserPlaylistTracks($config['user'],$playlistId, $songToDelete);
        }
    }
}