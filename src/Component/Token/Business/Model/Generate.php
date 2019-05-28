<?php declare(strict_types=1);


namespace App\Component\Token\Business\Model;

use GuzzleHttp\ClientInterface;
use SpotifyApiConnect\SpotifyApiConnectFactoryInterface;

class Generate implements GenerateInterface
{
    /**
     * @var SpotifyApiConnectFactoryInterface
     */
    private $spotifyApiConnectFactory;

    /**
     * @param SpotifyApiConnectFactoryInterface $spotifyApiConnectFactory
     * @param ClientInterface $client
     */
    public function __construct(SpotifyApiConnectFactoryInterface $spotifyApiConnectFactory)
    {
        $this->spotifyApiConnectFactory = $spotifyApiConnectFactory;
    }

    /**
     * @return string
     */
    public function url() : string
    {
        return $this->spotifyApiConnectFactory->createSpotifyApiAuth()->getAuthorizeUrlForPlaylistModifyPublic();
    }
}