<?php declare(strict_types=1);


namespace App\Component\NotFoundSong\Business;


use App\Component\NotFoundSong\Business\Find\PlayListForNotFoundSongInterface;

class NotFoundSongFacade implements NotFoundSongFacadeInterface
{
    /**
     * @var PlayListForNotFoundSongInterface
     */
    private $playListForNotFoundSong;

    /**
     * @param PlayListForNotFoundSongInterface $playListForNotFoundSong
     */
    public function __construct(PlayListForNotFoundSongInterface $playListForNotFoundSong)
    {
        $this->playListForNotFoundSong = $playListForNotFoundSong;
    }

    public function getSongsInfo()
    {
        $this->playListForNotFoundSong->getSongsInfo();
    }


}