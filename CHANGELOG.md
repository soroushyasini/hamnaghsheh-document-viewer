# Changelog

All notable changes to the Hamnaghsheh Document Viewer project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-12-29

### Added
- Initial release of Hamnaghsheh Document Viewer
- Main router (`index.php`) with file type validation and routing
- PDF viewer with PDF.js ES modules integration
  - Page navigation (previous, next, direct jump)
  - Zoom controls (in, out, scale limits)
  - Touch gesture support (swipe for pages)
  - Keyboard shortcuts (arrow keys, +/-)
  - Download functionality
  - Persian RTL interface
  - Mobile-responsive toolbar
  - Loading spinner and error handling
- Image viewer with Viewer.js integration
  - Zoom and pan functionality
  - Rotation (left/right)
  - Fullscreen mode
  - Reset view
  - Touch gesture support (pinch-to-zoom, drag)
  - Download functionality
  - Persian RTL interface
  - Mobile-responsive design
- Security features
  - Input validation for file and type parameters
  - XSS protection with `htmlspecialchars()`
  - Direct access prevention
  - HTTP 400 responses for invalid requests
- Brand styling with #09375B (primary) and #FFCF00 (accent) colors
- Comprehensive documentation
  - README.md with full project documentation
  - Installation and usage instructions
  - Security considerations
  - Troubleshooting guide
  - Browser compatibility information
- Support for file types: PDF, PNG, JPG, JPEG
- Integration with MinIO storage URLs
- Designed for deployment at https://hamnaghsheh.ir/document-viewer/
- .gitignore for proper version control
- MIT License
- CHANGELOG.md for version tracking

### Features
- Persian (Farsi) language throughout all interfaces
- Right-to-left (RTL) layout support
- Mobile-optimized with touch gestures
- Responsive design for all screen sizes
- Keyboard shortcuts for power users
- Disabled button states for better UX
- Smooth animations and transitions
- Professional error messages in Persian

### Technical Details
- PHP 7.4+ compatibility
- ES6 module imports for PDF.js
- Modern CSS3 with flexbox
- Semantic HTML5 markup
- No build process required
- Libraries included in repository

### Integration
- WordPress plugin integration ready
- Simple URL-based API
- GET parameter interface
- CORS-compatible

[1.0.0]: https://github.com/soroushyasini/hamnaghsheh-document-viewer/releases/tag/v1.0.0
