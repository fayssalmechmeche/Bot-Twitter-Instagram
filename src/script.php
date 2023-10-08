<?php
require 'vendor/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;
use Phpfastcache\Helper\Psr16Adapter;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$mysql = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_SERVER']);
$instagram =  \InstagramScraper\Instagram::withCredentials(new \GuzzleHttp\Client(), $_ENV['INSTAGRAM_LOGIN'], $_ENV['INSTAGRAM_PASSWORD'], new Psr16Adapter('Files'));
$instagram->login();
$instagram->saveSession();

$media = $instagram->getStories([$_ENV['INSTAGRAM_USER_ID']]);
$twitter = new TwitterOAuth($_ENV['TWITTER_CONSUMER_KEY'], $_ENV['TWITTER_CONSUMER_SECRET'], $_ENV['TWITTER_ACCESS_TOKEN'], $_ENV['TWITTER_ACCESS_TOKEN_SECRET']);
$twitter->setApiVersion("1.1");

echo ("Script lancé ! \n");


function checkPostSana($twitter, $mysql, $instagram)
{
    $medias = $instagram->getMedias($_ENV['INSTAGRAM_USER_USERNAME'], 20);
    foreach ($medias as $media) {

        switch ($media->getType()) {
            case 'image':
                $filename = 'src/media/' . 'post_' . $media->getId() . '.jpg';

                if (isFileExist($filename)) {
                    break;
                }
                $url = $media->getImageHighResolutionUrl();
                $stockage = file_get_contents($url);
                file_put_contents('src/media/' . $media->getId() . '.jpg', $stockage);

                if ($media->getCaption() != null) {
                    $caption = mysqli_real_escape_string($mysql, $media->getCaption());
                    $mysql->query("INSERT INTO posts (content,image,type) VALUES ('$caption','$filename','post')");
                }
                $picture = $twitter->upload('media/upload', ['media' => 'src/media/' . $media->getId() . '.jpg']);
                $twitter->setApiVersion("2");
                $date = new DateTime();
                $formattedDate = $date->format('d/m/Y');
                $text = "🐹📸 " . $formattedDate . " : " .  $media->getCaption() . "\n" . $media->getLink() . "\n \n" . "#TWICE #트와이스 #SANA";
                $parameters = [
                    'text' =>   $text,
                    'media' => [
                        'media_ids' => [$picture->media_id_string],
                    ]
                ];
                $twitter->post("tweets", $parameters);

                break;
            case 'video':
                $filename = 'src/media/' . 'post_' . $media->getId() . '.mp4';
                if (isFileExist($filename)) {
                    break;
                }
                $url = $media->getVideoStandardResolutionUrl();
                $stockage = file_get_contents($url);
                file_put_contents('src/media/' . $media->getId() . '.mp4', $stockage);

                if ($media->getCaption() != null) {
                    $caption = mysqli_real_escape_string($mysql, $media->getCaption());
                    $mysql->query("INSERT INTO posts (content,image,type) VALUES ('$caption','$filename','post')");
                }

                $twitter->setApiVersion("2");
                $date = new DateTime();
                $formattedDate = $date->format('d/m/Y');
                $text = "🐹📸 " . $formattedDate . " : " .  $media->getCaption() . "\n" . $media->getLink() . "\n \n" . "#TWICE #트와이스 #SANA";
                $parameters = [
                    'text' =>   $text,
                ];
                $twitter->post("tweets", $parameters);
                break;
            case 'carousel':
                $filename = 'src/media/' . 'post_' . $media->getId() . '.jpg';
                if (isFileExist($filename)) {
                    break;
                }
                $url = $media->getCarouselMedia()[0]->getImageHighResolutionUrl();
                $stockage = file_get_contents($url);
                file_put_contents('src/media/' . $media->getId() . '.jpg', $stockage);
                if ($media->getCaption() != null) {
                    $caption = mysqli_real_escape_string($mysql, $media->getCaption());
                    $mysql->query("INSERT INTO posts (content,image,type) VALUES ('$caption','$filename','post')");
                }
                $picture = $twitter->upload('media/upload', ['media' => 'src/media/' . $media->getId() . '.jpg']);
                $twitter->setApiVersion("2");
                $date = new DateTime();
                $formattedDate = $date->format('d/m/Y');
                $text = "🐹📸 " . $formattedDate . " : " .  $media->getCaption() . "\n" . $media->getLink() . "\n \n" . "#TWICE #트와이스 #SANA";
                $parameters = [
                    'text' =>   $text,
                    'media' => [
                        'media_ids' => [$picture->media_id_string],
                    ]
                ];
                $twitter->post("tweets", $parameters);
                break;
            case 'sidecar':
                $filename = 'src/media/' . 'post_' . $media->getId() . '.jpg';
                if (isFileExist($filename)) {
                    break;
                }
                $url = $media->getSidecarMedias()[0]->getImageHighResolutionUrl();
                $stockage = file_get_contents($url);
                file_put_contents('src/media/' . $media->getId() . '.jpg', $stockage);
                if ($media->getCaption() != null) {
                    $caption = mysqli_real_escape_string($mysql, $media->getCaption());
                    $mysql->query("INSERT INTO posts (content,image,type) VALUES ('$caption','$filename','post')");
                }
                $picture = $twitter->upload('media/upload', ['media' => 'src/media/' . $media->getId() . '.jpg']);
                $twitter->setApiVersion("2");
                $date = new DateTime();
                $formattedDate = $date->format('d/m/Y');
                $text = "🐹📸 " . $formattedDate . " : " .  $media->getCaption() . "\n" . $media->getLink() . "\n \n" . "#TWICE #트와이스 #SANA";
                $parameters = [
                    'text' =>   $text,
                    'media' => [
                        'media_ids' => [$picture->media_id_string],
                    ]
                ];
                $twitter->post("tweets", $parameters);
                break;
            default:
                $filename = 'src/media/' . 'post_' . $media->getId() . '.jpg';
                if (isFileExist($filename)) {
                    break;
                }
                $url = $media->getImageHighResolutionUrl();
                $stockage = file_get_contents($url);
                file_put_contents('src/media/' . $media->getId() . '.jpg', $stockage);
                if ($media->getCaption() != null) {
                    $caption = mysqli_real_escape_string($mysql, $media->getCaption());
                    $mysql->query("INSERT INTO posts (content,image,type) VALUES ('$caption','$filename','post')");
                }
                $picture = $twitter->upload('media/upload', ['media' => 'src/media/' . $media->getId() . '.jpg']);
                $twitter->setApiVersion("2");
                $date = new DateTime();
                $formattedDate = $date->format('d/m/Y');
                $text = "🐹📸 " . $formattedDate . " : " .  $media->getCaption() . "\n" . $media->getLink() . "\n \n" . "#TWICE #트와이스 #SANA";
                $parameters = [
                    'text' =>   $text,
                    'media' => [
                        'media_ids' => [$picture->media_id_string],
                    ]
                ];
                $twitter->post("tweets", $parameters);
                break;
        }
    }

    echo "Mise à jour des posts de Sana terminée ! \n";
    sleep(900);

    $storys = $instagram->getStories([51776454066]);

    foreach ($storys as $index => $mediaUrl) {
        $filename = 'src/story/' . uniqid('story_') . '.jpg';

        if (isFileExist($filename)) {
            continue;
        }

        $fileContents = file_get_contents($mediaUrl);
        file_put_contents($filename, $fileContents);

        $mysql->query("INSERT INTO posts (image,type) VALUES ('$filename','story')");

        $picture = $twitter->upload('media/upload', [$filename]);
        $twitter->setApiVersion("2");
        $date = new DateTime();
        $formattedDate = $date->format('d/m/Y');
        $text = "🐹⏳ " . $formattedDate . "\n" . "Nouvelle Story ! " . "\n" . " #TWICE #트와이스 #SANA";
        $parameters = [
            'text' =>  $text,
            'media' => [
                'media_ids' => [$picture->media_id_string],
            ]
        ];
        $twitter->post("tweets", $parameters);
    }

    echo "Mise à jour des storys de Sana terminée ! \n";

    sleep(900);
    checkPostSana($twitter, $mysql, $instagram);
}

function isFileExist($filename)
{
    if (file_exists($filename)) {
        echo "Le fichier existe déjà !";
        return true;
    } else {
        return false;
    }
}
checkPostSana($twitter, $mysql, $instagram);
