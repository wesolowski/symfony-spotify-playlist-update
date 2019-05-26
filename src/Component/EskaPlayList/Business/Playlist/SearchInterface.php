<?php declare(strict_types=1);

namespace App\Component\EskaPlayList\Business\Playlist;

use SpotifyApiConnect\Domain\DataTransferObject\TrackSearchRequestDataProvider;

interface SearchInterface
{
    /**
     * @param TrackSearchRequestDataProvider $trackSearchRequestDataProvider
     * @return string
     */
    public function searchSongs(TrackSearchRequestDataProvider $trackSearchRequestDataProvider): string;
}