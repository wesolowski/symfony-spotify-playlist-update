<?php declare(strict_types=1);


namespace App\Component\PlayListInfo;


use App\Component\SpotifyPlayList\Business\Page\HtmlInterface;
use App\Component\SpotifyPlayList\Business\Page\SongPageInterface;
use SpotifyApiConnect\Domain\DataTransferObject\TrackSearchRequestDataProvider;
use DOMDocument;
use DOMXPath;

class RadioZetListPrzebojow implements SongPageInterface
{
    private const URL = 'https://www.radiozet.pl/Radio/Lista-przebojow';

    private const SPOTIFY_PLAYLIST_NAME = 'Radio ZET - Lista przebojów';

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

        $nodes = $this->getXpath()->query('//div[contains(concat(" ",normalize-space(@class)," ")," votingData ")]//div[contains(concat(" ",normalize-space(@class)," ")," list-element ")]//div[contains(concat(" ",normalize-space(@class)," ")," track ")]');
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

    /**
     * @return DomXPath
     */
    private function getXpath(): DOMXPath
    {
        $dom = new DOMDocument();
        $html = $this->html->get(self::URL);
        $dom->loadHTML($html);
        return new DOMXPath($dom);
    }

    private function clearName(string $track): string
    {
        $pos = strpos($track, '(feat. ');
        if ($pos !== false) {
            $expolode = explode('(feat. ', $track);
            $track = ltrim($expolode[0]) . trim( substr($expolode[1], strpos($expolode[1], ')') + 1) );
        }
        return $track;
    }
}