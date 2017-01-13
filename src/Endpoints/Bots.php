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

class Bots extends Endpoint {

    /**
     * Retrieve a list of all bots
     *
     * @param int
     * @param int
     * @return array
     */
    public function all($page = 0, $itemsPerPage = 25) {

        $list = $this->api->request->get('bots', [
            'page' => $page,
            'itemsPerPage' => $itemsPerPage
        ]);

        $bots = [ ];

        foreach($list->bots as $bot) {
            $bots[] = new Objects\Bot($this->api, $bot);
        }

        return $bots;

    }

    /**
     * Retrieve a single bot
     *
     * @param int
     * @return Dyhli\BotTF\Objects\Bot
     */
    public function get($bid) {

        $bot = $this->api->request->get('bots/' . $bid);

        if(!isset($bot->bot)) {
            throw new Exceptions\GeneralException('Invalid bot object received');
        }

        return new Objects\Bot($this->api, $bot->bot);

    }

    /**
     * Add a friend
     *
     * @param int
     * @param string
     * @return boolean
     */
    public function addFriend($bid, $sid) {

        $this->api->request->put('bots/' . $bid . '/friends/' . $sid);

        return true;

    }

    /**
     * Delete a friend
     *
     * @param int
     * @param string
     * @return boolean
     */
    public function deleteFriend($bid, $sid) {

        $this->api->request->delete('bots/' . $bid . '/friends/' . $sid);

        return true;

    }

    /**
     * Get the friendlist
     *
     * @param int
     * @return array
     */
    public function friendList($bid) {

        $list = $this->api->request->get('bots/' . $bid . '/friends');

        $friends = [ ];

        foreach($list->friends as $friend) {
            $friend->botid = $bid;

            $friends[] = new Objects\Friend($this->api, $friend);
        }

        return $friends;

    }

    /**
     * Use an item (e.g. Backpack Expander)
     *
     * @param int
     * @param int
     * @return boolean
     */
    public function useItem($bid, $aid) {

        $this->api->request->post('bots/' . $bid . '/item/' . $aid);

        return true;

    }

    /**
     * Send a chat message
     *
     * @param int
     * @param string
     * @param string
     * @return boolean
     */
    public function chatMessage($bid, $sid, $msg) {

        $this->api->request->post('bots/' . $bid . '/chat/' . $sid, [
            'message' => $msg
        ]);

        return true;

    }

    /**
     * Restart a bot
     *
     * @param int
     * @return boolean
     */
    public function restart($bid) {

        $this->api->request->post('bots/' . $bid . '/restart');

        return true;

    }

}
?>
