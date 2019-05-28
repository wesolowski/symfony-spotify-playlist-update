<?php declare(strict_types=1);


namespace App\Tests\Unit\Component\Token\Business\Model;


use App\Component\Token\Business\Model\RefreshToken;
use PHPUnit\Framework\TestCase;
use SpotifyApiConnect\Application\SpotifyApiAuthInterface;
use SpotifyApiConnect\SpotifyApiConnectFactoryInterface;
use PHPUnit\Framework\Error\Warning;

class RefreshTokenTest extends TestCase
{
    /**
     * @var string
     */
    private $tokenFile;

    protected function setUp()
    {
        $this->tokenFile = __DIR__ . '/' . RefreshToken::FILE_NAME;
        parent::setUp();
    }

    protected function tearDown()
    {
        if (file_exists($this->tokenFile)) {
            unlink($this->tokenFile);
        }
        parent::tearDown();
    }

    public function testRefreshToken()
    {
        $spotifyTokenMock = new class implements SpotifyApiAuthInterface
        {
            /**
             * @var string
             */
            public $token = 'Unit-43-Test';

            public $accessToken = 'Unit-43-Access-Token';

            public function getRefreshTokenByCode(string $code): string
            {
                return $this->token;
            }

            public function getAuthorizeUrlForPlaylistModifyPublic(): string
            {
                return '';
            }

            public function getAccessByRefreshToken(string $refreshAccessToken): string
            {
                return $this->accessToken;
            }
        };

        $mockSpotifyApiConnectFactory = $this->createMock(SpotifyApiConnectFactoryInterface::class);
        $mockSpotifyApiConnectFactory->method(
            'createSpotifyApiAuth'
        )->willReturn($spotifyTokenMock);

        $refreshToken = new RefreshToken(
            $mockSpotifyApiConnectFactory,
            __DIR__
        );

        $refreshToken->save('codeUnit');
        $this->assertSame($spotifyTokenMock->accessToken, $refreshToken->get());
        $this->assertFileExists($this->tokenFile);
        $this->assertSame($spotifyTokenMock->token, file_get_contents($this->tokenFile));

        $this->checkWarning($refreshToken);
    }

    /**
     * @param RefreshToken $refreshToken
     */
    private function checkWarning(RefreshToken $refreshToken): void
    {
        $this->expectException(Warning::class);
        file_put_contents($this->tokenFile, '');
        $code = $refreshToken->get();
        $this->assertEmpty($code);
    }
}