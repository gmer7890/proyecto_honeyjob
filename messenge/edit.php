<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Code Editor</title>
    <style>
        body {
            margin: 0;
            display: flex;
            font-family: Arial, sans-serif;
        }
        aside {
            width: 200px;
            background-color: #f4f4f4;
            border-right: 1px solid #ccc;
            padding: 10px;
        }
        aside .file {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            cursor: pointer;
        }
        aside .file svg {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }
        .editor-container {
            flex: 1;
            position: relative;
            display: flex;
            flex-direction: column;
        }
        .line-numbers {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 50px;
            background: #e9e9e9;
            text-align: right;
            padding-right: 5px;
            line-height: 1.5em;
            color: #999;
            user-select: none;
        }
        .editor {
            margin-left: 50px;
            width: calc(100% - 50px);
            height: 100vh;
            padding: 10px;
            border: none;
            outline: none;
            font-family: monospace;
            line-height: 1.5em;
            white-space: pre;
            background: #272822;
            color: #f8f8f2;
            overflow: auto;
        }
        .keyword-red { color: red; }
        .keyword-blue { color: blue; }
        .keyword-yellow { color: darkgoldenrod; }
        .keyword-violet { color: violet; }
    </style>
</head>
<body>
    <aside>
        <div style="margin-left: 5px;" class="file">
            <svg viewBox="0 0 24 24" fill="blue">
                <path d="M10 4H4v16h16V8h-6z" />
            </svg>
            index
        </div>
        <div style="margin-left: 10px;" class="file">
           <svg xmlns="http://www.w3.org/2000/svg" fill="orange" viewBox="0 0 24 24" width="24" height="24">
  <path d="M12 2C6.48 2 2 3.79 2 6v12c0 2.21 4.48 4 10 4s10-1.79 10-4V6c0-2.21-4.48-4-10-4zm0 2c4.97 0 8 1.79 8 2s-3.03 2-8 2-8-1.79-8-2 3.03-2 8-2zm0 16c-4.97 0-8-1.79-8-2V15c1.78.91 4.8 1.5 8 1.5s6.22-.59 8-1.5v3c0 .21-3.03 2-8 2zm0-6c-4.97 0-8-1.79-8-2V9c1.78.91 4.8 1.5 8 1.5s6.22-.59 8-1.5v3c0 .21-3.03 2-8 2z"/>
</svg>
            Data.csc
        </div>
        <div style="margin-left: 10px;" class="file">
            <svg viewBox="0 0 24 24" fill="yellow">
                <path d="M10 4H4v16h16V8h-6z" />
            </svg>
            index.csc
        </div>
        <div style="margin-left: 10px;"class="file">
            <svg viewBox="0 0 24 24" fill="yellow">
                <path d="M10 4H4v16h16V8h-6z" />
            </svg>
            Sc.csc
        </div>
    </aside>
    <div class="editor-container">
        <div class="line-numbers" id="lineNumbers"></div>
        <textarea class="editor" id="editor"></textarea>
    </div>

    <script>
        const editor = document.getElementById('editor');
const lineNumbers = document.getElementById('lineNumbers');

function updateLineNumbers() {
    const lines = editor.innerText.split('\n').length;
    lineNumbers.innerHTML = Array.from({ length: lines }, (_, i) => i + 1).join('\n');
}

function highlightSyntax() {
    const keywordsRed = /\b(scl)\b/g;
    const keywordsBlue = /\b(rader|rom|tsum|leget|wall|reselt|doms)\b/g;
    const keywordsYellow = /\b(twitter|lang|docs|f\.scala)\b/g;

    const text = editor.innerText; // Obtener texto sin formato
    const highlightedText = text
        .replace(keywordsRed, '<span class="keyword-red">$&</span>')
        .replace(keywordsBlue, '<span class="keyword-blue">$&</span>')
        .replace(keywordsYellow, '<span class="keyword-yellow">$&</span>');

    const cursorPosition = window.getSelection().getRangeAt(0).startOffset; // Guardar posición del cursor

    editor.innerHTML = highlightedText;

    // Restaurar posición del cursor
    const range = document.createRange();
    const selection = window.getSelection();
    range.setStart(editor.childNodes[0], cursorPosition);
    range.collapse(true);
    selection.removeAllRanges();
    selection.addRange(range);
}

editor.addEventListener('input', () => {
    updateLineNumbers();
    highlightSyntax();
});

updateLineNumbers();

    </script>
</body>
</html>
