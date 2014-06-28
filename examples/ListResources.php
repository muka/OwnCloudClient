<?php

include './vendor/autoload.php';

class ListResources {

    private $done = [];

    public function __construct($config) {

        extract((array)$config);
        $this->client = new \muka\OwnCloud\Client($url, $username, $password);

    }

    public function showlist($path = '/', $depth = 0, $parent = null) {
        try {
            $list = $this->client->listResources($path);

            foreach($list as $item) {

                // avoid loop
                if(in_array($item->path, $this->done)) continue;
                $this->done[] = $item->path;

                $label = str_replace($parent, "/", $item->path);
                printf("%s %s\n", str_pad(" ", $depth*2), $label);

                if($item->type == 'dir') {
                    $this->showlist($item->path, $depth+1, $path);
                }
            }
        }
        catch(\Exception $e) {
            print "An error occured!\n".$e->getMessage();
        }
    }
}

if(isset($argv[1])) {

    $config = json_decode(file_get_contents("./tests/config.json"));
    $s = new ListResources($config);

    $s->showlist($argv[1]);
}
else {
    print "Usage:\n\tListResources.php /path/to/list\nExample:\n\tListResources.php /\n";
}