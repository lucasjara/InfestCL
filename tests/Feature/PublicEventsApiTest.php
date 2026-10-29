<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicEventsApiTest extends TestCase
{
    public function test_public_events_endpoint_returns_only_public_event_information(): void
    {
        config(['api.rate_limit' => 5, 'api.cache_max_age' => 60]);

        $response = $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
            ->getJson('/api/events');

        $response->assertOk()
            ->assertJsonCount(2)
            ->assertJsonPath('0.id', '01')
            ->assertJsonPath('1.id', '02')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('Referrer-Policy', 'no-referrer');

        $this->assertStringContainsString('public', $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('max-age=60', $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('s-maxage=60', $response->headers->get('Cache-Control'));
    }

    public function test_public_events_endpoint_rejects_overload_with_too_many_requests(): void
    {
        config(['api.rate_limit' => 2, 'api.cache_max_age' => 60]);

        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.2'])->getJson('/api/events')->assertOk();
        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.2'])->getJson('/api/events')->assertOk();

        $response = $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.2'])
            ->getJson('/api/events');

        $response->assertTooManyRequests()
            ->assertJson(['message' => 'Demasiadas solicitudes. Intenta nuevamente más tarde.'])
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'DENY');
    }

    public function test_public_events_endpoint_rejects_methods_other_than_get(): void
    {
        $this->postJson('/api/events')->assertMethodNotAllowed();
    }
}
