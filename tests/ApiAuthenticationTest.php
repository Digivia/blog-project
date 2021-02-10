<?php


namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User\User;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class ApiAuthenticationTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    public function testLogin(): void
    {
        $client = self::createClient();

        $user = new User();
        $user
            ->setEmail('test@example.com')
            ->setPassword(
                self::$container->get('security.password_encoder')->encodePassword($user, '$3CR3T')
            )
            ->setFirstname('test')
            ->setLastname('test')
            ->setRoles(['ROLE_API'])
            ;

        $manager = self::$container->get('doctrine')->getManager();
        $manager->persist($user);
        $manager->flush();

        // retrieve a token
        $response = $client->request('POST', '/api/authentication_token', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'test@example.com',
                'password' => '$3CR3T',
            ],
        ]);

        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);

        // test not authorized
        $client->request('GET', '/api/posts');
        $this->assertResponseStatusCodeSame(401);

        // test authorized
        $client->request('GET', '/api/posts', ['auth_bearer' => $json['token']]);
        $this->assertResponseIsSuccessful();
    }
}
