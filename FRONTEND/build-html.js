const fs = require('fs');
const path = require('path');

const srcDir = path.join(__dirname, 'src');
const layoutsDir = path.join(srcDir, 'layouts');
const componentsDir = path.join(srcDir, 'components');
const sectionsDir = path.join(srcDir, 'sections');
const distDir = path.join(__dirname, 'dist');

function ensureDir(dir) {
    if (!fs.existsSync(dir)) {
        fs.mkdirSync(dir, { recursive: true });
    }
}

function processIncludes(content) {
    // Simple regex to parse {% include "path/to/file.html" %}
    const includeRegex = /\{%\s*include\s*['"](.*?)['"]\s*%\}/g;
    
    return content.replace(includeRegex, (match, includePath) => {
        const fullPath = path.join(__dirname, includePath);
        if (fs.existsSync(fullPath)) {
            let includeContent = fs.readFileSync(fullPath, 'utf8');
            // Recursively process includes in included files
            return processIncludes(includeContent);
        }
        console.warn(`Warning: Missing include: ${includePath}`);
        return `<!-- Missing include: ${includePath} -->`;
    });
}

function renderPage(pagePath) {
    let pageTemplate = fs.readFileSync(pagePath, 'utf8');
    
    // Check if it extends a layout
    const extendMatch = pageTemplate.match(/\{%\s*extends\s*['"](.*?)['"]\s*%\}/);
    
    if (extendMatch) {
        const layoutPath = path.join(__dirname, extendMatch[1]);
        if (fs.existsSync(layoutPath)) {
            let layoutContent = fs.readFileSync(layoutPath, 'utf8');
            
            // Extract blocks from page
            const blockRegex = /\{%\s*block\s*(\w+)\s*%\}([\s\S]*?)\{%\s*endblock\s*%\}/g;
            let blocks = {};
            let match;
            while ((match = blockRegex.exec(pageTemplate)) !== null) {
                blocks[match[1]] = match[2];
            }
            
            // Inject blocks into layout
            let rendered = layoutContent.replace(/\{%\s*block\s*(\w+)\s*%\}([\s\S]*?)\{%\s*endblock\s*%\}/g, (m, blockName) => {
                return blocks[blockName] || m;
            });
            
            // Clean up block tags and process includes
            rendered = rendered.replace(/\{%\s*block\s*\w+\s*%\}/g, '').replace(/\{%\s*endblock\s*%\}/g, '');
            return processIncludes(rendered);
        }
    }
    
    return processIncludes(pageTemplate);
}

function build() {
    console.log('Building HTML files...');
    ensureDir(distDir);
    
    // Get all .html files in root
    const files = fs.readdirSync(__dirname).filter(f => f.endsWith('.html') && f !== 'package-lock.html');
    
    files.forEach(file => {
        console.log(`Processing ${file}...`);
        const rendered = renderPage(path.join(__dirname, file));
        fs.writeFileSync(path.join(distDir, file), rendered);
    });
    
    // Copy scripts
    const distScriptsDir = path.join(distDir, 'src', 'scripts');
    ensureDir(distScriptsDir);
    
    const srcScriptsDir = path.join(srcDir, 'scripts');
    if (fs.existsSync(srcScriptsDir)) {
        const scriptFiles = fs.readdirSync(srcScriptsDir);
        scriptFiles.forEach(script => {
            fs.copyFileSync(path.join(srcScriptsDir, script), path.join(distScriptsDir, script));
        });
    }

    // Copy any assets if they exist (none found yet but good for future)
    const publicDir = path.join(__dirname, 'public');
    if (fs.existsSync(publicDir)) {
        // Simple recursive copy would be needed here for production
    }

    console.log('Build complete.');
}

build();
