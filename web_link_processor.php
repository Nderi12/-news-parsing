<?php

// increase the memory limit
ini_set('memory_limit', '1024M');
$path= 'scrap/';
$date_added = date('Ymd');

// read all the files in the directory
$files = scandir($path.'/'.$date_added);

// remove the first two elements of the array
// because they are not file
array_shift($files);
array_shift($files);
$newsContents=[];

// read all the files in the directory and put them inside an array
foreach ($files as $file) {
    $newsContents[]=file_get_contents($path.'/'.$date_added.'/'.$file);

    // push the newsContents to rabbitmQ
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();
    $channel->queue_declare('news', false, false, false, false);
    $msg = new AMQPMessage($newsContents);
    $channel->basic_publish($msg, '', 'news');
    $channel->close();
}

// read the contents of rabbitMq and process it
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('news', false, false, false, false);
echo ' [*] Waiting for messages. To exit press CTRL+C', PHP_EOL;

$newsContents=null;
$parsed=[];
// get the contents from rabbitMq and save it to a variable
$callback = function ($msg) {
    echo " [x] Received ", $msg->body, "

";
    $newsContents = $msg->body;
};

$channel->basic_consume('news', '', false, true, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();


// loop through the files opening them and saving them in an array
if (!empty($newsContents)) {

    // get the title of the article
    preg_match('/<title>(.*?)<\/title>/s', $newsContents, $matches);
    $title = $matches[1];
    $newsContent=[];
    // look for all p element and get the content inside each
    preg_match_all('/<p>(.*?)<\/p>/s', $newsContents, $matches);
    $newsContent[$title]['content'] =$matches[1];
    $date_added='';
    // look for the date the article was added
    preg_match('/<time (.*?) datetime="(.*?)">/s', $newsContents, $matches);
    $date_added = $matches[2];
    $date_added = date('Y-m-d H:i:s', strtotime($date_added));
    $newsContent[$title]['date_added'] = $date_added;

    //look for h2 tag and get the content inside it
    preg_match('/<h2 (.*?)>(.*?)<\/h2>/s', $newsContents, $matches);
    $newsContent[$title]['description'] = $matches[2];

    $image='';
    // look for the image of the article in tag
    preg_match_all('/<img (.*?) src="(.*?)"/s', $newsContents, $matches);
    $image = $matches[2];
    $newsContent[$title]['image'] = $image[0];
    $parsed[]=$newsContent;
}

// loop through the array and save the content in the database
foreach ($parsed as $newsContent) {
    foreach ($newsContent as $key => $value) {
        $title=$key;
        $content=implode(' ', $value['content']);
        $short_description=$key;
        $picture=$value['image'];
        $date_added=$value['date_added'];
       $sql="INSERT INTO news (title, short_description, picture, added_on) VALUES ('$title', '$description', '$image', '$date_added')";
       $result=mysqli_query($conn, $sql);
    }
}