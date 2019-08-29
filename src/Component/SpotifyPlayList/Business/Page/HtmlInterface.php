<?php declare(strict_types=1);

namespace App\Component\SpotifyPlayList\Business\Page;

use DomDocument;
use DOMNodeList;
use DOMXPath;
use RuntimeException;
use Symfony\Contracts\HttpClient\Exception as HttpClientException;

interface HtmlInterface
{

    /**
     * @param string $url
     * @param string $xpath
     * @return DOMNodeList
     * @throws HttpClientException\ClientExceptionInterface
     * @throws HttpClientException\RedirectionExceptionInterface
     * @throws HttpClientException\ServerExceptionInterface
     * @throws HttpClientException\TransportExceptionInterface
     */
    public function get(string $url, string $xpath): DOMNodeList;
}