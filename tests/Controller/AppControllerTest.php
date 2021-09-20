<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.button-cell a');

        $client->clickLink('voir détails');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table.table');
    }
}