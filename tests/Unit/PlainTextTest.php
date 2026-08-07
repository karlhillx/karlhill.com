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
        $html = 'Cut costs by <strong>$30K</strong> — see <a href="/work/finium">case study</a>.';

        $this->assertSame(
            'Cut costs by $30K — see case study.',
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
