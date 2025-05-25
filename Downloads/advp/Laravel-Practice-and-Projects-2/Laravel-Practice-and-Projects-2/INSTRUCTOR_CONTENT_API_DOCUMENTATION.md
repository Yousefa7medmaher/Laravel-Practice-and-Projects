# 📚 Instructor Content Management API Documentation

## 🚀 Overview

This comprehensive API allows instructors to manage all their educational content (lectures, assignments, quizzes, labs, and materials) across all assigned courses. The API provides full CRUD operations, search functionality, bulk actions, and content statistics.

## 🔐 Authentication

All endpoints require authentication using Laravel Sanctum tokens:

```
Authorization: Bearer {your-token}
Accept: application/json
Content-Type: application/json
```

## 📋 Base URL

```
http://your-domain.com/api/instructor/content
```

## 🎯 Core Endpoints

### 1. Get All Content
**GET** `/instructor/content`

Returns all content for the instructor across all assigned courses.

**Response:**
```json
{
  "status": "success",
  "data": {
    "lectures": [...],
    "assignments": [...],
    "quizzes": [...],
    "labs": [...],
    "materials": [...],
    "summary": {
      "total_content": 15,
      "by_type": {
        "lectures": 5,
        "assignments": 4,
        "quizzes": 3,
        "labs": 2,
        "materials": 1
      },
      "by_course": [...]
    }
  },
  "message": "Content retrieved successfully"
}
```

### 2. Get Content by Type
**GET** `/instructor/content/{type}`

Valid types: `lectures`, `assignments`, `quizzes`, `labs`, `materials`

**Response:**
```json
{
  "status": "success",
  "data": [...],
  "type": "lectures",
  "count": 5,
  "message": "Lectures retrieved successfully"
}
```

### 3. Create Content
**POST** `/instructor/content/{type}`

#### Lecture Creation
```json
{
  "course_id": 1,
  "title": "Introduction to Programming",
  "description": "Basic programming concepts",
  "content": "Lecture content here...",
  "objectives": "Learning objectives",
  "video_url": "https://youtube.com/watch?v=...",
  "duration": 60,
  "scheduled_date": "2024-01-15 10:00:00",
  "order": 1,
  "is_published": true,
  "is_visible": true
}
```

#### Assignment Creation
```json
{
  "course_id": 1,
  "title": "Programming Assignment 1",
  "description": "Create a simple calculator",
  "instructions": "Follow the requirements...",
  "due_date": "2024-01-30 23:59:59",
  "max_score": 100,
  "allow_late_submission": false,
  "is_visible": true
}
```

#### Quiz Creation
```json
{
  "course_id": 1,
  "title": "Midterm Quiz",
  "description": "Covers chapters 1-5",
  "instructions": "Answer all questions...",
  "start_time": "2024-01-20 09:00:00",
  "end_time": "2024-01-20 11:00:00",
  "duration_minutes": 90,
  "max_score": 100,
  "max_attempts": 2,
  "is_published": false
}
```

#### Lab Creation
```json
{
  "course_id": 1,
  "title": "Database Lab",
  "description": "Hands-on database practice",
  "instructions": "Follow lab procedures...",
  "due_date": "2024-02-15 23:59:59",
  "max_score": 100,
  "equipment": "Computer, MySQL Workbench",
  "duration": 120,
  "allow_late_submission": true,
  "is_visible": true
}
```

#### Material Creation (Multipart Form)
```
Content-Type: multipart/form-data

course_id: 1
title: "Course Syllabus"
description: "Complete course syllabus"
file: [file upload]
is_downloadable: true
is_visible: true
```

### 4. Update Content
**PUT** `/instructor/content/{type}/{id}`

Same structure as creation, but all fields are optional (except validation requirements).

### 5. Delete Content
**DELETE** `/instructor/content/{type}/{id}`

**Response:**
```json
{
  "status": "success",
  "message": "Lecture deleted successfully"
}
```

## 🔍 Search & Statistics

### Search Content
**GET** `/instructor/content/search?q={query}&type={type}&course_id={id}`

**Parameters:**
- `q` (required): Search query (minimum 2 characters)
- `type` (optional): Content type filter (`all`, `lectures`, `assignments`, etc.)
- `course_id` (optional): Filter by specific course

**Response:**
```json
{
  "status": "success",
  "data": {
    "lectures": [...],
    "assignments": [...],
    "quizzes": [...],
    "labs": [...],
    "materials": [...]
  },
  "query": "programming",
  "type": "all",
  "total_results": 12,
  "message": "Search completed successfully"
}
```

### Get Statistics
**GET** `/instructor/content/statistics`

**Response:**
```json
{
  "status": "success",
  "data": {
    "total_courses": 3,
    "content_by_type": {
      "lectures": 15,
      "assignments": 10,
      "quizzes": 8,
      "labs": 5,
      "materials": 3
    },
    "recent_activity": [...],
    "content_by_course": [...],
    "published_vs_draft": {
      "lectures": {
        "published": 12,
        "draft": 3
      },
      "assignments": {
        "visible": 8,
        "hidden": 2
      }
    }
  },
  "message": "Statistics retrieved successfully"
}
```

## ⚡ Bulk Operations

### Bulk Actions
**POST** `/instructor/content/bulk-action`

**Request:**
```json
{
  "action": "publish|unpublish|delete|duplicate",
  "type": "lectures|assignments|quizzes|labs|materials",
  "ids": [1, 2, 3, 4]
}
```

**Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "success": true,
      "message": "Success"
    },
    {
      "id": 2,
      "success": false,
      "message": "Access denied"
    }
  ],
  "message": "Bulk publish completed successfully"
}
```

## 🔒 Security Features

1. **Course Access Control**: Instructors can only manage content for courses they're assigned to
2. **Role-based Authentication**: Only instructors can access these endpoints
3. **Validation**: Comprehensive input validation for all content types
4. **File Security**: Secure file upload and storage for materials
5. **Audit Trail**: Tracks who created/updated content

## 📝 Content Type Specific Features

### Lectures
- Video URL integration
- Content ordering
- Publishing status
- Scheduled dates

### Assignments
- Due date management
- Late submission control
- Submission tracking
- File attachments

### Quizzes
- Time-based availability
- Attempt limits
- Duration control
- Publishing workflow

### Labs
- Equipment requirements
- Duration tracking
- Submission management
- Practical instructions

### Materials
- File upload/download
- Multiple file types
- Size limitations (10MB)
- Download permissions

## 🚨 Error Handling

All endpoints return consistent error responses:

```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "title": ["The title field is required."],
    "due_date": ["The due date must be a date after now."]
  }
}
```

Common HTTP status codes:
- `200`: Success
- `201`: Created
- `400`: Bad Request (validation errors)
- `403`: Forbidden (access denied)
- `404`: Not Found
- `500`: Server Error

## 🎯 Usage Examples

### JavaScript/Frontend Integration

```javascript
// Get all content
const response = await fetch('/api/instructor/content', {
  headers: {
    'Authorization': `Bearer ${token}`,
    'Accept': 'application/json'
  }
});

// Create a lecture
const lectureData = {
  course_id: 1,
  title: 'New Lecture',
  description: 'Lecture description',
  is_published: true
};

const createResponse = await fetch('/api/instructor/content/lectures', {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(lectureData)
});

// Search content
const searchResponse = await fetch('/api/instructor/content/search?q=programming&type=lectures', {
  headers: {
    'Authorization': `Bearer ${token}`,
    'Accept': 'application/json'
  }
});
```

## 🎉 Features Summary

✅ **Complete CRUD Operations** for all content types  
✅ **Bulk Actions** (publish, unpublish, delete, duplicate)  
✅ **Advanced Search** with filters  
✅ **Content Statistics** and analytics  
✅ **File Upload** for materials  
✅ **Security & Access Control**  
✅ **Comprehensive Validation**  
✅ **Audit Trail** tracking  
✅ **Course-based Organization**  
✅ **RESTful API Design**  

This API provides everything an instructor needs to manage their educational content efficiently and securely! 🚀
