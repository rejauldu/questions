<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logic Designer + SVG Export</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .selected { stroke: #3b82f6 !important; stroke-width: 4px !important; filter: drop-shadow(0 0 3px rgba(59, 130, 246, 0.5)); }
        .canvas-bg { 
            background-color: #ffffff;
            background-image: radial-gradient(#cbd5e1 0.8px, transparent 0.8px); 
            background-size: 10px 10px; 
        }
        .movable { cursor: move; pointer-events: all; }
        #canvas { touch-action: none; }
    </style>
</head>
<body class="bg-slate-50 h-screen flex flex-col overflow-hidden select-none">

    <header class="bg-white border-b p-3 flex gap-4 items-center shadow-sm z-50">
        <h1 class="font-bold text-slate-700 mr-2">LogicDraw</h1>
        <div class="flex flex-wrap gap-1 bg-slate-100 p-1 rounded-md border" id="toolbar">
            <button id="btn-SELECT" onclick="setMode('SELECT')" class="mode-btn px-3 py-1 bg-blue-600 text-white rounded border text-xs">Select</button>
            <button id="btn-AND" onclick="setMode('AND')" class="mode-btn px-3 py-1 bg-white rounded border text-xs">AND</button>
            <button id="btn-OR" onclick="setMode('OR')" class="mode-btn px-3 py-1 bg-white rounded border text-xs">OR</button>
            <button id="btn-NOT" onclick="setMode('NOT')" class="mode-btn px-3 py-1 bg-white rounded border text-xs">NOT</button>
            <button id="btn-NAND" onclick="setMode('NAND')" class="mode-btn px-3 py-1 bg-white rounded border text-xs">NAND</button>
            <button id="btn-NOR" onclick="setMode('NOR')" class="mode-btn px-3 py-1 bg-white rounded border text-xs">NOR</button>
            <button id="btn-XOR" onclick="setMode('XOR')" class="mode-btn px-3 py-1 bg-white rounded border text-xs">XOR</button>
            <button id="btn-WIRE" onclick="setMode('WIRE')" class="mode-btn px-3 py-1 bg-white rounded border font-bold text-xs">WIRE</button>
        </div>
        <div class="ml-auto flex items-center gap-3">
            <input type="color" id="colorPicker" class="w-8 h-6 cursor-pointer" value="#334155">
            <button onclick="undo()" class="px-3 py-1 text-xs border border-slate-300 rounded hover:bg-slate-50">Undo</button>
            <button onclick="copySVG()" class="bg-green-600 text-white px-3 py-1 text-xs rounded hover:bg-green-700 font-bold">Copy SVG</button>
            <button onclick="clearCanvas()" class="text-red-500 px-3 py-1 text-xs border border-red-200 rounded hover:bg-red-50">Clear</button>
        </div>
    </header>

    <main class="flex-grow relative overflow-hidden">
        <svg id="canvas" class="w-full h-full canvas-bg">
            <g id="wire-layer"></g>
            <g id="gate-layer"></g>
            <g id="temp-layer"></g>
        </svg>
    </main>

    <script>
        const canvas = document.getElementById('canvas');
        const wireLayer = document.getElementById('wire-layer');
        const gateLayer = document.getElementById('gate-layer');
        const tempLayer = document.getElementById('temp-layer');
        const colorPicker = document.getElementById('colorPicker');
        
        const GRID = 10;
        let mode = 'SELECT';
        let activeDrag = null;
        let dragStart = { x: 0, y: 0 };
        let initialPos = {};
        let wireActive = false;
        let lastX = 0, lastY = 0;
        let history = [];

        const gatePaths = {
            AND: "M0,0 h20 a20,20 0 0,1 0,40 h-20 z",
            OR: "M0,0 c10,0 20,5 20,20 c0,15 -10,20 -20,20 c5,-10 5,-30 0,-40 z",
            NOT: "M0,0 L30,20 L0,40 z M30,20 a3,3 0 1,1 6,0 a3,3 0 1,1 -6,0",
            NAND: "M0,0 h20 a20,20 0 0,1 0,40 h-20 z M40,20 a3,3 0 1,1 6,0 a3,3 0 1,1 -6,0",
            NOR: "M0,0 c10,0 20,5 20,20 c0,15 -10,20 -20,20 c5,-10 5,-30 0,-40 z M25,20 a3,3 0 1,1 6,0 a3,3 0 1,1 -6,0",
            XOR: "M-5,0 c5,10 5,30 0,40 M0,0 c10,0 20,5 20,20 c0,15 -10,20 -20,20 c5,-10 5,-30 0,-40 z"
        };

        function copySVG() {
            // Clone the layers to manipulate them without affecting the canvas
            const wires = wireLayer.cloneNode(true);
            const gates = gateLayer.cloneNode(true);

            // Clean up attributes not needed for final SVG
            const clean = (el) => {
                el.removeAttribute('class');
                el.removeAttribute('data-x');
                el.removeAttribute('data-y');
                el.classList.remove('selected');
                if (el.children) Array.from(el.children).forEach(clean);
            };

            clean(wires);
            clean(gates);

            // Wrap in a valid SVG container
            const bbox = canvas.getBBox();
            const padding = 20;
            const svgOutput = `
<svg xmlns="http://www.w3.org/2000/svg" viewBox="${bbox.x - padding} ${bbox.y - padding} ${bbox.width + padding*2} ${bbox.height + padding*2}">
    <g id="wires">${wires.innerHTML.trim()}</g>
    <g id="gates">${gates.innerHTML.trim()}</g>
</svg>`;

            navigator.clipboard.writeText(svgOutput.trim()).then(() => {
                alert("Optimized SVG code copied to clipboard!");
            });
        }

        function saveState() {
            history.push({ wires: wireLayer.innerHTML, gates: gateLayer.innerHTML });
            if (history.length > 20) history.shift();
        }

        function undo() {
            if (history.length === 0) return;
            const prevState = history.pop();
            wireLayer.innerHTML = prevState.wires;
            gateLayer.innerHTML = prevState.gates;
            deselectAll();
        }

        function setMode(m) { 
            mode = m; 
            wireActive = false;
            tempLayer.innerHTML = '';
            document.querySelectorAll('.mode-btn').forEach(b => {
                b.classList.remove('bg-blue-600', 'text-white');
                b.classList.add('bg-white');
            });
            const activeBtn = document.getElementById(`btn-${m}`);
            if (activeBtn) {
                activeBtn.classList.remove('bg-white');
                activeBtn.classList.add('bg-blue-600', 'text-white');
            }
        }

        const snap = (v) => Math.round(v / GRID) * GRID;

        canvas.addEventListener('mousedown', (e) => {
            if (mode !== 'SELECT') saveState();
            const { x, y } = getCoords(e);
            const sx = snap(x);
            const sy = snap(y);

            if (gatePaths[mode]) {
                createGate(mode, sx, sy);
                setMode('SELECT');
                return;
            }

            if (mode === 'WIRE') {
                if (!wireActive) {
                    wireActive = true;
                    lastX = sx; lastY = sy;
                    showTempMarker(sx, sy);
                } else {
                    const pos = getOrthogonal(sx, sy);
                    createPermanentLine(lastX, lastY, pos.x, pos.y);
                    if (e.ctrlKey) {
                        lastX = pos.x; lastY = pos.y;
                        tempLayer.innerHTML = '';
                        showTempMarker(lastX, lastY);
                    } else {
                        setMode('SELECT');
                    }
                }
                return;
            }

            const target = e.target.closest('.gate-group') || (e.target.tagName === 'line' ? e.target : null);
            if (target) {
                saveState();
                activeDrag = target;
                dragStart = { x: sx, y: sy };
                
                if (target.tagName === 'g') {
                    initialPos = { x: parseInt(target.getAttribute('data-x')), y: parseInt(target.getAttribute('data-y')) };
                    gateLayer.appendChild(target); 
                } else {
                    initialPos = {
                        x1: parseInt(target.getAttribute('x1')), y1: parseInt(target.getAttribute('y1')),
                        x2: parseInt(target.getAttribute('x2')), y2: parseInt(target.getAttribute('y2'))
                    };
                }
                deselectAll();
                target.classList.add('selected');
            } else {
                deselectAll();
            }
        });

        window.addEventListener('mousemove', (e) => {
            const { x, y } = getCoords(e);
            const sx = snap(x);
            const sy = snap(y);

            if (activeDrag) {
                const dx = sx - dragStart.x;
                const dy = sy - dragStart.y;

                if (activeDrag.tagName === 'g') {
                    const nx = initialPos.x + dx;
                    const ny = initialPos.y + dy;
                    activeDrag.setAttribute('transform', `translate(${nx}, ${ny})`);
                    activeDrag.setAttribute('data-x', nx);
                    activeDrag.setAttribute('data-y', ny);
                } else {
                    activeDrag.setAttribute('x1', initialPos.x1 + dx);
                    activeDrag.setAttribute('y1', initialPos.y1 + dy);
                    activeDrag.setAttribute('x2', initialPos.x2 + dx);
                    activeDrag.setAttribute('y2', initialPos.y2 + dy);
                }
            }

            if (wireActive) {
                const pos = getOrthogonal(sx, sy);
                tempLayer.innerHTML = '';
                showTempMarker(lastX, lastY);
                const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
                line.setAttribute('x1', lastX); line.setAttribute('y1', lastY);
                line.setAttribute('x2', pos.x); line.setAttribute('y2', pos.y);
                line.setAttribute('stroke', '#3b82f6'); line.setAttribute('stroke-width', '2');
                line.setAttribute('stroke-dasharray', '3');
                tempLayer.appendChild(line);
            }
        });

        window.addEventListener('mouseup', () => { activeDrag = null; });

        function createPermanentLine(x1, y1, x2, y2) {
            const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
            line.setAttribute('x1', x1); line.setAttribute('y1', y1);
            line.setAttribute('x2', x2); line.setAttribute('y2', y2);
            line.setAttribute('stroke', colorPicker.value);
            line.setAttribute('stroke-width', '3');
            line.setAttribute('stroke-linecap', 'square');
            line.setAttribute('class', 'movable');
            wireLayer.appendChild(line);
        }

        function createGate(type, x, y) {
            const g = document.createElementNS("http://www.w3.org/2000/svg", "g");
            g.setAttribute('class', 'gate-group movable');
            const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
            path.setAttribute('d', gatePaths[type]);
            path.setAttribute('fill', 'white');
            path.setAttribute('stroke', colorPicker.value);
            path.setAttribute('stroke-width', '2');
            g.appendChild(path);
            g.setAttribute('transform', `translate(${x}, ${y})`);
            g.setAttribute('data-x', x); g.setAttribute('data-y', y);
            gateLayer.appendChild(g);
        }

        function getOrthogonal(sx, sy) {
            const dx = Math.abs(sx - lastX);
            const dy = Math.abs(sy - lastY);
            return dx > dy ? { x: sx, y: lastY } : { x: lastX, y: sy };
        }

        function showTempMarker(x, y) {
            const c = document.createElementNS("http://www.w3.org/2000/svg", "circle");
            c.setAttribute('cx', x); c.setAttribute('cy', y);
            c.setAttribute('r', '3'); c.setAttribute('fill', '#3b82f6');
            tempLayer.appendChild(c);
        }

        function deselectAll() {
            document.querySelectorAll('.selected').forEach(el => el.classList.remove('selected'));
        }

        function getCoords(e) {
            const rect = canvas.getBoundingClientRect();
            return { x: e.clientX - rect.left, y: e.clientY - rect.top };
        }

        function clearCanvas() {
            saveState();
            wireLayer.innerHTML = ''; gateLayer.innerHTML = ''; tempLayer.innerHTML = '';
        }

        window.addEventListener('keydown', (e) => {
            const sel = document.querySelector('.selected');
            if ((e.key === 'Delete' || e.key === 'Backspace') && sel) {
                saveState();
                sel.remove();
            }
            if (e.ctrlKey && e.key === 'z') { e.preventDefault(); undo(); }
        });
    </script>
</body>
</html>