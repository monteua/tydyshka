<?php

require 'vendor/autoload.php';
require_once 'config/config.php';

/**
 * API endpoint has a limit to 10 requests per hour
 */
$apiEndpoint = "http://quotes.rest/qod.json?category=inspire";

class Inspire {
    /**
     * After the free API calls would be exhausted, using the local library with quotes
     */
    function getQuote() {
        global $apiEndpoint;

        $client = new GuzzleHttp\Client();

        try {
            $res = $client->get($apiEndpoint);
            $response = json_decode($res->getBody());
            return $response->contents->quotes[0]->quote;
        } catch (Exception $e) {
            $quotesJson = file_get_contents(ROOT_PATH."json/quotes.json", true) or die("Unable to open the file");
            $quotes = array(json_decode($quotesJson, true))[0];
            $quote = array_rand($quotes);
            return $quotes[$quote]['text'];
        }
    }
}
