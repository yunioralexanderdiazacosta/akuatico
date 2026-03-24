const fs = require('fs');
const path = require('path');

function extractLangStrings(filePath) {
    const content = fs.readFileSync(filePath, 'utf8');
    const strings = [];
    
    const regex = /@lang\s*\(\s*(['"])(.*?)\1\s*\)/g;
    let match;
    
    while ((match = regex.exec(content)) !== null) {
        const string = match[2];
        if (!hasDynamicExpression(string)) {
            strings.push(string);
        }
    }
    
    return strings;
}

function hasDynamicExpression(str) {
    const dynamicPatterns = [
        /\$/,
        /\.\s*'/,
        /{{\s*\$.*}}/,
        /optional\(/,
        /route\(/,
        /config\(/,
    ];
    
    return dynamicPatterns.some(pattern => pattern.test(str));
}

function scanDirectory(dir, extensions = ['.blade.php']) {
    const results = [];
    
    function walk(directory) {
        const items = fs.readdirSync(directory);
        
        for (const item of items) {
            const fullPath = path.join(directory, item);
            const stat = fs.statSync(fullPath);
            
            if (stat.isDirectory()) {
                if (!item.startsWith('.') && item !== 'node_modules') {
                    walk(fullPath);
                }
            } else if (extensions.some(ext => item.endsWith(ext))) {
                const strings = extractLangStrings(fullPath);
                if (strings.length > 0) {
                    results.push({ file: fullPath, strings });
                }
            }
        }
    }
    
    walk(dir);
    return results;
}

const targetDir = process.argv[2] || path.join(__dirname, 'resources/views');
const langFile = process.argv[3] || path.join(__dirname, 'resources/lang/es.json');
const results = scanDirectory(targetDir);

const langJson = JSON.parse(fs.readFileSync(langFile, 'utf8'));

const allStrings = new Set();

for (const { file, strings } of results) {
    for (const str of strings) {
        allStrings.add(str);
    }
}

const missingStrings = [...allStrings].filter(str => !(str in langJson));

console.log('\n=== Strings extraídos de @lang() ===\n');
console.log(`Total de archivos con @lang(): ${results.length}`);
console.log(`Total de strings únicos extraídos: ${allStrings.size}`);
console.log(`Strings en es.json: ${Object.keys(langJson).length}`);
console.log(`Strings faltantes en es.json: ${missingStrings.length}\n`);

if (missingStrings.length > 0) {
    console.log('--- Strings NO encontrados en es.json ---');
    missingStrings.forEach(str => console.log(str));
    
    const readline = require('readline');
    const rl = readline.createInterface({
        input: process.stdin,
        output: process.stdout
    });
    
    rl.question(`\n¿Agregar ${missingStrings.length} strings faltantes a ${langFile}? (s/n): `, (answer) => {
        if (answer.toLowerCase() === 's' || answer.toLowerCase() === 'si' || answer.toLowerCase() === 'y' || answer.toLowerCase() === 'yes') {
            for (const str of missingStrings) {
                langJson[str] = "";
            }
            fs.writeFileSync(langFile, JSON.stringify(langJson, null, 4) + '\n');
            console.log(`✓ Se agregaron ${missingStrings.length} strings a ${langFile}`);
        } else {
            console.log('Operación cancelada.');
        }
        rl.close();
    });
} else {
    console.log('✓ Todos los strings están presentes en es.json');
}
