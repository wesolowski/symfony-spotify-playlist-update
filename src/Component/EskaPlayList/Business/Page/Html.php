<?php declare(strict_types=1);


namespace App\Component\EskaPlayList\Business\Page;


use GuzzleHttp\Client;

class Html implements HtmlInterface
{
    /**
     * @return string
     */
    public function get() : string
    {
        $client = new Client([
            'base_uri' => 'https://www.eska.pl/'
        ]);
        $res = $client->get('2xgoraca20');
        return (string)$res->getBody()->getContents();
    }
}