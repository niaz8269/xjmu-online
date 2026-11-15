<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** SEO safety: non-production copies are not indexable; production is. */
class NoIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_production_sends_noindex(): void
    {
        $this->get('/about')->assertOk()->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    public function test_production_has_no_noindex_header(): void
    {
        $this->app['env'] = 'production';
        $this->get('/about')->assertOk()->assertHeaderMissing('X-Robots-Tag');
    }
}
