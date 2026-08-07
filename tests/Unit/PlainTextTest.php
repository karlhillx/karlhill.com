<?php

namespace Tests\Unit;

use App\Support\PlainText;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PlainTextTest extends TestCase
{
    #[Test]
    public function it_strips_tags_and_decodes_entities(): void
    {
        $html = 'Cut costs by <strong>$30K</strong> annually.';

        $this->assertSame(
            'Cut costs by $30K annually.',
            PlainText::fromHtml($html)
        );
    }

    #[Test]
    public function it_drops_trailing_case_study_link_text(): void
    {
        $html = 'Delivered flood products. <a href="/work/flood-mapping-system">Case study</a>';

        $this->assertSame(
            'Delivered flood products.',
            PlainText::fromHtml($html)
        );
    }

    #[Test]
    public function it_handles_empty_input(): void
    {
        $this->assertSame('', PlainText::fromHtml(null));
        $this->assertSame('', PlainText::fromHtml(''));
    }
}
