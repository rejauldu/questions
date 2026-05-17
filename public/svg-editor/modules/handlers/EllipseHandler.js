export class EllipseHandler {
    get selector() { return 'ellipse'; }

    /**
     * Extracts snap points (center and four cardinal points).
     * Maps local ellipse coords to root SVG space.
     */
    getPoints(el) {
        const svg = el.ownerSVGElement;
        const matrix = el.getScreenCTM();
        const svgInverse = svg.getScreenCTM().inverse();
        const transform = svgInverse.multiply(matrix);

        const cx = parseFloat(el.getAttribute('cx') || 0);
        const cy = parseFloat(el.getAttribute('cy') || 0);
        const rx = parseFloat(el.getAttribute('rx') || 0);
        const ry = parseFloat(el.getAttribute('ry') || 0);

        return [
            { x: cx, y: cy },      // Center
            { x: cx + rx, y: cy }, // Right
            { x: cx - rx, y: cy }, // Left
            { x: cx, y: cy + ry }, // Bottom
            { x: cx, y: cy - ry }  // Top
        ].map(p => new DOMPoint(p.x, p.y).matrixTransform(transform));
    }

    /**
     * Creates purple handles for center move and axis resizing.
     */
    createHandles(svg, el, reshaper) {
        const matrix = el.getScreenCTM();
        const svgInverse = svg.getScreenCTM().inverse();
        const transform = svgInverse.multiply(matrix);

        const cx = parseFloat(el.getAttribute('cx') || 0);
        const cy = parseFloat(el.getAttribute('cy') || 0);
        const rx = parseFloat(el.getAttribute('rx') || 0);
        const ry = parseFloat(el.getAttribute('ry') || 0);

        // Center Handle (Move)
        const centerPt = new DOMPoint(cx, cy).matrixTransform(transform);
        this.createHandle(svg, centerPt, (e) => this.handleDrag(e, el, 'move', reshaper), reshaper);

        // RX Handle (Horizontal Resize - 3 o'clock)
        const rxPt = new DOMPoint(cx + rx, cy).matrixTransform(transform);
        this.createHandle(svg, rxPt, (e) => this.handleDrag(e, el, 'rx', reshaper), reshaper);

        // RY Handle (Vertical Resize - 6 o'clock)
        const ryPt = new DOMPoint(cx, cy + ry).matrixTransform(transform);
        this.createHandle(svg, ryPt, (e) => this.handleDrag(e, el, 'ry', reshaper), reshaper);
    }

    createHandle(svg, pt, onDrag, reshaper) {
        const handle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
        handle.setAttribute("cx", pt.x);
        handle.setAttribute("cy", pt.y);
        handle.setAttribute("r", "5");
        handle.setAttribute("fill", "purple"); 
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
        
        // Get the inverse matrix of the ellipse to map screen pixels to local rx/ry
        const inv = el.getScreenCTM().inverse();

        const onMove = (me) => {
            let mousePt = reshaper.getCoords(me); // SVG root space
            const snapped = reshaper.getSnapPoint(mousePt.x, mousePt.y);
            
            // Determine global target based on snap threshold
            const targetPt = snapped.dist < 15 ? new DOMPoint(snapped.x, snapped.y) : mousePt;
            
            // Map the screen coordinates directly to the ellipse's local coordinate system
            const screenPt = new DOMPoint(me.clientX, me.clientY);
            const localPt = screenPt.matrixTransform(inv);

            const cx = parseFloat(el.getAttribute('cx') || 0);
            const cy = parseFloat(el.getAttribute('cy') || 0);

            if (mode === 'move') {
                el.setAttribute('cx', localPt.x);
                el.setAttribute('cy', localPt.y);
            } else if (mode === 'rx') {
                // rx is the absolute distance from local center to local mouse X
                el.setAttribute('rx', Math.abs(localPt.x - cx));
            } else if (mode === 'ry') {
                // ry is the absolute distance from local center to local mouse Y
                el.setAttribute('ry', Math.abs(localPt.y - cy));
            }

            // Sync Hitbox
            const hitbox = el.previousElementSibling;
            if (hitbox?.getAttribute('data-type') === 'hitbox') {
                ['cx', 'cy', 'rx', 'ry'].forEach(attr => 
                    hitbox.setAttribute(attr, el.getAttribute(attr))
                );
            }

            // Real-time handle refresh
            reshaper.createHandles(el);
        };

        const stop = () => window.removeEventListener('mousemove', onMove);
        window.addEventListener('mousemove', onMove);
        window.addEventListener('mouseup', stop, { once: true });
    }
}