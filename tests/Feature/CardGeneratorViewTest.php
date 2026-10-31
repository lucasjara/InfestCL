<?php

namespace Tests\Feature;

use Tests\TestCase;

class CardGeneratorViewTest extends TestCase
{
    public function test_card_generator_view_includes_image_adjustment_controls(): void
    {
        $response = $this->get(route('cards.list'));

        $response->assertOk();
        $response->assertSee('Ajustes de imagen');
        $response->assertSee('id="image-zoom"', false);
        $response->assertSee('id="image-horizontal"', false);
        $response->assertSee('id="image-vertical"', false);
        $response->assertSee('Restablecer imagen');
        $response->assertSee('elements.previewImage.setPointerCapture(event.pointerId)', false);
        $response->assertSee('const cardImageBaseUrl =', false);
        $response->assertSee('images/cards/dragon-astral.webp', false);
        $response->assertSee('async function setCardImage', false);
        $response->assertSee('cardImageFallbackUrl', false);
    }
}
