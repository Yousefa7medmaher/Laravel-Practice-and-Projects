# Notification Integration Test Guide

## Overview
This guide provides step-by-step instructions to test the complete notification integration for the instructor course assignment feature.

## Prerequisites
1. Laravel application is running
2. Database is set up with sample data
3. At least one manager, one instructor, and some courses exist in the database

## Test Scenarios

### 1. Test Course Assignment Notifications

#### Step 1: Access Manager Instructors Page
1. Login as a manager
2. Navigate to `/manager/instructors`
3. Verify the page loads with instructor list

#### Step 2: Test Course Assignment Modal
1. Click the course assignment button (book icon) for any instructor
2. Verify the modal opens with available courses
3. Select/deselect some courses
4. Click "Save Assignments"
5. Verify success message includes notification information

#### Step 3: Verify Instructor Notifications
1. Login as the instructor who was assigned courses
2. Navigate to `/instructor/notifications`
3. Verify notifications appear for:
   - New course assignments (if courses were added)
   - Course unassignments (if courses were removed)

#### Step 4: Verify Manager Notifications
1. Login as a different manager (not the one who made the assignment)
2. Navigate to `/manager/notifications`
3. Verify notification appears about the instructor assignment update

### 2. Test Notification API Endpoints

#### Test the bulk assignment API directly:
```bash
# Example API call (replace with actual IDs)
POST /api/instructors/{instructorId}/assign-courses
{
    "course_ids": [1, 2, 3]
}
```

#### Test notification creation:
```bash
# Create test notifications
POST /api/test/notifications/create

# Get notification statistics
GET /api/test/notifications/stats

# Test specific course assignment workflow
POST /api/test/notifications/course-assignment
{
    "instructor_id": 1,
    "course_ids": [1, 2]
}
```

### 3. Test Notification Pages

#### Manager Notification Page (`/manager/notifications`)
- [ ] Page loads correctly with manager styling (indigo/purple theme)
- [ ] Statistics show correct counts
- [ ] Notifications display with proper formatting
- [ ] Filtering works (by type, priority, status)
- [ ] Mark as read functionality works
- [ ] Mark all as read functionality works
- [ ] Delete notification functionality works
- [ ] Pagination works if there are many notifications

#### Instructor Notification Page (`/instructor/notifications`)
- [ ] Page loads correctly with instructor styling (emerald/green theme)
- [ ] Statistics show correct counts for instructor-specific notifications
- [ ] Notifications display with proper formatting
- [ ] Filtering works correctly
- [ ] All notification actions work properly

### 4. Test Notification Content and Formatting

#### Instructor Assignment Notifications
- [ ] Title: "New Course Assignment"
- [ ] Message includes course names and helpful text
- [ ] Type: "course"
- [ ] Priority: "high"
- [ ] Action URL: "/instructor/courses"

#### Instructor Unassignment Notifications
- [ ] Title: "Course Unassignment"
- [ ] Message includes course names and warning text
- [ ] Type: "warning"
- [ ] Priority: "normal"
- [ ] Action URL: "/instructor/courses"

#### Manager Assignment Update Notifications
- [ ] Title: "Instructor Assignment Updated"
- [ ] Message includes instructor name, course count, and manager who made the change
- [ ] Type: "course"
- [ ] Priority: "normal"
- [ ] Action URL: "/manager/instructors"

### 5. Test Error Handling

#### Test with invalid data:
1. Try to assign courses to non-existent instructor
2. Try to assign non-existent courses
3. Verify proper error messages are returned
4. Verify notifications are not created for failed operations

### 6. Test Performance and Edge Cases

#### Test with multiple assignments:
1. Assign many courses to one instructor
2. Verify notification message handles long course lists properly
3. Test with zero course assignments (unassign all)

#### Test concurrent operations:
1. Have multiple managers making assignments simultaneously
2. Verify all notifications are created correctly

## Expected Results

### Successful Test Indicators:
1. ✅ Course assignments work through the UI
2. ✅ Notifications are created for both instructors and managers
3. ✅ Notification pages display correctly for both roles
4. ✅ All notification actions (read, delete, filter) work
5. ✅ API endpoints respond correctly
6. ✅ Error handling works properly
7. ✅ No JavaScript console errors
8. ✅ No PHP/Laravel errors in logs

### Common Issues to Check:
- Database migration for `unassigned_at` column
- Proper role-based access control
- Correct API endpoint routing
- Notification service imports and usage
- Frontend JavaScript API calls
- CSS styling consistency

## Quick Test Using Manager Dashboard

1. Login as manager
2. Go to Manager Dashboard
3. Click on "Performance" tab
4. Click "Test Notifications" button
5. Verify success message
6. Click "Notification Stats" to see current statistics
7. Navigate to notification pages to verify test notifications were created

## Troubleshooting

### If notifications don't appear:
1. Check Laravel logs for errors
2. Verify database has notification records
3. Check API responses in browser developer tools
4. Verify user roles are correct

### If styling is broken:
1. Check CSS classes are applied correctly
2. Verify Tailwind CSS is loading
3. Check for JavaScript errors in console

### If API calls fail:
1. Verify routes are registered correctly
2. Check authentication tokens
3. Verify middleware is working
4. Check database connections

## Success Criteria

The notification integration is successful when:
1. Managers can assign/unassign courses to instructors
2. Instructors receive notifications about their assignment changes
3. Other managers receive notifications about assignment updates
4. Both notification pages work correctly with proper styling
5. All notification actions function properly
6. Error handling works as expected
7. Performance is acceptable with multiple notifications
