<?php

namespace DryStandard;

use DryStandard\Rendering\Concerns\BuildsDocuments;
use DryStandard\Rendering\Concerns\RendersArchive;
use DryStandard\Rendering\Concerns\RendersCards;
use DryStandard\Rendering\Concerns\RendersCompare;
use DryStandard\Rendering\Concerns\RendersDirectory;
use DryStandard\Rendering\Concerns\RendersFeeds;
use DryStandard\Rendering\Concerns\RendersHome;
use DryStandard\Rendering\Concerns\RendersIndustry;
use DryStandard\Rendering\Concerns\RendersReview;

/**
 * Page rendering facade. Implementation lives in Rendering\Concerns\*.
 */
final class Renderer
{
    use BuildsDocuments;
    use RendersArchive;
    use RendersCards;
    use RendersCompare;
    use RendersDirectory;
    use RendersFeeds;
    use RendersHome;
    use RendersIndustry;
    use RendersReview;

    public function __construct(
        private readonly SiteConfig $config,
        private readonly View $view = new View(__DIR__.DIRECTORY_SEPARATOR.'views'),
        private readonly ?ReviewRepository $reviews = null,
    ) {}
}
