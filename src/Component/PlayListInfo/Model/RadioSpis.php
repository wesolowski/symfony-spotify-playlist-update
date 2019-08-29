<?php declare(strict_types=1);


namespace App\Component\PlayListInfo\Model;


use App\Component\SpotifyPlayList\Business\Page\HtmlInterface;
use SpotifyApiConnect\Domain\DataTransferObject\TrackSearchRequestDataProvider;
use DOMDocument;
use DOMNodeList;

final class RadioSpis implements RadioSpisInterface
{
    private const XPATH = '//h2[contains(concat(" ",normalize-space(@class)," ")," entry-title ")]//a';

    private const URL = 'https://radiospis.pl/szukaj?stacja=%s&dzien=%s&godzina=%s';

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
     * @param string $radio
     * @return TrackSearchRequestDataProvider[]
     */
    public function getList(string $radio): array
    {
        $infos = [];

        libxml_use_internal_errors(true);

        $songInfos = $this->getSongsInfo($radio);

        foreach ($songInfos as $song => $quanity) {
            $trackSearchRequestDataProvider = new TrackSearchRequestDataProvider();
            $trackSearchRequestDataProvider->setTrack(
                trim(trim(strstr($song, '-'), '-'))
            );
            $artist = trim(strstr($song, '-', true));
            $artist = str_replace([' / ', '/',], ', ', $artist);
            $trackSearchRequestDataProvider->setArtist(
                $artist
            );
            $infos[] = $trackSearchRequestDataProvider;
        }

        return $infos;
    }

    /**
     * @param string $radio
     * @return array
     */
    private function getSongsInfo(string $radio): array
    {
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
            for ($time = 7; $time <= 24; $time += 2) {
                $nodes = $this->html->get(sprintf(self::URL, $radio, $date, $time), self::XPATH);

                foreach ($nodes as $node) {
                    $song = $node->nodeValue;
                    if (!isset($songs[$song])) {
                        $songs[$song] = 0;
                    }
                    ++$songs[$song];
                }

            }
        }

        uasort($songs, function ($a, $b) {
            return -1 * ($a <=> $b);
        });

        return $songs;
    }
}