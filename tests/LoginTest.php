<?php


namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class LoginTest
 * @package App\Tests
 */
class LoginTest extends WebTestCase
{
    public function testSuccessfulLogin(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router  = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate("app_login"));
        $form    = $crawler
            ->filter("form[name=login]")
            ->form([
                       "email"    => 'admin@localhost.me',
                       "password" => 'azerty',
                   ]);

        $client->submit($form);
        // Response must be found
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        // Response must not redirect to login page (auth failed in this case)
        $this->assertNotContains($router->generate("app_login"), $client->getResponse()->headers->get('location'));
    }

    public function testInvalidCredentials(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router  = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate("app_login"));
        $form    = $crawler
            ->filter("form[name=login]")
            ->form([
                       "email"    => 'toto@localhost.me',
                       "password" => 'toto',
                   ]);

        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains("div.alert-danger", "Le nom d'utilisateur n'a pas pu être trouvé.");
    }

    public function testBadCredentials(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router  = $client->getContainer()->get("router");
        $crawler = $client->request(Request::METHOD_GET, $router->generate("app_login"));
        $form    = $crawler
            ->filter("form[name=login]")
            ->form([
                       "email"    => 'admin@localhost.me',
                       "password" => 'toto',
                   ]);

        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains("div.alert-danger", "Identifiants invalides.");
    }
}
