# Company Management Frontend

This folder contains the frontend implementation of the company management system, recreated from the Laravel Blade templates.

## Files Overview

### 1. `index.html` - Company List Page

- **Purpose**: Main dashboard showing all companies with statistics
- **Features**:
  - Statistics cards (Total Companies, Events, Users, Active Companies)
  - Searchable and sortable company table
  - Company avatars with initials
  - Action buttons (View, Edit, Delete)
  - Empty state when no companies exist
  - Delete confirmation modal

### 2. `create.html` - Add New Company

- **Purpose**: Form to create new companies
- **Features**:
  - Company information form (Name, Address, Email, Phone, Password)
  - Real-time form validation
  - Success modal after creation
  - Information box about what happens after creation
  - Cancel/Create another options

### 3. `show.html` - Company Details View

- **Purpose**: Detailed view of a specific company
- **Features**:
  - Company information display
  - Associated events listing
  - Company users listing
  - Quick statistics sidebar
  - Recent activity timeline
  - Action buttons (Analytics, Export, Settings, Delete)

### 4. `edit.html` - Edit Company Form

- **Purpose**: Form to update existing company information
- **Features**:
  - Pre-populated form with existing data
  - Form validation
  - Loading states
  - Success confirmation
  - Information box about update impacts

## Design Features

### Consistent UI Elements

- **Color Scheme**: Blue primary (#blue-600), with green, purple, and orange accents
- **Typography**: Inter font family for clean, modern look
- **Icons**: Font Awesome 6.4.0 for consistent iconography
- **Layout**: Responsive design with Tailwind CSS classes

### Interactive Elements

- **Modals**: Delete confirmations and success messages
- **Loading States**: Spinners and disabled states during API calls
- **Form Validation**: Real-time validation with error messages
- **Hover Effects**: Smooth transitions on buttons and cards

### Data Handling

- **Sample Data**: Each page includes sample data for demonstration
- **API Ready**: Structure prepared for backend integration
- **State Management**: JavaScript handles UI state and user interactions

## Navigation Flow

```
index.html (Company List)
    ├── create.html (Add Company)
    ├── show.html (View Company Details)
    │   └── edit.html (Edit Company)
    └── edit.html (Edit Company)
```

## Backend Integration Notes

The HTML files are designed to easily integrate with the existing Laravel backend:

1. **API Endpoints**: Replace sample data with actual API calls
2. **Form Submissions**: Update form actions to submit to Laravel routes
3. **Authentication**: Add authentication checks and user context
4. **Error Handling**: Implement proper error handling for API failures
5. **Pagination**: Add pagination for large company lists

## Dependencies

- **Tailwind CSS**: For styling (via CDN in main Frontend folder)
- **Font Awesome**: For icons
- **Axios**: For API calls (available in assets/js/)
- **Inter Font**: From Google Fonts

## Usage

1. Open any HTML file in a web browser
2. Navigate between pages using the provided links
3. Interact with forms and buttons to see JavaScript functionality
4. All functionality is demonstrated with sample data

## Responsive Design

- Mobile-first approach
- Responsive grid layouts
- Collapsible navigation on small screens
- Touch-friendly button sizes
- Optimized for tablets and mobile devices

## Browser Compatibility

- Modern browsers (Chrome, Firefox, Safari, Edge)
- ES6+ JavaScript features used
- CSS Grid and Flexbox for layouts
- Supports devices with JavaScript enabled
