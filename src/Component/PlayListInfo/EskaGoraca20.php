<?php declare(strict_types=1);

namespace App\Component\PlayListInfo;


use App\Component\PlayListInfo\Model\XpathParser;
use App\Component\PlayListInfo\Model\XpathParserInterface;
use App\Component\SpotifyPlayList\Business\Page\HtmlInterface;
use App\Component\SpotifyPlayList\Business\Page\SongPageInterface;
use SpotifyApiConnect\Domain\DataTransferObject\TrackSearchRequestDataProvider;

class EskaGoraca20 implements SongPageInterface
{
    private const URL = 'https://www.eska.pl/2xgoraca20';

    private const SPOTIFY_PLAYLIST_NAME = 'Radio Eska - 2 x Gorąca 20';

    private const XPATH = '//div[@class="single-hit__info"]';

    /**
     * @var HtmlInterface
     */
    private $html;

    /**
     * @var XpathParserInterface
     */
    private $xpathParser;

    /**
     * @param HtmlInterface $html
     * @param XpathParserInterface $xpathParser
     */
    public function __construct(HtmlInterface $html, XpathParserInterface $xpathParser)
    {
        $this->html = $html;
        $this->xpathParser = $xpathParser;
    }


    /**
     * @return TrackSearchRequestDataProvider[]
     */
    public function getList(): array
    {
        $infos = [];
        libxml_use_internal_errors(true);

        $html = $this->html->get(self::URL);
        $nodes = $this->xpathParser->parser($html, self::XPATH);

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