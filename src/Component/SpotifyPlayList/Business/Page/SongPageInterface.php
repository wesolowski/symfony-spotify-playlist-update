<?php declare(strict_types=1);

namespace App\Component\SpotifyPlayList\Business\Page;

use App\Component\PlayListInfo\EskaGoraca20\Songs;
use SpotifyApiConnect\Domain\DataTransferObject\TrackSearchRequestDataProvider;

interface SongPageInterface
{
    /**
     * @return TrackSearchRequestDataProvider[]
     */
    public function getList(): array;

    /**
     * @return string
     */
    public function getSpotifyPlaylistName(): string;

    /**
     * @return string
     */
    public function getConsoleName(): string;
}