<?

function connDBManager()
{
    echo (test_redis_con()) ? "redis server run" : "redis server shutdown";
}


function connRedisServer()
{


    if(class_exists('Redis') && $GLOBALS['isRedisRun']){
    $redis = new Redis();
    $redis->connect($GLOBALS['configs']->{'rds_server'}, $GLOBALS['configs']->{'rds_port'});
    // "Connection to server sucessfully";
    return $redis;
    }
    else
        return false;



}

function setRedis($key, $value)
{


        $redis = connRedisServer();
        if($redis) {
            $res = $redis->set($key, $value);
            $redis->close();
            return $res;
        }
        else{
            return 'redis error';}


}

function getRedis($key)
{
    $redis = connRedisServer();
    if($redis) {
    $obj = (object)[
        $key => $redis->get($key),
    ];
    $redis->close();
    return json_encode($obj);
    }
    else{
    return 'redis error';}
}

function keys()
{
    $redis = connRedisServer();
    if($redis) {
    $arList = $redis->keys("*");
    $redis->close();
    $text = "";
    foreach ($arList as $value) {
        $text .= $value . "<br>";
    }
    return $text;}
    else{
        return 'redis error';}
}

function queryDB($queryString)
{
    $db = require 'db.php';

    $result = $db->query($queryString);
    $text = '';
    $arr = [];

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $obj = (object)[];

            foreach ($row as $key => $attribute) {
                $obj->$key = $attribute;
            }
            $arr[] = $obj;
        }

        $text = json_encode($arr);
    }

    $db->close();

    return $text;
}


function ALL()
{
    $redis = connRedisServer();

   if($redis) {$obj = (object)[];
    $arList = $redis->keys("*");

    foreach ($arList as $value) {
        $obj->$value = $redis->get($value);
    }
    $redis->close();
    return json_encode($obj);}
    else
        return 'redis error';
}


function getdata($id)
{
    $db = require 'db.php';
    $redis = connRedisServer();
    $obj = (object)[];
    $jsonObject = null;


    if ($GLOBALS['isRedisRun'] && class_exists('redis')) {

        if ($redis->get($id)) {
            $obj->{'redis'} = true;
            $obj->{'mysql'} = false;
            $newobj = (object)[
                'memberId' => $id,
                'name' => $redis->get($id),

            ];
            $arr[] = $newobj;
            $obj->{'user'} = $arr;


        } else {
            $obj->{'redis'} = false;
            $obj->{'mysql'} = true;
            $text = queryDB("SELECT * FROM member where idmember='$id' LIMIT 1;");
            $jsonObject = json_decode($text);
            if ($jsonObject[0] && $jsonObject[0]->name) {
                $redis->set($jsonObject[0]->idmember, $jsonObject[0]->name);
            }

            $obj->{'user'} = json_decode($text);

        }

    } else if ($db) {
        $obj->{'redis'} = false;
        $obj->{'mysql'} = true;
        $text = queryDB("SELECT * FROM member where idmember='$id' LIMIT 1;");
        $obj->{'user'} = json_decode($text);


    } else {
        $obj->{'redis'} = false;
        $obj->{'mysql'} = false;
    }


    return json_encode($obj);


}

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'test.php';
require 'vendor/autoload.php';

global $isRedisRun,$configs;

$isRedisRun = test_redis_con();
$configs = include('config.php');

$app = new \Slim\App([

    'settings' => ['displayErrorDetails' => false,]

]);
$container = $app->getContainer();
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig(__DIR__ . '/views', ['cache' => false,]);

    // Instantiate and add Slim specific extension

    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $c['request']->getUri()));

    return $view;
};

require 'routes.php';


$app->get('/GETUSER/{ID}', function (Request $request, Response $response) {

    $response->getBody()->write(getdata($request->getAttribute('ID')));

    return $response;
});


$app->get('/CHECK', function (Request $request, Response $response) {

    $response->getBody()->write(connDBManager());

    return $response;
});


$app->get('/DB', function (Request $request, Response $response) {

    $text = queryDB('SELECT * FROM member;');


    $response->getBody()->write($text);

    return $response;
});


$app->get('/SET/{KEY}/{VALUE}', function (Request $request, Response $response) {
    $key = $request->getAttribute('KEY');
    $value = $request->getAttribute('VALUE');
    $text = (setRedis($key, $value) > 0) ? "Set ok" : "error";
    $response->getBody()->write($text);

    return $response;
});

$app->get('/GET/{KEY}', function (Request $request, Response $response) {
    $key = $request->getAttribute('KEY');
    $text = getRedis($key);
    $response->getBody()->write($text);
    return $response;
});

$app->get('/KEYS', function (Request $request, Response $response) {

    $response->getBody()->write(keys());

    return $response;
});

$app->get('/ALL', function (Request $request, Response $response) {

    $response->getBody()->write(ALL());

    return $response;
});


$app->run();

?>