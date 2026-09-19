<?php

namespace DryStandard;

final class SiteBuilder
{
    public function __construct(
        private readonly Paths $paths,
        private readonly SiteConfig $config,
        private readonly ReviewRepository $reviews,
        private readonly ReviewValidator $validator,
    ) {}

    /**
     * @return array{pages: int, errors: array<int, string>, products: int}
     */
    public function build(): array
    {
        $sync = (new CatalogSync($this->paths, Catalog::open($this->paths)))->run();
        $published = $this->reviews->published();
        $errors = [];

        foreach ($published as $review) {
            $reviewErrors = $this->validator->errors($review, $this->config, forPublish: true);
            foreach ($reviewErrors as $error) {
                $errors[] = $review->slug.': '.$error;
            }
        }

        if ($errors !== []) {
            return ['pages' => 0, 'errors' => $errors, 'products' => $sync['products']];
        }

        return [
            'pages' => $published->count(),
            'errors' => [],
            'products' => $sync['products'],
        ];
    }
}
