<?php declare(strict_types=1);


namespace App\Component\PlayListInfo\Test;


use App\Component\SpotifyPlayList\Business\Page\HtmlInterface;
use App\Component\SpotifyPlayList\Business\Page\SongPageInterface;
use SpotifyApiConnect\Domain\DataTransferObject\TrackSearchRequestDataProvider;
use DOMDocument;
use DomXPath;

class Songs implements SongPageInterface
{
    private const URL = 'https://www.eska.pl/2xgoraca20';

    private const SPOTIFY_PLAYLIST_NAME = 'Test';

    private const CONSOLE_COMMAND_NAME = 'Test';

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

        $nodes = $this->getXpath()->query('//div[@class="single-hit__info"]');
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
    public function getConsoleName() : string
    {
        return self::CONSOLE_COMMAND_NAME;
    }

    /**
     * @return DomXPath
     */
    private function getXpath(): DomXPath
    {
        $dom = new DomDocument;
        $html = $this->html->get(self::URL);
        $dom->loadHTML($html);
        return new DomXPath($dom);
    }
}