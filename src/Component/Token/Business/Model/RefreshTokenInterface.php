<?php declare(strict_types=1);

namespace App\Component\Token\Business\Model;

interface RefreshTokenInterface
{
    /**
     * @param string $code
     */
    public function save(string $code): void;

    /**
     * @return string
     */
    public function get(): string;
}