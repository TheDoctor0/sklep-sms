<?php

namespace Tests\Feature\Pages\Admin;

use Tests\Psr4\Concerns\AuthConcern;
use Tests\Psr4\TestCases\AdminTestCase;

class TariffsTest extends AdminTestCase
{
    use AuthConcern;

    /** @test */
    public function it_loads()
    {
        // given
        $user = $this->factory->user();
        $this->actingAs($user);

        // when
        $response = $this->get('/', ['pid' => 'tariffs']);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Panel Admina', $response->getContent());
        $this->assertContains('<div class="title">Taryfy', $response->getContent());
    }
}
