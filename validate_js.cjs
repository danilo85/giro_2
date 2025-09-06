const fs = require('fs');

try {
    const content = fs.readFileSync('resources/views/financial/transactions/index.blade.php', 'utf8');
    const lines = content.split('\n');
    
    console.log('Checking for syntax issues around line 348...');
    
    let openBraces = 0;
    let openBrackets = 0;
    let openParens = 0;
    let inString = false;
    let stringChar = '';
    let inComment = false;
    let inMultiLineComment = false;
    
    for (let i = 0; i < lines.length; i++) {
        const line = lines[i];
        const lineNum = i + 1;
        
        for (let j = 0; j < line.length; j++) {
            const char = line[j];
            const nextChar = line[j + 1] || '';
            const prevChar = line[j - 1] || '';
            
            // Handle comments
            if (!inString) {
                if (char === '/' && nextChar === '/') {
                    inComment = true;
                    continue;
                }
                if (char === '/' && nextChar === '*') {
                    inMultiLineComment = true;
                    continue;
                }
                if (char === '*' && nextChar === '/') {
                    inMultiLineComment = false;
                    j++; // skip next char
                    continue;
                }
            }
            
            if (inComment || inMultiLineComment) {
                continue;
            }
            
            // Handle strings
            if ((char === '"' || char === "'" || char === '`') && prevChar !== '\\') {
                if (!inString) {
                    inString = true;
                    stringChar = char;
                } else if (char === stringChar) {
                    inString = false;
                    stringChar = '';
                }
                continue;
            }
            
            if (inString) {
                continue;
            }
            
            // Count brackets, braces, and parentheses
            if (char === '{') openBraces++;
            if (char === '}') openBraces--;
            if (char === '[') openBrackets++;
            if (char === ']') openBrackets--;
            if (char === '(') openParens++;
            if (char === ')') openParens--;
            
            // Check for negative counts (closing without opening)
            if (openBraces < 0 || openBrackets < 0 || openParens < 0) {
                console.log(`ERROR at line ${lineNum}, char ${j + 1}: Closing without opening`);
                console.log(`Line: ${line}`);
                console.log(`Counts: braces=${openBraces}, brackets=${openBrackets}, parens=${openParens}`);
            }
        }
        
        // Reset comment flag at end of line
        inComment = false;
        
        // Report status around line 348
        if (lineNum >= 345 && lineNum <= 350) {
            console.log(`Line ${lineNum}: braces=${openBraces}, brackets=${openBrackets}, parens=${openParens}`);
            if (lineNum === 348) {
                console.log(`Line 348 content: "${line}"`);
            }
        }
    }
    
    console.log('\nFinal counts:');
    console.log(`Braces: ${openBraces} (should be 0)`);
    console.log(`Brackets: ${openBrackets} (should be 0)`);
    console.log(`Parentheses: ${openParens} (should be 0)`);
    
    if (openBraces !== 0 || openBrackets !== 0 || openParens !== 0) {
        console.log('\nSYNTAX ERROR: Unmatched brackets/braces/parentheses detected!');
    } else {
        console.log('\nNo syntax errors found in bracket/brace/parentheses matching.');
    }
    
} catch (err) {
    console.error('Error reading file:', err.message);
}