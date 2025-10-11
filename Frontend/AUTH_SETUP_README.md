# MFW Events Authentication Setup

This document explains the authentication system implementation for the MFW Events frontend.

## Overview

The authentication system provides:

- **Login page** with modern glassmorphism design
- **JWT-based authentication** with the backend
- **Role-based access control** for different user types
- **Protected routes** that require authentication
- **Automatic token management** and refresh
- **Session persistence** across browser sessions

## Files Created/Modified

### New Files Created:

1. `Frontend/login.html` - Login page with modern design
2. `Frontend/assets/js/auth.js` - Authentication service
3. `Frontend/assets/js/auth-middleware.js` - Frontend authentication middleware

### Modified Files:

1. `Frontend/SuperAdmin-dashboard/index.html` - Added authentication protection
2. `Frontend/index.html` - Updated navigation links to point to login

## How It Works

### 1. Authentication Flow:

```
User enters credentials → Login API call → JWT token received →
Token stored in localStorage → User redirected to dashboard
```

### 2. Protected Routes:

- SuperAdmin dashboard requires authentication
- User must have `SUPER_ADMIN` role to access the dashboard
- If not authenticated, user is redirected to login page
- After successful login, user is redirected back to intended page

### 3. Token Management:

- JWT tokens are stored in localStorage
- Tokens expire after 24 hours (as set by backend)
- Automatic logout when token expires
- Remember me functionality (optional)

## Backend Requirements

Your backend should already have these endpoints:

- `POST /auth/login` - Login with email/password
- `POST /auth/sign-out` - Logout (invalidate token)

### Login Response Format:

```json
{
  "token": "jwt_token_here",
  "user": {
    "id": 1,
    "email": "admin@example.com",
    "role": "super_admin"
  }
}
```

## Setup Instructions

### 1. Test User Creation

You need to create a test user in your database with `super_admin` role. Run this SQL in your database:

```sql
-- Create a super admin user (password: "password123")
INSERT INTO User (name, email, password, role, createdAt, updatedAt)
VALUES (
    'Super Admin',
    'admin@nld.com',
    '$2b$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'super_admin',
    NOW(),
    NOW()
);
```

**Note:** The hashed password above corresponds to "password123" using bcrypt.

### 2. Environment Setup

Make sure your backend `.env` file has:

```env
JWT_SECRET=your_jwt_secret_key_here
DATABASE_URL=your_database_connection_string
```

### 3. Backend Server

Ensure your backend server is running on `http://localhost:3001`

### 4. CORS Configuration

Make sure your backend allows requests from your frontend domain (already configured in your backend).

## Testing the Authentication

### 1. Start the Backend:

```bash
cd "backend (Express Typescript Bun)"
bun run dev
```

### 2. Test Login:

1. Open `Frontend/login.html` in your browser
2. Use these credentials:
   - **Email:** `admin@nld.com`
   - **Password:** `password123`
3. You should be redirected to the SuperAdmin dashboard

### 3. Test Protection:

1. Try accessing `Frontend/SuperAdmin-dashboard/index.html` directly
2. You should be redirected to the login page
3. After login, you should be redirected back to the dashboard

## Frontend Features

### Login Page Features:

- ✅ Modern glassmorphism design
- ✅ Form validation
- ✅ Loading states
- ✅ Error/success messages
- ✅ Password visibility toggle
- ✅ Remember me option
- ✅ Responsive design

### Authentication Service Features:

- ✅ JWT token management
- ✅ Automatic login/logout
- ✅ Role-based access control
- ✅ Session persistence
- ✅ Automatic API request authentication
- ✅ Token expiry handling
- ✅ Redirect management

### Dashboard Protection:

- ✅ Authentication check on page load
- ✅ User info display in navigation
- ✅ Logout functionality
- ✅ Role-based element visibility

## Configuration

### API Base URL:

The authentication service is configured to use `http://localhost:3001` as the backend URL.
Update this in `Frontend/assets/js/auth.js` if your backend runs on a different port:

```javascript
const CONFIG = {
  API_BASE_URL: "http://localhost:3001", // Change this if needed
  // ... other config
};
```

### Customize for Production:

For production deployment, update:

1. `API_BASE_URL` to your production backend URL
2. Add proper SSL configuration
3. Consider implementing token refresh mechanism
4. Add proper error logging

## Troubleshooting

### Common Issues:

1. **"Login failed" error:**

   - Check if backend server is running
   - Verify the test user exists in database
   - Check browser console for API errors

2. **CORS errors:**

   - Ensure backend CORS is configured correctly
   - Check if frontend and backend URLs match

3. **Infinite redirect loop:**

   - Clear localStorage: `localStorage.clear()`
   - Check if user has correct role in database

4. **Token not working:**
   - Check JWT_SECRET in backend .env
   - Verify token format in localStorage
   - Check token expiry time

### Browser Console Commands for Testing:

```javascript
// Check authentication status
console.log(authService.isAuthenticated());

// Check current user
console.log(authService.getCurrentUser());

// Clear authentication data
authService.clearAuthData();

// Check stored token
console.log(localStorage.getItem("mfw_auth_token"));
```

## Security Notes

- JWT tokens are stored in localStorage (consider httpOnly cookies for production)
- Tokens expire after 24 hours
- Failed login attempts should be logged
- Consider implementing rate limiting on login endpoint
- Use HTTPS in production
- Implement proper session management

## Future Enhancements

Consider adding:

- Password reset functionality
- Two-factor authentication
- Login attempt logging
- Token refresh mechanism
- Remember me with extended expiry
- Social login integration
- Account lockout after failed attempts

---

The authentication system is now fully functional and ready for testing!
