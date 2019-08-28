<?php declare(strict_types=1);


namespace App\Component\PlayListInfo;

use App\Component\SpotifyPlayList\Business\Page\HtmlInterface;
use App\Component\SpotifyPlayList\Business\Page\SongPageInterface;
use SpotifyApiConnect\Domain\DataTransferObject\TrackSearchRequestDataProvider;
use DOMDocument;
use DomXPath;

class RadioZetWeek implements SongPageInterface
{
    private const URL = 'https://radiospis.pl/szukaj?stacja=zet&dzien=%s&godzina=%s';

    private const SPOTIFY_PLAYLIST_NAME = 'Radio ZET Tydzien';

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

        $rangeDate = [
            '-1 Day',
            '-2 Day',
            '-3 Day',
            '-4 Day',
            '-5 Day',
            '-6 Day',
            '-7 Day',
        ];

        $songs = [];

        foreach ($rangeDate as $searchDate) {
            $date = date('Y-m-d', strtotime($searchDate));
            for ($i = 7; $i <= 24; $i += 2) {
                $nodes = $this->getXpath($date, $i);

                foreach ($nodes as $node) {
                    $song = $node->nodeValue;
                    if (!isset($songs[$song])) {
                        $songs[$song] = 0;
                    }
                    ++$songs[$song];
                }

            }
        }

        uasort($songs, function($a, $b) {
            return -1 * ($a <=> $b);
        });

        foreach ($songs as $song => $quanity) {
            $trackSearchRequestDataProvider = new TrackSearchRequestDataProvider();
            $trackSearchRequestDataProvider->setTrack(
                trim(trim(strstr($song, '-'),'-'))
            );
            $artist = trim(strstr($song, '-', true));
            $artist = str_replace([' / ', '/', ], ', ', $artist);
            $trackSearchRequestDataProvider->setArtist(
                $artist
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
     * @param $date
     * @param $time
     * @return \DOMNodeList
     */
    private function getXpath($date, $time): \DOMNodeList
    {
        $dom = new DomDocument;
        $html = $this->html->get(sprintf(self::URL, $date, $time));
        $dom->loadHTML($html);
        return (new DomXPath($dom))->query('//h2[contains(concat(" ",normalize-space(@class)," ")," entry-title ")]//a');
    }
}