<?php declare(strict_types=1);


namespace App\Component\SpotifyPlayList\Business\Page;


use Symfony\Contracts\HttpClient\HttpClientInterface;
use RuntimeException;
use DomDocument;
use DOMXPath;
use DOMNodeList;
use \Symfony\Contracts\HttpClient\Exception as HttpClientException;

class Html implements HtmlInterface
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $url
     * @param string $xpath
     * @return DOMNodeList
     * @throws HttpClientException\ClientExceptionInterface
     * @throws HttpClientException\RedirectionExceptionInterface
     * @throws HttpClientException\ServerExceptionInterface
     * @throws HttpClientException\TransportExceptionInterface
     */
    public function get(string $url, string $xpath): DOMNodeList
    {
        $response = $this->httpClient->request('GET', $url);
        $statusCode = $response->getStatusCode();
        if (200 > $statusCode || $statusCode > 299) {
            throw new RuntimeException('Content not found for page: ' . $url);
        }

        $dom = new DomDocument;
        $dom->loadHTML(
            $response->getContent()
        );

        $domXpath = new DOMXPath($dom);
        $domNodeList = $domXpath->query($xpath);

        if (!$domNodeList instanceof DOMNodeList) {
            throw new RuntimeException(sprintf(
                'Xpath "%s" not found for page: %s', $xpath, $url
            ));
        }

        return $domNodeList;
    }
}