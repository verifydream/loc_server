# Implementation Plan

- [x] 1. Set up Laravel project structure and database configuration





  - Create new Laravel 10.x project
  - Configure database connection in .env file
  - Set up MySQL database
  - _Requirements: 7.1_

- [x] 2. Create database migrations and models





  - [x] 2.1 Create locations table migration


    - Write migration for locations table with fields: id, location_code, location_name, online_url, timestamps
    - Add unique constraint on location_code
    - Add indexes for location_code
    - _Requirements: 7.1, 7.2, 3.2_
  
  - [x] 2.2 Create users table migration


    - Write migration for users table with fields: id, email, location_id, status, timestamps
    - Add foreign key constraint to locations table with ON DELETE RESTRICT
    - Add unique constraint on email
    - Add indexes for email, location_id, and status
    - _Requirements: 7.1, 7.2, 7.3, 1.1_
  
  - [x] 2.3 Create admins table migration


    - Write migration for admins table with fields: id, name, email, password, remember_token, timestamps
    - Add unique constraint on email
    - Add index for email
    - _Requirements: 7.1, 4.2_
  
  - [x] 2.4 Create Eloquent models


    - Create Location model with fillable fields and users relationship
    - Create User model with fillable fields, location relationship, email mutator (lowercase), and active scope
    - Create Admin model extending Authenticatable with fillable fields and hidden password
    - _Requirements: 7.2, 7.5_

- [x] 3. Create database seeders for initial data




  - [x] 3.1 Create LocationSeeder


    - Write seeder to create 8 default locations: Surabaya (sby, sby.web.com), Jakarta (jkt, jkt.web.com), Belawan (blw, blw.web.com), Semarang (smr, smr.web.com), BNS (bns, bns.web.com), Java (java, java.web.com), Test (test, test.web.com), Dev (dev, dev.web.com)
    - Implement idempotent logic to check existing data before inserting
    - _Requirements: 8.1, 8.3_
  

  - [x] 3.2 Create AdminSeeder

    - Write seeder to create default admin user with predefined email and password
    - Implement idempotent logic to check existing admin before inserting
    - _Requirements: 8.2, 8.3_

- [x] 4. Implement repository layer for data access






  - [x] 4.1 Create UserRepository

    - Implement findByEmail method to retrieve user with location relationship
    - Implement search method with filters for email, location_id, and status
    - Implement CRUD methods: create, update, delete
    - _Requirements: 1.1, 1.2, 6.1, 6.2, 6.3_
  

  - [x] 4.2 Create LocationRepository

    - Implement methods to get all locations
    - Implement CRUD methods: create, update, delete
    - Implement hasUsers method to check if location is assigned to any users
    - _Requirements: 3.1, 3.2, 3.4, 3.6_

- [x] 5. Implement service layer for business logic






  - [x] 5.1 Create LocationService

    - Implement checkUserLocation method that takes email and returns location data
    - Implement validateUserAccess method to check if user is active
    - Handle cases: email not found, user inactive, user active
    - _Requirements: 1.1, 1.2, 1.3, 1.5_
  

  - [x] 5.2 Create UserService

    - Implement createUser method with validation
    - Implement updateUser method with validation
    - Implement deleteUser method
    - Implement searchUsers method with email, location, and status filters
    - _Requirements: 2.5, 2.6, 2.7, 2.8, 2.9, 6.1, 6.2, 6.3_
  
  - [x] 5.3 Create LocationManagementService


    - Implement createLocation method with validation
    - Implement updateLocation method with validation
    - Implement deleteLocation method with usage validation
    - Implement canDeleteLocation method to check if location has users
    - _Requirements: 3.2, 3.3, 3.4, 3.5, 3.6_

- [x] 6. Create API endpoint for mobile app






  - [x] 6.1 Create API routes

    - Define POST route /api/check-location
    - Apply rate limiting middleware (60 requests per minute)
    - _Requirements: 1.1_
  
  - [x] 6.2 Create LocationController for API


    - Implement checkLocation method to handle POST /api/check-location
    - Validate email parameter (required, email format)
    - Call LocationService to get user location data
    - Return JSON response with success/error format
    - Handle errors: email not found (404), user inactive (403), validation error (422)
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5, 5.2, 5.3, 5.5_
  
  - [ ]*  6.3 Write API integration tests
    - Test successful location lookup with valid active user
    - Test email not found scenario (404 response)
    - Test inactive user scenario (403 response)
    - Test missing email parameter (422 response)
    - Test invalid email format (422 response)
    - Test response time < 500ms
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5_

- [x] 7. Implement admin authentication







  - [x] 7.1 Configure authentication guard for admins
    - Update config/auth.php to add admin guard using session driver
    - Configure admin provider using admins table
    - _Requirements: 4.1, 4.2_

  
  - [x] 7.2 Create authentication routes

    - Define GET /admin/login route for login form
    - Define POST /admin/login route for login submission
    - Define POST /admin/logout route for logout
    - _Requirements: 4.1, 4.3_
  

  - [x] 7.3 Create AdminAuthController

    - Implement showLoginForm method to display login page
    - Implement login method to authenticate admin with email and password
    - Implement logout method to destroy session and redirect
    - Add session timeout handling (2 hours)
    - _Requirements: 4.1, 4.2, 4.3, 4.4_
  

  - [x] 7.4 Create authentication middleware

    - Create middleware to check if admin is authenticated
    - Redirect to login page if not authenticated
    - Handle session expiration with appropriate message
    - _Requirements: 4.1, 4.4_

- [x] 8. Create admin dashboard views





  - [x] 8.1 Create login page view


    - Create Blade template for login form with email and password fields
    - Add CSRF token
    - Display validation errors
    - _Requirements: 2.1_
  
  - [x] 8.2 Create dashboard layout


    - Create master layout with navigation menu
    - Add links to Users and Locations management
    - Add logout button
    - Include Bootstrap 5 CSS
    - _Requirements: 2.2, 2.3_

- [x] 9. Implement user management interface






  - [x] 9.1 Create user management routes

    - Define resource routes for /admin/users (index, create, store, edit, update, destroy)
    - Apply authentication middleware
    - _Requirements: 2.3, 2.4, 2.5, 2.6, 2.7, 2.8_
  
  - [x] 9.2 Create UserController for dashboard


    - Implement index method to display users table with search and filters
    - Implement create method to show add user form
    - Implement store method to save new user with validation
    - Implement edit method to show edit user form
    - Implement update method to save user changes with validation
    - Implement destroy method to delete user with confirmation
    - _Requirements: 2.3, 2.4, 2.5, 2.6, 2.7, 2.8, 2.9_
  
  - [x] 9.3 Create user list view


    - Create Blade template for users table
    - Display columns: email, location_name, online_url, status, action buttons
    - Add search box for email filtering
    - Add filter dropdowns for location and status
    - Add "Add User" button
    - Add pagination
    - _Requirements: 2.3, 6.1, 6.2, 6.3, 6.4_
  
  - [x] 9.4 Create user form views


    - Create Blade template for add/edit user form
    - Add fields: email (text input), location (dropdown), status (active/inactive radio)
    - Auto-fill online_url based on selected location using JavaScript
    - Display validation errors inline
    - Add CSRF token
    - _Requirements: 2.4, 2.5, 2.6, 2.9_

- [x] 10. Implement location management interface






  - [x] 10.1 Create location management routes

    - Define resource routes for /admin/locations (index, create, store, edit, update, destroy)
    - Apply authentication middleware
    - _Requirements: 3.1, 3.2, 3.3, 3.4_
  

  - [x] 10.2 Create LocationManagementController for dashboard

    - Implement index method to display locations table
    - Implement create method to show add location form
    - Implement store method to save new location with validation
    - Implement edit method to show edit location form
    - Implement update method to save location changes with validation
    - Implement destroy method to delete location with usage validation
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6_
  

  - [x] 10.3 Create location list view

    - Create Blade template for locations table
    - Display columns: location_code, location_name, online_url, action buttons
    - Add "Add Location" button
    - _Requirements: 3.1_
  

  - [x] 10.4 Create location form views

    - Create Blade template for add/edit location form
    - Add fields: location_code, location_name, online_url
    - Display validation errors inline
    - Add CSRF token
    - _Requirements: 3.2, 3.3, 3.4_

- [x] 11. Implement validation and error handling




  - [x] 11.1 Create form request validators


    - Create CheckLocationRequest for API with email validation rules
    - Create StoreUserRequest with validation rules: email (required, email, unique), location_id (required, exists), status (required, in:active,inactive)
    - Create UpdateUserRequest with same rules but unique email except current user
    - Create StoreLocationRequest with validation rules: location_code (required, unique, max:10), location_name (required, max:100), online_url (required, url)
    - Create UpdateLocationRequest with same rules but unique location_code except current location
    - _Requirements: 5.1, 5.2, 5.3_
  
  - [x] 11.2 Implement custom error messages


    - Define custom validation messages in language files
    - Implement API error response format: {success, message, errors}
    - _Requirements: 5.1, 5.2, 5.3, 5.5_
  
  - [x] 11.3 Add global exception handling


    - Update Handler.php to catch database errors and return generic message
    - Log detailed error information to file
    - Return appropriate HTTP status codes
    - _Requirements: 5.4, 5.5_

- [x] 12. Implement search and filter functionality




  - [x] 12.1 Add search functionality to user list


    - Implement real-time search using JavaScript to filter table by email
    - Update UserController index method to handle search query parameter
    - _Requirements: 6.1, 6.4_
  
  - [x] 12.2 Add filter functionality to user list


    - Add location filter dropdown populated from locations table
    - Add status filter dropdown (active/inactive/all)
    - Update UserController index method to handle filter parameters
    - Display "No users found" message when no results
    - _Requirements: 6.2, 6.3, 6.4_

- [x] 13. Add JavaScript enhancements for dashboard





  - [x] 13.1 Implement auto-fill online_url on user form


    - Add JavaScript to populate online_url field when location is selected
    - Fetch location data via AJAX or embed in page data
    - _Requirements: 2.4_
  


  - [x] 13.2 Add delete confirmation modals


    - Create confirmation modal for user deletion
    - Create confirmation modal for location deletion
    - Show appropriate error message if location cannot be deleted
    - _Requirements: 2.8, 3.4, 3.6_

  
  - [x] 13.3 Add flash message notifications

    - Implement toast/alert notifications for success messages
    - Implement toast/alert notifications for error messages
    - Auto-dismiss after 5 seconds
    - _Requirements: 2.5, 2.7, 3.3, 3.4_

- [x] 14. Configure application settings and optimization





  - [x] 14.1 Configure rate limiting


    - Set up rate limiter for API endpoint (60 requests per minute per IP)
    - Add rate limit exceeded response
    - _Requirements: 1.1_
  
  - [x] 14.2 Add database query optimization


    - Ensure eager loading of relationships to prevent N+1 queries
    - Verify indexes are created on frequently queried columns
    - _Requirements: 1.5, 7.2_
  
  - [x] 14.3 Configure logging


    - Set up logging for API requests with email and response time
    - Set up logging for authentication attempts
    - Set up logging for database errors
    - _Requirements: 5.4_

- [x] 15. Create environment configuration and deployment setup





  - [x] 15.1 Create .env.example file
    - Document all required environment variables
    - Include database configuration
    - Include app configuration (APP_ENV, APP_DEBUG)
    - _Requirements: 7.1_

  
  - [x] 15.2 Create deployment documentation


    - Document migration steps
    - Document seeder execution steps
    - Document cache clearing and optimization commands
    - _Requirements: 8.1, 8.2_
