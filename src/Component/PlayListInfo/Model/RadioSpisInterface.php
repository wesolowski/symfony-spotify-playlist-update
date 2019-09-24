<?php declare(strict_types=1);

namespace App\Component\PlayListInfo\Model;

use SpotifyApiConnect\Domain\DataTransferObject\TrackSearchRequestDataProvider;

interface RadioSpisInterface
{
    /**
     * @param string $radio
     * @return TrackSearchRequestDataProvider[]
     */
    public function getList(string $radio): array;
}