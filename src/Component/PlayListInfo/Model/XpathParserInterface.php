<?php declare(strict_types=1);

namespace App\Component\PlayListInfo\Model;

use DOMNodeList;

interface XpathParserInterface
{
    /**
     * @param string $html
     * @param string $xpath
     * @return DOMNodeList
     */
    public function parser(string $html, string $xpath): DOMNodeList;
}