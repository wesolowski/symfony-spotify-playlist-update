<?php declare(strict_types=1);


namespace App\Component\PlayListInfo\Model;

use DOMDocument;
use DOMXPath;
use DOMNodeList;


class XpathParser implements XpathParserInterface
{
    /**
     * @param string $html
     * @param string $xpath
     * @return DOMNodeList
     */
    public function parser(string $html, string $xpath): DOMNodeList
    {
        $dom = new DOMDocument;
        $dom->loadHTML(
            $html
        );

        $domXpath = new DOMXPath($dom);
        $domNodeList = $domXpath->query($xpath);

        if (!$domNodeList instanceof DOMNodeList) {
            throw new RuntimeException(sprintf(
                'Xpath "%s" not found', $xpath
            ));
        }

        return $domNodeList;
    }
}