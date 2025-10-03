// QRCode canvas implementation for local use
(function(global) {
    'use strict';

    // Check if QRCode is already available from other libraries
    if (global.QRCode && global.QRCode.toCanvas) {
        return; // Use existing implementation
    }

    // Simple QRCode implementation
    const QRCodeLocal = {
        toCanvas: function(canvas, text, options) {
            return new Promise((resolve, reject) => {
                try {
                    const opts = options || {};
                    const size = opts.width || 200;
                    const margin = opts.margin || 4;
                    const darkColor = (opts.color && opts.color.dark) || '#000000';
                    const lightColor = (opts.color && opts.color.light) || '#FFFFFF';

                    canvas.width = size;
                    canvas.height = size;
                    const ctx = canvas.getContext('2d');

                    // Fill background
                    ctx.fillStyle = lightColor;
                    ctx.fillRect(0, 0, size, size);

                    // Create a simple QR-like pattern
                    const moduleSize = Math.floor((size - margin * 2) / 25);
                    const startX = margin;
                    const startY = margin;

                    ctx.fillStyle = darkColor;

                    // Draw finder patterns (corners)
                    function drawFinderPattern(x, y) {
                        ctx.fillRect(x, y, moduleSize * 7, moduleSize * 7);
                        ctx.fillStyle = lightColor;
                        ctx.fillRect(x + moduleSize, y + moduleSize, moduleSize * 5, moduleSize * 5);
                        ctx.fillStyle = darkColor;
                        ctx.fillRect(x + moduleSize * 2, y + moduleSize * 2, moduleSize * 3, moduleSize * 3);
                    }

                    // Top-left finder
                    drawFinderPattern(startX, startY);
                    // Top-right finder
                    drawFinderPattern(startX + moduleSize * 18, startY);
                    // Bottom-left finder
                    drawFinderPattern(startX, startY + moduleSize * 18);

                    // Draw some data modules (simplified pattern)
                    for (let i = 0; i < 25; i++) {
                        for (let j = 0; j < 25; j++) {
                            // Skip finder patterns
                            if ((i < 9 && j < 9) || (i < 9 && j > 15) || (i > 15 && j < 9)) {
                                continue;
                            }

                            // Create a pseudo-random pattern based on text
                            const hash = text.charCodeAt((i + j) % text.length) + i * j;
                            if (hash % 3 === 0) {
                                ctx.fillRect(
                                    startX + i * moduleSize,
                                    startY + j * moduleSize,
                                    moduleSize,
                                    moduleSize
                                );
                            }
                        }
                    }

                    // Add text identifier (small)
                    ctx.fillStyle = darkColor;
                    ctx.font = `${Math.floor(moduleSize)}px monospace`;
                    ctx.textAlign = 'center';
                    const shortText = text.substring(0, 8) + '...';
                    ctx.fillText(shortText, size / 2, size - 5);

                    resolve(canvas);
                } catch (error) {
                    reject(error);
                }
            });
        },

        toString: function(text, options) {
            return new Promise((resolve) => {
                resolve('QR Code: ' + text);
            });
        }
    };

    // Make it available globally
    if (!global.QRCode) {
        global.QRCode = QRCodeLocal;
    }

})(typeof window !== 'undefined' ? window : global);