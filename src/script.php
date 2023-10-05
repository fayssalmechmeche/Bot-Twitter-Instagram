<?php
require 'vendor/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;
use Phpfastcache\Helper\Psr16Adapter;



$mysql = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_SERVER']);



// $consumerKey = $_env['TWITTER_CONSUMER_KEY'];
// $consumerSecret = $_env['TWITTER_CONSUMER_SECRET'];
// $accessToken = $_env['TWITTER_ACCESS_TOKEN'];
// $accessTokenSecret = $_env['TWITTER_ACCESS_TOKEN_SECRET'];

// $connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
$instagram = \InstagramScraper\Instagram::withCredentials(new \GuzzleHttp\Client(), $_ENV['INSTAGRAM_LOGIN'], $_ENV['INSTAGRAM_PASSWORD'], new Psr16Adapter('Files'));
$instagram->login();
$instagram->saveSession();
$twitter = new TwitterOAuth($_ENV['TWITTER_CONSUMER_KEY'], $_ENV['TWITTER_CONSUMER_SECRET'], $_ENV['TWITTER_ACCESS_TOKEN'], $_ENV['TWITTER_ACCESS_TOKEN_SECRET']);
$twitter->setApiVersion("1.1");

$medias = $instagram->getMedias($_ENV['INSTAGRAM_USERNAME'], 20);
function checkPostSana($medias, $twitter, $mysql)
{
    foreach ($medias as $media) {

        switch ($media->getType()) {
            case 'image':
                $filename = 'src/media/' . $media->getId() . '.jpg';

                if (isFileExist($filename)) {
                    break;
                }
                $url = $media->getImageHighResolutionUrl();
                $stockage = file_get_contents($url);
                file_put_contents('src/media/' . $media->getId() . '.jpg', $stockage);
                // insérer le post dans la base de données
                if ($media->getCaption() != null) {
                    $caption = mysqli_real_escape_string($mysql, $media->getCaption());
                    $mysql->query("INSERT INTO posts (content,image) VALUES ('$caption','$filename')");
                }
                $picture = $twitter->upload('media/upload', ['media' => 'src/media/' . $media->getId() . '.jpg']);
                $twitter->setApiVersion("2");
                $date = new DateTime();
                $formattedDate = $date->format('d/m/Y');
                $parameters = [
                    'text' =>  '🐹📸 ' . $formattedDate . ' ' . 'Nouvelle Story : ' .  $media->getCaption() . ' ' . $media->getLink() . '      #TWICE #트와이스 #SANA',
                    'media' => [
                        'media_ids' => [$picture->media_id_string],
                    ]
                ];
                $twitter->post("tweets", $parameters);

                break;
            case 'video':
                $filename = 'src/media/' . $media->getId() . '.mp4';
                if (isFileExist($filename)) {
                    break;
                }
                $url = $media->getVideoStandardResolutionUrl();
                $stockage = file_get_contents($url);
                file_put_contents('src/media/' . $media->getId() . '.mp4', $stockage);
                // insérer le post dans la base de données
                if ($media->getCaption() != null) {
                    $caption = mysqli_real_escape_string($mysql, $media->getCaption());
                    $mysql->query("INSERT INTO posts (content,image) VALUES ('$caption','$filename')");
                }

                $twitter->setApiVersion("2");
                $date = new DateTime();
                $formattedDate = $date->format('d/m/Y');
                $parameters = [
                    'text' =>  '🐹📸 ' . $formattedDate . ' ' . 'Nouvelle Story : ' .  $media->getCaption() . ' ' . $media->getLink() . '      #TWICE #트와이스 #SANA',
                ];
                $twitter->post("tweets", $parameters);
                break;
            case 'carousel':
                $filename = 'src/media/' . $media->getId() . '.jpg';
                if (isFileExist($filename)) {
                    break;
                }
                $url = $media->getCarouselMedia()[0]->getImageHighResolutionUrl();
                $stockage = file_get_contents($url);
                file_put_contents('src/media/' . $media->getId() . '.jpg', $stockage);
                if ($media->getCaption() != null) {
                    $caption = mysqli_real_escape_string($mysql, $media->getCaption());
                    $mysql->query("INSERT INTO posts (content,image) VALUES ('$caption','$filename')");
                }
                $picture = $twitter->upload('media/upload', ['media' => 'src/media/' . $media->getId() . '.jpg']);
                $twitter->setApiVersion("2");
                $date = new DateTime();
                $formattedDate = $date->format('d/m/Y');
                $parameters = [
                    'text' =>  '🐹📸 ' . $formattedDate . ' ' . 'Nouvelle Story : ' .  $media->getCaption() . ' ' . $media->getLink() . '      #TWICE #트와이스 #SANA',
                    'media' => [
                        'media_ids' => [$picture->media_id_string],
                    ]
                ];
                $twitter->post("tweets", $parameters);
                break;
            case 'sidecar':
                $filename = 'src/media/' . $media->getId() . '.jpg';
                if (isFileExist($filename)) {
                    break;
                }
                $url = $media->getSidecarMedias()[0]->getImageHighResolutionUrl();
                $stockage = file_get_contents($url);
                file_put_contents('src/media/' . $media->getId() . '.jpg', $stockage);
                if ($media->getCaption() != null) {
                    $caption = mysqli_real_escape_string($mysql, $media->getCaption());
                    $mysql->query("INSERT INTO posts (content,image) VALUES ('$caption','$filename')");
                }
                $picture = $twitter->upload('media/upload', ['media' => 'src/media/' . $media->getId() . '.jpg']);
                $twitter->setApiVersion("2");
                $date = new DateTime();
                $formattedDate = $date->format('d/m/Y');
                $parameters = [
                    'text' =>  '🐹📸 ' . $formattedDate . ' ' . 'Nouvelle Story : ' .  $media->getCaption() . ' ' . $media->getLink() . '      #TWICE #트와이스 #SANA',
                    'media' => [
                        'media_ids' => [$picture->media_id_string],
                    ]
                ];
                $twitter->post("tweets", $parameters);
                break;
            default:
                $filename = 'src/media/' . $media->getId() . '.jpg';
                if (isFileExist($filename)) {
                    break;
                }
                $url = $media->getImageHighResolutionUrl();
                $stockage = file_get_contents($url);
                file_put_contents('src/media/' . $media->getId() . '.jpg', $stockage);
                if ($media->getCaption() != null) {
                    $caption = mysqli_real_escape_string($mysql, $media->getCaption());
                    $mysql->query("INSERT INTO posts (content,image) VALUES ('$caption','$filename')");
                }
                $picture = $twitter->upload('media/upload', ['media' => 'src/media/' . $media->getId() . '.jpg']);
                $twitter->setApiVersion("2");
                $date = new DateTime();
                $formattedDate = $date->format('d/m/Y');
                $parameters = [
                    'text' =>  '🐹📸 ' . $formattedDate . ' ' . 'Nouvelle Story : ' .  $media->getCaption() . ' ' . $media->getLink() . '      #TWICE #트와이스 #SANA',
                    'media' => [
                        'media_ids' => [$picture->media_id_string],
                    ]
                ];
                $twitter->post("tweets", $parameters);
                break;
        }
    }

    echo "Mise à jour des posts de Sana terminée !";
    sleep(1800);
    checkPostSana($medias, $twitter, $mysql);
}

function isFileExist($filename)
{
    if (file_exists($filename)) {
        return true;
    } else {
        return false;
    }
}
checkPostSana($medias, $twitter, $mysql);
