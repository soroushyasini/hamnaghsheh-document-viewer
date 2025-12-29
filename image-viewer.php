<?php
/**
 * Image Viewer - Displays images using Viewer.js
 * 
 * Integrated image viewer with Persian RTL support and mobile optimization
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
    <title>نمایش تصویر - همنقشه</title>
    
    <!-- Viewer.js CSS -->
    <link rel="stylesheet" href="./assets/css/viewer.min.css">
    
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
            z-index: 1000;
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

        #toolbar button:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.5);
        }

        #toolbar button:active {
            background: rgba(255, 255, 255, 0.35);
        }

        /* Image Container */
        #image-container {
            flex: 1;
            overflow: hidden;
            background: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
        }

        #viewer-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            cursor: move;
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

            #image-container {
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

        /* Override Viewer.js toolbar to use our custom toolbar */
        .viewer-toolbar {
            display: none !important;
        }
    </style>
</head>
<body>
    <!-- Toolbar -->
    <div id="toolbar">
        <div class="brand">
            <span>🖼️</span>
            <span>نمایش تصویر - <span class="accent">همنقشه</span></span>
        </div>
        <div class="controls">
            <button id="rotate-left" title="چرخش به چپ">↶ چرخش چپ</button>
            <button id="rotate-right" title="چرخش به راست">چرخش راست ↷</button>
            <button id="zoom-out" title="کوچک‌تر">−</button>
            <button id="zoom-in" title="بزرگ‌تر">+</button>
            <button id="reset" title="بازنشانی">🔄 بازنشانی</button>
            <button id="fullscreen" title="تمام صفحه">⛶ تمام صفحه</button>
            <button id="download" title="دانلود تصویر">⬇ دانلود</button>
        </div>
    </div>

    <!-- Image Container -->
    <div id="image-container">
        <div id="loading">
            <div class="spinner"></div>
            <p>در حال بارگذاری تصویر...</p>
        </div>
        <img id="viewer-image" src="<?php echo $file_url; ?>" alt="تصویر" style="display: none;">
    </div>

    <!-- Viewer.js Library -->
    <script src="./assets/js/viewerjs/viewer.min.js"></script>
    
    <script>
        // Get DOM elements
        const imageElement = document.getElementById('viewer-image');
        const loading = document.getElementById('loading');
        const rotateLeftBtn = document.getElementById('rotate-left');
        const rotateRightBtn = document.getElementById('rotate-right');
        const zoomInBtn = document.getElementById('zoom-in');
        const zoomOutBtn = document.getElementById('zoom-out');
        const resetBtn = document.getElementById('reset');
        const fullscreenBtn = document.getElementById('fullscreen');
        const downloadBtn = document.getElementById('download');
        
        // Initialize Viewer.js
        let viewer = null;

        /**
         * Initialize image viewer
         */
        function initViewer() {
            viewer = new Viewer(imageElement, {
                inline: true,
                toolbar: false,
                navbar: false,
                title: false,
                tooltip: false,
                movable: true,
                zoomable: true,
                rotatable: true,
                scalable: true,
                transition: true,
                fullscreen: true,
                keyboard: true,
                ready: function() {
                    // Hide loading and show image
                    loading.classList.add('hidden');
                    imageElement.style.display = 'block';
                },
                viewed: function() {
                    // Image is now visible
                }
            });
        }

        /**
         * Rotate image left
         */
        function rotateLeft() {
            viewer.rotate(-90);
        }

        /**
         * Rotate image right
         */
        function rotateRight() {
            viewer.rotate(90);
        }

        /**
         * Zoom in
         */
        function zoomIn() {
            viewer.zoom(0.1);
        }

        /**
         * Zoom out
         */
        function zoomOut() {
            viewer.zoom(-0.1);
        }

        /**
         * Reset view
         */
        function resetView() {
            viewer.reset();
        }

        /**
         * Toggle fullscreen
         */
        function toggleFullscreen() {
            viewer.full();
        }

        /**
         * Download image
         */
        function downloadImage() {
            const link = document.createElement('a');
            link.href = '<?php echo $file_url; ?>';
            link.download = 'image';
            link.target = '_blank';
            link.click();
        }

        /**
         * Show error message
         */
        function showError(message) {
            const container = document.getElementById('image-container');
            container.innerHTML = `
                <div class="error-message">
                    <h2>⚠️ خطا در بارگذاری</h2>
                    <p>${message}</p>
                </div>
            `;
        }

        // Event Listeners
        rotateLeftBtn.addEventListener('click', rotateLeft);
        rotateRightBtn.addEventListener('click', rotateRight);
        zoomInBtn.addEventListener('click', zoomIn);
        zoomOutBtn.addEventListener('click', zoomOut);
        resetBtn.addEventListener('click', resetView);
        fullscreenBtn.addEventListener('click', toggleFullscreen);
        downloadBtn.addEventListener('click', downloadImage);

        // Handle image load error
        imageElement.addEventListener('error', function() {
            loading.classList.add('hidden');
            showError('امکان نمایش تصویر وجود ندارد. لطفاً دوباره تلاش کنید.');
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === '+' || e.key === '=') {
                e.preventDefault();
                zoomIn();
            } else if (e.key === '-') {
                e.preventDefault();
                zoomOut();
            } else if (e.key === 'r' || e.key === 'R') {
                e.preventDefault();
                resetView();
            } else if (e.key === 'f' || e.key === 'F') {
                e.preventDefault();
                toggleFullscreen();
            }
        });

        // Initialize viewer on page load
        window.addEventListener('load', initViewer);
    </script>
</body>
</html>