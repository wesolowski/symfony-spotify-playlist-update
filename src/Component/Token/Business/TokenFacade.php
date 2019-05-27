<?php declare(strict_types=1);


namespace App\Component\Token\Business;


use App\Component\Token\Business\Model\GenerateInterface;
use App\Component\Token\Business\Model\RefreshTokenInterface;

class TokenFacade implements TokenFacadeInterface
{
    /**
     * @var GenerateInterface
     */
    private $generate;

    /**
     * @var RefreshTokenInterface
     */
    private $refreshToken;

    /**
     * @param GenerateInterface $generate
     * @param RefreshTokenInterface $refreshToken
     */
    public function __construct(GenerateInterface $generate, RefreshTokenInterface $refreshToken)
    {
        $this->generate = $generate;
        $this->refreshToken = $refreshToken;
    }

    /**
     * @return string
     */
    public function generateUrlToRefreshToken() : string
    {
        return $this->generate->url();
    }

    /**
     * @param string $code
     * @return string
     */
    public function saveToken(string $code) : void
    {
        $this->refreshToken->save($code);
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->refreshToken->get();
    }


}