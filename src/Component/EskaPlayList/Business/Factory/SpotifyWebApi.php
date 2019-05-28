<?php declare(strict_types=1);


namespace App\Component\EskaPlayList\Business\Factory;


use App\Component\EskaPlayList\Business\EskaPlayListConfig;
use App\Component\Token\Business\TokenFacadeInterface;
use SpotifyApiConnect\Application\SpotifyWebApiInterface;
use SpotifyApiConnect\SpotifyApiConnectFactoryInterface;

class SpotifyWebApi
{
    /**
     * @var SpotifyApiConnectFactoryInterface
     */
    private $spotifyApiConnectFactory;

    /**
     * @var TokenFacadeInterface
     */
    private $tokenFacade;

    /**
     * @param SpotifyApiConnectFactoryInterface $spotifyApiConnectFactory
     * @param TokenFacadeInterface $tokenFacade
     */
    public function __construct(
        SpotifyApiConnectFactoryInterface $spotifyApiConnectFactory,
        TokenFacadeInterface $tokenFacade
    )
    {
        $this->spotifyApiConnectFactory = $spotifyApiConnectFactory;
        $this->tokenFacade = $tokenFacade;
    }

    /**
     * @return \SpotifyApiConnect\Application\SpotifyWebApiInterface
     */
    public function createSpotifyWebApi() : SpotifyWebApiInterface
    {
        return $this->spotifyApiConnectFactory->createSpotifyWebApi(
            $this->tokenFacade->getToken()
        );
    }
}