<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\RequestBody;
use ApiPlatform\Core\OpenApi\OpenApi;

class OpenApiFactory implements OpenApiFactoryInterface
{

    public function __construct(private OpenApiFactoryInterface $decorate)
    {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorate->__invoke($context);
        foreach ($openApi->getPaths()->getPaths() as $key => $path){
            if ($path->getGet() && $path->getGet()->getSummary() == 'hidden'){
                $openApi->getPaths()->addPath($key, $path->withGet(null));
            }
        }

        $schemas = $openApi->getComponents()->getSecuritySchemes();
        $schemas['cookieAuth'] = new \ArrayObject([
            'type' => 'apiKey',
            'in' => 'cookie',
            'name' => 'PHPSESSID'
        ]);
//        $openApi = $openApi->withSecurity([['cookieAuth' => []]]);

        $schemas = $openApi->getComponents()->getSchemas();
        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                    'example' => 'moe@gmail.com'
                ],
                'password' => [
                    'type' => 'string',
                    'example' => '0000'
                ]
            ]
        ]);


        $pathItem = new PathItem(
            post: new Operation(
                operationId: 'studentApiLogin',
                tags: ['Auth'],
                responses: [
                    '200' => [
                        'description' => 'Utilisateur connecte',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/User-read.User'

                                ]
                            ]
                        ]
                    ]
                ],
                requestBody: new RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials'
                            ]
                        ]
                    ])
                )
            )
        );

        $mePOperations = $openApi->getPaths()->getPath('/api/me')->getGet()->withParameters([]);
        $mePathItem = $openApi->getPaths()->getPath('/api/me')->withGet($mePOperations);
        $openApi->getPaths()->addPath('/api/me', $mePathItem);

        $openApi->getPaths()->addPath('/api/login', $pathItem);
        $pathItem = new PathItem(
            post: new Operation(
                operationId: 'studentApiLogout',
                tags: ['Auth'],
                responses: [
                    '204' => []
                ]
            )
        );
        $openApi->getPaths()->addPath('/api/logout', $pathItem);

        return $openApi;
    }
}