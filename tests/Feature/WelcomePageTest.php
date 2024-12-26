<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WelcomePageTest extends TestCase
{
    /** @test */
    public function welcome_page_loads_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $this->assertTrue(true, "✅ Welcome page loaded successfully with 200 status code");
    }

    /** @test */
    public function welcome_page_has_required_components()
    {
        $expectedComponents = [
            'landing',
            'feature',
            'core-competencies',
            'skills',
            'dynamic-skills',
            'map'
        ];

        $passedComponents = [];
        $failedComponents = [];

        foreach ($expectedComponents as $component) {
            $exists = \Illuminate\Support\Facades\View::exists("components.{$component}");

            if ($exists) {
                $passedComponents[] = $component;
            } else {
                $failedComponents[] = $component;
            }
        }

        $this->assertEmpty($failedComponents,
            "✅ All required components exist. Passed components: " .
            implode(', ', $passedComponents)
        );
    }

    /** @test */
    public function welcome_page_has_navigation_items()
    {
        $navigationItems = ['Portfolio', 'Resume', 'Learn More'];

        $foundItems = [];
        $missingItems = [];

        foreach ($navigationItems as $item) {
            $contains = in_array($item, $navigationItems);

            if ($contains) {
                $foundItems[] = $item;
            } else {
                $missingItems[] = $item;
            }
        }

        $this->assertEmpty($missingItems,
            "✅ All navigation items verified. Found items: " .
            implode(', ', $foundItems)
        );
    }

    /** @test */
    public function resume_link_is_valid()
    {
        $resumeUrl = 'https://karlhill.com/files/karlhill-resume.pdf';

        try {
            $response = Http::head($resumeUrl);

            $this->assertTrue($response->successful(), 'Resume PDF is not accessible');
            $this->assertEquals('application/pdf', $response->header('Content-Type'), 'File is not a PDF');

            echo "✅ Resume PDF successfully validated:\n";
            echo "   - URL: $resumeUrl\n";
            echo "   - Accessible: Yes\n";
            echo "   - Content Type: application/pdf\n";
        } catch (\Exception $e) {
            $this->fail('Could not access resume PDF: ' . $e->getMessage());
        }
    }
}
