<?php declare(strict_types=1);

namespace App\Component\PlayListInfo;


use App\Component\SpotifyPlayList\Business\Page\HtmlInterface;
use App\Component\SpotifyPlayList\Business\Page\SongPageInterface;
use SpotifyApiConnect\Domain\DataTransferObject\TrackSearchRequestDataProvider;
use DOMDocument;
use DomXPath;

class EskaGoraca20 implements SongPageInterface
{
    private const URL = 'https://www.eska.pl/2xgoraca20';

    private const SPOTIFY_PLAYLIST_NAME = 'Radio Eska - 2 x Gorąca 20';

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
    public function getList(): array
    {
        $infos = [];
        libxml_use_internal_errors(true);

        $nodes = $this->html->get(self::URL, '//div[@class="single-hit__info"]');
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

    /**
     * @return string
     */
    public function getSpotifyPlaylistName() : string
    {
        return self::SPOTIFY_PLAYLIST_NAME;
    }

    /**
     * @return string
     */
    public function getConsoleName(): string
    {
        return substr(strrchr(__CLASS__, "\\"), 1);
    }
}