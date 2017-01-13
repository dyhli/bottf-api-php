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

use Dyhli\BotTF\Exceptions;

class Offer extends Object {

    /**
     * Get the bot associated with the offer
     */
    public function getBot() {

        return $this->api->bots->get($this->object->botid);

    }

    /**
     * Is the offer pending?
     *
     * @return boolean
     */
    public function isPending() {

        return in_array($this->object->state->code, [ 2, 9 ]);

    }

    /**
     * Is the offer accepted?
     *
     * @return boolean
     */
    public function isAccepted() {

        return ($this->object->state->code == 3);

    }

    /**
     * Did the offer fail?
     *
     * @return boolean
     */
    public function hasFailed() {

        return !in_array($this->object->state->code, [ 3, 9, 11 ]);

    }

    /**
     * Was the offer sent by us?
     *
     * @return boolean
     */
    public function isSentByMe() {

        return $this->object->sentbyme;

    }

    /**
     * Check if escrow is applicable
     *
     * @return boolean
     */
    public function hasEscrow() {

        return $this->object->escrow;

    }

    /**
     * Is it a donation?
     *
     * @return boolean
     */
    public function isDonation() {

        return ($this->object->isdonation && count($this->object->itemsme) == 0);

    }

    /**
     * Accept the offer
     *
     * @return object
     */
    public function accept() {

        if(
            $this->object->state->code != 2 ||
            $this->object->sentbyme
        ) {
            throw new Exceptions\GeneralException('Offer #' . $this->object->id . ' cannot be accepted.');
        }

        return $this->api->offers->accept($this->object->id);

    }

    /**
     * Decline the offer
     *
     * @return object
     */
    public function decline() {

        if(
            $this->object->state->code != 2 ||
            $this->object->sentbyme
        ) {
            throw new Exceptions\GeneralException('Offer #' . $this->object->id . ' cannot be declined.');
        }

        return $this->api->offers->decline($this->object->id);

    }

    /**
     * Cancel the offer
     *
     * @return object
     */
    public function cancel() {

        if(
            !in_array($this->object->state->code, [ 2, 9 ]) ||
            !$this->object->sentbyme
        ) {
            throw new Exceptions\GeneralException('Offer #' . $this->object->id . ' cannot be cancelled.');
        }

        return $this->api->offers->cancel($this->object->id);

    }

    /**
     * Add the associated user as friend
     *
     * @return boolean
     */
    public function addFriend() {

        return $this->api->bots->addFriend($this->object->botid, $this->object->steamid);

    }

    /**
     * Add the associated user to your banlist
     *
     * @param string
     * @return boolean
     */
    public function ban($reason = '') {

        return $this->api->banlist->add(
            $this->object->steamid,
            $reason
        );

    }

    /**
     * Remove the associated user from your banlist
     *
     * @return boolean
     */
    public function unban() {

        return $this->api->banlist->remove($this->object->steamid);

    }

}
?>
