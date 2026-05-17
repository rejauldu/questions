export class CircleHandler {
    // The CSS selector for the Reshaper registry
    get selector() { return 'circle'; }

    /**
     * Extracts the center point for the "Magnetic Snapping" registry.
     * Uses ScreenCTM to ensure the coordinate is in the global SVG user space.
     */
    getPoints(el) {
        const svg = el.ownerSVGElement;
        const matrix = el.getScreenCTM();
        const svgInverse = svg.getScreenCTM().inverse();
        
        // Matrix to convert local circle coords to root SVG coords
        const transform = svgInverse.multiply(matrix);

        const pt = new DOMPoint(
            parseFloat(el.getAttribute('cx') || 0), 
            parseFloat(el.getAttribute('cy') || 0)
        ).matrixTransform(transform);
        
        return [{ x: pt.x, y: pt.y }];
    }

    /**
     * Creates visual handles for the circle.
     * Maps local cx, cy, and radius points to screen positions.
     */
    createHandles(svg, el, reshaper) {
        const matrix = el.getScreenCTM();
        const svgInverse = svg.getScreenCTM().inverse();
        const transform = svgInverse.multiply(matrix);

        const cx = parseFloat(el.getAttribute('cx') || 0);
        const cy = parseFloat(el.getAttribute('cy') || 0);
        const r = parseFloat(el.getAttribute('r') || 0);

        // Handle 1: Move Center
        const centerPt = new DOMPoint(cx, cy).matrixTransform(transform);
        this.createHandle(svg, centerPt, (me) => this.handleDrag(me, el, 'move', reshaper), reshaper);

        // Handle 2: Adjust Radius (positioned at 3 o'clock)
        const radiusPt = new DOMPoint(cx + r, cy).matrixTransform(transform);
        this.createHandle(svg, radiusPt, (me) => this.handleDrag(me, el, 'resize', reshaper), reshaper);
    }

    /**
     * Helper to create and REGISTER the handle with the reshaper.
     */
    createHandle(svg, pt, onDragCallback, reshaper) {
        const handle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
        handle.setAttribute("cx", pt.x);
        handle.setAttribute("cy", pt.y);
        handle.setAttribute("r", "5");
        handle.setAttribute("fill", "blue");
        handle.style.cursor = "pointer";
        
        handle.addEventListener('mousedown', (e) => {
            e.stopPropagation();
            onDragCallback(e);
        });
        
        svg.appendChild(handle);
        // Push to reshaper.handles so clearHandles() can find/remove it
        reshaper.handles.push(handle);
    }

    handleDrag(e, el, mode, reshaper) {
        e.stopPropagation();
        
        // Inverse ScreenCTM allows us to turn screen mouse positions 
        // directly into the circle's local coordinate system.
        const inverseMatrix = el.getScreenCTM().inverse();

        const onMove = (me) => {
            let mousePt = reshaper.getCoords(me); // Root SVG space
            const snapped = reshaper.getSnapPoint(mousePt.x, mousePt.y);
            
            // Determine the global target point (snap or mouse)
            const targetPt = (snapped.dist < 15) ? 
                new DOMPoint(snapped.x, snapped.y) : mousePt;

            // Transform back to local coordinate space using the raw client coordinates
            // for maximum precision against nested transforms.
            const screenPt = new DOMPoint(me.clientX, me.clientY);
            const localPt = screenPt.matrixTransform(inverseMatrix);

            if (mode === 'move') {
                el.setAttribute('cx', localPt.x);
                el.setAttribute('cy', localPt.y);
            } else if (mode === 'resize') {
                const cx = parseFloat(el.getAttribute('cx'));
                const cy = parseFloat(el.getAttribute('cy'));
                // Calculate new radius in local space
                const newR = Math.hypot(localPt.x - cx, localPt.y - cy);
                el.setAttribute('r', newR);
            }
            
            // Sync the invisible hitbox
            const hitbox = el.previousElementSibling;
            if (hitbox?.getAttribute('data-type') === 'hitbox') {
                ['cx', 'cy', 'r'].forEach(a => hitbox.setAttribute(a, el.getAttribute(a)));
            }
            
            // Refresh handles in real-time
            reshaper.createHandles(el);
        };

        const stop = () => window.removeEventListener('mousemove', onMove);
        window.addEventListener('mousemove', onMove);
        window.addEventListener('mouseup', stop, { once: true });
    }
}