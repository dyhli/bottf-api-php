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

use Dyhli\BotTF\Endpoints;

class API {

    /**
     * Constructor
     *
     * @param string
     */
    public function __construct($apiKey) {

        /**
         * @var Dyhli\BotTF\Request
         */
        $this->request = new Request($apiKey);

        /**
         * @var Dyhli\BotTF\Helpers
         */
        $this->helpers = new Helpers;

        /**
         * @var Dyhli\BotTF\Endpoints\Account
         */
        $this->account = new Endpoints\Account($this);

        /**
         * @var Dyhli\BotTF\Endpoints\Offers
         */
        $this->offers = new Endpoints\Offers($this);

        /**
         * @var Dyhli\BotTF\Endpoints\Bots
         */
        $this->bots = new Endpoints\Bots($this);

        /**
         * @var Dyhli\BotTF\Endpoints\Banking
         */
        $this->banking = new Endpoints\Banking($this);

        /**
         * @var Dyhli\BotTF\Endpoints\BuyOrders
         */
        $this->buyOrders = new Endpoints\BuyOrders($this);

        /**
         * @var Dyhli\BotTF\Endpoints\SellOrders
         */
        $this->sellOrders = new Endpoints\SellOrders($this);

        /**
         * @var Dyhli\BotTF\Endpoints\Banlist
         */
        $this->banlist = new Endpoints\Banlist($this);

        /**
         * @var Dyhli\BotTF\Endpoints\Inventory
         * @todo
         */
        //$this->inventory = new Endpoints\Inventory($this);

        /**
         * @var Dyhli\BotTF\Endpoints\Steam
         * @todo
         */
        //$this->steam = new Endpoints\Steam($this);
    }

    /**
     * Get the API rate limit stats
     *
     * @return object
     */
    public function getRate() {
        return $this->request->getRate();
    }

    /**
     * Get the time to finish the request in ms
     *
     * @return int
     */
    public function getTime() {
        return $this->request->getTime();
    }

}
?>
