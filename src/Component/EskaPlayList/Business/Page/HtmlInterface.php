<?php declare(strict_types=1);

namespace App\Component\EskaPlayList\Business\Page;

interface HtmlInterface
{
    /**
     * @return string
     */
    public function get(): string;
}