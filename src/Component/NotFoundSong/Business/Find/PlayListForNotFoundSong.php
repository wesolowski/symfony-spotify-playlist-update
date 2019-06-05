<?php declare(strict_types=1);


namespace App\Component\NotFoundSong\Business\Find;


use SpotifyApiConnect\Application\SpotifyWebApiInterface;
use SpotifyApiConnect\Domain\DataTransferObject\PlaylistDataProvider;

class PlayListForNotFoundSong implements PlayListForNotFoundSongInterface
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
    public function __construct(SpotifyWebApiInterface $spotifyWebApi, PlaylistDataProvider $playlistDataProvider)
    {
        $this->spotifyWebApi = $spotifyWebApi;
        $this->playlistDataProvider = $playlistDataProvider;
    }

    public function getSongsInfo(): void
    {
        $playlistTracksDataProvider = $this->spotifyWebApi->getPlaylistTracks(
            $this->playlistDataProvider->getId()
        );

        foreach ($playlistTracksDataProvider->getItems() as $song) {
            dump([
                'id' => $song->getTrack()->getId(),
                'song' => $song->getTrack()->getName(),
                'artist' => $song->getTrack()->getArtists()
            ]);
        }
    }
}