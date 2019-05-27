<?php declare(strict_types=1);

namespace App\Component\Token\Business;

interface TokenFacadeInterface
{
    /**
     * @return string
     */
    public function generateUrlToRefreshToken() : string;

    /**
     * @param string $code
     * @return string
     */
    public function saveToken(string $code) : void;

    /**
     * @return string
     */
    public function getToken(): string;
}