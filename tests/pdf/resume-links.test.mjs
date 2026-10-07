import assert from 'node:assert/strict';
import { readFile } from 'node:fs/promises';
import test from 'node:test';
import { PDFArray, PDFDict, PDFDocument, PDFName, PDFString } from 'pdf-lib';

test('resume PDF has clickable sidebar contacts and profile labels and URLs', async () => {
    const bytes = await readFile(
        new URL('../../public/files/Karl-Hill-Resume.pdf', import.meta.url)
    );
    const pdf = await PDFDocument.load(bytes);
    assert.equal(pdf.getPageCount(), 2);

    const page = pdf.getPage(0);
    const sidebarLeft = page.getWidth() - 2.42 * 72;
    const annotations = page.node.Annots();
    assert.ok(annotations, 'PDF must contain link annotations');

    const links = [];
    for (let i = 0; i < annotations.size(); i++) {
        const annotation = annotations.lookup(i, PDFDict);
        if (annotation.lookup(PDFName.of('Subtype')) !== PDFName.of('Link')) continue;
        const action = annotation.lookup(PDFName.of('A'), PDFDict);
        assert.equal(action.lookup(PDFName.of('S')), PDFName.of('URI'));
        const uri = new URL(action.lookup(PDFName.of('URI'), PDFString).decodeText()).href;
        const rect = annotation
            .lookup(PDFName.of('Rect'), PDFArray)
            .asArray()
            .map((n) => n.asNumber());
        links.push({ uri, rect });
    }

    for (const [uri, minimum] of [
        ['tel:+12025991442', 1],
        ['mailto:karlhillx@gmail.com', 1],
        ['https://www.linkedin.com/in/khill/', 2],
        ['https://github.com/karlhillx', 2],
        ['https://karlhill.com', 2],
    ]) {
        const sidebarLinks = links.filter(
            (link) => link.uri === new URL(uri).href && link.rect[0] >= sidebarLeft
        );
        assert.ok(sidebarLinks.length >= minimum, `Missing sidebar hit areas for ${uri}`);
        for (const {
            rect: [x1, y1, x2, y2],
        } of sidebarLinks) {
            assert.ok(x2 > x1 && y2 > y1, `Empty hit area for ${uri}`);
            assert.ok(x2 <= page.getWidth() && y1 >= 0 && y2 <= page.getHeight());
        }
        assert.ok(
            links.some((link) => link.uri === new URL(uri).href && link.rect[0] < sidebarLeft),
            `Main-column link must remain intact for ${uri}`
        );
    }
});

test('resume PDF publication DOI is clickable', async () => {
    const bytes = await readFile(
        new URL('../../public/files/Karl-Hill-Resume.pdf', import.meta.url)
    );
    const pdf = await PDFDocument.load(bytes);
    const page = pdf.getPage(1);
    const annotations = page.node.Annots();
    assert.ok(annotations, 'Publication page must contain links');

    const targets = [];
    for (let i = 0; i < annotations.size(); i++) {
        const annotation = annotations.lookup(i, PDFDict);
        if (annotation.lookup(PDFName.of('Subtype')) !== PDFName.of('Link')) continue;
        const action = annotation.lookup(PDFName.of('A'), PDFDict);
        targets.push(action.lookup(PDFName.of('URI'), PDFString).decodeText());
    }
    assert.ok(targets.includes('https://doi.org/10.1144/gh2025-7'), 'Missing DOI link');
});
