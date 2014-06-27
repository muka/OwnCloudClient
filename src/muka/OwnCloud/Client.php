<?php

/*
 * The MIT License
 *
 * Copyright 2014 Luca Capra <luca.capra@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace muka\OwnCloud;

class Client {

    private $client;
    private $allowInsecureCert = false;
    private $baseUrl;

    public function __construct($url, $user, $password, $allowInsecureCert = false) {

        // enforce https
        if(substr($url, 0, 5) != "https") {
            throw new Exception\ClientException;
        }

        $this->allowInsecureCert = $allowInsecureCert;
        $this->baseUrl = "https://$user:$password@". substr($url, 8);

        $this->client = new \PestJSON($this->baseUrl);
        $this->setClientOptions($this->client);
    }


    protected function setClientOptions(\Pest $client) {

        $client->curl_opts[CURLOPT_SSL_VERIFYPEER] = $this->allowInsecureCert;
        // good for dev, but HOST should be kept
        $client->curl_opts[CURLOPT_SSL_VERIFYHOST] = $this->allowInsecureCert;

        // Not supported on hosts running safe_mode!
        $client->curl_opts[CURLOPT_FOLLOWLOCATION] = true;

    }

    protected function request() {

        $args = func_get_args();
        $result = call_user_func_array([$this->client, array_shift($args)], $args);

        if($result && isset($result['status'])) {
            if($result['status'] == 'error') {
                throw new Exception\ClientException($result['data']['message']);
            }
        }

        return $result;
    }

    public function listResources($dir = "/", $html = false) {
        $url = $html ? "apps/files/ajax/list.php" : "apps/files/ajax/rawlist.php";
        return $this->request("get", $url, compact("dir"));
    }

    public function download($files) {

        // use base Pest class instead of JSON extension,
        // otherwise will return null response
        $client = new \Pest($this->baseUrl);
        $this->setClientOptions($client);

        $url = "apps/files/ajax/download.php";

        if(!is_array($files)) {
            $files = [$files];
        }

        $files = json_encode($files);
//        die($files);
        return $client->get($url, compact("files"));
    }

}
