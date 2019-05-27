<?php declare(strict_types=1);


namespace App\Component\Token\Business\Model;


use GuzzleHttp\ClientInterface;
use SpotifyApiConnect\Application\SpotifyApiAuthInterface;
use SpotifyApiConnect\SpotifyApiConnectFactoryInterface;
use GuzzleHttp\Client;

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


    public function url()
    {
        return $this->spotifyApiConnectFactory->createSpotifyApiAuth()->getAuthorizeUrlForPlaylistModifyPublic();
    }
}