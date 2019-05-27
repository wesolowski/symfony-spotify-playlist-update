<?php declare(strict_types=1);


namespace App\Component\EskaPlayList\Business\Factory;


use App\Component\EskaPlayList\Business\EskaPlayListConfig;
use SpotifyApiConnect\Application\SpotifyWebApiInterface;
use SpotifyApiConnect\SpotifyApiConnectFactoryInterface;

class SpotifyWebApi
{
    /**
     * @var SpotifyApiConnectFactoryInterface
     */
    private $spotifyApiConnectFactory;

    /**
     * @var
     */
    private $kernelProjectDir;

    /**
     * @param SpotifyApiConnectFactoryInterface $spotifyApiConnectFactory
     * @param string $kernelProjectDir
     */
    public function __construct(
        SpotifyApiConnectFactoryInterface $spotifyApiConnectFactory,
        string $kernelProjectDir
    )
    {
        $this->spotifyApiConnectFactory = $spotifyApiConnectFactory;
        $this->kernelProjectDir = $kernelProjectDir;
    }


    /**
     * @return \SpotifyApiConnect\Application\SpotifyWebApiInterface
     */
    public function createSpotifyWebApi() : SpotifyWebApiInterface
    {
        $accessToken = '';
        $spotifyApiAuth = $this->spotifyApiConnectFactory->createSpotifyApiAuth();
        $tokenPath = $this->kernelProjectDir . '/'.  EskaPlayListConfig::REFRESH_TOKEN_FILE_NAME;
        if (file_exists($tokenPath) && ($token = file_get_contents($tokenPath))) {
            $accessToken = $spotifyApiAuth->getAccessByRefreshToken($token);
        } else {
            trigger_error('Token file not found ' . $tokenPath, E_USER_WARNING);
        }

        return $this->spotifyApiConnectFactory->createSpotifyWebApi($accessToken);
    }
}