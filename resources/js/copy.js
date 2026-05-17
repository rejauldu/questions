/**
 * LaTeX to Word Linear Converter
 * Optimized for MS Word Equation Editor (Alt + =)
 */
function convertToWordLinear(text) {
    // 1. Initial cleanup
    let result = text.replace(/\$/g, "").trim();
    result = result.replace(/\\phy/g, "\\phi"); // Common typo fix

    // 2. MATRIX HANDLING (Must happen before general symbol replacement)
    // Converts \begin{bmatrix} a & b \\ c & d \end{bmatrix} -> [\matrix(a & b @ c & d)]
    result = result.replace(/\\begin\{(bmatrix|pmatrix|matrix)\}([\s\S]*?)\\end\{\1\}/g, (match, type, content) => {
        // Replace LaTeX column separator & with Word &
        // Replace LaTeX row separator \\ with Word @
        let internal = content
            .replace(/\\\\/g, "@")
            .replace(/&/g, "&");

        if (type === 'bmatrix') return `[\\matrix(${internal})]`;
        if (type === 'pmatrix') return `(\\matrix(${internal}))`;
        return `\\matrix(${internal})`;
    });

    // 3. Comprehensive Mapping (Symbols)
    const symbolMap = {
        // --- Layout, Functions & Structures ---
        "\\\\frac": "/", "\\\\sqrt": "√", "\\\\text": "", "\\\\vec": "→", 
        "\\\\hat": "^", "\\\\dot": "˙", "\\\\bar": "‾", "\\\\overline": "‾", 
        "\\\\underline": "_", "\\\\sum": "∑", "\\\\prod": "∏", "\\\\int": "∫", 
        "\\\\oint": "∮", "\\\\log": "log", "\\\\ln": "ln", "\\\\sin": "sin", 
        "\\\\cos": "cos", "\\\\tan": "tan", "\\\\cot": "cot", "\\\\sec": "sec", 
        "\\\\csc": "csc", "\\\\arcsin": "arcsin", "\\\\arccos": "arccos", 
        "\\\\arctan": "arctan", "\\\\mathbb": "", "\\\\begin": "", "\\\\end": "",

        // --- Math Operators & Symbols ---
        "\\\\pm": "±", "\\\\mp": "∓", "\\\\times": "×", "\\\\div": "÷",
        "\\\\approx": "≈", "\\\\neq": "≠", "\\\\le": "≤", "\\\\ge": "≥",
        "\\\\infty": "∞", "\\\\degree": "°", "\\\\circ": "∘", "\\\\angle": "∠",
        "\\\\bullet": "∙", "\\\\cdot": "⋅", "\\\\propto": "∝", "\\\\hbar": "ℏ", 
        "\\\\ell": "ℓ", "\\\\wp": "℘", "\\\\Re": "ℜ", "\\\\Im": "ℑ", 
        "\\\\nabla": "∇", "\\\\partial": "∂", "\\\\parallel": "∥", 
        "\\\\dots": "…", "\\\\quad": "  ", "\\\\det": "det", 
        "\\\\lim": "lim", "\\\\to": "→",

        // --- Logic & Sets ---
        "\\\\forall": "∀", "\\\\exists": "∃", "\\\\in": "∈", "\\\\notin": "∉", 
        "\\\\subset": "⊂", "\\\\supset": "⊃", "\\\\cup": "∪", "\\\\cap": "∩", 
        "\\\\therefore": "∴", "\\\\because": "∵", "\\\\implies": "⇒", 
        "\\\\impliedby": "⇐", "\\\\iff": "⇔",

        // --- Greek Lowercase ---
        "\\\\alpha": "α", "\\\\beta": "β", "\\\\gamma": "γ", "\\\\delta": "δ",
        "\\\\epsilon": "ε", "\\\\zeta": "ζ", "\\\\eta": "η", "\\\\theta": "θ",
        "\\\\iota": "ι", "\\\\kappa": "κ", "\\\\lambda": "λ", "\\\\mu": "μ",
        "\\\\nu": "ν", "\\\\xi": "ξ", "\\\\omicron": "ο", "\\\\pi": "π",
        "\\\\rho": "ρ", "\\\\sigma": "σ", "\\\\tau": "τ", "\\\\upsilon": "υ",
        "\\\\phi": "φ", "\\\\chi": "χ", "\\\\psi": "ψ", "\\\\omega": "ω",

        // --- Greek Uppercase ---
        "\\\\Delta": "Δ", "\\\\Gamma": "Γ", "\\\\Theta": "Θ", "\\\\Lambda": "Λ",
        "\\\\Xi": "Ξ", "\\\\Pi": "Π", "\\\\Sigma": "Σ", "\\\\Phi": "Φ",
        "\\\\Psi": "Ψ", "\\\\Omega": "Ω",

        // --- Chemistry & Arrows ---
        "\\\\ce": "", "\\\\bond": "-", "\\\\pH": "pH", "\\\\isotope": "", 
        "\\\\xleftarrow": "←", "\\\\xrightarrow": "→", "\\\\rightleftharpoons": "⇌", 
        "\\\\longrightarrow": "⟶", "\\\\uparrow": "↑", "\\\\downarrow": "↓",

        // --- Delimiters ---
        "\\\\left": "", "\\\\right": "",

        // --- Expanded / Missing to reach 128+ ---
        "\\\\iint": "∬", "\\\\iiint": "∭", "\\\\land": "∧", "\\\\lor": "∨",
        "\\\\neg": "¬", "\\\\oplus": "⊕", "\\\\otimes": "⊗", "\\\\empty": "∅",
        "\\\\equiv": "≡", "\\\\aleph": "ℵ", "\\\\beth": "ℶ", "\\\\blacksquare": "■",
        "\\\\square": "□", "\\\\checkmark": "✓", "\\\\smile": "⌣", "\\\\frown": "⌢",
        "\\\\diamond": "⋄", "\\\\ast": "∗", "\\\\star": "★", "\\\\dagger": "†",
        "\\\\ddagger": "‡", "\\\\top": "⊤", "\\\\bot": "⊥", "\\\\vdash": "⊢",
        "\\\\dashv": "⊣", "\\\\lfloor": "⌊", "\\\\floor": "⌊", "\\\\rfloor": "⌋",
        "\\\\lceil": "⌈", "\\\\ceil": "⌈", "\\\\rceil": "⌉", "\\\\langle": "⟨",
        "\\\\rangle": "⟩", "\\\\mid": "|", "\\\\vert": "|", "\\\\Vert": "‖"
    };

    // Apply the map (Longest keys first to prevent partial matches like \int -> \in)
    const sortedKeys = Object.keys(symbolMap).sort((a, b) => b.length - a.length);
    sortedKeys.forEach(key => {
        const escapedKey = key.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        // The (?![a-zA-Z]) ensures \in doesn't match the start of \int
        result = result.replace(new RegExp(escapedKey + '(?![a-zA-Z])', "g"), symbolMap[key]);
    });

    // 4. Handle specific functions
    const funcs = ['sin', 'cos', 'tan', 'cot', 'sec', 'csc', 'log', 'ln', 'prod', 'sum', 'int', 'oint', 'lim', 'det'];
    funcs.forEach(f => {
        result = result.replace(new RegExp(`\\\\${f}`, "g"), f);
    });

    // 5. Advanced Structures
    result = result.replace(/\\sqrt\s*{([^}]*)}/g, "sqrt($1)");
    result = result.replace(/\\overline{([^}]*)}/g, "$1\u0305");
    result = result.replace(/\\underline{([^}]*)}/g, "$1\u0332");

    // Unit Vector / Hat / Vec / Dot
    result = result.replace(/\\hat{i}/g, "i\u0302");
    result = result.replace(/\\hat{j}/g, "j\u0302");
    result = result.replace(/\\hat{k}/g, "k\u0302");
    result = result.replace(/\\hat{([^}]*)}/g, "$1\u0302");
    result = result.replace(/\\vec{([^}]*)}/g, "$1\u20D7");
    result = result.replace(/\\dot{([^}]*)}/g, "$1\u0307");

    // 6. Fractions: \frac{a}{b} -> (a)/(b)
    let iterations = 0;
    while (result.includes("\\frac") && iterations < 10) {
        let nextResult = result.replace(/\\frac\s*{([^{}]*)}\s*{([^{}]*)}/g, "($1)/($2)");
        if (nextResult === result) break;
        result = nextResult;
        iterations++;
    }

    // 7. Subscript/Superscript formatting
    result = result.replace(/_([a-zA-Z0-9])/g, "_$1 "); 
    result = result.replace(/_{([^}]*)}/g, "_($1) ");
    result = result.replace(/\^([a-zA-Z0-9])/g, "^$1 ");
    result = result.replace(/\^{([^}]*)}/g, "^($1) ");

    // 8. Final Cleanup
    result = result.replace(/\\ce{([^}]*)}/g, "$1");
    result = result.replace(/\\text{([^}]*)}/g, "$1");
    result = result.replace(/\\left|\\right/g, ""); // Remove left/right scaling commands
    result = result.replace(/\s\s+/g, ' '); 
    
    return result.trim();
}

/**
 * UI Logic
 */
document.addEventListener("DOMContentLoaded", function () {
    initCopyButtons();
});

function initCopyButtons() {
    const copyButtons = document.querySelectorAll(".copy-btn");
    if (!copyButtons.length) return;

    copyButtons.forEach(btn => {
        btn.addEventListener("click", function () {
            const rawText = this.getAttribute("data-copy");
            if (!rawText) return;

            const convertedText = convertToWordLinear(rawText);

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(convertedText)
                    .then(() => showCopyFeedback(this))
                    .catch(err => console.error("Could not copy text:", err));
            } else {
                // Fallback for older browsers
                const textarea = document.createElement("textarea");
                textarea.value = convertedText;
                textarea.style.position = "fixed";
                textarea.style.opacity = "0";
                document.body.appendChild(textarea);
                textarea.focus();
                textarea.select();
                try {
                    if (document.execCommand("copy")) showCopyFeedback(this);
                } catch (err) {
                    console.error("Fallback copy failed:", err);
                } finally {
                    document.body.removeChild(textarea);
                }
            }
        });
    });
}

function showCopyFeedback(button) {
    const originalHtml = button.innerHTML;
    button.classList.add("!text-success-600");

    button.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 inline-block"
            viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 13l4 4L19 7" />
        </svg>
        <span class="text-[10px] md:text-xs font-semibold uppercase tracking-wider text-success-600">Copied!</span>
    `;

    setTimeout(() => {
        if (button.innerHTML.includes("Copied!")) {
            button.classList.remove("!text-success-600");
            button.innerHTML = originalHtml;
        }
    }, 1000);

    function applySymbolMap(input) {
        let output = input;
        
        // Sort keys by length (longest first) so \int is replaced before \in
        const sortedKeys = Object.keys(symbolMap).sort((a, b) => b.length - a.length);

        sortedKeys.forEach(key => {
            // Escaping the key for Regex
            const escapedKey = key.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            // Negative lookahead (?![a-zA-Z]) ensures we don't match partial words
            const regex = new RegExp(escapedKey + '(?![a-zA-Z])', 'g');
            output = output.replace(regex, symbolMap[key]);
        });

        return output;
    }
}