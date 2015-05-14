<?php
/**
 * Local variables
 * @var \Phalcon\Mvc\Micro $app
 */

/**
 * Add your routes here
 */
$app->get('/', function () use ($app) {
    echo $app['view']->render('index');
});

$app->get('/api/autos', function() use ($app) {
    $phql = "SELECT * FROM Autos ORDER BY brand";
    $autos = $app->modelsManager->executeQuery($phql);
    $data = array();
    foreach( $autos as $auto){
        $data[] = array(
            'id' => $auto->getId(),
            'img' => $auto->getImg(),
            'brand' => $auto->getBrand(),
            'model' => $auto->getModel()
        );
    }
    echo json_encode($data);
});
// Получение робота по ключу
$app->get('/api/autos/{string}', function($string) use ($app){
    $id = preg_replace("/[^0-9]/","",$string);
    $format = substr($string, strpos($string, ".") + 1);
    $objFormat = new Formats($format);
    if ('' == $id) {
        $phql = "SELECT * FROM Autos ORDER BY brand";
        $autos = $app->modelsManager->executeQuery($phql);
        //$data = array();
    /*foreach( $autos as $auto){
        $data[] = array(
            'id' => $auto->getId(),
            'img' => $auto->getImg(),
            'brand' => $auto->getBrand(),
            'model' => $auto->getModel()
        );*/
        $data = $objFormat->transfer ($autos);
    }
    echo json_encode($data);
    } else {
        $phql = "SELECT * FROM Autos WHERE id = :id:";
    $auto = $app->modelsManager->executeQuery($phql, array(
        'id' => $id
    ))->getFirst();
    //Create a response
    $response = new Phalcon\Http\Response();
    if ($auto == false) {
        $response->setJsonContent(array());
    } else {
        $response->setJsonContent(array(
            'id' => $auto->getId(),
            'img' => $auto->getImg(),
            'brand' => $auto->getBrand(),
            'model' => $auto->getModel(),
            'year' => $auto->getModel(),
            'capacity' => $auto->getModel(),
            'color' => $auto->getModel(),
            'max_speed' => $auto->getModel(),
            'price' => $auto->getModel()
        ));
    }

    return $response;
    //return $response;
    }
    
});
/**
 * Not found handler
 */
$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo $app['view']->render('404');
});
//phalcon create-model --name=autos --output=models --get-set --namespace=\Phalcon\Mvc\Model --extends=\Phalcon\Mvc\Model --doc;
