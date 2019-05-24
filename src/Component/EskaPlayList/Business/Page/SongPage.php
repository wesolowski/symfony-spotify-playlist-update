<?php declare(strict_types=1);


namespace App\Component\EskaPlayList\Business\Page;


use SpotifyApiConnect\Domain\DataTransferObject\TrackSearchRequestDataProvider;
use DomDocument;
use DomXPath;

class SongPage implements SongPageInterface
{
    /**
     * @var HtmlInterface
     */
    private $html;

    /**
     * @param HtmlInterface $html
     */
    public function __construct(HtmlInterface $html)
    {
        $this->html = $html;
    }


    /**
     * @return TrackSearchRequestDataProvider[]
     */
    public function getList() : array
    {
        libxml_use_internal_errors(true);
        $dom = new DomDocument;
        $html = $this->html->get();
        $dom->loadHTML($html);
        $xpath = new DomXPath($dom);
        $nodes = $xpath->query('//div[@class="single-hit__info"]');

        $infos = [];
        foreach ($nodes as $node) {
            $trackSearchRequestDataProvider = new TrackSearchRequestDataProvider();
            $trackSearchRequestDataProvider->setTrack(
                trim($node->getElementsByTagName('a')[0]->nodeValue)
            );
            $trackSearchRequestDataProvider->setArtist(
                trim($node->getElementsByTagName('a')[1]->nodeValue)
            );
            $infos[] = $trackSearchRequestDataProvider;
        }

        return $infos;
    }
}