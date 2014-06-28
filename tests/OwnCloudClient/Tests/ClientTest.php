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

namespace OwnCloudClient\Tests;

class ClientTest extends \PHPUnit_Framework_TestCase {

    private $config = './tests/config.json';

    /**
     * @var \muka\OwnCloud\Client
     */
    private $client;

    /**
     * @before
     */
    public function setUp()
    {
        if(!$this->client) {
            $config = json_decode(file_get_contents($this->config));
            $this->client = new \muka\OwnCloud\Client($config->url, $config->username, $config->password, 2);
        }
    }

    public function testListResources() {
        $list = $this->client->listResources("/");
        $this->assertTrue(is_array($list));
    }

    public function testDownload() {
        if($list = $this->client->listResources("/")) {

            if(!$list) {
                throw new \Exception;
            }

            foreach ($list as $resource) {
                if($resource->type == 'file') {
                    $content = $this->client->download($resource->path);
                    $this->assertNotNull($content);
                }
            }
        }
    }

}