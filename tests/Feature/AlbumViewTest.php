<?php

namespace Tests\Feature;

use Tests\TestCase;

class AlbumViewTest extends TestCase
{
    public function test_album_index_view_is_rendered(): void
    {
        $response = $this->get(route('album.index'));

        $response->assertOk();
        $response->assertSee('Mis colecciones');
        $response->assertSee('Nueva colección');
        $response->assertSee('data-album-page="index"', false);
    }

    public function test_album_detail_view_is_rendered(): void
    {
        $collectionId = 'collection-123';

        $response = $this->get(route('album.show', ['id' => $collectionId]));

        $response->assertOk();
        $response->assertSee('Detalle de colección');
        $response->assertSee('data-collection-id="'.$collectionId.'"', false);
        $response->assertSee('Contenedor para las cartas de la colección.');
    }
}
