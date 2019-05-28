<?php declare(strict_types=1);


namespace App\Component\EskaPlayList\Business\Playlist;


use SpotifyApiConnect\Application\SpotifyWebApiInterface;
use SpotifyApiConnect\Domain\DataTransferObject\DeleteTrackInfoDataProvider;
use SpotifyApiConnect\Domain\DataTransferObject\PlaylistDataProvider;

class Clear implements ClearInterface
{
    /**
     * @var SpotifyWebApiInterface
     */
    private $spotifyWebApi;

    /**
     * @var PlaylistDataProvider
     */
    private $playlistDataProvider;

    /**
     * @param SpotifyWebApiInterface $spotifyWebApi
     * @param PlaylistDataProvider $playlistDataProvider
     */
    public function __construct(
        SpotifyWebApiInterface $spotifyWebApi,
        PlaylistDataProvider $playlistDataProvider
    )
    {
        $this->spotifyWebApi = $spotifyWebApi;
        $this->playlistDataProvider = $playlistDataProvider;
    }


    public function deleteAllSong()
    {
        $playListId = $this->playlistDataProvider->getId();
        $playlistTracksDataProvider = $this->spotifyWebApi->getPlaylistTracks($playListId);

        $songToDelete = [];
        foreach ($playlistTracksDataProvider->getItems() as $song) {
            $deleteTrackInfoDataProvider = new DeleteTrackInfoDataProvider();
            $deleteTrackInfoDataProvider->setId(
                $song->getTrack()->getId()
            );
            $songToDelete[] = $deleteTrackInfoDataProvider;
        }

        if(!empty($songToDelete)) {
            $this->spotifyWebApi->deletePlaylistTracks($playListId, $songToDelete );
        }
    }
}