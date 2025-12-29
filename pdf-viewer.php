<?php
/**
 * PDF Viewer - Displays PDF documents using PDF.js
 * 
 * Integrated PDF viewer with Persian RTL support and mobile optimization
 * Part of Hamnaghseh PM System
 * 
 * @version 1.0.0
 * @author Soroush Yasini & Arash
 * @date 29/12/2025
 */

// Security check
if (!defined('ABSPATH')) {
    die('Direct access not permitted');
}

// Sanitize file parameter
$file_url = htmlspecialchars($file, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>نمایش PDF - همنقشه</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Tahoma, sans-serif;
            background: #f5f5f5;
            overflow: hidden;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Toolbar Styles */
        #toolbar {
            background: #09375B;
            color: white;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            flex-wrap: wrap;
            min-height: 56px;
        }

        #toolbar .brand {
            font-weight: bold;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #toolbar .controls {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        #toolbar button {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-family: Tahoma, sans-serif;
            font-size: 14px;
            transition: all 0.2s;
            white-space: nowrap;
        }

        #toolbar button:hover:not(:disabled) {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.5);
        }

        #toolbar button:active:not(:disabled) {
            background: rgba(255, 255, 255, 0.35);
        }

        #toolbar button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        #toolbar .page-info {
            background: rgba(255, 255, 255, 0.15);
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            min-width: 100px;
            text-align: center;
        }

        #toolbar input[type="number"] {
            width: 60px;
            padding: 6px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.15);
            color: white;
            text-align: center;
            font-family: Tahoma, sans-serif;
        }

        #toolbar input[type="number"]::-webkit-inner-spin-button,
        #toolbar input[type="number"]::-webkit-outer-spin-button {
            opacity: 1;
        }

        /* Canvas Container */
        #viewer-container {
            flex: 1;
            overflow: auto;
            background: #e0e0e0;
            display: flex;
            justify-content: center;
            padding: 20px;
            position: relative;
        }

        #pdf-canvas {
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            display: block;
            max-width: 100%;
            height: auto;
        }

        /* Loading Spinner */
        #loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .spinner {
            border: 4px solid rgba(9, 55, 91, 0.1);
            border-right-color: #09375B;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        #loading p {
            color: #09375B;
            font-size: 16px;
        }

        /* Error Message */
        .error-message {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            margin: auto;
        }

        .error-message h2 {
            color: #d32f2f;
            margin-bottom: 10px;
        }

        .error-message p {
            color: #666;
        }

        /* Mobile Optimizations */
        @media (max-width: 768px) {
            #toolbar {
                padding: 8px 12px;
            }

            #toolbar .brand {
                font-size: 14px;
                width: 100%;
                margin-bottom: 8px;
            }

            #toolbar .controls {
                width: 100%;
                justify-content: space-between;
            }

            #toolbar button {
                padding: 6px 10px;
                font-size: 12px;
            }

            #toolbar .page-info {
                padding: 6px 8px;
                font-size: 12px;
                min-width: 80px;
            }

            #viewer-container {
                padding: 10px;
            }
        }

        /* Accent Color */
        .accent {
            color: #FFCF00;
        }

        /* Hidden utility class */
        .hidden {
            display: none !important;
        }
    </style>
</head>
<body>
    <!-- Toolbar -->
    <div id="toolbar">
        <div class="brand">
            <span>📄</span>
            <span>نمایش PDF - <span class="accent">همنقشه</span></span>
        </div>
        <div class="controls">
            <button id="prev-page" title="صفحه قبل (←)">→ قبلی</button>
            <div class="page-info">
                <span>صفحه </span>
                <input type="number" id="page-num" min="1" value="1">
                <span> از </span>
                <span id="page-count">-</span>
            </div>
            <button id="next-page" title="صفحه بعد (→)">بعدی ←</button>
            <button id="zoom-out" title="کوچک‌تر">−</button>
            <button id="zoom-in" title="بزرگ‌تر">+</button>
            <button id="download" title="دانلود PDF">⬇ دانلود</button>
        </div>
    </div>

    <!-- Viewer Container -->
    <div id="viewer-container">
        <div id="loading">
            <div class="spinner"></div>
            <p>در حال بارگذاری PDF...</p>
        </div>
        <canvas id="pdf-canvas"></canvas>
    </div>

    <!-- PDF.js ES Module -->
    <script type="module">
        // Import PDF.js as ES module
        import * as pdfjsLib from './assets/js/pdfjs/pdf.mjs';

        // Configure worker
        pdfjsLib.GlobalWorkerOptions.workerSrc = './assets/js/pdfjs/pdf.worker.mjs';

        // Global variables
        let pdfDoc = null;
        let pageNum = 1;
        let pageRendering = false;
        let pageNumPending = null;
        let scale = 1.5;
        const canvas = document.getElementById('pdf-canvas');
        const ctx = canvas.getContext('2d');

        // UI Elements
        const loading = document.getElementById('loading');
        const prevButton = document.getElementById('prev-page');
        const nextButton = document.getElementById('next-page');
        const pageNumInput = document.getElementById('page-num');
        const pageCountSpan = document.getElementById('page-count');
        const zoomInButton = document.getElementById('zoom-in');
        const zoomOutButton = document.getElementById('zoom-out');
        const downloadButton = document.getElementById('download');

        /**
         * Render PDF page
         */
        function renderPage(num) {
            pageRendering = true;
            
            pdfDoc.getPage(num).then(page => {
                const viewport = page.getViewport({ scale: scale });
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };

                const renderTask = page.render(renderContext);

                renderTask.promise.then(() => {
                    pageRendering = false;
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            });

            // Update UI
            pageNumInput.value = num;
            updateButtons();
        }

        /**
         * Queue page rendering
         */
        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        /**
         * Show previous page
         */
        function onPrevPage() {
            if (pageNum <= 1) return;
            pageNum--;
            queueRenderPage(pageNum);
        }

        /**
         * Show next page
         */
        function onNextPage() {
            if (pageNum >= pdfDoc.numPages) return;
            pageNum++;
            queueRenderPage(pageNum);
        }

        /**
         * Go to specific page
         */
        function goToPage() {
            const page = parseInt(pageNumInput.value);
            if (page >= 1 && page <= pdfDoc.numPages) {
                pageNum = page;
                queueRenderPage(pageNum);
            } else {
                pageNumInput.value = pageNum;
            }
        }

        /**
         * Zoom in
         */
        function zoomIn() {
            if (scale < 3) {
                scale += 0.25;
                queueRenderPage(pageNum);
            }
        }

        /**
         * Zoom out
         */
        function zoomOut() {
            if (scale > 0.5) {
                scale -= 0.25;
                queueRenderPage(pageNum);
            }
        }

        /**
         * Update button states
         */
        function updateButtons() {
            prevButton.disabled = pageNum <= 1;
            nextButton.disabled = pageNum >= pdfDoc.numPages;
            zoomOutButton.disabled = scale <= 0.5;
            zoomInButton.disabled = scale >= 3;
        }

        /**
         * Download PDF
         */
        function downloadPDF() {
            const link = document.createElement('a');
            link.href = '<?php echo $file_url; ?>';
            link.download = 'document.pdf';
            link.target = '_blank';
            link.click();
        }

        /**
         * Show error message
         */
        function showError(message) {
            const container = document.getElementById('viewer-container');
            container.innerHTML = `
                <div class="error-message">
                    <h2>⚠️ خطا در بارگذاری</h2>
                    <p>${message}</p>
                </div>
            `;
        }

        /**
         * Initialize PDF viewer
         * Updated: 29/12/2025 - Added CMap support for Persian/Arabic/CJK fonts
         */
        async function initViewer() {
            const pdfUrl = '<?php echo $file_url; ?>';

            try {
                /**
                 * Load PDF with CMap support for Persian/Arabic/CJK fonts
                 * Added by: Arash & Soroush - 29/12/2025
                 * 
                 * CMaps (Character Maps) are required for proper rendering of:
                 * - Persian (Farsi)
                 * - Arabic
                 * - Chinese, Japanese, Korean (CJK)
                 * - Other complex scripts
                 */
                const loadingTask = pdfjsLib.getDocument({
                    url: pdfUrl,
                    // CMap support for complex scripts and right-to-left languages
                    cMapUrl: './assets/js/pdfjs/cmaps/',
                    cMapPacked: true,
                    // Standard fonts for better text rendering
                    standardFontDataUrl: './assets/js/pdfjs/standard_fonts/',
                    // Disable worker fetch for better compatibility
                    useWorkerFetch: false,
                    isEvalSupported: false,
                    // Increase max image size for high-resolution PDFs
                    maxImageSize: 16777216 // 16 MB (adjustable based on needs)
                });
                
                pdfDoc = await loadingTask.promise;

                // Hide loading
                loading.classList.add('hidden');
                canvas.style.display = 'block';

                // Update page count
                pageCountSpan.textContent = pdfDoc.numPages;
                pageNumInput.max = pdfDoc.numPages;

                // Render first page
                renderPage(pageNum);

            } catch (error) {
                console.error('Error loading PDF:', error);
                // Show detailed error message
                const errorMessage = error.message || 'خطای نامشخص';
                showError(`امکان نمایش فایل PDF وجود ندارد. لطفاً دوباره تلاش کنید.<br><small style="font-size: 12px; opacity: 0.8; display: block; margin-top: 10px;">${errorMessage}</small>`);
            }
        }

        // Event Listeners
        prevButton.addEventListener('click', onPrevPage);
        nextButton.addEventListener('click', onNextPage);
        pageNumInput.addEventListener('change', goToPage);
        pageNumInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') goToPage();
        });
        zoomInButton.addEventListener('click', zoomIn);
        zoomOutButton.addEventListener('click', zoomOut);
        downloadButton.addEventListener('click', downloadPDF);

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                e.preventDefault();
                onPrevPage();
            } else if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                e.preventDefault();
                onNextPage();
            } else if (e.key === '+' || e.key === '=') {
                e.preventDefault();
                zoomIn();
            } else if (e.key === '-') {
                e.preventDefault();
                zoomOut();
            }
        });

        // Touch gesture support for mobile
        let touchStartY = 0;
        let touchEndY = 0;

        canvas.addEventListener('touchstart', (e) => {
            touchStartY = e.changedTouches[0].screenY;
        }, false);

        canvas.addEventListener('touchend', (e) => {
            touchEndY = e.changedTouches[0].screenY;
            handleSwipe();
        }, false);

        function handleSwipe() {
            const swipeThreshold = 50;
            if (touchStartY - touchEndY > swipeThreshold) {
                // Swipe up - next page
                onNextPage();
            } else if (touchEndY - touchStartY > swipeThreshold) {
                // Swipe down - previous page
                onPrevPage();
            }
        }

        // Initialize viewer on load
        initViewer();
    </script>
</body>
</html>