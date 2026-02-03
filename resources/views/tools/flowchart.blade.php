<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlowDraw Pro - Export Fixed</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .selected { stroke: #3b82f6 !important; stroke-width: 3px !important; }
        .canvas-bg { background-color: #ffffff; background-image: radial-gradient(#e2e8f0 1.5px, transparent 1.5px); background-size: 20px 20px; }
        .shape-group { cursor: move; pointer-events: all; }
        .resizing { cursor: nwse-resize !important; }
        .connector-dot { fill: #3b82f6; opacity: 0; transition: opacity 0.2s; cursor: crosshair; pointer-events: all; }
        .shape-group:hover .connector-dot { opacity: 1; }
        .text { pointer-events: none; font-family: sans-serif }
        .polyline { pointer-events: all; cursor: pointer; }
    </style>
</head>
<body class="bg-slate-50 h-screen flex flex-col overflow-hidden select-none">

    <header class="bg-white border-b p-3 flex gap-4 items-center shadow-sm z-50">
        <div class="flex flex-wrap gap-1 bg-slate-100 p-1 rounded-md border" id="toolbar">
            <button id="btn-SELECT" onclick="setMode('SELECT')" class="mode-btn px-3 py-1 bg-blue-600 text-white rounded border text-xs">Select</button>
            <button id="btn-RECT" onclick="setMode('RECT')" class="mode-btn px-3 py-1 bg-white rounded border text-xs">Process</button>
            <button id="btn-DIAMOND" onclick="setMode('DIAMOND')" class="mode-btn px-3 py-1 bg-white rounded border text-xs">Decision</button>
            <button id="btn-PARALLEL" onclick="setMode('PARALLEL')" class="mode-btn px-3 py-1 bg-white rounded border text-xs">Data</button>
            <button id="btn-OVAL" onclick="setMode('OVAL')" class="mode-btn px-3 py-1 bg-white rounded border text-xs">Start/End</button>
            <button id="btn-TEXT" onclick="setMode('TEXT')" class="mode-btn px-3 py-1 bg-white rounded border text-xs">Add Text</button>
        </div>
        <div class="ml-auto flex items-center gap-3">
            <input type="color" id="colorPicker" class="w-8 h-6 cursor-pointer" value="#475569">
            <button onclick="undo()" class="px-3 py-1 text-xs border rounded hover:bg-white">Undo</button>
            <button onclick="copySVG()" class="bg-green-600 text-white px-3 py-1 text-xs rounded font-bold hover:bg-green-700">Copy SVG</button>
        </div>
    </header>

    <main class="flex-grow relative overflow-hidden">
        <svg id="canvas" class="w-full h-full canvas-bg">
            <defs id="svg-defs">
                <marker id="arrowhead" markerWidth="10" markerHeight="7" refX="9" refY="3.5" orient="auto">
                    <polygon points="0 0, 10 3.5, 0 7" fill="#475569" />
                </marker>
            </defs>
            <g id="main-layer"></g>
            <g id="temp-layer"></g>
        </svg>
    </main>

    <script>
        const canvas = document.getElementById('canvas');
        const mainLayer = document.getElementById('main-layer');
        const tempLayer = document.getElementById('temp-layer');
        const colorPicker = document.getElementById('colorPicker');
        
        const GRID = 20;
        let mode = 'SELECT', activeDrag = null, isResizing = false;
        let dragOffset = { x: 0, y: 0 }, initialDim = {};
        let arrowStart = null, history = [];

        function setMode(m) {
            mode = m; arrowStart = null;
            document.querySelectorAll('.mode-btn').forEach(b => b.className = "mode-btn px-3 py-1 bg-white rounded border text-xs");
            const btn = document.getElementById(`btn-${m}`);
            if(btn) btn.className = "mode-btn px-3 py-1 bg-blue-600 text-white rounded border text-xs";
        }

        const snap = (v) => Math.round(v / GRID) * GRID;

        canvas.addEventListener('mousedown', (e) => {
            const { x, y } = getCoords(e);
            const sx = snap(x), sy = snap(y);

            if (e.target.classList.contains('connector-dot')) {
                arrowStart = { x: sx, y: sy }; return;
            }

            if (['RECT', 'DIAMOND', 'OVAL', 'PARALLEL'].includes(mode)) {
                saveState(); createShape(mode, sx, sy); setMode('SELECT'); return;
            }

            if (mode === 'TEXT') {
                const targetG = e.target.closest('.shape-group');
                if (targetG) { 
                    const text = prompt("Enter text:");
                    if (text !== null) { saveState(); targetG.querySelector('text').textContent = text; }
                }
                setMode('SELECT'); return;
            }

            const target = e.target.closest('.shape-group') || (e.target.tagName === 'polyline' ? e.target : null);
            if (target) {
                saveState();
                activeDrag = target;
                isResizing = e.ctrlKey;
                
                if (target.tagName === 'g') {
                    const tx = parseInt(target.getAttribute('data-x'));
                    const ty = parseInt(target.getAttribute('data-y'));
                    dragOffset = { x: sx - tx, y: sy - ty };
                    initialDim = { w: parseInt(target.getAttribute('data-w')), h: parseInt(target.getAttribute('data-h')), x: tx, y: ty };
                } else {
                    dragOffset = { x: sx, y: sy };
                    initialDim = { points: target.getAttribute('points') };
                }
                deselectAll();
                target.classList.add('selected');
            } else { deselectAll(); }
        });

        window.addEventListener('mousemove', (e) => {
            const { x, y } = getCoords(e);
            const sx = snap(x), sy = snap(y);

            if (arrowStart) {
                const points = `${arrowStart.x},${arrowStart.y} ${sx},${arrowStart.y} ${sx},${y}`;
                tempLayer.innerHTML = `<polyline points="${points}" fill="none" stroke="#3b82f6" stroke-width="2" marker-end="url(#arrowhead)" />`;
                return;
            }

            if (!activeDrag) return;

            if (activeDrag.tagName === 'g') {
                if (isResizing) {
                    const newW = snap(Math.max(40, x - initialDim.x));
                    const newH = snap(Math.max(20, y - initialDim.y));
                    updateShapePath(activeDrag, newW, newH);
                } else {
                    const nx = sx - dragOffset.x, ny = sy - dragOffset.y;
                    activeDrag.setAttribute('transform', `translate(${nx}, ${ny})`);
                    activeDrag.setAttribute('data-x', nx); activeDrag.setAttribute('data-y', ny);
                }
            } else if (activeDrag.tagName === 'polyline') {
                const dx = sx - dragOffset.x, dy = sy - dragOffset.y;
                const newPoints = initialDim.points.split(' ').map(p => {
                    const [px, py] = p.split(',').map(Number);
                    return `${px + dx},${py + dy}`;
                }).join(' ');
                activeDrag.setAttribute('points', newPoints);
            }
        });

        window.addEventListener('mouseup', (e) => {
            if (arrowStart) {
                const { x, y } = getCoords(e);
                const points = `${arrowStart.x},${arrowStart.y} ${snap(x)},${arrowStart.y} ${snap(x)},${snap(y)}`;
                createArrow(points);
                tempLayer.innerHTML = ''; arrowStart = null;
            }
            if(activeDrag) activeDrag.classList.remove('resizing');
            activeDrag = null;
        });

        function createShape(type, x, y) {
            const g = document.createElementNS("http://www.w3.org/2000/svg", "g");
            g.classList.add('shape-group');
            const color = colorPicker.value;
            g.innerHTML = `<path fill="white" stroke="${color}" stroke-width="2" /><text text-anchor="middle" dominant-baseline="middle"></text>`;
            for(let i=0; i<4; i++) {
                const c = document.createElementNS("http://www.w3.org/2000/svg", "circle");
                c.setAttribute('class', 'connector-dot'); c.setAttribute('r', '6');
                g.appendChild(c);
            }
            g.setAttribute('data-type', type);
            g.setAttribute('transform', `translate(${x}, ${y})`);
            g.setAttribute('data-x', x); g.setAttribute('data-y', y);
            updateShapePath(g, 100, 60);
            mainLayer.appendChild(g);
        }

        function updateShapePath(g, w, h) {
            const type = g.getAttribute('data-type');
            let d = "";
            if (type === 'RECT') d = `M0,0 h${w} v${h} h-${w} z`;
            else if (type === 'DIAMOND') d = `M${w/2},0 L${w},${h/2} L${w/2},${h} L0,${h/2} z`;
            else if (type === 'OVAL') d = `M${h/2},0 h${w-h} a${h/2},${h/2} 0 0,1 0,${h} h-${w-h} a${h/2},${h/2} 0 0,1 0,-${h} z`;
            else if (type === 'PARALLEL') d = `M20,0 h${w} L${w-20},${h} h-${w} z`;
            
            g.querySelector('path').setAttribute('d', d);
            g.setAttribute('data-w', w); g.setAttribute('data-h', h);
            const txt = g.querySelector('text'); txt.setAttribute('x', w/2); txt.setAttribute('y', h/2);
            const dots = g.querySelectorAll('.connector-dot');
            dots[0].setAttribute('cx', w/2); dots[0].setAttribute('cy', 0);
            dots[1].setAttribute('cx', w);   dots[1].setAttribute('cy', h/2);
            dots[2].setAttribute('cx', w/2); dots[2].setAttribute('cy', h);
            dots[3].setAttribute('cx', 0);   dots[3].setAttribute('cy', h/2);
        }

        function createArrow(points) {
            const poly = document.createElementNS("http://www.w3.org/2000/svg", "polyline");
            poly.setAttribute('points', points);
            poly.setAttribute('fill', 'none');
            poly.setAttribute('stroke', colorPicker.value); 
            poly.setAttribute('stroke-width', '2');
            poly.setAttribute('marker-end', 'url(#arrowhead)');
            mainLayer.appendChild(poly);
        }

        function saveState() { history.push(mainLayer.innerHTML); if (history.length > 20) history.shift(); }
        function undo() { if (history.length) mainLayer.innerHTML = history.pop(); }
        function deselectAll() { document.querySelectorAll('.selected').forEach(el => el.classList.remove('selected')); }
        function getCoords(e) { const r = canvas.getBoundingClientRect(); return { x: e.clientX - r.left, y: e.clientY - r.top }; }
        
        function copySVG() {
            const bbox = canvas.getBBox();
            const defs = document.getElementById('svg-defs').outerHTML;
            // Clean the main layer for export
            let content = mainLayer.innerHTML;
            content = content.replace(/class="connector-dot"/g, 'style="display:none"');
            content = content.replace(/selected/g, '');

            const svg = `
<svg xmlns="http://www.w3.org/2000/svg" viewBox="${bbox.x-10} ${bbox.y-10} ${bbox.width+20} ${bbox.height+20}">
    ${defs}
    ${content}
</svg>`;
            navigator.clipboard.writeText(svg.trim()).then(() => alert("SVG with Arrows Copied!"));
        }

        window.addEventListener('keydown', (e) => {
            if ((e.key === 'Delete' || e.key === 'Backspace') && document.querySelector('.selected')) {
                saveState(); document.querySelector('.selected').remove();
            }
            if (e.ctrlKey && e.key === 'z') { e.preventDefault(); undo(); }
        });
    </script>
</body>
</html>