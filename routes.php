<?php

$app->get('/PAGE/{ID}', function ($request, $response, $args) {

    $obj = (object) [
        ' ' => 'some string',
        'dataArray' => [ 1, 2, 3 ]
    ];


    return $this->view->render($response, 'page.twig', [
        'ID' => $args['ID'],
        'DATA' => $obj
    ]);
});

?>
