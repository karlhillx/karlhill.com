<?php

namespace Tests\Feature;

use App\Support\ProjectCatalog;
use Tests\TestCase;

class ProjectCatalogTest extends TestCase
{
    public function test_featured_projects_have_case_studies(): void
    {
        $featured = ProjectCatalog::all()->where('featured', true);

        $this->assertGreaterThan(0, $featured->count());
        foreach ($featured as $project) {
            $this->assertTrue(ProjectCatalog::hasCaseStudy($project), $project['slug']);
            $this->assertStringContainsString('/work/', ProjectCatalog::cardUrl($project));
        }
    }

    public function test_unknown_case_study_returns_404(): void
    {
        $response = $this->get('/work/not-a-real-project');

        $response->assertStatus(404);
    }

    public function test_adjacent_case_studies(): void
    {
        $studies = ProjectCatalog::withCaseStudies()->values();
        $this->assertGreaterThan(2, $studies->count());

        $middle = $studies[1];
        $adjacent = ProjectCatalog::adjacent($middle['slug']);

        $this->assertSame($studies[0]['slug'], $adjacent['previous']['slug']);
        $this->assertSame($studies[2]['slug'], $adjacent['next']['slug']);
    }

    public function test_related_projects_share_tags(): void
    {
        $project = ProjectCatalog::find('nasa-earth-observatory');
        $related = ProjectCatalog::related($project);

        $this->assertGreaterThan(0, $related->count());
        foreach ($related as $candidate) {
            $this->assertNotSame($project['slug'], $candidate['slug']);
            $this->assertNotEmpty(array_intersect($project['tags'], $candidate['tags']));
        }
    }

    public function test_tag_slug_round_trip(): void
    {
        $slug = ProjectCatalog::tagSlug('RESTful APIs');
        $this->assertSame('restful-apis', $slug);
        $this->assertSame('RESTful APIs', ProjectCatalog::tagFromSlug($slug));
    }
}
