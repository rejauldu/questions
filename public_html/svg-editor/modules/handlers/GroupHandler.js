/**
 * GroupHandler manages <g> elements.
 * It uses the 'transform' attribute since groups don't have x/y attributes.
 */
export class GroupHandler {
    get selector() { return 'g'; }

    /**
     * Calculates snapping points (corners and center) for the group.
     * Uses ScreenCTM to ensure coordinates are in the global SVG user space.
     */
    getPoints(el) {
        const svg = el.ownerSVGElement;
        const bbox = el.getBBox();
        const matrix = el.getScreenCTM();
        const svgInverse = svg.getScreenCTM().inverse();
        
        // This matrix converts local group coordinates to root SVG coordinates
        const transform = svgInverse.multiply(matrix);

        return [
            { x: bbox.x, y: bbox.y },                               // Top-Left
            { x: bbox.x + bbox.width, y: bbox.y },                  // Top-Right
            { x: bbox.x, y: bbox.y + bbox.height },                 // Bottom-Left
            { x: bbox.x + bbox.width, y: bbox.y + bbox.height },    // Bottom-Right
            { x: bbox.x + bbox.width / 2, y: bbox.y + bbox.height / 2 } // Center
        ].map(p => new DOMPoint(p.x, p.y).matrixTransform(transform));
    }

    /**
     * Creates a red handle at the center of the group.
     */
    createHandles(svg, el, reshaper) {
        const bbox = el.getBBox();
        const matrix = el.getScreenCTM();
        const svgInverse = svg.getScreenCTM().inverse();
        const transform = svgInverse.multiply(matrix);

        // Find the visual center in root SVG space
        const center = new DOMPoint(
            bbox.x + bbox.width / 2, 
            bbox.y + bbox.height / 2
        ).matrixTransform(transform);
        
        this.createHandle(svg, center, (e) => this.handleDrag(e, el, reshaper), reshaper);
    }

    createHandle(svg, pt, onDrag, reshaper) {
        const handle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
        handle.setAttribute("cx", pt.x);
        handle.setAttribute("cy", pt.y);
        handle.setAttribute("r", "8");
        handle.setAttribute("fill", "red");
        handle.setAttribute("stroke", "white");
        handle.setAttribute("stroke-width", "2");
        handle.style.cursor = "move";
        handle.style.pointerEvents = "all"; 
        
        handle.addEventListener('mousedown', (e) => {
            e.preventDefault();
            e.stopPropagation(); 
            onDrag(e);
        });

        svg.appendChild(handle);
        reshaper.handles.push(handle);
    }

    /**
     * Specialized drag logic for the Group Handle.
     * Uses delta movement to update the translate transformation.
     */
    handleDrag(e, el, reshaper) {
        // We use root SVG coordinates for delta calculation to stay zoom-independent
        const startMouse = reshaper.getCoords(e);
        
        // Parse the current translation
        const transformAttr = el.getAttribute('transform') || 'translate(0,0)';
        const match = transformAttr.match(/translate\(([^,)\s]+)[,\s]*([^,)\s]*)\)/);
        
        let startX = 0, startY = 0;
        if (match) {
            startX = parseFloat(match[1]);
            startY = match[2] ? parseFloat(match[2]) : 0;
        }

        const onMove = (me) => {
            const currMouse = reshaper.getCoords(me);
            
            // Calculate how far the mouse has moved in root SVG space
            const dx = currMouse.x - startMouse.x;
            const dy = currMouse.y - startMouse.y;

            let targetX = startX + dx;
            let targetY = startY + dy;

            // Apply Reshaper's snapping
            const snapped = reshaper.getSnapPoint(targetX, targetY);
            if (snapped.dist < 15) {
                targetX = snapped.x;
                targetY = snapped.y;
            }

            const newTranslate = `translate(${targetX}, ${targetY})`;

            // 1. Move Visual Group
            el.setAttribute('transform', newTranslate);

            // 2. Sync Hitbox
            const hitbox = el.previousElementSibling;
            if (hitbox && hitbox.getAttribute('data-type') === 'hitbox') {
                hitbox.setAttribute('transform', newTranslate);
            }

            // Refresh handle position in real-time
            reshaper.createHandles(el);
        };

        const stop = () => {
            window.removeEventListener('mousemove', onMove);
            window.removeEventListener('mouseup', stop);
        };

        window.addEventListener('mousemove', onMove);
        window.addEventListener('mouseup', stop);
    }
}