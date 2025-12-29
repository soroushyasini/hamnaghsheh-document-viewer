# 📄 Hamnaghsheh Document Viewer

A mobile-optimized, Persian RTL document and image viewer module designed for the Hamnaghsheh PM System. This viewer provides seamless viewing capabilities for PDF documents and images (PNG, JPG, JPEG) with comprehensive touch gesture support.

## 🌟 Features

### PDF Viewer
- **PDF.js Integration**: Uses ES modules for modern, efficient PDF rendering
- **Page Navigation**: Previous/next buttons, direct page jump, keyboard shortcuts
- **Zoom Controls**: Smooth zoom in/out with configurable scale limits (0.5x - 3x)
- **Touch Gestures**: Swipe up/down for page navigation on mobile devices
- **Keyboard Shortcuts**: Arrow keys for navigation, +/- for zoom
- **Download Support**: Direct PDF download functionality
- **Loading States**: Elegant loading spinner with Persian messages
- **Error Handling**: User-friendly Persian error messages

### Image Viewer
- **Viewer.js Integration**: Professional image viewing library
- **Zoom & Pan**: Pinch-to-zoom, drag to pan on touch devices
- **Rotation**: Rotate left/right in 90-degree increments
- **Fullscreen Mode**: Immersive viewing experience
- **Reset View**: One-click return to original state
- **Download Support**: Direct image download
- **Touch Optimized**: Native touch gesture support
- **Keyboard Shortcuts**: +/- for zoom, R for reset, F for fullscreen

### General Features
- **Persian RTL Support**: Full right-to-left interface
- **Mobile Responsive**: Optimized layouts for all screen sizes
- **Brand Colors**: Consistent use of #09375B (primary) and #FFCF00 (accent)
- **Security**: Input validation, sanitization, and access controls
- **Error Handling**: Comprehensive error messages in Persian
- **Modern Design**: Clean, professional interface with smooth animations

## 🛠️ Technology Stack

- **PHP**: Server-side routing and security
- **PDF.js**: PDF rendering (ES modules)
- **Viewer.js**: Image viewing library
- **HTML5**: Modern semantic markup
- **CSS3**: Responsive design with flexbox
- **JavaScript ES6+**: Module imports, async/await

## 📁 Directory Structure

```
hamnaghsheh-document-viewer/
├── index.php              # Main router - handles file type routing
├── pdf-viewer.php         # PDF viewer implementation
├── image-viewer.php       # Image viewer implementation
├── assets/
│   ├── js/
│   │   ├── pdfjs/
│   │   │   ├── pdf.mjs           # PDF.js main module
│   │   │   ├── pdf.worker.mjs    # PDF.js web worker
│   │   │   └── pdf.sandbox.mjs   # PDF.js sandbox
│   │   └── viewerjs/
│   │       └── viewer.min.js     # Viewer.js library
│   └── css/
│       └── viewer.min.css        # Viewer.js styles
├── README.md              # This file
├── CHANGELOG.md           # Version history
├── LICENSE                # MIT License
└── .gitignore             # Git ignore rules
```

## 📥 Installation

### Prerequisites
- PHP 7.4 or higher
- Web server (Apache/Nginx)
- Modern browser with ES6 support

### Steps

1. **Clone or download** this repository to your web server:
   ```bash
   git clone https://github.com/soroushyasini/hamnaghsheh-document-viewer.git
   cd hamnaghsheh-document-viewer
   ```

2. **Deploy** to your web server document root or subdirectory:
   ```bash
   # Example: Deploy to subdirectory
   cp -r hamnaghsheh-document-viewer /var/www/html/document-viewer/
   ```

3. **Configure permissions**:
   ```bash
   chmod 755 *.php
   chmod -R 755 assets/
   ```

4. **Verify libraries** are in place:
   - Check `assets/js/pdfjs/` contains `pdf.mjs`, `pdf.worker.mjs`
   - Check `assets/js/viewerjs/` contains `viewer.min.js`
   - Check `assets/css/` contains `viewer.min.css`

### Library Requirements

This project requires the following libraries (already included in `assets/`):

- **PDF.js** v3.x or later (ES modules)
  - Source: https://mozilla.github.io/pdf.js/
  - Files: `pdf.mjs`, `pdf.worker.mjs`
  
- **Viewer.js** v1.x
  - Source: https://fengyuanchen.github.io/viewerjs/
  - Files: `viewer.min.js`, `viewer.min.css`

## 🚀 Usage

### URL Format

The viewer accepts two GET parameters:

```
https://your-domain.com/document-viewer/index.php?file={FILE_URL}&type={FILE_TYPE}
```

**Parameters:**
- `file`: Full URL to the file (must be publicly accessible)
- `type`: File extension - one of: `pdf`, `png`, `jpg`, `jpeg`

### Examples

**View PDF:**
```
https://hamnaghsheh.ir/document-viewer/index.php?file=https://storage.example.com/docs/report.pdf&type=pdf
```

**View Image:**
```
https://hamnaghsheh.ir/document-viewer/index.php?file=https://storage.example.com/images/photo.jpg&type=jpg
```

### Integration with WordPress Plugin

This viewer integrates with the Hamnaghsheh PM WordPress plugin:

```php
// In your WordPress plugin
$file_url = 'https://minio.example.com/bucket/document.pdf';
$viewer_url = 'https://hamnaghsheh.ir/document-viewer/index.php';
$full_url = $viewer_url . '?file=' . urlencode($file_url) . '&type=pdf';

// Generate link
echo '<a href="' . esc_url($full_url) . '" target="_blank">View Document</a>';
```

### PHP API Example

```php
<?php
// Generate viewer URL
function get_viewer_url($file_url, $file_type) {
    $viewer_base = 'https://hamnaghsheh.ir/document-viewer/index.php';
    return $viewer_base . '?file=' . urlencode($file_url) . '&type=' . $file_type;
}

// Usage
$pdf_viewer = get_viewer_url('https://example.com/file.pdf', 'pdf');
$image_viewer = get_viewer_url('https://example.com/photo.jpg', 'jpg');
?>
```

## 📱 Mobile Features

### Touch Gestures

**PDF Viewer:**
- Swipe up: Next page
- Swipe down: Previous page
- Canvas is scrollable for zoomed content

**Image Viewer:**
- Pinch to zoom
- Drag to pan
- Double-tap to zoom
- Swipe gestures supported by Viewer.js

### Responsive Design

- Toolbar automatically adapts to screen size
- On mobile (<768px): Stacked layout, smaller buttons
- Touch-friendly button sizes (minimum 44px)
- Optimized font sizes for readability

## 🌐 Browser Compatibility

- **Chrome/Edge**: ✅ Full support
- **Firefox**: ✅ Full support
- **Safari**: ✅ Full support (iOS 12+)
- **Opera**: ✅ Full support
- **IE11**: ❌ Not supported (requires ES6 modules)

### Minimum Browser Versions
- Chrome 63+
- Firefox 60+
- Safari 11.1+
- Edge 79+

## 🔒 Security Considerations

### Input Validation
- File parameter is required and cannot be empty
- Type parameter must be one of allowed types
- Invalid inputs return HTTP 400 with Persian error message

### XSS Protection
- All output uses `htmlspecialchars()` with `ENT_QUOTES`
- PHP variables properly escaped in HTML
- No direct user input rendering without sanitization

### Direct Access Prevention
- PHP files check for `ABSPATH` constant
- Sub-viewers (pdf-viewer.php, image-viewer.php) die if accessed directly
- Must be included through index.php router

### CORS Considerations
- Files must be accessible from viewer domain
- If using MinIO or S3, configure CORS appropriately:
  ```json
  {
    "AllowedOrigins": ["https://hamnaghsheh.ir"],
    "AllowedMethods": ["GET"],
    "AllowedHeaders": ["*"]
  }
  ```

### Recommendations
1. Use HTTPS for all file URLs
2. Implement authentication in calling application
3. Use signed URLs for sensitive files (MinIO presigned URLs)
4. Rate limiting on web server level
5. Monitor for abuse in access logs

## 🐛 Troubleshooting

### PDF Not Loading
- **Check CORS**: Ensure PDF URL allows cross-origin requests
- **Check URL**: Verify file URL is publicly accessible
- **Browser Console**: Check for JavaScript errors
- **Worker Path**: Verify `pdf.worker.mjs` path is correct

### Image Not Loading
- **Check URL**: Verify image URL is accessible
- **CORS Headers**: Ensure images allow cross-origin access
- **File Format**: Confirm type parameter matches actual file
- **Network Tab**: Check browser network tab for 404 errors

### Mobile Touch Not Working
- **Viewport Meta**: Ensure viewport meta tag is present
- **Touch Events**: Check browser console for JavaScript errors
- **iOS Safari**: Try adding `-webkit-overflow-scrolling: touch`

### Styling Issues
- **RTL Layout**: Ensure `dir="rtl"` is set on HTML tag
- **Font Missing**: Tahoma should fallback to system sans-serif
- **Colors Wrong**: Verify CSS is not being overridden

### Performance Issues
- **Large PDFs**: PDF.js renders one page at a time, no fix needed
- **High-Res Images**: Consider serving optimized versions
- **Slow Loading**: Check CDN/storage server performance

## 📝 License

MIT License

Copyright (c) 2025 Soroush Yasini & Arash

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

## 👥 Authors

- **Soroush Yasini** - Project Lead & Development
- **Arash** - Co-developer

## 🔗 Related Projects

- **Hamnaghsheh PM WordPress Plugin**: https://github.com/soroushyasini/hamnaghseh-PM
- **PDF.js**: https://mozilla.github.io/pdf.js/
- **Viewer.js**: https://fengyuanchen.github.io/viewerjs/

## 📊 Version History

See [CHANGELOG.md](CHANGELOG.md) for detailed version history.

## 💬 Support

For issues, questions, or contributions:
- Open an issue on GitHub
- Contact: Hamnaghsheh PM System team

---

**Deployment URL**: https://hamnaghsheh.ir/document-viewer/  
**Version**: 1.0.0  
**Last Updated**: December 29, 2025
