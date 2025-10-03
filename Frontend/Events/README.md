# Event Sharing System Documentation

## Overview

This system allows events to be shared publicly via a shareable link. Users can register for events and receive a PDF badge with a QR code for event access.

## Features Implemented

### 1. Public Event Sharing Page (`share.html`)

- **URL Format**: `share.html?id={eventId}`
- **Features**:
  - Displays event banner, logo, name, and description
  - Shows event details (date, time, location, contact info)
  - Lists available tickets for the event
  - Registration form for each ticket type
  - PDF badge generation with QR code
  - Responsive design with modern UI

### 2. Share Button Integration (`index.html`)

- Added share buttons to each event card in the events list
- One-click copy to clipboard functionality
- Toast notifications for user feedback
- Generates shareable links automatically

### 3. Backend API Endpoints

#### Public Event Access (No Authentication Required)

```
GET /events/public/{eventId}
```

Returns public event information suitable for sharing.

#### Event Tickets (No Authentication Required)

```
GET /tickets/event/{eventId}
```

Returns all tickets associated with an event.

#### Public Registration (No Authentication Required)

```
POST /attendees/register
```

Registers a new attendee and generates QR code.

**Request Body:**

```json
{
  "firstName": "string (required)",
  "lastName": "string (required)",
  "eventId": "number",
  "ticketId": "number",
  "email": "string",
  "phone": "string",
  "company": "string",
  "jobTitle": "string",
  "country": "string"
}
```

**Response:**

```json
{
  "success": true,
  "message": "Registration successful",
  "attendee": {
    "id": 123,
    "firstName": "John",
    "lastName": "Doe",
    "email": "john@example.com",
    "company": "Example Corp",
    "jobTitle": "Developer",
    "qrCode": "unique-qr-id",
    "qrCodeData": "{...json...}"
  }
}
```

### 4. PDF Badge Generation

- **Size**: A6 (105mm × 148mm)
- **Contents**:
  - Event logo (if available)
  - Event name
  - Attendee full name
  - Job title and company
  - QR code for event access
  - Professional design with borders and styling

### 5. QR Code System

- **Technology**: qrcode.js library
- **Data Format**: JSON containing:
  - Attendee ID
  - Event ID
  - Ticket ID
  - Full name
  - Email
  - Company
  - Timestamp

**Example QR Data:**

```json
{
  "attendeeId": "1-1-1640995200000",
  "eventId": 1,
  "ticketId": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "company": "Example Corp",
  "timestamp": "2023-01-01T00:00:00.000Z"
}
```

## Usage Instructions

### For Event Organizers

1. Navigate to the Events page (`Events/index.html`)
2. Find the event you want to share
3. Click the "Share" button on the event card
4. The shareable link is automatically copied to your clipboard
5. Share the link via email, social media, or any other channel

### For Event Attendees

1. Open the shared event link
2. Browse available tickets
3. Click "Register Now" on desired ticket
4. Fill out the registration form
5. Submit the form
6. PDF badge will automatically download
7. Save the PDF badge for event entry

## Testing

Use `test-api.html` to test the API endpoints:

- Test public event retrieval
- Test ticket listing
- Test attendee registration
- View API responses and debug issues

## File Structure

```
Frontend/Events/
├── share.html          # Public event sharing page
├── index.html          # Events list with share buttons
├── test-api.html       # API testing page
└── ...

backend/
├── controllers/
│   ├── event.Controller.ts     # Added getPublicEvent
│   ├── ticket.controller.ts    # Added getEventTickets
│   └── attendee.controller.ts  # Added registerAttendee
├── routes/
│   ├── event.routes.ts         # Added public route
│   ├── ticket.routes.ts        # Added event tickets route
│   └── attendee.route.ts       # Added register route
└── services/
    ├── ticket.service.ts       # Added getTicketsByEventId
    └── attendee.service.ts     # QR code support
```

## Security Considerations

- Public endpoints don't require authentication
- Only essential event data is exposed publicly
- QR codes contain minimal necessary information
- CORS is configured for cross-origin requests

## Dependencies Added

- **Frontend**:
  - qrcode.js (QR code generation)
  - jsPDF (PDF generation)
- **Backend**: No new dependencies required

## Browser Compatibility

- Modern browsers supporting ES6+
- Canvas API for QR code generation
- Clipboard API for share functionality (with fallback)
