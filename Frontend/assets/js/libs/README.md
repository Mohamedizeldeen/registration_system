# Export/Import Libraries - Local Setup

## Libraries Installed

### SheetJS (xlsx.full.min.js)

- **Version**: 0.18.5
- **Size**: 881,727 bytes
- **Purpose**: Excel/CSV export functionality
- **Source**: https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js
- **Documentation**: https://docs.sheetjs.com/

### PapaParse (papaparse.min.js)

- **Version**: 5.4.1
- **Size**: 19,469 bytes
- **Purpose**: CSV parsing and import functionality
- **Source**: https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.4.1/papaparse.min.js
- **Documentation**: https://www.papaparse.com/

## Usage in HTML

```html
<!-- Load libraries before your scripts -->
<script src="../assets/js/libs/xlsx.full.min.js"></script>
<script src="../assets/js/libs/papaparse.min.js"></script>
```

## Features Provided

### Export (SheetJS)

- Export attendees to Excel (.xlsx) format
- Professional formatting with column widths
- Multi-sheet support
- Cross-platform compatibility

### Import (PapaParse + SheetJS)

- Parse CSV files with robust error handling
- Parse Excel files (.xlsx, .xls)
- Data validation and preview
- Batch processing with progress feedback

## Testing

Run `test-libraries.html` to verify local libraries are working correctly.

## Maintenance

To update libraries:

1. Download new versions from CDN URLs above
2. Replace files in `assets/js/libs/` folder
3. Test functionality with `test-libraries.html`
4. Update version numbers in this file
