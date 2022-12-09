<?php

// include('simple_html_dom.php');

// $html = file_get_html('https://highload.today/uk/');

// $html->find('title', 0)->plaintext;

// $articleList =$html->find('div[class="lenta-item"]', 0);

// foreach ($list->find('a') as $article) {
//     echo $article->plaintext;
//     echo "<br>";
// };

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://www.sciencenews.org/all-stories");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$html = curl_exec($ch);

$dom = new DOMDocument();
@ $dom->loadHTML($html);

$article_list = $dom->getElementById('sidebar-center');

$xpath = new DOMXPath($dom);
$articles = $xpath->query("//div[@id='sidebar-center']/div");

echo $articles;

$article = array();

foreach ($articles as $article) {
    echo $article;
}