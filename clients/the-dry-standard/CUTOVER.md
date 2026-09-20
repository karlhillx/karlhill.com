# Domain cutover checklist

When The Dry Standard leaves `/clients/the-dry-standard/` for its own host:

1. Set `site.canonical_host` and `site.base_path: ""` in `data/config.yaml`.
2. Set `site.index: true` and drop the Laravel `X-Robots-Tag: noindex` override.
3. Point DNS + TLS at the app; keep Vite entries `clients/the-dry-standard/assets/{css,js}/site.*` in the deploy build (hashed under `/build`).
4. Submit `/sitemap.xml` in Search Console; keep review URLs date-free.
5. Move industry mail DNS (`drinkdrystandard@…`) and Turnstile keys if used.
6. Keep `content/`, `data/`, and `media/` portable — they are the product boundary.
7. Retire the parent `base href` injection in `DryStandardSiteController`.

Do not flip `index: true` while still mounted under karlhill.com.
