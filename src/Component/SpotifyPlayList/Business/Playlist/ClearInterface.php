<?php declare(strict_types=1);

namespace App\Component\SpotifyPlayList\Business\Playlist;

use SpotifyApiConnect\Domain\DataTransferObject\PlaylistDataProvider;

interface ClearInterface
{
    public function deleteAllSong(PlaylistDataProvider $playlistDataProvider);
}