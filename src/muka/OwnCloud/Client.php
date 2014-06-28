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
    private $settings;

    private $basePath = "/remote.php/webdav";

    public function __construct($url, $user, $password, $allowInsecureCert = false) {

        $this->settings = array(
            'baseUri' => substr($url, -1) == '/' ? substr($url, 0, -1) : $url,
            'userName' => $user,
            'password' => $password,
        //    'proxy' => 'locahost:8888',
        );

        $this->client = new \Sabre\DAV\Client($this->settings);

        $this->client->addCurlSetting(CURLOPT_SSL_VERIFYPEER, !$allowInsecureCert);
        $this->client->addCurlSetting(CURLOPT_SSL_VERIFYHOST, !$allowInsecureCert);
    }

    protected function request() {

        $args = func_get_args();

        try {
            $result = call_user_func_array([$this->client, "request"], $args);
        } catch(\Exception $e) {
            var_dump($e);
        }

        return $result;
    }

    public function listResources($dir = "/", $options = null, $depth = 1) {

        $options = is_null($options) ? [
//            "{DAV:}supported-live-property-set",
//            "{DAV:}supported-method-set"
        ] : $options;

        $response = $this->client->propFind($this->basePath.$dir, $options, $depth);

        $list = [];
        foreach($response as $path => $item) {

            $prop = new \stdClass();

//            $prop->realPath = $path;
//            $prop->__srcItem = $item;

            $prop->raw = $item;
            $prop->path = str_replace($this->basePath, "", $path);
            $prop->type = "dir";

            foreach($item as $key => $val) {
                $subkey = explode('}', $key);
                switch($subkey[1]) {
                    case "id":
                        $prop->id = $val;
                        break;
                    case "getetag":
                        $prop->eTag = $val;
                        break;
                    case "getcontenttype":
                        $prop->contentType = $val;
                        $prop->type = "file";
                        break;
                    case "getlastmodified":
                        $prop->lastModified = strtotime($val);
                        break;
                }
            }

            $list[$path] = $prop;
        }

        return $list;
    }

    public function download($file) {
        return $this->client->request('GET', $file);
    }



}
