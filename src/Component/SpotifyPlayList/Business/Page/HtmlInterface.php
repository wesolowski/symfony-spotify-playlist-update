<?php declare(strict_types=1);

namespace App\Component\SpotifyPlayList\Business\Page;

interface HtmlInterface
{
    /**
     * @return string
     */
    public function get(string $url): string;
}