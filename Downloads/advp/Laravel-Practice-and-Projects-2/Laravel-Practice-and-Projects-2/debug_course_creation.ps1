# Debug Course Creation Issues
$baseUrl = "http://127.0.0.1:8001/api"

Write-Host "=== DEBUGGING COURSE CREATION ===" -ForegroundColor Cyan
Write-Host ""

# Step 1: Test manager login
Write-Host "Step 1: Testing manager login..." -ForegroundColor Yellow
$loginBody = @{
    email = "manager@gmail.com"
    password = "manager123"
} | ConvertTo-Json

try {
    $loginResponse = Invoke-RestMethod -Uri "$baseUrl/login" -Method POST -Body $loginBody -ContentType "application/json"
    $token = $loginResponse.access_token
    Write-Host "✅ Login successful. Token: $($token.Substring(0,20))..." -ForegroundColor Green
    Write-Host "   User: $($loginResponse.user.name) ($($loginResponse.user.role))" -ForegroundColor Gray
} catch {
    Write-Host "❌ Login failed: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

# Step 2: Check existing courses
Write-Host ""
Write-Host "Step 2: Checking existing courses..." -ForegroundColor Yellow
try {
    $headers = @{
        "Authorization" = "Bearer $token"
        "Accept" = "application/json"
    }
    
    $coursesResponse = Invoke-RestMethod -Uri "$baseUrl/manager/courses" -Method GET -Headers $headers
    $courses = $coursesResponse.data.data
    
    Write-Host "✅ Found $($courses.Count) existing courses:" -ForegroundColor Green
    foreach ($course in $courses) {
        Write-Host "   📚 $($course.code) - $($course.title)" -ForegroundColor Gray
    }
    
    # Check for conflicts
    $conflictCodes = @("11111", "WEB101", "CS201")
    foreach ($code in $conflictCodes) {
        $existing = $courses | Where-Object { $_.code -eq $code }
        if ($existing) {
            Write-Host "   ❌ Code '$code' is taken by: $($existing.title)" -ForegroundColor Red
        } else {
            Write-Host "   ✅ Code '$code' is available" -ForegroundColor Green
        }
    }
} catch {
    Write-Host "❌ Failed to get courses: $($_.Exception.Message)" -ForegroundColor Red
}

# Step 3: Get available instructors
Write-Host ""
Write-Host "Step 3: Getting available instructors..." -ForegroundColor Yellow
try {
    $usersResponse = Invoke-RestMethod -Uri "$baseUrl/users?role=instructor" -Method GET -Headers $headers
    $instructors = $usersResponse.data
    
    Write-Host "✅ Found $($instructors.Count) instructors:" -ForegroundColor Green
    foreach ($instructor in $instructors) {
        Write-Host "   👨‍🏫 ID: $($instructor.id) - $($instructor.name) ($($instructor.email))" -ForegroundColor Gray
    }
} catch {
    Write-Host "❌ Failed to get instructors: $($_.Exception.Message)" -ForegroundColor Red
}

# Step 4: Test course creation with unique code
Write-Host ""
Write-Host "Step 4: Testing course creation..." -ForegroundColor Yellow

$uniqueCode = "TEST$(Get-Random -Minimum 100 -Maximum 999)"
$courseData = @{
    title = "Test Advanced Web Development"
    code = $uniqueCode
    description = "Test course for debugging"
    credit_hours = 3
    max_capacity = 30
    instructor_id = if ($instructors -and $instructors.Count -gt 0) { $instructors[0].id } else { $null }
} | ConvertTo-Json

Write-Host "Course data to send:" -ForegroundColor Gray
Write-Host $courseData -ForegroundColor Gray

try {
    $createHeaders = @{
        "Authorization" = "Bearer $token"
        "Accept" = "application/json"
        "Content-Type" = "application/json"
    }
    
    $createResponse = Invoke-RestMethod -Uri "$baseUrl/courses" -Method POST -Body $courseData -Headers $createHeaders
    
    Write-Host "✅ Course created successfully!" -ForegroundColor Green
    Write-Host "   📚 Title: $($createResponse.data.title)" -ForegroundColor Gray
    Write-Host "   🔢 Code: $($createResponse.data.code)" -ForegroundColor Gray
    Write-Host "   🆔 ID: $($createResponse.data.id)" -ForegroundColor Gray
    Write-Host "   👨‍🏫 Instructor: $($createResponse.data.instructor.name)" -ForegroundColor Gray
    
} catch {
    Write-Host "❌ Course creation failed!" -ForegroundColor Red
    Write-Host "   Error: $($_.Exception.Message)" -ForegroundColor Red
    
    if ($_.Exception.Response) {
        try {
            $errorStream = $_.Exception.Response.GetResponseStream()
            $reader = New-Object System.IO.StreamReader($errorStream)
            $errorContent = $reader.ReadToEnd()
            $errorData = $errorContent | ConvertFrom-Json
            
            Write-Host "   Status: $($_.Exception.Response.StatusCode)" -ForegroundColor Red
            Write-Host "   Response: $errorContent" -ForegroundColor Red
            
            if ($errorData.errors) {
                Write-Host "   Validation Errors:" -ForegroundColor Red
                foreach ($field in $errorData.errors.PSObject.Properties) {
                    Write-Host "     - $($field.Name): $($field.Value -join ', ')" -ForegroundColor Red
                }
            }
        } catch {
            Write-Host "   Could not parse error response" -ForegroundColor Red
        }
    }
}

# Step 5: Test with the exact data from the form
Write-Host ""
Write-Host "Step 5: Testing with form data (WEB101)..." -ForegroundColor Yellow

$formData = @{
    title = "advanced web"
    code = "WEB101"
    description = "dh"
    credit_hours = 3
    max_capacity = 30
    instructor_id = if ($instructors -and $instructors.Count -gt 0) { $instructors[0].id } else { $null }
} | ConvertTo-Json

Write-Host "Form data to send:" -ForegroundColor Gray
Write-Host $formData -ForegroundColor Gray

try {
    $formResponse = Invoke-RestMethod -Uri "$baseUrl/courses" -Method POST -Body $formData -Headers $createHeaders
    
    Write-Host "✅ Form course created successfully!" -ForegroundColor Green
    Write-Host "   📚 Title: $($formResponse.data.title)" -ForegroundColor Gray
    Write-Host "   🔢 Code: $($formResponse.data.code)" -ForegroundColor Gray
    
} catch {
    Write-Host "❌ Form course creation failed!" -ForegroundColor Red
    Write-Host "   Error: $($_.Exception.Message)" -ForegroundColor Red
    
    if ($_.Exception.Response) {
        try {
            $errorStream = $_.Exception.Response.GetResponseStream()
            $reader = New-Object System.IO.StreamReader($errorStream)
            $errorContent = $reader.ReadToEnd()
            
            Write-Host "   Status: $($_.Exception.Response.StatusCode)" -ForegroundColor Red
            Write-Host "   Response: $errorContent" -ForegroundColor Red
        } catch {
            Write-Host "   Could not parse error response" -ForegroundColor Red
        }
    }
}

Write-Host ""
Write-Host "=== RECOMMENDATIONS ===" -ForegroundColor Cyan
Write-Host "1. Use a unique course code (not 11111)" -ForegroundColor Yellow
Write-Host "2. Make sure you select an instructor" -ForegroundColor Yellow
Write-Host "3. Check browser console for JavaScript errors" -ForegroundColor Yellow
Write-Host "4. Verify you're logged in as manager" -ForegroundColor Yellow
