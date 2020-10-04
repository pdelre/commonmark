<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace League\CommonMark\Tests\Unit\Extension\Footnote\Renderer;

use League\CommonMark\Configuration\Configuration;
use League\CommonMark\Extension\Footnote\Node\FootnoteContainer;
use League\CommonMark\Extension\Footnote\Renderer\FootnoteContainerRenderer;
use League\CommonMark\Tests\Unit\Renderer\FakeChildNodeRenderer;
use PHPUnit\Framework\TestCase;

final class FootnoteContainerRendererTest extends TestCase
{
    public function testDefaultSettings(): void
    {
        $renderer = new FootnoteContainerRenderer();
        $renderer->setConfiguration(new Configuration());

        $container = new FootnoteContainer();

        $output = $renderer->render($container, new FakeChildNodeRenderer());

        $this->assertSame('footnotes', $output->getAttribute('class'));
        $this->assertSame('doc-endnotes', $output->getAttribute('role'));

        $this->assertStringContainsString('<hr />', $output->getContents());
    }

    public function testCustomClassAddedViaAST(): void
    {
        $renderer = new FootnoteContainerRenderer();
        $renderer->setConfiguration(new Configuration());

        $container = new FootnoteContainer();
        $container->data->set('attributes/class', 'custom class');

        $output = $renderer->render($container, new FakeChildNodeRenderer());

        $this->assertSame('custom class footnotes', $output->getAttribute('class'));
    }

    public function testClassConfiguration(): void
    {
        $renderer = new FootnoteContainerRenderer();
        $renderer->setConfiguration(new Configuration(['footnote' => ['container_class' => 'my-custom-class']]));

        $container = new FootnoteContainer();

        $output = $renderer->render($container, new FakeChildNodeRenderer());

        $this->assertSame('my-custom-class', $output->getAttribute('class'));
    }

    public function testAddHRConfiguration(): void
    {
        $renderer = new FootnoteContainerRenderer();
        $renderer->setConfiguration(new Configuration(['footnote' => ['container_add_hr' => false]]));

        $container = new FootnoteContainer();

        $output = $renderer->render($container, new FakeChildNodeRenderer());

        $this->assertStringNotContainsString('<hr />', $output->getContents());
    }
}
