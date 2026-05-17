export class TextHandler {
    get selector() { return 'text'; }

    /**
     * Extracts the text anchor point for snapping.
     * Uses ScreenCTM to map the x/y attributes to root SVG space.
     */
    getPoints(el) {
        const svg = el.ownerSVGElement;
        const matrix = el.getScreenCTM();
        const svgInverse = svg.getScreenCTM().inverse();
        
        // Matrix to convert local text coords to root SVG coords
        const transform = svgInverse.multiply(matrix);

        const x = parseFloat(el.getAttribute('x') || 0);
        const y = parseFloat(el.getAttribute('y') || 0);

        const pt = new DOMPoint(x, y).matrixTransform(transform);
        return [{ x: pt.x, y: pt.y }];
    }

    /**
     * Creates a green handle at the text's anchor point.
     */
    createHandles(svg, el, reshaper) {
        const matrix = el.getScreenCTM();
        const svgInverse = svg.getScreenCTM().inverse();
        const transform = svgInverse.multiply(matrix);

        const x = parseFloat(el.getAttribute('x') || 0);
        const y = parseFloat(el.getAttribute('y') || 0);

        // Convert local anchor to root SVG space for handle placement
        const anchorPt = new DOMPoint(x, y).matrixTransform(transform);
        this.createHandle(svg, anchorPt, (me) => this.handleDrag(me, el, reshaper), reshaper);
    }

    createHandle(svg, pt, onDrag, reshaper) {
        const handle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
        handle.setAttribute("cx", pt.x);
        handle.setAttribute("cy", pt.y);
        handle.setAttribute("r", "5");
        handle.setAttribute("fill", "green"); // Green for text anchor
        handle.style.cursor = "pointer";
        
        handle.addEventListener('mousedown', (e) => {
            e.stopPropagation();
            onDrag(e);
        });

        svg.appendChild(handle);
        reshaper.handles.push(handle);
    }

    handleDrag(e, el, reshaper) {
        e.stopPropagation();
        
        // Inverse matrix to map screen cursor directly to local text space
        const inverseMatrix = el.getScreenCTM().inverse();

        const onMove = (me) => {
            let mousePt = reshaper.getCoords(me); // Root SVG space
            const snapped = reshaper.getSnapPoint(mousePt.x, mousePt.y);
            
            // Determine the global target point (snap or mouse)
            const targetPt = (snapped.dist < 15) ? 
                new DOMPoint(snapped.x, snapped.y) : mousePt;

            // Map global/screen position back to local coordinate system
            const screenPt = new DOMPoint(me.clientX, me.clientY);
            const localPt = screenPt.matrixTransform(inverseMatrix);

            // Update text position attributes
            el.setAttribute('x', localPt.x);
            el.setAttribute('y', localPt.y);
            
            // Sync Hitbox (assuming hitbox is also a <text> or similar rect)
            const hitbox = el.previousElementSibling;
            if (hitbox?.getAttribute('data-type') === 'hitbox') {
                hitbox.setAttribute('x', localPt.x);
                hitbox.setAttribute('y', localPt.y);
            }

            // Real-time handle refresh
            reshaper.createHandles(el);
        };

        const stop = () => window.removeEventListener('mousemove', onMove);
        window.addEventListener('mousemove', onMove);
        window.addEventListener('mouseup', stop, { once: true });
    }
}