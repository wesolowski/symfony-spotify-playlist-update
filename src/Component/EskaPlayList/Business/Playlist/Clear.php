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
     * @param SpotifyWebApiInterface $spotifyWebApi
     * @param PlaylistDataProvider $playlistDataProvider
     */
    public function __construct(
        SpotifyWebApiInterface $spotifyWebApi
    )
    {
        $this->spotifyWebApi = $spotifyWebApi;
    }


    public function deleteAllSong(PlaylistDataProvider $playlistDataProvider) : void
    {
        $playListId = $playlistDataProvider->getId();
        $playlistTracksDataProvider = $this->spotifyWebApi->getPlaylistTracks($playListId);

        $songToDelete = [];
        foreach ($playlistTracksDataProvider->getItems() as $song) {
            $deleteTrackInfoDataProvider = new DeleteTrackInfoDataProvider();
            $deleteTrackInfoDataProvider->setId(
                $song->getTrack()->getId()
            );
            $songToDelete[] = $deleteTrackInfoDataProvider;

            if(count($songToDelete) % 100 === 0) {
                $this->spotifyWebApi->deletePlaylistTracks($playListId, $songToDelete );
                $songToDelete = [];
            }
        }

        if(!empty($songToDelete)) {
            $this->spotifyWebApi->deletePlaylistTracks($playListId, $songToDelete );
        }
    }
}