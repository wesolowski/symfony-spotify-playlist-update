<?php declare(strict_types=1);


namespace App\Service\Spotify\Persistence;


class SongInfo
{
    /**
     * @var string
     */
    private $artist;

    /**
     * @var string
     */
    private $song;

    /**
     * @return string
     */
    public function getArtist(): string
    {
        return $this->artist;
    }

    /**
     * @param string $artist
     */
    public function setArtist(string $artist): void
    {
        $this->artist = $artist;
    }

    /**
     * @return string
     */
    public function getSong(): string
    {
        return $this->song;
    }

    /**
     * @param string $song
     */
    public function setSong(string $song): void
    {
        $this->song = $song;
    }
}