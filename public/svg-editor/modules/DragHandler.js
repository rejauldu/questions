/**
 * DragHandler manages moving SVG elements (visuals and hitboxes)
 * via the 'transform' attribute with advanced snapping.
 */
export class DragHandler {
    constructor(svg) {
        if (!svg) console.error("DragHandler: SVG element is missing!");
        this.svg = svg; 
        
        this.isDragging = false;
        this.start = { x: 0, y: 0 };
        this.selection = []; 
    }

    /**
     * Prepares the selection for movement and refreshes the snap registry.
     */
    startDrag(e, targets, reshaper) {
        if (!targets) return;

        const elements = (Array.isArray(targets) ? targets.flat() : [targets])
            .filter(el => el && typeof el.getAttribute === 'function');

        if (elements.length === 0) return;

        // Refresh the global snap registry before starting the move
        if (reshaper && typeof reshaper.updateRegistry === 'function') {
            reshaper.updateRegistry();
        }

        this.isDragging = true;
        
        // Use the improved coordinate getter
        const pt = this.getCoords(e);
        this.start = { x: pt.x, y: pt.y };

        this.selection = elements.map(el => {
            let hitbox, visual;
            if (el.getAttribute('data-type') === 'hitbox') {
                hitbox = el;
                visual = el.nextElementSibling;
            } else {
                visual = el;
                hitbox = el.previousElementSibling;
            }
            
            // If no visual found, the element itself is the visual
            if (!visual) visual = el;

            const coords = this.parseTranslate(visual);

            return { 
                visual, 
                hitbox, 
                offsets: { x: coords.x, y: coords.y } 
            };
        });
    }

    /**
     * Updates positions with "Geometric Snapping".
     */
    onMouseMove(e, reshaper) {
        if (!this.isDragging || this.selection.length === 0) return;

        const pt = this.getCoords(e);
        let dx = pt.x - this.start.x;
        let dy = pt.y - this.start.y;

        if (reshaper && typeof reshaper.getHandler === 'function') {
            let bestSnap = { dist: Infinity, dx: 0, dy: 0 };
            const threshold = 15;

            for (const item of this.selection) {
                const handler = reshaper.getHandler(item.visual);
                if (!handler) continue;

                // getPoints provides coordinates in the SVG's local space
                const points = handler.getPoints(item.visual);

                for (const p of points) {
                    // Try to find a snap target for the current point's new position
                    const snapped = reshaper.getSnapPoint(p.x + dx, p.y + dy, threshold);
                    
                    if (snapped.dist < bestSnap.dist && snapped.dist < threshold) {
                        bestSnap = {
                            dist: snapped.dist,
                            dx: snapped.x - p.x,
                            dy: snapped.y - p.y
                        };
                    }
                }
                if (bestSnap.dist === 0) break;
            }

            // If a snap was found, override the mouse delta with the snap delta
            if (bestSnap.dist < threshold) {
                dx = bestSnap.dx;
                dy = bestSnap.dy;
            }
        }

        // Apply the translation to all selected items
        this.selection.forEach(item => {
            const newX = item.offsets.x + dx;
            const newY = item.offsets.y + dy;
            this.applyTransform(item.visual, item.hitbox, newX, newY);
        });
    }

    onMouseUp() { 
        this.isDragging = false; 
        this.selection = [];
    }

    /**
     * Move elements by a fixed amount (usually for arrow-key nudging).
     */
    nudge(targets, dx, dy) {
        const elements = (Array.isArray(targets) ? targets.flat() : [targets])
            .filter(el => el && typeof el.getAttribute === 'function');

        elements.forEach(el => {
            let hitbox, visual;
            if (el.getAttribute('data-type') === 'hitbox') {
                hitbox = el;
                visual = el.nextElementSibling;
            } else {
                visual = el;
                hitbox = el.previousElementSibling;
            }
            if (!visual) visual = el;

            const current = this.parseTranslate(visual);
            this.applyTransform(visual, hitbox, current.x + dx, current.y + dy);
        });
    }

    /**
     * Helper to apply the transform attribute.
     */
    applyTransform(visual, hitbox, x, y) {
        // Use fixed decimals to prevent microscopic drifting in the SVG string
        const newTranslate = `translate(${x.toFixed(2)}, ${y.toFixed(2)})`;
        if (visual) visual.setAttribute('transform', newTranslate);
        if (hitbox && hitbox !== visual) {
            hitbox.setAttribute('transform', newTranslate);
        }
    }

    /**
     * Extracts x and y from a translate(x, y) string.
     */
    parseTranslate(el) {
        const transform = el.getAttribute('transform') || 'translate(0,0)';
        const match = transform.match(/translate\(([^,)\s]+)[,\s]*([^,)\s]*)\)/);
        let x = 0, y = 0;
        if (match) {
            x = parseFloat(match[1]);
            y = match[2] ? parseFloat(match[2]) : 0;
        }
        return { x, y };
    }

    /**
     * Converts screen pixels (e.clientX) to SVG coordinates.
     * Crucial for making handles and shapes align.
     */
    getCoords(e) {
		if (!this.svg) return { x: 0, y: 0 };
		const pt = this.svg.createSVGPoint();
		pt.x = e.clientX;
		pt.y = e.clientY;
		
		// getScreenCTM is the most reliable way to handle browser zooming, 
		// CSS scaling, and SVG viewBox mismatches simultaneously.
		const ctm = this.svg.getScreenCTM();
		return pt.matrixTransform(ctm.inverse());
	}
}