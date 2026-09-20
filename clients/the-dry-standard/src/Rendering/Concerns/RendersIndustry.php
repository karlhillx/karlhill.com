<?php

namespace DryStandard\Rendering\Concerns;

use DryStandard\Review;

trait RendersIndustry
{
    public function industryNote(): string
    {
        $url = $this->config->publicUrl('industry/');

        return '<p class="industry-note">Brands, producers, importers, and other industry partners may <a href="'.$this->e($url).'">submit a product</a> or inquire about collaborations. Editorial coverage is independent of samples and commercial relationships.</p>';
    }

    /**
     * @param  array<int, array{label: string, url: string}>  $crumbs
     */
    public function industrySubmit(array $form): string
    {
        return $this->industryFormPage(
            'Submit a product',
            'Tell us about a non-alcoholic drink for editorial consideration. Submission does not guarantee publication or a favorable review.',
            'industry/submit/',
            'submit',
            $form,
        );
    }

    /**
     * @param  array{csrf: string, errors: array<string, string>, old: array<string, mixed>, sent: bool, failed: bool}  $form
     */
    public function industryPartnerships(array $form): string
    {
        return $this->industryFormPage(
            'Partnerships & business inquiries',
            'A quiet front desk for advertising, distribution, product feeds, and other collaborations — without turning the cellar into a sales floor.',
            'industry/partnerships/',
            'partnerships',
            $form,
        );
    }

    public function industryHome(): string
    {
        $crumbs = $this->crumbs(['For Brands & Industry' => 'industry/']);
        $body = $this->view->render('industry', [
            'breadcrumbs' => $this->breadcrumbs($crumbs),
            'submitUrl' => $this->config->publicUrl('industry/submit/'),
            'samplesUrl' => $this->config->publicUrl('industry/samples/'),
            'partnershipsUrl' => $this->config->publicUrl('industry/partnerships/'),
            'aboutUrl' => $this->config->publicUrl('about/'),
            'privacyUrl' => $this->config->publicUrl('privacy/'),
            'editorDesk' => $this->editorDesk(),
        ]);

        return $this->document(
            'For Brands & Industry',
            'Submit a product for editorial consideration, request sample-shipping details, or inquire about collaborations with The Dry Standard.',
            'industry/',
            $body,
            [
                'nav' => 'industry',
                'json_ld' => $this->jsonLd([
                    $this->breadcrumbGraph($crumbs),
                    [
                        '@type' => 'WebPage',
                        'name' => 'For Brands & Industry',
                        'url' => $this->config->canonicalUrl('industry/'),
                    ],
                ]),
            ],
        );
    }

    public function industrySamples(): string
    {
        $crumbs = $this->crumbs([
            'For Brands & Industry' => 'industry/',
            'Editorial samples' => 'industry/samples/',
        ]);
        $body = $this->view->render('industry-samples', [
            'breadcrumbs' => $this->breadcrumbs($crumbs),
            'submitUrl' => $this->config->publicUrl('industry/submit/'),
            'industryUrl' => $this->config->publicUrl('industry/'),
            'privacyUrl' => $this->config->publicUrl('privacy/'),
            ...$this->editorViewData(),
        ]);

        return $this->document(
            'Editorial samples',
            'How brands may send products to The Dry Standard for independent editorial consideration.',
            'industry/samples/',
            $body,
            [
                'nav' => 'industry',
                'json_ld' => $this->jsonLd([
                    $this->breadcrumbGraph($crumbs),
                    [
                        '@type' => 'WebPage',
                        'name' => 'Editorial samples',
                        'url' => $this->config->canonicalUrl('industry/samples/'),
                    ],
                ]),
            ],
        );
    }

    /**
     * @param  array{csrf: string, errors: array<string, string>, old: array<string, mixed>, sent: bool, failed: bool}  $form
     */
    private function industryFormPage(
        string $title,
        string $description,
        string $path,
        string $kind,
        array $form,
    ): string {
        $crumbs = $this->crumbs([
            'For Brands & Industry' => 'industry/',
            $title => $path,
        ]);
        $template = $kind === 'partnerships' ? 'industry-partnerships' : 'industry-submit';
        $body = $this->view->render($template, [
            'breadcrumbs' => $this->breadcrumbs($crumbs),
            'action' => $this->config->publicUrl($path),
            'csrf' => $form['csrf'],
            'errors' => $form['errors'],
            'old' => $form['old'],
            'sent' => $form['sent'],
            'failed' => $form['failed'] ?? false,
            'industryUrl' => $this->config->publicUrl('industry/'),
            'samplesUrl' => $this->config->publicUrl('industry/samples/'),
            'submitUrl' => $this->config->publicUrl('industry/submit/'),
            'privacyUrl' => $this->config->publicUrl('privacy/'),
            'categories' => array_map(
                fn (string $category): array => [
                    'value' => $category,
                    'label' => $this->config->categoryLabel($category),
                ],
                $this->config->categories(),
            ),
            'productionTypes' => array_map(
                fn (string $value): array => [
                    'value' => $value,
                    'label' => Review::PRODUCTION_TYPES[$value],
                ],
                array_keys(Review::PRODUCTION_TYPES),
            ),
            ...$this->editorViewData(),
        ]);

        return $this->document($title, $description, $path, $body, [
            'nav' => 'industry',
            'json_ld' => $this->jsonLd([
                $this->breadcrumbGraph($crumbs),
                [
                    '@type' => 'WebPage',
                    'name' => $title,
                    'url' => $this->config->canonicalUrl($path),
                ],
            ]),
        ]);
    }
}
