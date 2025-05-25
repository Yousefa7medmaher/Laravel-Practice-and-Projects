# 🔧 **API CONNECTION DEBUG GUIDE**

## 🎯 **Current Issue: API Not Connecting**

The instructor dashboard shows "Failed to load courses" which indicates an API connection issue. Let's debug this step by step.

---

## 🧪 **Step 1: Test Basic API Connection**

### **Test the API Test Endpoint:**
Open browser developer tools (F12) and run in console:

```javascript
// Test basic API connection
fetch('/api/instructor/test', {
    method: 'GET',
    headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
})
.then(response => response.json())
.then(data => console.log('API Test Result:', data))
.catch(error => console.error('API Test Error:', error));
```

**Expected Result:**
```json
{
    "status": "success",
    "message": "Instructor API is working",
    "user": {
        "id": 123,
        "name": "Zeyad",
        "role": "instructor"
    },
    "timestamp": "2025-01-23T..."
}
```

---

## 🧪 **Step 2: Test Assigned Courses Endpoint**

```javascript
// Test assigned courses endpoint
fetch('/api/instructor/assigned-courses', {
    method: 'GET',
    headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
})
.then(response => response.json())
.then(data => console.log('Assigned Courses Result:', data))
.catch(error => console.error('Assigned Courses Error:', error));
```

**Expected Result (No Courses Assigned):**
```json
{
    "status": "success",
    "data": [],
    "debug": {
        "instructor_id": 123,
        "user_role": "instructor",
        "direct_courses_count": 0,
        "assignments_count": 0,
        "service_courses_count": 0
    },
    "message": "Assigned courses retrieved successfully"
}
```

---

## 🧪 **Step 3: Check Authentication Token**

```javascript
// Check if token exists and is valid
console.log('Token:', localStorage.getItem('token'));
console.log('User:', localStorage.getItem('user'));
console.log('Role:', localStorage.getItem('role'));

// Test profile endpoint
fetch('/api/profile', {
    method: 'GET',
    headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
        'Accept': 'application/json'
    }
})
.then(response => response.json())
.then(data => console.log('Profile Result:', data))
.catch(error => console.error('Profile Error:', error));
```

---

## 🔍 **Common Issues and Solutions**

### **Issue 1: 401 Unauthorized**
**Cause:** Token is invalid or expired
**Solution:**
1. Logout and login again
2. Check if token exists in localStorage
3. Verify token format (should start with numbers|letters)

### **Issue 2: 403 Forbidden**
**Cause:** User doesn't have instructor role
**Solution:**
1. Check user role in database
2. Verify user is actually an instructor
3. Check role-based middleware

### **Issue 3: 404 Not Found**
**Cause:** Route not found
**Solution:**
1. Clear route cache: `php artisan route:clear`
2. Check if route exists: `php artisan route:list | grep instructor`
3. Verify API prefix is correct

### **Issue 4: 500 Internal Server Error**
**Cause:** Server-side error
**Solution:**
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check database connection
3. Verify all models and relationships exist

---

## 🛠️ **Quick Fixes**

### **Fix 1: Update Frontend API Call**
The dashboard might be calling the wrong endpoint. Update the JavaScript:

```javascript
// Change from:
const result = await apiCall('/courses');

// To:
const result = await apiCall('/instructor/assigned-courses');
```

### **Fix 2: Handle Empty Response**
Update the frontend to handle empty course lists properly:

```javascript
if (result && result.ok && result.data.status === 'success') {
    const courses = result.data.data || [];
    
    if (courses.length === 0) {
        // Show "No courses assigned" message instead of "Failed to load"
        coursesContainer.innerHTML = `
            <div class="col-span-2 text-center py-8">
                <i class="fas fa-chalkboard-teacher text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">No courses assigned yet</p>
                <p class="text-sm text-gray-400">Contact your manager to get courses assigned</p>
            </div>
        `;
    } else {
        // Display courses normally
    }
}
```

---

## 🚀 **Testing Commands**

### **Check Routes:**
```bash
php artisan route:list | grep instructor
```

### **Check Logs:**
```bash
tail -f storage/logs/laravel.log
```

### **Test Database:**
```bash
php artisan tinker
```
```php
// In tinker:
$user = App\Models\User::find(123); // Replace with actual instructor ID
echo $user->role;
$courses = App\Models\Course::where('instructor_id', 123)->get();
echo $courses->count();
```

---

## 🎯 **Next Steps**

1. **Test API Endpoints:** Use the JavaScript commands above
2. **Check Logs:** Look for any errors in Laravel logs
3. **Verify Database:** Ensure instructor exists and has correct role
4. **Update Frontend:** Fix the API endpoint being called
5. **Test Assignment:** Have a manager assign courses to test full workflow

---

## 📋 **Checklist**

- [ ] API test endpoint works (`/api/instructor/test`)
- [ ] Authentication token is valid
- [ ] User has instructor role
- [ ] Assigned courses endpoint returns data (`/api/instructor/assigned-courses`)
- [ ] Frontend calls correct endpoint
- [ ] Error handling shows appropriate messages
- [ ] Logs show no errors

---

## 🎉 **Expected Final Result**

After fixing the API connection:
- ✅ Dashboard loads without errors
- ✅ Shows "No courses assigned" message (if no courses)
- ✅ API endpoints respond correctly
- ✅ Ready for manager to assign courses
- ✅ Will show assigned courses when available

The system is working correctly - it just needs proper API connection and error handling!
