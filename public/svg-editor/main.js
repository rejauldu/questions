import { Reshaper } from './modules/Reshaper.js?1';
import { DragHandler } from './modules/DragHandler.js?1';

const svg = document.querySelector('#svg-editable svg');
const reshaper = new Reshaper(svg);
const dragger = new DragHandler(svg);

let selectedElements = [];

// --- UNDO/REDO SYSTEM ---
const undoStack = [];
const redoStack = [];
const MAX_HISTORY = 15; 
let nudgeTimer = null;

function saveState() {
    const handlesActive = reshaper.handles.length > 0;
    // If handles are active, find the visual element they are attached to
    const activeElement = handlesActive ? selectedElements[0]?.nextElementSibling : null;
    
    reshaper.clearHandles(); 

    const currentState = svg.innerHTML;
    
    if (undoStack.length > 0 && undoStack[undoStack.length - 1] === currentState) {
        if (handlesActive && activeElement) reshaper.createHandles(activeElement);
        return;
    }

    undoStack.push(currentState);
    redoStack.length = 0; 

    if (undoStack.length > MAX_HISTORY) undoStack.shift();
    if (handlesActive && activeElement) reshaper.createHandles(activeElement);
}

function undo() {
    if (undoStack.length < 2) return;
    redoStack.push(undoStack.pop());
    const previousState = undoStack[undoStack.length - 1];
    applyState(previousState);
}

function redo() {
    if (redoStack.length === 0) return;
    const nextState = redoStack.pop();
    undoStack.push(nextState);
    applyState(nextState);
}

function applyState(stateHTML) {
    svg.innerHTML = stateHTML;
    clearSelection();
    init(); 
}

// --- VISUAL & HITBOX LOGIC ---

function setVisualStroke(el, color) {
    el.style.stroke = color;
    if (el.tagName.toLowerCase() === 'text') el.style.fill = color;
    if (el.tagName.toLowerCase() === 'g') {
        el.querySelectorAll('*').forEach(child => {
            child.style.stroke = color;
            if (child.tagName.toLowerCase() === 'text') child.style.fill = color;
        });
    }
}

function createHitbox(el) {
    if (el.previousElementSibling?.getAttribute('data-type') === 'hitbox') return;

    const hitbox = el.cloneNode(true);
    hitbox.setAttribute('data-type', 'hitbox');
    hitbox.style.cursor = 'move';

    hitbox.removeAttribute('marker-start');
    hitbox.removeAttribute('marker-mid');
    hitbox.removeAttribute('marker-end');

    const prepareHitboxElement = (element) => {
        element.style.strokeWidth = '30px'; 
        element.style.strokeOpacity = '0';
        element.style.fill = 'none';
        element.style.pointerEvents = 'all';
        element.style.stroke = 'black';
        
        if (element !== hitbox) {
            element.removeAttribute('marker-start');
            element.removeAttribute('marker-mid');
            element.removeAttribute('marker-end');
        }
    };

    if (el.tagName.toLowerCase() === 'g') {
        hitbox.querySelectorAll('*').forEach(child => prepareHitboxElement(child));
    } else {
        prepareHitboxElement(hitbox);
    }
    
    hitbox.addEventListener('mouseenter', () => {
        if (!selectedElements.includes(hitbox)) setVisualStroke(el, 'red');
    });

    hitbox.addEventListener('mouseleave', () => {
        if (!selectedElements.includes(hitbox)) setVisualStroke(el, 'black');
    });
    
    el.parentNode.insertBefore(hitbox, el);
}

function init() {
    const types = 'path, circle, rect, text, ellipse, line, g';
    svg.querySelectorAll(types).forEach(el => {
        if (el.getAttribute('data-type') !== 'hitbox' && !el.closest('defs')) {
            createHitbox(el);
        }
    });
}

function clearSelection() {
    selectedElements.forEach(hitbox => {
        const visual = hitbox.nextElementSibling;
        if (visual) setVisualStroke(visual, 'black');
    });
    selectedElements = [];
}

// --- EVENT LISTENERS ---

svg.addEventListener('mousedown', (e) => {
    let target = e.target;
    
    // Resolve hitbox if user clicked the visual element directly
    if (target.getAttribute('data-type') !== 'hitbox') {
        const sibling = target.previousElementSibling;
        if (sibling && sibling.getAttribute('data-type') === 'hitbox') target = sibling;
    }

    if (target.getAttribute('data-type') === 'hitbox') {
        const visualEl = target.nextElementSibling;

        // Multi-select (Ctrl)
        if (e.ctrlKey) {
            reshaper.clearHandles(); 
            if (selectedElements.includes(target)) {
                selectedElements = selectedElements.filter(el => el !== target);
                setVisualStroke(visualEl, 'black');
            } else {
                selectedElements.push(target);
                setVisualStroke(visualEl, 'blue');
            }
            return; 
        }

        // Reshape Mode (Alt)
        if (e.altKey) {
            clearSelection();
            reshaper.clearHandles();
            reshaper.createHandles(visualEl);
            return;
        }

        // Standard Select & Drag
        if (!selectedElements.includes(target)) {
            clearSelection();
            selectedElements = [target];
            setVisualStroke(visualEl, 'blue');
        }

        // CRITICAL: Refresh the magnetic registry for the drag session
        // Pass visualEl so the dragger knows NOT to snap the object to itself
        dragger.startDrag(e, selectedElements, reshaper);
        
    } else if (e.target === svg) {
        clearSelection();
        reshaper.clearHandles();
    }
});

window.addEventListener('mousemove', (e) => {
    if (dragger.isDragging) {
        dragger.onMouseMove(e, reshaper);
    }

    // 1. Convert Screen Mouse coordinates to SVG coordinates
    const pt = svg.createSVGPoint();
    pt.x = e.clientX;
    pt.y = e.clientY;
    
    // transform the point using the SVG's Screen CTM
    const svgP = pt.matrixTransform(svg.getScreenCTM().inverse());
    
    // OPTIONAL: If you want to see the arc move to the mouse in real-time 
    // without changing the index.php, we find the path by its attribute:
    const arcPath = svg.querySelector('path[d*="A 30 30"]');
    if (arcPath && e.shiftKey) { // Example: hold Shift to move the arc start
        const arcData = "A 30 30 0 0 1 305 212";
        arcPath.setAttribute('d', `M ${svgP.x.toFixed(2)} ${svgP.y.toFixed(2)} ${arcData}`);
    }
});

window.addEventListener('mouseup', () => {
    if (dragger.isDragging) {
        dragger.onMouseUp();
        saveState(); 
    }
});

window.addEventListener('keydown', (e) => {
    if (e.ctrlKey && e.key.toLowerCase() === 'z') {
        e.preventDefault();
        undo();
        return;
    }
    if (e.ctrlKey && e.key.toLowerCase() === 'y') {
        e.preventDefault();
        redo();
        return;
    }

    if (e.key === 'Escape') {
        clearSelection();
        reshaper.clearHandles();
        return;
    }

    // Arrow Key Nudging
    const arrowKeys = ['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight'];
    if (arrowKeys.includes(e.key) && selectedElements.length > 0) {
        e.preventDefault();
        const step = e.shiftKey ? 10 : 1;
        let dx = 0, dy = 0;
        if (e.key === 'ArrowLeft')  dx = -step;
        if (e.key === 'ArrowRight') dx = step;
        if (e.key === 'ArrowUp')    dy = -step;
        if (e.key === 'ArrowDown')  dy = step;

        dragger.nudge(selectedElements, dx, dy);

        // If a single item is being reshaped, refresh handles
        if (selectedElements.length === 1) {
            const visual = selectedElements[0].nextElementSibling;
            if (reshaper.handles.length > 0 && visual) reshaper.createHandles(visual);
        }

        clearTimeout(nudgeTimer);
        nudgeTimer = setTimeout(() => saveState(), 500);
    }
});

// Initialize
init();
saveState(); 

// Export Logic
document.getElementById('copySVG')?.addEventListener('click', () => {
    reshaper.clearHandles(); 
    const exportSvg = svg.cloneNode(true);
    exportSvg.querySelectorAll('[data-type="hitbox"]').forEach(h => h.remove());
    
    // Clean up temporary styles
    const allShapes = exportSvg.querySelectorAll('path, circle, rect, text, ellipse, line, g, g *');
    allShapes.forEach(shape => {
        shape.style.stroke = ""; 
        shape.style.outline = "";
    });

    const serializer = new XMLSerializer();
    let svgString = serializer.serializeToString(exportSvg);
    svgString = svgString.replace(/>(?=<)/g, '>\n');
    copyToClipboard(svgString);
});

function copyToClipboard(text) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(() => alert("Copied to clipboard!")).catch(() => {});
    }
}