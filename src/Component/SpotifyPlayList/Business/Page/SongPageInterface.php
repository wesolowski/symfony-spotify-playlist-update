<?php declare(strict_types=1);

namespace App\Component\SpotifyPlayList\Business\Page;

use SpotifyApiConnect\Domain\DataTransferObject\TrackSearchRequestDataProvider;

interface SongPageInterface
{
    /**
     * @return TrackSearchRequestDataProvider[]
     */
    public function getList(): array;
}