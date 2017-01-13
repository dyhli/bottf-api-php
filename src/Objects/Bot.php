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
 
namespace Dyhli\BotTF\Objects;

class Bot extends Object {

    /**
     * Get the friendlist
     */
    public function friendList() {

        return $this->api->bots->friendList($this->object->id);

    }

    /**
     * Add a friend
     *
     * @param string
     * @return boolean
     */
    public function addFriend($sid) {

        return $this->api->bots->addFriend($this->object->id, $sid);

    }

    /**
     * Delete a friend
     *
     * @param string
     * @return boolean
     */
    public function deleteFriend($sid) {

        return $this->api->bots->deleteFriend($this->object->id, $sid);

    }

    /**
     * Use an item
     *
     * @param int
     * @return boolean
     */
    public function useItem($aid) {

        return $this->api->bots->useItem($this->object->id, $aid);

    }

    /**
     * Send a chat message
     *
     * @param string
     * @param string
     * @return boolean
     */
    public function chatMessage($sid, $msg) {

        return $this->api->bots->chatMessage($this->object->id, $sid, $msg);

    }

    /**
     * Send a chat message
     *
     * @param string
     * @param string
     * @return boolean
     */
    public function sendOffer($offerUrl, $itemsMe = [ ], $itemsThem = [ ], $message = '') {

        return $this->api->offers->send($this->object->id, $offerUrl, $itemsMe, $itemsThem, $message);

    }

    /**
     * Restart the bot
     *
     * @return boolean
     */
    public function restart() {

        return $this->api->bots->restart($this->object->id);

    }

}
?>
