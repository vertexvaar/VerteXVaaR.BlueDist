<?php
use VerteXVaaR\BlueDist\Controller\Welcome;
use VerteXVaaR\BlueSprints\Http\Server\Middleware\RoutingMiddleware;

return [
    // safe methods
    RoutingMiddleware::HTTP_METHOD_GET => [
        '/applyLeaves' => [
            'controller' => Welcome::class,
            'action' => 'applyLeaves',
        ],
        '/newTree' => [
            'controller' => Welcome::class,
            'action' => 'newTree',
        ],
        '/updateFruit' => [
            'controller' => Welcome::class,
            'action' => 'updateFruit',
        ],
        '/editFruit' => [
            'controller' => Welcome::class,
            'action' => 'editFruit',
        ],
        '/listFruits' => [
            'controller' => Welcome::class,
            'action' => 'listFruits',
        ],
        '.*' => [
            'controller' => Welcome::class,
            'action' => 'index',
        ],
    ],
    RoutingMiddleware::HTTP_METHOD_HEAD => [],
    // not safe methods
    RoutingMiddleware::HTTP_METHOD_POST => [
        '/createDemoFruits' => [
            'controller' => Welcome::class,
            'action' => 'createDemoFruits',
        ],
        '/deleteFruit' => [
            'controller' => Welcome::class,
            'action' => 'deleteFruit',
        ],
        '/addLeaf' => [
            'controller' => Welcome::class,
            'action' => 'addLeaf',
        ],
        '/growBranches' => [
            'controller' => Welcome::class,
            'action' => 'growBranches',
        ],
        '/createTree' => [
            'controller' => Welcome::class,
            'action' => 'createTree',
        ],
        '/updateFruit' => [
            'controller' => Welcome::class,
            'action' => 'updateFruit',
        ],
        '/createFruit' => [
            'controller' => Welcome::class,
            'action' => 'createFruit',
        ],
    ],
    RoutingMiddleware::HTTP_METHOD_PUT => [],
    RoutingMiddleware::HTTP_METHOD_DELETE => [],
];
