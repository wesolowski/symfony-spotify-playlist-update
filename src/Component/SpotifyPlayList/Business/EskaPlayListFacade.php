<?php declare(strict_types=1);


namespace App\Component\SpotifyPlayList\Business;


use App\Component\SpotifyPlayList\Business\Model\UpdateInterface;

class EskaPlayListFacade implements EskaPlayListFacadeInterface
{

    /**
     * @var UpdateInterface
     */
    private $update;

    /**
     * @param UpdateInterface $update
     */
    public function __construct(UpdateInterface $update)
    {
        $this->update = $update;
    }

    public function updatePlayList()
    {
        $this->update->updatePlayList();
    }
}