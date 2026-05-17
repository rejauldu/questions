/**
 * PathHandler handles the specific logic for SVG <path> elements,
 * including anchor point extraction and precise 'd' attribute manipulation.
 */
export class PathHandler {
    get selector() { return 'path'; }

    /**
     * Extracts coordinates for snapping.
     * Maps local path coordinates to the root SVG coordinate space.
     */
    getPoints(path) {
        try {
            const svg = path.ownerSVGElement;
            const matrix = path.getScreenCTM();
            const transform = svg.getScreenCTM().inverse().multiply(matrix);

            const d = path.getAttribute('d') || "";
            const points = [];
            const coordRegex = /(-?\d*\.?\d+)[ ,]+(-?\d*\.?\d+)/g;
            let match;

            while ((match = coordRegex.exec(d)) !== null) {
                // Filter out secondary arc parameters (radii and flags)
                if (this.isParamSecondary(d, match.index)) continue;

                const x = parseFloat(match[1]);
                const y = parseFloat(match[2]);

                const pt = new DOMPoint(x, y).matrixTransform(transform);
                points.push({ x: pt.x, y: pt.y });
            }
            return points;
        } catch (e) { return []; }
    }

    /**
     * Identifies if a coordinate pair in the 'd' string is a destination 
     * point or a secondary parameter (like arc radii/flags).
     */
    isParamSecondary(d, matchIndex) {
        const lastA = d.lastIndexOf('A', matchIndex);
        const lastM = d.lastIndexOf('M', matchIndex);
        const lastL = d.lastIndexOf('L', matchIndex);
        const lastCommand = Math.max(lastA, lastM, lastL);

        if (lastCommand === lastA) {
            const segment = d.substring(lastA, matchIndex);
            const params = segment.split(/[ ,]+/).filter(Boolean);
            // Arc destination (x, y) are the 6th and 7th parameters.
            if (params.length < 6) return true;
        }
        return false;
    }

    /**
     * Generates red handles for each anchor point in the path.
     */
    createHandles(svg, pathEl, reshaper) {
        const d = pathEl.getAttribute('d') || "";
        const matrix = pathEl.getScreenCTM();
        const svgInverse = svg.getScreenCTM().inverse();
        const transform = svgInverse.multiply(matrix);
        
        const coordRegex = /(-?\d*\.?\d+)[ ,]+(-?\d*\.?\d+)/g;
        let match;

        while ((match = coordRegex.exec(d)) !== null) {
            if (this.isParamSecondary(d, match.index)) continue;

            const x = parseFloat(match[1]);
            const y = parseFloat(match[2]);
            
            const pt = new DOMPoint(x, y).matrixTransform(transform);
            const handle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
            
            handle.setAttribute("cx", pt.x);
            handle.setAttribute("cy", pt.y);
            handle.setAttribute("r", "6");
            handle.setAttribute("fill", "red");
            handle.style.cursor = "crosshair";
            handle.setAttribute("data-type", "handle");

            // Capture the state of the coordinate in the string for this specific handle
            const coordData = { index: match.index, length: match[0].length };

            handle.addEventListener('mousedown', (e) => {
                e.stopPropagation();
                if (reshaper.updateRegistry) reshaper.updateRegistry(pathEl);
                this.handleDrag(e, pathEl, coordData, reshaper);
            });

            svg.appendChild(handle);
            reshaper.handles.push(handle);
        }
    }

    /**
     * Logic for dragging an individual anchor point.
     */
    handleDrag(e, pathEl, coordData, reshaper) {
		e.stopPropagation();
		const handle = e.target;
		const svg = pathEl.ownerSVGElement;

		// FIX: Lock the inverse CTM at the start of the drag
		// This maps Screen Pixels -> Local Path Data units
		const localMatrix = pathEl.getScreenCTM().inverse();

		const onMove = (me) => {
			// 1. Get Mouse Screen position (NOT reshaper.getCoords yet)
			const pt = svg.createSVGPoint();
			pt.x = me.clientX;
			pt.y = me.clientY;

			// 2. Map Screen Mouse directly to Local Path Data
			const localPt = pt.matrixTransform(localMatrix);

			// 3. Update 'd' string
			let d = pathEl.getAttribute('d');
			const newCoordStr = `${localPt.x.toFixed(2)} ${localPt.y.toFixed(2)}`;
			const prefix = d.substring(0, coordData.index);
			const suffix = d.substring(coordData.index + coordData.length);
			const newD = prefix + newCoordStr + suffix;

			pathEl.setAttribute('d', newD);
			if (pathEl.previousElementSibling?.getAttribute('data-type') === 'hitbox') {
				pathEl.previousElementSibling.setAttribute('d', newD);
			}

			coordData.length = newCoordStr.length;

			// 4. Update handle position (Handles live in Global SVG space)
			const globalPt = pt.matrixTransform(svg.getScreenCTM().inverse());
			handle.setAttribute("cx", globalPt.x);
			handle.setAttribute("cy", globalPt.y);
		};

        const stop = () => {
            window.removeEventListener('mousemove', onMove);
            window.removeEventListener('mouseup', stop);
            // Full refresh of handles to reset string indices for future edits
            reshaper.createHandles(pathEl);
        };

        window.addEventListener('mousemove', onMove);
        window.addEventListener('mouseup', stop);
    }
}