<?php declare(strict_types=1);


namespace App\Component\EskaPlayList\Business;


use App\Component\EskaPlayList\Business\Model\UpdateInterface;

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