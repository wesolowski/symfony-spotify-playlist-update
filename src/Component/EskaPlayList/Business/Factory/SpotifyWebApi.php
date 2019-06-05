<?php declare(strict_types=1);


namespace App\Component\EskaPlayList\Business\Factory;


use App\Component\EskaPlayList\Business\EskaPlayListConfig;
use App\Component\Token\Business\TokenFacadeInterface;
use SpotifyApiConnect\Application\SpotifyWebApiInterface;
use SpotifyApiConnect\SpotifyApiConnectFactoryInterface;

class SpotifyWebApi
{
    /**
     * @param SpotifyApiConnectFactoryInterface $spotifyApiConnectFactory
     * @param TokenFacadeInterface $tokenFacade
     * @return SpotifyWebApiInterface
     */
    public static function createSpotifyWebApi(
        SpotifyApiConnectFactoryInterface $spotifyApiConnectFactory,
        TokenFacadeInterface $tokenFacade
    ): SpotifyWebApiInterface
    {
        return $spotifyApiConnectFactory->createSpotifyWebApi(
            $tokenFacade->getToken()
        );
    }
}