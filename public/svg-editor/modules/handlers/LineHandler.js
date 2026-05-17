export class LineHandler {
    get selector() { return 'line'; }

    /**
     * Extracts snapping points (start and end).
     * Maps them from local space to root SVG space.
     */
    getPoints(el) {
        const svg = el.ownerSVGElement;
        const matrix = el.getScreenCTM();
        const svgInverse = svg.getScreenCTM().inverse();
        
        // This matrix converts local line coords to root SVG coords
        const transform = svgInverse.multiply(matrix);

        const x1 = parseFloat(el.getAttribute('x1') || 0);
        const y1 = parseFloat(el.getAttribute('y1') || 0);
        const x2 = parseFloat(el.getAttribute('x2') || 0);
        const y2 = parseFloat(el.getAttribute('y2') || 0);

        return [
            { x: x1, y: y1 },
            { x: x2, y: y2 }
        ].map(p => new DOMPoint(p.x, p.y).matrixTransform(transform));
    }

    /**
     * Creates orange handles at the line's global start and end points.
     */
    createHandles(svg, el, reshaper) {
        const matrix = el.getScreenCTM();
        const svgInverse = svg.getScreenCTM().inverse();
        const transform = svgInverse.multiply(matrix);

        const x1 = parseFloat(el.getAttribute('x1') || 0);
        const y1 = parseFloat(el.getAttribute('y1') || 0);
        const x2 = parseFloat(el.getAttribute('x2') || 0);
        const y2 = parseFloat(el.getAttribute('y2') || 0);

        // Calculate global positions for handles
        const pt1 = new DOMPoint(x1, y1).matrixTransform(transform);
        const pt2 = new DOMPoint(x2, y2).matrixTransform(transform);

        // Handle for Point 1 (x1, y1)
        this.createHandle(svg, pt1, (e) => this.handleDrag(e, el, 'p1', reshaper), reshaper);

        // Handle for Point 2 (x2, y2)
        this.createHandle(svg, pt2, (e) => this.handleDrag(e, el, 'p2', reshaper), reshaper);
    }

    createHandle(svg, pt, onDrag, reshaper) {
        const handle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
        handle.setAttribute("cx", pt.x);
        handle.setAttribute("cy", pt.y);
        handle.setAttribute("r", "6");
        handle.setAttribute("fill", "orange");
        handle.style.cursor = "pointer";

        handle.addEventListener('mousedown', (e) => {
            e.stopPropagation(); // Prevents selection logic in main.js
            onDrag(e);
        });

        svg.appendChild(handle);
        reshaper.handles.push(handle);
    }

    handleDrag(e, el, pointKey, reshaper) {
        e.stopPropagation();
        
        // Use ScreenCTM inverse to convert mouse screen coords to local line space
        const inv = el.getScreenCTM().inverse();

        const onMove = (me) => {
            let mousePt = reshaper.getCoords(me); // Returns root SVG space coords
            const snapped = reshaper.getSnapPoint(mousePt.x, mousePt.y);
            
            // Determine global target (either snapped or mouse)
            const targetPt = snapped.dist < 15 ? new DOMPoint(snapped.x, snapped.y) : mousePt;
            
            // Map global point back to the line's specific coordinate system
            // We use the screen event directly for the most accurate mapping
            const screenPt = new DOMPoint(me.clientX, me.clientY);
            const localPt = screenPt.matrixTransform(inv);

            // Update attributes
            if (pointKey === 'p1') {
                el.setAttribute('x1', localPt.x);
                el.setAttribute('y1', localPt.y);
            } else {
                el.setAttribute('x2', localPt.x);
                el.setAttribute('y2', localPt.y);
            }

            // Sync Hitbox
            const hitbox = el.previousElementSibling;
            if (hitbox?.getAttribute('data-type') === 'hitbox') {
                ['x1', 'y1', 'x2', 'y2'].forEach(attr => 
                    hitbox.setAttribute(attr, el.getAttribute(attr))
                );
            }

            // Refresh handles in real-time
            reshaper.createHandles(el);
        };

        const stop = () => {
            window.removeEventListener('mousemove', onMove);
            
            // FORCE MARKER REDRAW
            // This fixes the issue where arrowheads point the wrong way until the next click
            const markerEnd = el.getAttribute('marker-end');
            if (markerEnd) {
                el.removeAttribute('marker-end');
                // Force layout recalculation
                void el.offsetWidth; 
                el.setAttribute('marker-end', markerEnd);
            }
        };

        window.addEventListener('mousemove', onMove);
        window.addEventListener('mouseup', stop, { once: true });
    }
}