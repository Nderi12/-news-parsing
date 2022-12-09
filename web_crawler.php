<?php

$urls=[
    'https://www.sciencenews.org/all-stories',
];

$data=[];
$links=[];
$newLinks=[];
foreach ($urls as $url) {
    $data[$url]=fopen($url, 'r');
    $data[$url]=stream_get_contents($data[$url]);
    $article=[];

    preg_match_all('/<h1 (.*?)>(.*?)<\/h1>/s', $data[$url], $matches);
    $articles = $matches[2];

    preg_match_all('/<h2 (.*?)>(.*?)<\/h2>/s', $data[$url], $matches);
    $articles = array_merge($matches[2],$articles);

    preg_match_all('/<h3 (.*?)>(.*?)<\/h3>/s', $data[$url], $matches);
    $articles = array_merge($matches[2],$articles);

    preg_match_all('/<h4 (.*?)>(.*?)<\/h4>/s', $data[$url], $matches);
    $articles = array_merge($matches[2],$articles);

    // Print the contents of the <article> tags
    foreach ($articles as $article) {
        preg_match_all('/<a\s+href="(https?:\/\/\S+)"/', $data[$url], $matches);
        $links = $matches[1];
        foreach ($links as $link) {
            // check if linkn has string asrticle in it
            if (strpos($link, 'article') !== false) {
                preg_match_all('/https?:\/\/\S+/', $link, $matches);
                $newLinks[]= $matches[0];
            }
        }
        $links=$newLinks;
    }

    // get today's date
    $date_added = date('Ymd');

    // create the directory path
    $path = 'scrap/'.$date_added.'/';

    // check if the directory path exists
    if (!file_exists($path)) {
        // if it does not exist create it
        mkdir($path, 0777, true);
    }

    foreach ($links as $link) {
        foreach ($link as $l) {
            $l=ltrim($l,'"');
            $l=rtrim($l,'"');
            echo $l.PHP_EOL;
            $random_file_name=uniqid();

            // save the content of each link to a specific file using file_get_contents
            $content=file_get_contents($l);
            $file=fopen('scrap/'.$date_added.'/'.$random_file_name.'.html', 'w');
            fwrite($file, $content);
            fclose($file);
        }
    }
}

