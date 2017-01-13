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

class Webhook {

    /**
     * @var array
     */
    private $eventTypes = [
        'tradeStateChanged' => [ 'tradeOfferId' ],
        'tradeReceived' => [ 'tradeOfferId' ],
        'tradeSent' => [ 'tradeOfferId' ],
        'botProfileChanged' => [ 'botId' ],
        'ping' => [ ],
        'bankingChanged' => [ 'botId' ],
        'sellOrdersChanged' => [ 'botId' ],
        'buyOrdersChanged' => [ 'botId' ],
        'friendRequest' => [ 'steamId', 'botId' ],
        'friendRemoved' => [ 'steamId', 'botId' ]
    ];

    /**
     * @var boolean/object
     */
    public $eventData = false;

    /**
     * @param Dyhli\BotTF\API
     */
    public function __construct(API $api) {
        $this->api = $api;
    }

    /**
     * Process the webhook
     */
    public function run() {

        if(
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['type']) &&
            array_key_exists($_POST['type'], $this->eventTypes)
        ) {

            $this->eventData = (object) [
                'type' => $_POST['type']
            ];

            foreach($this->eventTypes[$_POST['type']] as $eventParameter) {
                if(!isset($_POST[$eventParameter])) {
                    throw new Exceptions\WebhookException('Invalid webhook received (2).');
                } else {
                    $this->eventData->$eventParameter = $_POST[$eventParameter];
                }
            }

            return true;

        }

        throw new Exceptions\WebhookException('Invalid webhook received.');

    }

    /**
     * Webhook event data
     *
     * @return boolean/object
     */
    public function eventData() {
        
        return $this->eventData;

    }

}
?>
