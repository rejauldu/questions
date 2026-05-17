export class RectangleHandler {
    get selector() { return 'rect'; }

    /**
     * Extracts registry points for snapping (the 4 corners).
     * Maps local rect coordinates to root SVG space using ScreenCTM.
     */
    getPoints(el) {
        const svg = el.ownerSVGElement;
        const matrix = el.getScreenCTM();
        const svgInverse = svg.getScreenCTM().inverse();
        
        // Combined matrix to go from local -> screen -> root SVG space
        const transform = svgInverse.multiply(matrix);

        const x = parseFloat(el.getAttribute('x') || 0);
        const y = parseFloat(el.getAttribute('y') || 0);
        const w = parseFloat(el.getAttribute('width') || 0);
        const h = parseFloat(el.getAttribute('height') || 0);

        return [
            { x: x, y: y },           // Top-Left
            { x: x + w, y: y },       // Top-Right
            { x: x + w, y: y + h },   // Bottom-Right
            { x: x, y: y + h }        // Bottom-Left
        ].map(p => new DOMPoint(p.x, p.y).matrixTransform(transform));
    }

    /**
     * Creates blue handles for the top-left (move/resize) and bottom-right (resize).
     */
    createHandles(svg, el, reshaper) {
        const matrix = el.getScreenCTM();
        const svgInverse = svg.getScreenCTM().inverse();
        const transform = svgInverse.multiply(matrix);

        const x = parseFloat(el.getAttribute('x') || 0);
        const y = parseFloat(el.getAttribute('y') || 0);
        const w = parseFloat(el.getAttribute('width') || 0);
        const h = parseFloat(el.getAttribute('height') || 0);

        // 1. Top-Left handle
        const tlPt = new DOMPoint(x, y).matrixTransform(transform);
        this.createHandle(svg, tlPt, (e) => this.handleDrag(e, el, 'top-left', reshaper), reshaper);

        // 2. Bottom-Right handle
        const brPt = new DOMPoint(x + w, y + h).matrixTransform(transform);
        this.createHandle(svg, brPt, (e) => this.handleDrag(e, el, 'bottom-right', reshaper), reshaper);
    }

    createHandle(svg, pt, onDrag, reshaper) {
        const handle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
        handle.setAttribute("cx", pt.x);
        handle.setAttribute("cy", pt.y);
        handle.setAttribute("r", "6");
        handle.setAttribute("fill", "blue");
        handle.style.cursor = "pointer";
        
        handle.addEventListener('mousedown', (e) => {
            e.stopPropagation();
            onDrag(e);
        });

        svg.appendChild(handle);
        reshaper.handles.push(handle);
    }

    handleDrag(e, el, mode, reshaper) {
        e.stopPropagation();
        
        // Inverse matrix to map screen cursor directly to local rect space
        const inv = el.getScreenCTM().inverse();

        const onMove = (me) => {
            let mousePt = reshaper.getCoords(me); // Root SVG space
            const snapped = reshaper.getSnapPoint(mousePt.x, mousePt.y);
            
            // Choose target point (snapped or raw mouse)
            const targetPt = snapped.dist < 15 ? new DOMPoint(snapped.x, snapped.y) : mousePt;
            
            // Transform the global screen position to the rectangle's internal local space
            const screenPt = new DOMPoint(me.clientX, me.clientY);
            const localPt = screenPt.matrixTransform(inv);

            if (mode === 'top-left') {
                // Calculate the fixed bottom-right corner in local space
                const oldX2 = parseFloat(el.getAttribute('x')) + parseFloat(el.getAttribute('width'));
                const oldY2 = parseFloat(el.getAttribute('y')) + parseFloat(el.getAttribute('height'));
                
                // Update x/y and adjust width/height relative to the fixed bottom-right point
                const newWidth = Math.max(1, oldX2 - localPt.x);
                const newHeight = Math.max(1, oldY2 - localPt.y);
                
                el.setAttribute('x', oldX2 - newWidth);
                el.setAttribute('y', oldY2 - newHeight);
                el.setAttribute('width', newWidth);
                el.setAttribute('height', newHeight);
            } else {
                // Bottom-right: x/y stay fixed, width/height grow based on mouse
                const x = parseFloat(el.getAttribute('x'));
                const y = parseFloat(el.getAttribute('y'));
                
                el.setAttribute('width', Math.max(1, localPt.x - x));
                el.setAttribute('height', Math.max(1, localPt.y - y));
            }

            // Sync Hitbox
            const hitbox = el.previousElementSibling;
            if (hitbox?.getAttribute('data-type') === 'hitbox') {
                ['x', 'y', 'width', 'height'].forEach(attr => 
                    hitbox.setAttribute(attr, el.getAttribute(attr))
                );
            }

            // Real-time handle refresh
            reshaper.createHandles(el);
        };

        const stop = () => {
            window.removeEventListener('mousemove', onMove);
        };

        window.addEventListener('mousemove', onMove);
        window.addEventListener('mouseup', stop, { once: true });
    }
}