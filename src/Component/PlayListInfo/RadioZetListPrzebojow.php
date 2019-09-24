<?php declare(strict_types=1);


namespace App\Component\PlayListInfo;


use App\Component\PlayListInfo\Model\XpathParserInterface;
use App\Component\SpotifyPlayList\Business\Page\HtmlInterface;
use App\Component\SpotifyPlayList\Business\Page\SongPageInterface;
use SpotifyApiConnect\Domain\DataTransferObject\TrackSearchRequestDataProvider;

class RadioZetListPrzebojow implements SongPageInterface
{
    private const URL = 'https://www.radiozet.pl/Radio/Lista-przebojow';

    private const SPOTIFY_PLAYLIST_NAME = 'Radio ZET - Lista przebojów';

    private const XPATH = '//div[contains(concat(" ",normalize-space(@class)," ")," votingData ")]//div[contains(concat(" ",normalize-space(@class)," ")," list-element ")]//div[contains(concat(" ",normalize-space(@class)," ")," track ")]';

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
                trim($this->clearName($node->getElementsByTagName('div')[1]->nodeValue))
            );
            $trackSearchRequestDataProvider->setArtist(
                trim($node->getElementsByTagName('div')[0]->nodeValue)
            );

            $infos[] = $trackSearchRequestDataProvider;
        }

        return $infos;
    }

    /**
     * @return string
     */
    public function getSpotifyPlaylistName(): string
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


    private function clearName(string $track): string
    {
        $pos = strpos($track, '(feat. ');
        if ($pos !== false) {
            $explode = explode('(feat. ', $track);
            $track = ltrim($explode[0]) . trim( substr($explode[1], strpos($explode[1], ')') + 1) );
        }
        return $track;
    }
}