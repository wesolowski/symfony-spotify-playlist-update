<?php declare(strict_types=1);

namespace App\Component\SpotifyPlayList\Business\Model;

use App\Component\SpotifyPlayList\Business\Page\SongPageInterface;

interface UpdateInterface
{
    public function updatePlayList(SongPageInterface $songPageInfo);
}