#OwnCloud Client

A minimal client to retrieve data from an OwnCloud instance


##Installation

Add `muka/OwnCloudClient` as dependecy in your `composer.json`

##Setup

Initialize the Client class

```php
$ocClient = new \muka\OwnCloud\Client($url = "https://owncloud.example.com", $username = "myuser", $password = "mypassword", $allowInsecureCertificate = true);
```

##Usage

*List Resources*

```php
$list = $ocClient->listResources($baseDir = "/", $getAsHtml = false);
```

*Download a file*

Can be a single file or an array

```php
$ocClient->download([ "/my/path/to/data.txt", "/my//open/data.csv", ]);
```


##License

The MIT license

Copyright 2014 Luca Capra

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.