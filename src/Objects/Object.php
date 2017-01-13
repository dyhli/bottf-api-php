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

use Dyhli\BotTF;
use Dyhli\BotTF\Exceptions;

class Object {

    /**
     * @var Dyhli\BotTF\API
     */
    protected $api = null;

    /**
     * @var object
     */
    protected $object = null;

    /**
     * @param resource
     * @param object
     */
    public function __construct(BotTF\API $api, $object) {
        $this->api = $api;
        $this->object = $object;
    }

    /**
     * Get one property
     */
    public function __get($property) {
        if(isset($this->object->$property)) {
            return $this->object->$property;
        } else {
            throw new Exceptions\GeneralException('Property "' . $property . '" does not exist in ' . get_class($this));
        }
    }

    /**
     * Retrieve this object
     *
     * @return object
     */
    public function dump() {

        return $this->object;

    }

}
?>
