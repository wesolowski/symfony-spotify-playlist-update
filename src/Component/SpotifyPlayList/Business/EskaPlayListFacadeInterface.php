<?php declare(strict_types=1);

namespace App\Component\SpotifyPlayList\Business;

use App\Component\SpotifyPlayList\Business\Page\SongPageInterface;

interface EskaPlayListFacadeInterface
{
    public function updatePlayList(SongPageInterface $songPageInfo);
}