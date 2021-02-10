<?php
declare(strict_types=1);

namespace App\Api\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model;
use ArrayObject;

final class JwtDecorator implements OpenApiFactoryInterface
{
    private OpenApiFactoryInterface $decorated;

    public function __construct(
        OpenApiFactoryInterface $decorated
    )
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $schemas = $openApi->getComponents()->getSchemas();

        $schemas['Token']       = new ArrayObject(
            [
                'type'       => 'object',
                'properties' => [
                    'token' => [
                        'type'     => 'string',
                        'readOnly' => true,
                    ],
                ],
            ]
        );
        $schemas['Credentials'] = new ArrayObject(
            [
                'type'       => 'object',
                'properties' => [
                    'email'    => [
                        'type'    => 'string',
                        'example' => 'johndoe@example.com',
                    ],
                    'password' => [
                        'type'    => 'string',
                        'example' => 'azerty',
                    ],
                ],
            ]
        );

        $pathItem = new Model\PathItem(
            'JWT Token',
            null,
            null,
            null,
            null,
            new Model\Operation(
                'postCredentialsItem',
                [],
                [
                    '200' => [
                        'description' => 'Get JWT token',
                        'content'     => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token',
                                ],
                            ],
                        ],
                    ],
                ],
                'Get JWT token to login.',
                '',
                null,
                [],
                new Model\RequestBody(
                    'Generate new JWT Token',
                    new ArrayObject(
                        [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Credentials',
                                ],
                            ],
                        ],
                    ),
                ),
            ),
        );
        $openApi->getPaths()->addPath('/api/authentication_token', $pathItem);

        return $openApi;
    }
}
