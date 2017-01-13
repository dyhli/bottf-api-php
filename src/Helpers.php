<?php
/**
 * Copyright (c) 2017 Yuan Hao 'Danny' Li
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
 
namespace Dyhli\BotTF;

use Dyhli\BotTF\Exceptions;

class Helpers {

    /**
     * Convert scraps to refined
     *
     * @param int
     * @return float
     */
    public function toRefined($scraps) {
        return (float) number_format(floor($scraps / 9 * 100) / 100, 2);
    }

    /**
     * Convert refined to scraps
     *
     * @param float
     * @return int
     */
    public function toScraps($refined) {
        return (int) ceil(round($refined * 9, 1, PHP_ROUND_HALF_UP) * 2) / 2;
    }

    /**
     * Parse a Steam trade offer URL
     *
     * @param string
     * @return boolean/array
     */
    public function parseOfferUrl($offerUrl) {

        preg_match('/https?\:\/\/steamcommunity\.com\/tradeoffer\/new\/\?partner=([0-9]+)&token=(.*)/', $offerUrl, $matches);

        if(count($matches) == 3 && strlen($matches[2]) == 8) {
            return [
                'partner' => $matches[1],
                'token' => $matches[2]
            ];
        }

        return false;

    }

    /**
     * Parse a Steam group URL
     *
     * @param string
     * @return boolean/string
     */
    public function parseGroupUrl($groupUrl) {

        preg_match('/https?\:\/\/steamcommunity\.com\/groups\/(.*)/', $groupUrl, $matches);

        if(count($matches) == 2 && strlen($matches[1]) > 2) {
           return $matches[1];
        }
    
       return false;

    }

    /**
     * Check if an user is marked on Steamrep as scammer
     * Will throw a `HelperException` if it fails
     *
     * @param string
     * @return boolean
     */
    public function isScammer($steamId) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://steamrep.com/api/beta3/reputation/' . trim($steamId) . '?json=1');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        curl_close($ch);

        if($response === false) {
            throw new Exception\HelperException('cURL Error: ' . curl_error($ch));
        }

        $response = json_decode($response);
        if(
            $response &&
            isset($response->steamrep) &&
            isset($response->steamrep->flags)
        ) {

            // is marked?
            if($response->steamrep->flags->status == 'exists' && $response->steamrep->reputation->summary == 'SCAMMER') {
                return true;
            }

            return false;

        } else {
            throw new Exception\HelperException('Received malformed data from Steamrep.com');
        }

    }

}
?>
