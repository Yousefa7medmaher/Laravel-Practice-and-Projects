# 🚀 Course Detail Page API Endpoints Documentation

## 📋 Overview

This document provides comprehensive documentation for the API endpoints that support the Course Detail Page functionality. These endpoints are designed to work seamlessly with the existing Phase 1 implementation and provide enhanced course content access for enrolled students.

## 🔐 Authentication

All protected endpoints require JWT token-based authentication:

```
Authorization: Bearer {your_jwt_token}
```

**Token Management:**
- Tokens are stored in `localStorage` on the frontend
- Automatic redirect to login page if token is invalid or expired
- Consistent authentication patterns across all endpoints

## 📚 API Endpoints

### 1. Enhanced Course Details API

**Endpoint:** `GET /api/courses/{id}`

**Description:** Returns comprehensive course information with related content counts, enrollment statistics, and user-specific progress.

**Authentication:** Required (JWT Token)

**Authorization:** 
- Students: Must be enrolled in the course
- Instructors/Managers: Can access any course

**Parameters:**
- `id` (path parameter): Course ID

**Response Format:**
```json
{
    "status": "success",
    "data": {
        "id": 1,
        "title": "Advanced Web Programming",
        "code": "CS301",
        "description": "Comprehensive web development course...",
        "instructor": {
            "id": 2,
            "name": "Dr. John Smith",
            "email": "john.smith@university.edu"
        },
        "credit_hours": 3,
        "status": "active",
        "content_counts": {
            "lectures": 12,
            "assignments": 6,
            "quizzes": 4,
            "labs": 8,
            "materials": 6
        },
        "enrollment_stats": {
            "total_students": 45,
            "active_students": 42
        },
        "user_progress": {
            "percentage": 65,
            "completed_items": 20,
            "total_items": 30,
            "lectures_completed": 8,
            "assignments_completed": 4,
            "quizzes_completed": 3,
            "labs_completed": 5
        },
        "lectures": [...],
        "assignments": [...],
        "quizzes": [...],
        "labs": [...],
        "materials": [...]
    }
}
```

**Error Responses:**
- `401 Unauthorized`: Authentication required
- `403 Forbidden`: Not enrolled in course (for students)
- `404 Not Found`: Course not found

---

### 2. Course Materials API

**Endpoint:** `GET /api/courses/{courseId}/materials`

**Description:** Returns downloadable course materials including PDFs, documents, and resources for enrolled students.

**Authentication:** Required (JWT Token)

**Authorization:** 
- Students: Must be enrolled in the course
- Instructors/Managers: Can access any course materials

**Parameters:**
- `courseId` (path parameter): Course ID

**Response Format:**
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "title": "Course Syllabus",
            "description": "Complete course syllabus with learning objectives and schedule",
            "file_name": "syllabus.pdf",
            "file_type": "pdf",
            "file_size": 512000,
            "formatted_file_size": "500.0 KB",
            "file_extension": "pdf",
            "download_url": "http://127.0.0.1:8001/storage/materials/syllabus.pdf",
            "is_downloadable": true,
            "uploaded_at": "2024-05-22 10:30:00",
            "uploaded_date": "May 22, 2024"
        },
        {
            "id": 2,
            "title": "Lecture Slides - Week 1",
            "description": "Introduction to the course and basic concepts",
            "file_name": "week1_slides.pptx",
            "file_type": "pptx",
            "file_size": 2097152,
            "formatted_file_size": "2.0 MB",
            "file_extension": "pptx",
            "download_url": "http://127.0.0.1:8001/storage/materials/week1_slides.pptx",
            "is_downloadable": true,
            "uploaded_at": "2024-05-22 11:15:00",
            "uploaded_date": "May 22, 2024"
        }
    ],
    "message": "Course materials retrieved successfully"
}
```

**Features:**
- **File Information**: Complete file metadata including size, type, and extension
- **Download URLs**: Direct download links for materials
- **Formatted Sizes**: Human-readable file sizes (KB, MB, GB)
- **Upload Tracking**: Upload dates and timestamps
- **Ordering**: Materials ordered by custom order field and creation date

**Error Responses:**
- `401 Unauthorized`: Authentication required
- `403 Forbidden`: Not enrolled in course (for students)
- `404 Not Found`: Course not found or materials could not be retrieved

---

### 3. Course Labs API

**Endpoint:** `GET /api/courses/{courseId}/labs`

**Description:** Returns laboratory exercises and practical assignments with due dates, status tracking, and submission requirements.

**Authentication:** Required (JWT Token)

**Authorization:** 
- Students: Must be enrolled in the course
- Instructors/Managers: Can access any course labs

**Parameters:**
- `courseId` (path parameter): Course ID

**Response Format:**
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "title": "JavaScript Fundamentals Lab",
            "description": "Practice basic JavaScript concepts and syntax",
            "instructions": "Complete the exercises in the provided template...",
            "due_date": "2024-06-15 23:59:59",
            "formatted_due_date": "Jun 15, 2024",
            "is_overdue": false,
            "days_until_due": 15,
            "created_at": "2024-05-22 09:00:00",
            "status": "available"
        },
        {
            "id": 2,
            "title": "DOM Manipulation Lab",
            "description": "Learn to manipulate HTML elements using JavaScript",
            "instructions": "Build an interactive web page using DOM methods...",
            "due_date": "2024-06-22 23:59:59",
            "formatted_due_date": "Jun 22, 2024",
            "is_overdue": false,
            "days_until_due": 22,
            "created_at": "2024-05-22 09:30:00",
            "status": "available"
        }
    ],
    "message": "Course labs retrieved successfully"
}
```

**Features:**
- **Due Date Tracking**: Formatted due dates and countdown timers
- **Status Management**: Automatic status calculation (available, overdue, completed)
- **Instructions**: Detailed lab instructions and requirements
- **Progress Tracking**: User-specific completion status
- **Deadline Alerts**: Visual indicators for approaching deadlines

**Status Values:**
- `available`: Lab is available for completion
- `overdue`: Lab due date has passed
- `not_started`: Lab not yet started by student
- `in_progress`: Lab currently being worked on
- `completed`: Lab successfully completed

**Error Responses:**
- `401 Unauthorized`: Authentication required
- `403 Forbidden`: Not enrolled in course (for students)
- `404 Not Found`: Course not found or labs could not be retrieved

---

## 🔧 Integration Points

### Frontend Integration

**Course Detail Page JavaScript:**
```javascript
// Load course materials
async function loadMaterialsContent() {
    try {
        const result = await apiCall(`/courses/${courseId}/materials`);
        const materialsHtml = generateMaterialsHtml(result?.data?.data || []);
        document.getElementById('materials-list').innerHTML = materialsHtml;
    } catch (error) {
        console.error('Error loading materials:', error);
    }
}

// Load course labs
async function loadLabsContent() {
    try {
        const result = await apiCall(`/courses/${courseId}/labs`);
        const labsHtml = generateLabsHtml(result?.data?.data || []);
        document.getElementById('labs-list').innerHTML = labsHtml;
    } catch (error) {
        console.error('Error loading labs:', error);
    }
}
```

### Existing Controller Integration

**EnrollmentController Integration:**
- Uses existing enrollment verification methods
- Maintains consistency with current enrollment patterns
- Supports existing JWT authentication flow

**StudentDashboardController Patterns:**
- Follows same API response structure
- Uses identical error handling patterns
- Maintains consistent authentication requirements

### Database Relationships

**Course Model Relationships:**
```php
// Enhanced Course model with new relationships
public function materials(): HasMany
{
    return $this->hasMany(Material::class);
}

public function labs(): HasMany
{
    return $this->hasMany(Lab::class);
}
```

## 🛡️ Security Features

### Authentication & Authorization
- **JWT Token Validation**: All endpoints require valid authentication
- **Enrollment Verification**: Students can only access enrolled course content
- **Role-based Access**: Different access levels for students, instructors, and managers
- **Input Validation**: Comprehensive validation for all parameters

### Data Protection
- **Sanitized Responses**: All output properly sanitized
- **File Access Control**: Secure file download URLs with proper permissions
- **Error Handling**: No sensitive information leaked in error messages
- **Rate Limiting**: Built-in Laravel rate limiting for API endpoints

## 📊 Performance Optimizations

### Database Optimization
- **Eager Loading**: Related models loaded efficiently with `with()` clauses
- **Indexed Queries**: Proper database indexing for fast lookups
- **Optimized Counting**: Efficient count queries for statistics
- **Selective Loading**: Only necessary data loaded for each endpoint

### Caching Strategy
- **Response Caching**: API responses cached for improved performance
- **File Metadata Caching**: File information cached to reduce disk I/O
- **Progress Calculation**: User progress cached and updated incrementally

## 🧪 Testing

### API Testing Examples

**Test Course Details API:**
```bash
# Using curl (replace with actual token)
curl -X GET "http://127.0.0.1:8001/api/courses/1" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json"
```

**Test Materials API:**
```bash
curl -X GET "http://127.0.0.1:8001/api/courses/1/materials" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json"
```

**Test Labs API:**
```bash
curl -X GET "http://127.0.0.1:8001/api/courses/1/labs" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json"
```

### Frontend Testing
- **Course Detail Page**: `http://127.0.0.1:8001/courses/1`
- **Tab Navigation**: Test all 6 tabs (Overview, Lectures, Assignments, Quizzes, Labs, Materials)
- **Authentication**: Test with valid and invalid tokens
- **Enrollment**: Test with enrolled and non-enrolled students

## 🎯 Conclusion

These API endpoints provide comprehensive support for the Course Detail Page functionality, offering:

- **🔐 Secure Access**: Proper authentication and authorization
- **📊 Rich Data**: Comprehensive course information and statistics
- **📱 Frontend Ready**: Optimized for modern web applications
- **🚀 Performance**: Efficient database queries and caching
- **🛡️ Security**: Robust security measures and validation
- **🔧 Integration**: Seamless integration with existing systems

The APIs are production-ready and fully integrated with the existing Phase 1 implementation, providing students with complete access to their course content through a beautiful, modern interface.
