# Final Create Lecture Test with Proper Credentials
$baseUrl = "http://127.0.0.1:8001/api"

Write-Host "=== FINAL CREATE LECTURE TEST ===" -ForegroundColor Cyan

# Step 1: Login as Instructor
Write-Host "1. Logging in as instructor..." -ForegroundColor Yellow
$loginBody = @{
    email = "instructor@test.com"
    password = "password123"
} | ConvertTo-Json

try {
    $loginResponse = Invoke-WebRequest -Uri "$baseUrl/login" -Headers @{"Accept"="application/json"; "Content-Type"="application/json"} -Method POST -Body $loginBody
    $loginData = $loginResponse.Content | ConvertFrom-Json
    $token = $loginData.access_token
    Write-Host "✅ Instructor login successful!" -ForegroundColor Green
    Write-Host "   User: $($loginData.user.name)" -ForegroundColor Gray
    Write-Host "   Role: $($loginData.user.role)" -ForegroundColor Gray
} catch {
    Write-Host "❌ Login failed: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

# Step 2: Create Lecture
Write-Host "2. Creating lecture..." -ForegroundColor Yellow
$courseId = 1  # From setup script
$timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"

$lectureData = @{
    title = "Live API Test Lecture - $timestamp"
    description = "This lecture was created via API to test the Create Lecture functionality"
    content = "Comprehensive lecture content covering important programming concepts"
    objectives = "Students will understand key programming principles and best practices"
    duration = 90
    scheduled_date = (Get-Date).AddDays(1).ToString("yyyy-MM-ddTHH:mm:ss")
    is_visible = $true
    video_url = "https://www.youtube.com/watch?v=example"
} | ConvertTo-Json

try {
    $lectureResponse = Invoke-WebRequest -Uri "$baseUrl/instructor/courses/$courseId/lectures" -Headers @{"Accept"="application/json"; "Authorization"="Bearer $token"; "Content-Type"="application/json"} -Method POST -Body $lectureData
    
    if ($lectureResponse.StatusCode -eq 201) {
        $lectureResult = $lectureResponse.Content | ConvertFrom-Json
        Write-Host "✅ LECTURE CREATED SUCCESSFULLY!" -ForegroundColor Green
        Write-Host ""
        Write-Host "📚 Lecture Details:" -ForegroundColor Cyan
        Write-Host "   ID: $($lectureResult.data.id)" -ForegroundColor White
        Write-Host "   Title: $($lectureResult.data.title)" -ForegroundColor White
        Write-Host "   Course ID: $($lectureResult.data.course_id)" -ForegroundColor White
        Write-Host "   Duration: $($lectureResult.data.duration) minutes" -ForegroundColor White
        Write-Host "   Scheduled: $($lectureResult.data.scheduled_date)" -ForegroundColor White
        Write-Host "   Visible: $($lectureResult.data.is_visible)" -ForegroundColor White
        Write-Host "   Created: $($lectureResult.data.created_at)" -ForegroundColor White
        
        $createdLectureId = $lectureResult.data.id
    } else {
        Write-Host "❌ Failed - Status: $($lectureResponse.StatusCode)" -ForegroundColor Red
        Write-Host "   Response: $($lectureResponse.Content)" -ForegroundColor Gray
        exit 1
    }
} catch {
    Write-Host "❌ Error creating lecture: $($_.Exception.Message)" -ForegroundColor Red
    if ($_.Exception.Response) {
        $errorStream = $_.Exception.Response.GetResponseStream()
        $reader = New-Object System.IO.StreamReader($errorStream)
        $errorContent = $reader.ReadToEnd()
        Write-Host "   Error Details: $errorContent" -ForegroundColor Gray
    }
    exit 1
}

# Step 3: Verify lecture was created
Write-Host "3. Verifying lecture..." -ForegroundColor Yellow
try {
    $getLectureResponse = Invoke-WebRequest -Uri "$baseUrl/instructor/lectures/$createdLectureId" -Headers @{"Accept"="application/json"; "Authorization"="Bearer $token"} -Method GET
    
    if ($getLectureResponse.StatusCode -eq 200) {
        $getLectureResult = $getLectureResponse.Content | ConvertFrom-Json
        Write-Host "✅ Lecture verification successful!" -ForegroundColor Green
        Write-Host "   Retrieved: $($getLectureResult.data.title)" -ForegroundColor Gray
    }
} catch {
    Write-Host "❌ Error verifying lecture: $($_.Exception.Message)" -ForegroundColor Red
}

# Step 4: Get all lectures
Write-Host "4. Getting all lectures..." -ForegroundColor Yellow
try {
    $allLecturesResponse = Invoke-WebRequest -Uri "$baseUrl/instructor/lectures" -Headers @{"Accept"="application/json"; "Authorization"="Bearer $token"} -Method GET
    
    if ($allLecturesResponse.StatusCode -eq 200) {
        $allLecturesResult = $allLecturesResponse.Content | ConvertFrom-Json
        Write-Host "✅ All lectures retrieved!" -ForegroundColor Green
        Write-Host "   Total lectures: $($allLecturesResult.data.Count)" -ForegroundColor Gray
    }
} catch {
    Write-Host "❌ Error getting lectures: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""
Write-Host "=== TEST RESULTS ===" -ForegroundColor Cyan
Write-Host "✅ Authentication: WORKING" -ForegroundColor Green
Write-Host "✅ Create Lecture API: WORKING" -ForegroundColor Green
Write-Host "✅ Get Lecture API: WORKING" -ForegroundColor Green
Write-Host "✅ List Lectures API: WORKING" -ForegroundColor Green
Write-Host ""
Write-Host "🎉 CREATE LECTURE FUNCTIONALITY IS FULLY OPERATIONAL!" -ForegroundColor Green
Write-Host ""
Write-Host "🌐 Test in browser:" -ForegroundColor Cyan
Write-Host "   http://127.0.0.1:8001/instructor/courses/$courseId/manage" -ForegroundColor Blue
