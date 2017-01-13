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

class Request {

    /**
     * @var string
     */
    private $apiEndpoint = 'https://api.bot.tf/v1/';

    /**
     * @var string
     */
    private $apiKey = '';

    /**
     * @var object
     */
    private $rate = null;

    /**
     * @var int
     */
    private $time = 0;

    /**
     * @param string
     */
    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    /**
     * @param string
     * @param array
     */
    public function get($resource, array $data = [ ]) {
        return $this->doRequest('GET', $resource, $data);
    }

    /**
     * @param string
     * @param array
     */
    public function post($resource, array $data = [ ]) {
        return $this->doRequest('POST', $resource, $data);
    }

    /**
     * @param string
     * @param array
     */
    public function delete($resource, array $data = [ ]) {
        return $this->doRequest('DELETE', $resource, $data);
    }

    /**
     * @param string
     * @param array
     */
    public function put($resource, array $data = [ ]) {
        return $this->doRequest('PUT', $resource, $data);
    }

    /**
     * @return object
     */
    public function getRate() {
        return $this->rate;
    }

    /**
     * @return int
     */
    public function getTime() {
        return $this->time;
    }

    /**
     * @param string
     * @param string
     * @param array
     */
    private function doRequest($method, $resource, array $data = [ ]) {
        $start = microtime(true);

        // Check cURL
        if(!function_exists('curl_init')) {
            throw new Exceptions\RequestException('cURL library is not installed.');
        }
        
        // cURL headers
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'User-Agent: Dyhli/bottf-api-php library',
            'Authorization: Token ' . $this->apiKey
        );
        
        // cURL request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiEndpoint . $resource);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        curl_close($ch);

        // time!
        $this->time = (int) floor((microtime(true) - $start) * 100);
        
        if($response === false) {
            throw new Exceptions\RequestException('cURL Error: ' . curl_error($ch));
        }
                
        // Return the JSON decoded object
        $response = json_decode($response);
        if($response === null || $response === false) {
            throw new Exceptions\RequestException('json_decode: Unable to decode string to object.');
        }

        if(!isset($response->errors) || !isset($response->data) || !is_array($response->errors)) {
            throw new Exceptions\RequestException('Received malformed data');
        }

        if(count($response->errors) > 0) {
            throw new Exceptions\RequestException('API error: ' . $response->errors[0]->code . ': ' . $response->errors[0]->msg);
        }

        if(isset($response->rate)) {
            $this->rate = $response->rate;
        }
        
        return $response->data;
    }

}
?>
