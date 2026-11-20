<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Halaman root diarahkan ke panel simulasi kalkulator IP.
     */
    public function test_the_application_redirects_to_nilai_index(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/uji-nilai');
    }
}
