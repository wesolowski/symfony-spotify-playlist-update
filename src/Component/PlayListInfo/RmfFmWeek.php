<?php declare(strict_types=1);


namespace App\Component\PlayListInfo;

use App\Component\PlayListInfo\Model\RadioSpisInterface;
use App\Component\SpotifyPlayList\Business\Page\SongPageInterface;
use SpotifyApiConnect\Domain\DataTransferObject\TrackSearchRequestDataProvider;

class RmfFmWeek implements SongPageInterface
{
    private const RADIO = 'rmf-fm';

    private const SPOTIFY_PLAYLIST_NAME = 'RmfFM Tydzien';

    /**
     * @var RadioSpisInterface
     */
    private $radioSpis;

    /**
     * @param RadioSpisInterface $radioSpis
     */
    public function __construct(RadioSpisInterface $radioSpis)
    {
        $this->radioSpis = $radioSpis;
    }

    /**
     * @return TrackSearchRequestDataProvider[]
     */
    public function getList(): array
    {
        return $this->radioSpis->getList(self::RADIO);
    }

    /**
     * @return string
     */
    public function getSpotifyPlaylistName(): string
    {
        return self::SPOTIFY_PLAYLIST_NAME;
    }

    /**
     * @return string
     */
    public function getConsoleName(): string
    {
        return substr(strrchr(__CLASS__, "\\"), 1);
    }
}