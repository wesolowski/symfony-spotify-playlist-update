<?php declare(strict_types=1);


namespace App\Component\SpotifyPlayList\Persistence\DataTransferObject;


use SpotifyApiConnect\Domain\DataTransferObject\TrackSearchRequestDataProvider;

class SongResult
{
    /**
     * @var TrackSearchRequestDataProvider[]
     */
    private $findSongs = [];

    /**
     * @var TrackSearchRequestDataProvider[]
     */
    private $notFoundSongs = [];

    /**
     * @return TrackSearchRequestDataProvider[]
     */
    public function getFindSongs(): array
    {
        return $this->findSongs;
    }

    /**
     * @param TrackSearchRequestDataProvider $findSong
     */
    public function addFindSongs(TrackSearchRequestDataProvider $findSong): void
    {
        $this->findSongs[] = $findSong;
    }

    /**
     * @return TrackSearchRequestDataProvider[]
     */
    public function getNotFoundSongs(): array
    {
        return $this->notFoundSongs;
    }

    /**
     * @param TrackSearchRequestDataProvider $notFoundSong
     */
    public function addNotFoundSongs(TrackSearchRequestDataProvider $notFoundSong): void
    {
        $this->notFoundSongs[] = $notFoundSong;
    }
}