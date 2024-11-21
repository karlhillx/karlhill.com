const fs = require('fs').promises;
const css = require('css');
const { JSDOM } = require('jsdom');

async function processCSS(htmlPath, cssPath, outputPath) {
    try {
        // Read files asynchronously
        const [htmlContent, cssContent] = await Promise.all([
            fs.readFile('resources/views/welcome.blade.php', 'utf-8'),
            fs.readFile(cssPath, 'utf-8')
        ]);

        const dom = new JSDOM(htmlContent);
        const document = dom.window.document;
        const parsedCSS = css.parse(cssContent);

        // Use Set for better performance with unique selectors
        const processedSelectors = new Set();
        const matchingRules = new Map();

        // Process rules more efficiently
        parsedCSS.stylesheet.rules
            .filter(rule => rule.type === 'rule')
            .forEach(rule => {
                rule.selectors.forEach(selector => {
                    if (!processedSelectors.has(selector)) {
                        processedSelectors.add(selector);
                        try {
                            const matchingElements = document.querySelectorAll(selector);
                            if (matchingElements.length > 0) {
                                matchingRules.set(selector, rule.declarations);
                            }
                        } catch (selectorError) {
                            console.log(`Skipping invalid selector: ${selector}`);
                        }
                    }
                });
            });

        // Generate optimized CSS
        const output = Array.from(matchingRules.entries())
            .map(([selector, declarations]) => {
                const rules = declarations
                    .map(d => `    ${d.property}: ${d.value};`)
                    .join('\n');
                return `${selector} {\n${rules}\n}`;
            })
            .join('\n\n');

        // Write output asynchronously
        await fs.writeFile(outputPath, output, 'utf-8');

        return {
            success: true,
            processedSelectors: processedSelectors.size,
            matchingRules: matchingRules.size
        };
    } catch (error) {
        return {
            success: false,
            error: error.message
        };
    }
}

// Usage
processCSS('resources/views/welcome.blade.php', 'public/css/app.css', 'public/css/output.css')
    .then(result => {
        if (result.success) {
            console.log(`✅ CSS processing complete:
- Processed selectors: ${result.processedSelectors}
- Matching rules: ${result.matchingRules}
- Output written to output.css`);
        } else {
            console.error('❌ Error:', result.error);
        }
    });
