<?php declare(strict_types=1);


namespace App\Component\Token\Business\Model;

use SpotifyApiConnect\SpotifyApiConnectFactoryInterface;

class RefreshToken implements RefreshTokenInterface
{
    private const FILE_NAME = 'token.txt';

    /**
     * @var SpotifyApiConnectFactoryInterface
     */
    private $spotifyApiConnectFactory;

    /**
     * @var string
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
     * @param string $code
     */
    public function save(string $code) : void
    {
        $spotifyApiAuth = $this->spotifyApiConnectFactory->createSpotifyApiAuth();
        $refreshToken = $spotifyApiAuth->getRefreshTokenByCode($code);
        file_put_contents($this->getPath(), $refreshToken);
    }

    /**
     * @return string
     */
    public function get() : string
    {
        $accessToken = '';
        $tokenPath = $this->getPath();
        if (file_exists($tokenPath) && ($token = file_get_contents($tokenPath))) {
            $accessToken = $this->spotifyApiConnectFactory->createSpotifyApiAuth()->getAccessByRefreshToken($token);
        } else {
            trigger_error(
                'Token file not found. Pleas call http://<url>/token/generate for generate it',
                E_USER_WARNING
            );
        }

        return $accessToken;
    }

    private function getPath() : string
    {
        return $this->kernelProjectDir . '/' . self::FILE_NAME;
    }
}