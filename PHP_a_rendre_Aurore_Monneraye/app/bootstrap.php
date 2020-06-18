<?php


namespace App;

use app\src\App;
use app\Routing;
use app\src\Response\Response;
use app\src\servicecontainer\ServiceContainer;
use database\DataBase;
use model\finder\ProfileFinder;
use model\finder\MessageFinder;

$container = new ServiceContainer();
$app = new App($container);

$app->setService('database', new DataBase(
    "127.0.0.1",
    "thew",
    "root",
    "",
    "3306"));

$app->setService('profileFinder', new ProfileFinder($app));
$app->setService('messageFinder', new MessageFinder($app));

$app->setService('render', function (String $template, Array $params = []) {
    ob_start();
    include __DIR__ . '/../view/' . $template . '.php';
    $content = ob_get_contents();
    ob_end_clean();

    if($template === '404') {
        header("HTTP/1.0 404 Not Found");
        $reponse = new Response($content, 404, ['HTTP/1.0 404 Not Found']);
        return $reponse;
    }

    return $content;
});

$routing = new Routing($app);
$routing->setup();

return $app;