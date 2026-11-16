<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Un visitante sin autenticar es redirigido al inicio de sesión.
     */
    public function test_un_visitante_es_redirigido_al_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }
}
