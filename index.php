<?php
/**
 * Document Viewer - Main Router
 * 
 * Routes document/image viewing requests to appropriate viewer
 * Part of Hamnaghseh PM System
 * 
 * @version 1.0.0
 * @author Soroush Yasini & Arash
 * @date 29/12/2025
 */

// Security check
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__));
}

// Get parameters
$file = isset($_GET['file']) ? $_GET['file'] : '';
$type = isset($_GET['type']) ? strtolower($_GET['type']) : '';

// Validate inputs
if (empty($file) || empty($type)) {
    http_response_code(400);
    die('
    <!DOCTYPE html>
    <html lang="fa" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>خطا - همنقشه</title>
        <style>
            body {
                font-family: Tahoma, sans-serif;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
                margin: 0;
                background: #f5f5f5;
            }
            .error {
                background: white;
                padding: 40px;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                text-align: center;
            }
            .error h1 {
                color: #d32f2f;
                margin: 0 0 10px 0;
            }
            .error p {
                color: #666;
                margin: 0;
            }
        </style>
    </head>
    <body>
        <div class="error">
            <h1>⚠️ خطا</h1>
            <p>پارامترهای نامعتبر. لطفاً از طریق سیستم همنقشه وارد شوید.</p>
        </div>
    </body>
    </html>
    ');
}

// Define allowed types
$allowed_types = ['pdf', 'png', 'jpg', 'jpeg'];

// Validate file type
if (!in_array($type, $allowed_types)) {
    http_response_code(400);
    die('
    <!DOCTYPE html>
    <html lang="fa" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>خطا - همنقشه</title>
        <style>
            body {
                font-family: Tahoma, sans-serif;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
                margin: 0;
                background: #f5f5f5;
            }
            .error {
                background: white;
                padding: 40px;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                text-align: center;
            }
            .error h1 {
                color: #d32f2f;
                margin: 0 0 10px 0;
            }
            .error p {
                color: #666;
                margin: 0;
            }
        </style>
    </head>
    <body>
        <div class="error">
            <h1>⚠️ خطا</h1>
            <p>فرمت فایل پشتیبانی نمیشود. فرمتهای مجاز: PDF, PNG, JPG, JPEG</p>
        </div>
    </body>
    </html>
    ');
}

// Route to appropriate viewer
if ($type === 'pdf') {
    include __DIR__ . '/pdf-viewer.php';
} elseif (in_array($type, ['png', 'jpg', 'jpeg'])) {
    include __DIR__ . '/image-viewer.php';
}
