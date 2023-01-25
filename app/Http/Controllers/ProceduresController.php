<?php

namespace App\Http\Controllers;

use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;

class ProceduresController extends Controller
{
    public function dolarBCV()
    {
        // Get https://www.bcv.org.ve/ with guzzle

        $url = 'https://www.bcv.org.ve/';
        $dolarPrice = 22.34;
        try {
            $client = new \GuzzleHttp\Client();
            // Set verify to false to avoid SSL errors
            $body = $response=$client->request('GET', $url, ['verify' => false]);

            $body = $response->getBody();
            $body = $body->getContents();
            $body = self::fix_html_error($body);
            // return $body;

            // Parse the html with DOMDocument
            $dom = new \DOMDocument();
            $dom->loadHTML($body);

            // Get the div with the dollar price
            $xpath = new \DOMXPath($dom);
            $div = $xpath->query('//div[@class="col-sm-6 col-xs-6 centrado"]')->item(4);

            // Replace comma with dot
            $div->nodeValue = str_replace(',', '.', $div->nodeValue);

            // Convert to float
            $div->nodeValue = floatval($div->nodeValue);
            $dolarPrice = $div->nodeValue;
                //code...
        }catch (\Throwable $th) {}

        return ApiResponseController::response('Consulta Exitosa', 200, $dolarPrice);
    }

    static public function getDolarBCV()
    {
        // Get https://www.bcv.org.ve/ with guzzle

        $url = 'https://www.bcv.org.ve/';
        $dolarPrice = 22.34;
        try {
            $client = new \GuzzleHttp\Client();
            // Set verify to false to avoid SSL errors
            $body = $response=$client->request('GET', $url, ['verify' => false]);

            $body = $response->getBody();
            $body = $body->getContents();
            $body = self::fix_html_error($body);
            // return $body;

            // Parse the html with DOMDocument
            $dom = new \DOMDocument();
            $dom->loadHTML($body);

            // Get the div with the dollar price
            $xpath = new \DOMXPath($dom);
            $div = $xpath->query('//div[@class="col-sm-6 col-xs-6 centrado"]')->item(4);

            // Replace comma with dot
            $div->nodeValue = str_replace(',', '.', $div->nodeValue);

            // Convert to float
            $div->nodeValue = floatval($div->nodeValue);
            $dolarPrice = $div->nodeValue;
                //code...
        }catch (\Throwable $th) {}

        return $dolarPrice;
    }

    static public function fix_html_error($html) {
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $clean_html = $purifier->purify($html);
        return $clean_html;
    }
}
