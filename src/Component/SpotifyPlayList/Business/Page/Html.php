<?php declare(strict_types=1);


namespace App\Component\SpotifyPlayList\Business\Page;


use Symfony\Contracts\HttpClient\HttpClientInterface;
use RuntimeException;

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
     * @return string
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function get(string $url): string
    {
        $response = $this->httpClient->request('GET', $url);
        $statusCode = $response->getStatusCode();
        if (200 > $statusCode || $statusCode > 299) {
            throw new RuntimeException('Content not found for page: ' . $url);
        }

        return $response->getContent();
    }
}