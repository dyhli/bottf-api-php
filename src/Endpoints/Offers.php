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
 
namespace Dyhli\BotTF\Endpoints;

use Dyhli\BotTF\Exceptions;
use Dyhli\BotTF\Objects;

class Offers extends Endpoint {

    /**
     * Retrieve a list of all offers
     *
     * @param int
     * @param int
     * @return array
     */
    public function all($page = 0, $itemsPerPage = 25) {

        $list = $this->api->request->get('offers', [
            'page' => $page,
            'itemsPerPage' => $itemsPerPage
        ]);

        $offers = [ ];

        foreach($list->offers as $offer) {
            $offers[] = new Objects\Offer($this->api, $offer);
        }

        return $offers;

    }

    /**
     * Retrieve a single offer
     *
     * @param int
     * @return Dyhli\BotTF\Objects\Offer
     */
    public function get($tid) {

        $offer = $this->api->request->get('offers/' . $tid);

        if(!isset($offer->offer)) {
            throw new Exceptions\GeneralException('Invalid offer object received');
        }

        return new Objects\Offer($this->api, $offer->offer);

    }

    /**
     * Get pending offers
     *
     * @return array
     */
    public function pending() {

        return $this->api->request->get('offers/pending');

    }

    /**
     * Accept an offer
     *
     * @param int
     * @return object
     */
    public function accept($tid) {

        $this->api->request->post('offers/' . $tid . '/accept');

        return true;

    }

    /**
     * Decline an offer
     *
     * @param int
     * @return object
     */
    public function decline($tid) {

        $this->api->request->post('offers/' . $tid . '/decline');

        return true;

    }

    /**
     * Cancel an offer
     *
     * @param int
     * @return object
     */
    public function cancel($tid) {

        $this->api->request->post('offers/' . $tid . '/cancel');

        return true;

    }

    /**
     * Send a trade offer
     *
     * @param int
     * @param string
     * @param array
     * @param array
     * @param string
     * @return object
     */
    public function send($bid, $offerUrl, $itemsMe = [ ], $itemsThem = [ ], $message = '') {

        // check if offerUrl is valid
        if(!$this->api->helpers->parseOfferUrl($offerUrl)) {
            throw new Exceptions\GeneralException('offerUrl is an invald trade offer URL');
        }

        // check if itemsMe is an array
        if(!is_array($itemsMe)) {
            throw new Exceptions\GeneralException('itemsMe must be an array');
        }

        // check if itemsThem is an array
        if(!is_array($itemsThem)) {
            throw new Exceptions\GeneralException('itemsThem must be an array');
        }

        // check if itemsMe and itemsThem aren't both empty
        if(count($itemsMe) == 0 && count($itemsThem) == 0) {
            throw new Exceptions\GeneralException('itemsMe and itemsThem cannot both be empty');
        }

        return $this->api->request->put('offers', [
            'botid' => $bid,
            'offerurl' => $offerUrl,
            'itemsme' => $itemsMe,
            'itemsthem' => $itemsThem,
            'message' => $message
        ]);

    }

}
?>
