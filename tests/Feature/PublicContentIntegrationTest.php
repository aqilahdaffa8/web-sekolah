<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicContentIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_content_endpoints_return_contracts_used_by_views(): void
    {
        $this->getJson('/api/public/home')
            ->assertOk()
            ->assertJsonStructure(['banners', 'stats', 'latest_news', 'upcoming_events', 'featured_products', 'extracurriculars']);

        $this->getJson('/api/public/hubin')
            ->assertOk()
            ->assertJsonStructure(['partners', 'vacancies']);

        $this->getJson('/api/public/tefa')
            ->assertOk()
            ->assertJsonStructure(['products']);

        $this->getJson('/api/public/agenda')
            ->assertOk();

        $this->getJson('/api/public/extracurriculars')
            ->assertOk();

        $this->getJson('/api/public/achievements')
            ->assertOk();
    }
}
