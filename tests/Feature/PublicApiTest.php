<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicApiTest extends TestCase
{
    public function test_events_endpoint_returns_success_and_security_headers(): void
    {
        $response = $this->getJson('/api/events');

        $response->assertOk()
            ->assertHeader('Content-Type', 'application/json')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('Referrer-Policy', 'no-referrer')
            ->assertHeader(
                'Cache-Control',
                'max-age=60, public, s-maxage=60'
            );
    }

    public function test_questions_endpoint_returns_success_and_security_headers(): void
    {
        $response = $this->getJson('/api/questions');

        $response->assertOk()
            ->assertHeader('Content-Type', 'application/json')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('Referrer-Policy', 'no-referrer')
            ->assertHeader(
                'Cache-Control',
                'max-age=60, public, s-maxage=60'
            );
    }
}