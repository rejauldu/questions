import { PathHandler } from './handlers/PathHandler.js?1';
import { CircleHandler } from './handlers/CircleHandler.js?1';
import { RectangleHandler } from './handlers/RectangleHandler.js?1';
import { TextHandler } from './handlers/TextHandler.js?1';
import { EllipseHandler } from './handlers/EllipseHandler.js?1';
import { LineHandler } from './handlers/LineHandler.js?1';
import { GroupHandler } from './handlers/GroupHandler.js?1';

export class Reshaper {
    constructor(svg) {
        this.svg = svg;
        this.handles = [];
        this.snapRegistry = []; // Cached points for performance
        
        this.handlers = {
            'path': new PathHandler(),
            'circle': new CircleHandler(),
            'rect': new RectangleHandler(),
            'text': new TextHandler(),
            'ellipse': new EllipseHandler(),
            'line': new LineHandler(),
            'g': new GroupHandler()
        };
    }

    createHandles(el) {
        this.clearHandles();
        const type = el.tagName.toLowerCase();
        if (this.handlers[type]) {
            this.handlers[type].createHandles(this.svg, el, this);
        }
    }

    getHandler(el) {
        return this.handlers[el.tagName.toLowerCase()];
    }

    /**
     * UPDATED: Refreshes cached snap points.
     * Ensures we are capturing the most recent positions of all objects.
     */
    updateRegistry(excludeElement = null) {
        const points = [];
        
        // We iterate through all registered handler types
        Object.keys(this.handlers).forEach(type => {
            // Find all elements of this type within the SVG
            const allElements = this.svg.querySelectorAll(type);

            allElements.forEach(el => {
                // 1. Skip hitboxes
                if (el.getAttribute('data-type') === 'hitbox') return;
                // 2. Skip the element being dragged (prevents self-snapping)
                if (el === excludeElement) return;
                // 3. Skip elements in defs/markers
                if (el.closest('defs')) return;

                try {
                    const handler = this.handlers[type];
                    if (handler && typeof handler.getPoints === 'function') {
                        const elPoints = handler.getPoints(el);
                        // elPoints are expected to be in Global SVG User Space
                        points.push(...elPoints);
                    }
                } catch (err) {
                    console.warn(`Reshaper: Failed to get points for ${type}`, err);
                }
            });
        });
        
        this.snapRegistry = points;
    }

    /**
     * Unified Snapping Logic.
     * threshold: distance in SVG units to trigger the "magnet"
     */
    getSnapPoint(x, y, threshold = 15) {
        let closest = { x, y, dist: Infinity };

        for (const pt of this.snapRegistry) {
            const dx = pt.x - x;
            const dy = pt.y - y;
            
            // Optimization: skip math if outside the bounding box of the threshold
            if (Math.abs(dx) > threshold || Math.abs(dy) > threshold) continue;

            const dist = Math.sqrt(dx * dx + dy * dy);
            if (dist < closest.dist) {
                closest = { x: pt.x, y: pt.y, dist };
            }
        }
        return closest;
    }

    clearHandles() {
        this.handles.forEach(h => {
            if (h && h.parentNode) h.parentNode.removeChild(h);
        });
        this.handles = [];
    }

    /**
     * Maps screen pixels (e.clientX) to SVG User Units.
     * Essential for consistent snapping regardless of zoom or pan.
     */
    getCoords(e) {
        const pt = this.svg.createSVGPoint();
        pt.x = e.clientX;
        pt.y = e.clientY;
        
        const screenCTM = this.svg.getScreenCTM();
        if (!screenCTM) return { x: e.clientX, y: e.clientY };

        const transformed = pt.matrixTransform(screenCTM.inverse());
        return { x: transformed.x, y: transformed.y };
    }
}