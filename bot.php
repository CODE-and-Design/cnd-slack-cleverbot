<?php
require 'chatter-bot-api/php/chatterbotapi.php';

// Init the cleverbot
$factory = new ChatterBotFactory();

$bot1 = $factory->create(ChatterBotType::CLEVERBOT);
$bot1session = $bot1->createSession('cnd-session.txt');

$question = isset($_POST['text']) ? $_POST['text'] : "Test connection";
$answer = $bot1session->think($question);

// Define some faces
$faces = array(
    0 => ':bowtie:', 
    1 => ':glitch_crab:', 
    2 => ':japanese_goblin:', 
    3 => ':smiling_imp:', 
    4 => ':angel::cop:', 
    5 => ':sleuth_or_spy:', 
    6 => ':ghost::robot_face:', 
    7 => ':construction_worker:',
    8 => ':alien:', 
    9 => ':panda_face:', 
    10 => ':neckbeard:', 
    11 => ':octocat:', 
    12 => ':rage1:');
//$face_index = rand(0, count($faces));
$face_index = (int)date('g');
$face = $faces[$face_index-1];

// Return the answer to Slack
$curlUrl = 'SLACK-URL';
$curlData = array(
  'payload' => json_encode(
    array(
      'username' => 'BO',
      'channel' => '#' . $_POST['channel_name'],
      //'pretext' => $question,
      'icon_emoji' => $face,
      'text' => $answer,
    )
  )
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $curlUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $curlData);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$curlResponseData = json_decode(curl_exec($ch));
$curlResponseCode = curl_getinfo($ch);
curl_close($ch);

