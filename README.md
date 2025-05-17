# 🎓 Education Platform API

A comprehensive education platform backend built with Laravel, providing authentication, course management, content delivery, and student enrollment functionality through RESTful API endpoints.

## 🧰 Tech Stack

- **Backend Framework**: Laravel 10+
- **Authentication**: Laravel Sanctum
- **Database**: MySQL / SQLite (configurable via `.env`)
- **API Testing**: Postman / Thunder Client recommended
- **File Storage**: Laravel's filesystem for course materials and user profiles

## 👥 User Roles

The platform supports three user roles, each with different permissions:

1. **Student**
   - Can enroll in courses
   - Can view course content (lectures, assignments, quizzes, labs)
   - Can view their enrolled courses

2. **Instructor**
   - Can create and manage their own courses
   - Can add/edit/delete course content
   - Can view students enrolled in their courses
   - Can update student enrollment status

3. **Manager**
   - Has all instructor permissions
   - Can manage all courses, including those created by other instructors
   - Can manage all users

## 📦 API Endpoints

### Authentication

#### 1. POST `/api/register` — Register a New User
Registers a new user and returns an authentication token.

**Request Body (JSON):**
```json
{
  "name": "Yousef",
  "email": "yousef@example.com",
  "password": "12345678",
  "password_confirmation": "12345678"
}
```

**Success Response:**
```json
{
  "user": {
    "id": 1,
    "name": "Yousef",
    "email": "yousef@example.com",
    "role": "student"
  },
  "access_token": "your_token_here",
  "token_type": "Bearer"
}
```

#### 2. POST `/api/login` — Log In
Authenticates a user and returns an access token.

**Request Body:**
```json
{
  "email": "yousef@example.com",
  "password": "12345678"
}
```

**Success Response:**
```json
{
  "user": {
    "id": 1,
    "name": "Yousef",
    "email": "yousef@example.com",
    "role": "student"
  },
  "access_token": "your_token_here",
  "token_type": "Bearer"
}
```

#### 3. POST `/api/logout` — Log Out (Requires Token)
Revokes the user's current token.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response:**
```json
{
  "message": "Logged out"
}
```

#### 4. GET `/api/profile` — Get User Profile
Returns the authenticated user's profile information.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response:**
```json
{
  "user": {
    "id": 1,
    "name": "Yousef",
    "email": "yousef@example.com",
    "role": "student",
    "imgProfilePath": "storage/profile_images/1234567890_profile.jpg",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-01T00:00:00.000000Z"
  }
}
```

#### 5. POST `/api/profile/update` — Update User Profile
Updates the authenticated user's profile information.

**Headers:**
```
Authorization: Bearer your_token_here
Content-Type: multipart/form-data
```

**Request Body (FormData):**
```
name: "New Name"
email: "new.email@example.com"
password: "newpassword"
password_confirmation: "newpassword"
imgProfile: [file upload]
```

**Success Response:**
```json
{
  "status": "success",
  "message": "Profile updated successfully. Updated fields: name, email, profile image",
  "user": {
    "id": 1,
    "name": "New Name",
    "email": "new.email@example.com",
    "role": "student",
    "imgProfilePath": "storage/profile_images/1234567890_profile.jpg",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-01T12:00:00.000000Z"
  }
}
```

### Course Management

#### 1. GET `/api/courses` — List All Courses
Returns a list of all available courses.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response:**
```json
{
  "courses": [
    {
      "id": 1,
      "title": "Introduction to Programming",
      "description": "Learn the basics of programming",
      "instructor_id": 2,
      "instructor_name": "John Doe",
      "thumbnail_path": "storage/courses/thumbnails/intro_programming.jpg",
      "created_at": "2023-01-01T00:00:00.000000Z",
      "updated_at": "2023-01-01T00:00:00.000000Z"
    }
  ]
}
```

#### 2. POST `/api/courses` — Create a New Course (Instructor/Manager only)
Creates a new course.

**Headers:**
```
Authorization: Bearer your_token_here
Content-Type: multipart/form-data
```

**Request Body (FormData):**
```
title: "Advanced Mathematics"
description: "In-depth exploration of advanced math concepts"
thumbnail: [file upload]
```

**Success Response:**
```json
{
  "status": "success",
  "message": "Course created successfully",
  "course": {
    "id": 2,
    "title": "Advanced Mathematics",
    "description": "In-depth exploration of advanced math concepts",
    "instructor_id": 2,
    "thumbnail_path": "storage/courses/thumbnails/advanced_math.jpg",
    "created_at": "2023-01-02T00:00:00.000000Z",
    "updated_at": "2023-01-02T00:00:00.000000Z"
  }
}
```

#### 3. GET `/api/courses/{id}` — Get Course Details
Returns detailed information about a specific course.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response:**
```json
{
  "course": {
    "id": 1,
    "title": "Introduction to Programming",
    "description": "Learn the basics of programming",
    "instructor_id": 2,
    "instructor_name": "John Doe",
    "thumbnail_path": "storage/courses/thumbnails/intro_programming.jpg",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-01T00:00:00.000000Z",
    "lectures_count": 10,
    "assignments_count": 5,
    "quizzes_count": 3,
    "labs_count": 2,
    "enrolled_students_count": 25
  }
}
```

#### 4. PUT `/api/courses/{id}` — Update Course (Instructor/Manager only)
Updates an existing course.

**Headers:**
```
Authorization: Bearer your_token_here
Content-Type: multipart/form-data
```

**Request Body (FormData):**
```
title: "Updated Course Title"
description: "Updated course description"
thumbnail: [file upload]
```

**Success Response:**
```json
{
  "status": "success",
  "message": "Course updated successfully",
  "course": {
    "id": 1,
    "title": "Updated Course Title",
    "description": "Updated course description",
    "instructor_id": 2,
    "thumbnail_path": "storage/courses/thumbnails/updated_thumbnail.jpg",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-03T00:00:00.000000Z"
  }
}
```

#### 5. DELETE `/api/courses/{id}` — Delete Course (Instructor/Manager only)
Deletes a course and all its related content.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response:**
```json
{
  "status": "success",
  "message": "Course deleted successfully"
}
```

### Lecture Management

#### 1. GET `/api/courses/{courseId}/lectures` — List All Lectures
Returns all lectures for a specific course.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response:**
```json
{
  "lectures": [
    {
      "id": 1,
      "course_id": 1,
      "title": "Introduction to Variables",
      "description": "Learn about variables and data types",
      "content": "Detailed lecture content here...",
      "video_url": "https://example.com/videos/lecture1.mp4",
      "order": 1,
      "created_at": "2023-01-01T00:00:00.000000Z",
      "updated_at": "2023-01-01T00:00:00.000000Z"
    }
  ]
}
```

#### 2. POST `/api/courses/{courseId}/lectures` — Create a New Lecture (Instructor/Manager only)
Creates a new lecture for a course.

**Headers:**
```
Authorization: Bearer your_token_here
Content-Type: multipart/form-data
```

**Request Body (FormData):**
```
title: "Functions and Methods"
description: "Understanding functions and methods in programming"
content: "Detailed lecture content..."
video: [file upload]
materials: [file upload]
order: 2
```

**Success Response:**
```json
{
  "status": "success",
  "message": "Lecture created successfully",
  "lecture": {
    "id": 2,
    "course_id": 1,
    "title": "Functions and Methods",
    "description": "Understanding functions and methods in programming",
    "content": "Detailed lecture content...",
    "video_url": "storage/courses/1/lectures/videos/functions.mp4",
    "materials_path": "storage/courses/1/lectures/materials/functions_resources.zip",
    "order": 2,
    "created_at": "2023-01-02T00:00:00.000000Z",
    "updated_at": "2023-01-02T00:00:00.000000Z"
  }
}
```

#### 3. GET `/api/courses/{courseId}/lectures/{id}` — Get Lecture Details
Returns detailed information about a specific lecture.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response:**
```json
{
  "lecture": {
    "id": 1,
    "course_id": 1,
    "title": "Introduction to Variables",
    "description": "Learn about variables and data types",
    "content": "Detailed lecture content here...",
    "video_url": "https://example.com/videos/lecture1.mp4",
    "materials_path": "storage/courses/1/lectures/materials/variables_resources.zip",
    "order": 1,
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-01T00:00:00.000000Z"
  }
}
```

#### 4. PUT `/api/courses/{courseId}/lectures/{id}` — Update Lecture (Instructor/Manager only)
Updates an existing lecture.

**Headers:**
```
Authorization: Bearer your_token_here
Content-Type: multipart/form-data
```

**Request Body (FormData):**
```
title: "Updated Lecture Title"
description: "Updated lecture description"
content: "Updated lecture content..."
video: [file upload]
materials: [file upload]
order: 3
```

**Success Response:**
```json
{
  "status": "success",
  "message": "Lecture updated successfully",
  "lecture": {
    "id": 1,
    "course_id": 1,
    "title": "Updated Lecture Title",
    "description": "Updated lecture description",
    "content": "Updated lecture content...",
    "video_url": "storage/courses/1/lectures/videos/updated_video.mp4",
    "materials_path": "storage/courses/1/lectures/materials/updated_resources.zip",
    "order": 3,
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-03T00:00:00.000000Z"
  }
}
```

#### 5. DELETE `/api/courses/{courseId}/lectures/{id}` — Delete Lecture (Instructor/Manager only)
Deletes a lecture from a course.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response:**
```json
{
  "status": "success",
  "message": "Lecture deleted successfully"
}
```

### Assignment Management

#### 1. GET `/api/courses/{courseId}/assignments` — List All Assignments
Returns all assignments for a specific course.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response:**
```json
{
  "assignments": [
    {
      "id": 1,
      "course_id": 1,
      "title": "Variables Practice",
      "description": "Practice exercises on variables",
      "instructions": "Complete the following exercises...",
      "due_date": "2023-01-15T23:59:59.000000Z",
      "total_points": 100,
      "attachment_path": "storage/courses/1/assignments/variables_practice.pdf",
      "created_at": "2023-01-01T00:00:00.000000Z",
      "updated_at": "2023-01-01T00:00:00.000000Z"
    }
  ]
}
```

#### 2. POST `/api/courses/{courseId}/assignments` — Create a New Assignment (Instructor/Manager only)
Creates a new assignment for a course.

**Headers:**
```
Authorization: Bearer your_token_here
Content-Type: multipart/form-data
```

**Request Body (FormData):**
```
title: "Functions Assignment"
description: "Practice implementing different types of functions"
instructions: "Detailed instructions for the assignment..."
due_date: "2023-02-15T23:59:59"
total_points: 150
attachment: [file upload]
```

**Success Response:**
```json
{
  "status": "success",
  "message": "Assignment created successfully",
  "assignment": {
    "id": 2,
    "course_id": 1,
    "title": "Functions Assignment",
    "description": "Practice implementing different types of functions",
    "instructions": "Detailed instructions for the assignment...",
    "due_date": "2023-02-15T23:59:59.000000Z",
    "total_points": 150,
    "attachment_path": "storage/courses/1/assignments/functions_assignment.pdf",
    "created_at": "2023-01-10T00:00:00.000000Z",
    "updated_at": "2023-01-10T00:00:00.000000Z"
  }
}
```

#### 3. GET `/api/courses/{courseId}/assignments/{id}` — Get Assignment Details
Returns detailed information about a specific assignment.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response:**
```json
{
  "assignment": {
    "id": 1,
    "course_id": 1,
    "title": "Variables Practice",
    "description": "Practice exercises on variables",
    "instructions": "Complete the following exercises...",
    "due_date": "2023-01-15T23:59:59.000000Z",
    "total_points": 100,
    "attachment_path": "storage/courses/1/assignments/variables_practice.pdf",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-01T00:00:00.000000Z"
  }
}
```

#### 4. PUT `/api/courses/{courseId}/assignments/{id}` — Update Assignment (Instructor/Manager only)
Updates an existing assignment.

**Headers:**
```
Authorization: Bearer your_token_here
Content-Type: multipart/form-data
```

**Request Body (FormData):**
```
title: "Updated Assignment Title"
description: "Updated assignment description"
instructions: "Updated instructions..."
due_date: "2023-01-20T23:59:59"
total_points: 120
attachment: [file upload]
```

**Success Response:**
```json
{
  "status": "success",
  "message": "Assignment updated successfully",
  "assignment": {
    "id": 1,
    "course_id": 1,
    "title": "Updated Assignment Title",
    "description": "Updated assignment description",
    "instructions": "Updated instructions...",
    "due_date": "2023-01-20T23:59:59.000000Z",
    "total_points": 120,
    "attachment_path": "storage/courses/1/assignments/updated_assignment.pdf",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-05T00:00:00.000000Z"
  }
}
```

#### 5. DELETE `/api/courses/{courseId}/assignments/{id}` — Delete Assignment (Instructor/Manager only)
Deletes an assignment from a course.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response:**
```json
{
  "status": "success",
  "message": "Assignment deleted successfully"
}
```

### Quiz Management

#### 1. GET `/api/courses/{courseId}/quizzes` — List All Quizzes
Returns all quizzes for a specific course.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response:**
```json
{
  "quizzes": [
    {
      "id": 1,
      "course_id": 1,
      "title": "Variables Quiz",
      "description": "Test your understanding of variables",
      "time_limit_minutes": 30,
      "total_points": 50,
      "available_from": "2023-01-10T00:00:00.000000Z",
      "available_until": "2023-01-20T23:59:59.000000Z",
      "created_at": "2023-01-01T00:00:00.000000Z",
      "updated_at": "2023-01-01T00:00:00.000000Z"
    }
  ]
}
```

#### 2. POST `/api/courses/{courseId}/quizzes` — Create a New Quiz (Instructor/Manager only)
Creates a new quiz for a course.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Request Body:**
```json
{
  "title": "Functions Quiz",
  "description": "Test your knowledge of functions and methods",
  "time_limit_minutes": 45,
  "total_points": 75,
  "available_from": "2023-02-10T00:00:00",
  "available_until": "2023-02-20T23:59:59",
  "questions": [
    {
      "question": "What is a function?",
      "type": "multiple_choice",
      "options": ["A variable", "A block of code", "A data type"],
      "correct_answer": "A block of code",
      "points": 15
    },
    {
      "question": "Explain the difference between parameters and arguments.",
      "type": "text",
      "points": 20
    }
  ]
}
```

**Success Response:**
```json
{
  "status": "success",
  "message": "Quiz created successfully",
  "quiz": {
    "id": 2,
    "course_id": 1,
    "title": "Functions Quiz",
    "description": "Test your knowledge of functions and methods",
    "time_limit_minutes": 45,
    "total_points": 75,
    "available_from": "2023-02-10T00:00:00.000000Z",
    "available_until": "2023-02-20T23:59:59.000000Z",
    "created_at": "2023-01-15T00:00:00.000000Z",
    "updated_at": "2023-01-15T00:00:00.000000Z",
    "questions_count": 2
  }
}
```

#### 3. GET `/api/courses/{courseId}/quizzes/{id}` — Get Quiz Details
Returns detailed information about a specific quiz.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response:**
```json
{
  "quiz": {
    "id": 1,
    "course_id": 1,
    "title": "Variables Quiz",
    "description": "Test your understanding of variables",
    "time_limit_minutes": 30,
    "total_points": 50,
    "available_from": "2023-01-10T00:00:00.000000Z",
    "available_until": "2023-01-20T23:59:59.000000Z",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-01T00:00:00.000000Z",
    "questions": [
      {
        "id": 1,
        "question": "What is a variable?",
        "type": "multiple_choice",
        "options": ["A container for data", "A function", "A class"],
        "points": 10
      }
    ]
  }
}
```

#### 4. PUT `/api/courses/{courseId}/quizzes/{id}` — Update Quiz (Instructor/Manager only)
Updates an existing quiz.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Request Body:**
```json
{
  "title": "Updated Quiz Title",
  "description": "Updated quiz description",
  "time_limit_minutes": 40,
  "total_points": 60,
  "available_from": "2023-01-15T00:00:00",
  "available_until": "2023-01-25T23:59:59"
}
```

**Success Response:**
```json
{
  "status": "success",
  "message": "Quiz updated successfully",
  "quiz": {
    "id": 1,
    "course_id": 1,
    "title": "Updated Quiz Title",
    "description": "Updated quiz description",
    "time_limit_minutes": 40,
    "total_points": 60,
    "available_from": "2023-01-15T00:00:00.000000Z",
    "available_until": "2023-01-25T23:59:59.000000Z",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-05T00:00:00.000000Z"
  }
}
```

#### 5. DELETE `/api/courses/{courseId}/quizzes/{id}` — Delete Quiz (Instructor/Manager only)
Deletes a quiz from a course.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response:**
```json
{
  "status": "success",
  "message": "Quiz deleted successfully"
}
```

### Lab Management

#### 1. GET `/api/courses/{courseId}/labs` — List All Labs
Returns all labs for a specific course.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response:**
```json
{
  "labs": [
    {
      "id": 1,
      "course_id": 1,
      "title": "Programming Environment Setup",
      "description": "Setting up your development environment",
      "instructions": "Follow these steps to set up your environment...",
      "due_date": "2023-01-15T23:59:59.000000Z",
      "total_points": 50,
      "materials_path": "storage/courses/1/labs/setup_materials.zip",
      "created_at": "2023-01-01T00:00:00.000000Z",
      "updated_at": "2023-01-01T00:00:00.000000Z"
    }
  ]
}
```

#### 2. POST `/api/courses/{courseId}/labs` — Create a New Lab (Instructor/Manager only)
Creates a new lab for a course.

**Headers:**
```
Authorization: Bearer your_token_here
Content-Type: multipart/form-data
```

**Request Body (FormData):**
```
title: "Functions Lab"
description: "Hands-on practice with functions"
instructions: "In this lab, you will implement several functions..."
due_date: "2023-02-15T23:59:59"
total_points: 75
materials: [file upload]
```

**Success Response:**
```json
{
  "status": "success",
  "message": "Lab created successfully",
  "lab": {
    "id": 2,
    "course_id": 1,
    "title": "Functions Lab",
    "description": "Hands-on practice with functions",
    "instructions": "In this lab, you will implement several functions...",
    "due_date": "2023-02-15T23:59:59.000000Z",
    "total_points": 75,
    "materials_path": "storage/courses/1/labs/functions_lab.zip",
    "created_at": "2023-01-10T00:00:00.000000Z",
    "updated_at": "2023-01-10T00:00:00.000000Z"
  }
}
```

#### 3. GET `/api/courses/{courseId}/labs/{id}` — Get Lab Details
Returns detailed information about a specific lab.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response:**
```json
{
  "lab": {
    "id": 1,
    "course_id": 1,
    "title": "Programming Environment Setup",
    "description": "Setting up your development environment",
    "instructions": "Follow these steps to set up your environment...",
    "due_date": "2023-01-15T23:59:59.000000Z",
    "total_points": 50,
    "materials_path": "storage/courses/1/labs/setup_materials.zip",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-01T00:00:00.000000Z"
  }
}
```

#### 4. PUT `/api/courses/{courseId}/labs/{id}` — Update Lab (Instructor/Manager only)
Updates an existing lab.

**Headers:**
```
Authorization: Bearer your_token_here
Content-Type: multipart/form-data
```

**Request Body (FormData):**
```
title: "Updated Lab Title"
description: "Updated lab description"
instructions: "Updated instructions..."
due_date: "2023-01-20T23:59:59"
total_points: 60
materials: [file upload]
```

**Success Response:**
```json
{
  "status": "success",
  "message": "Lab updated successfully",
  "lab": {
    "id": 1,
    "course_id": 1,
    "title": "Updated Lab Title",
    "description": "Updated lab description",
    "instructions": "Updated instructions...",
    "due_date": "2023-01-20T23:59:59.000000Z",
    "total_points": 60,
    "materials_path": "storage/courses/1/labs/updated_materials.zip",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-05T00:00:00.000000Z"
  }
}
```

#### 5. DELETE `/api/courses/{courseId}/labs/{id}` — Delete Lab (Instructor/Manager only)
Deletes a lab from a course.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response:**
```json
{
  "status": "success",
  "message": "Lab deleted successfully"
}
```

### Enrollment Management

#### 1. POST `/api/courses/{courseId}/enroll` — Enroll in a Course
Enrolls the authenticated user in a course.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response:**
```json
{
  "status": "success",
  "message": "Successfully enrolled in the course",
  "enrollment": {
    "user_id": 1,
    "course_id": 1,
    "status": "active",
    "enrolled_at": "2023-01-05T00:00:00.000000Z"
  }
}
```

#### 2. POST `/api/courses/{courseId}/unenroll` — Unenroll from a Course
Unenrolls the authenticated user from a course.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response:**
```json
{
  "status": "success",
  "message": "Successfully unenrolled from the course"
}
```

#### 3. GET `/api/my-courses` — List Enrolled Courses
Returns all courses the authenticated user is enrolled in.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response:**
```json
{
  "courses": [
    {
      "id": 1,
      "title": "Introduction to Programming",
      "description": "Learn the basics of programming",
      "instructor_id": 2,
      "instructor_name": "John Doe",
      "thumbnail_path": "storage/courses/thumbnails/intro_programming.jpg",
      "enrollment_status": "active",
      "enrolled_at": "2023-01-05T00:00:00.000000Z",
      "progress": {
        "completed_lectures": 5,
        "total_lectures": 10,
        "completed_assignments": 2,
        "total_assignments": 5
      }
    }
  ]
}
```

#### 4. GET `/api/courses/{courseId}/students` — List Enrolled Students (Instructor/Manager only)
Returns all students enrolled in a specific course.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response:**
```json
{
  "students": [
    {
      "id": 1,
      "name": "Yousef",
      "email": "yousef@example.com",
      "enrollment_status": "active",
      "enrolled_at": "2023-01-05T00:00:00.000000Z",
      "progress": {
        "completed_lectures": 5,
        "total_lectures": 10,
        "completed_assignments": 2,
        "total_assignments": 5
      }
    }
  ]
}
```

#### 5. PUT `/api/courses/{courseId}/students/{userId}` — Update Enrollment Status (Instructor/Manager only)
Updates the enrollment status of a student in a course.

**Headers:**
```
Authorization: Bearer your_token_here
```

**Request Body:**
```json
{
  "status": "completed"
}
```

**Success Response:**
```json
{
  "status": "success",
  "message": "Enrollment status updated successfully",
  "enrollment": {
    "