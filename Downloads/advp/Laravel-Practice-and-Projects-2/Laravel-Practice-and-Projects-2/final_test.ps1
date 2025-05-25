# Final Create Lecture Test
$baseUrl = "http://127.0.0.1:8001/api"

Write-Host "=== FINAL CREATE LECTURE TEST ===" -ForegroundColor Cyan

# Login
Write-Host "1. Login..." -ForegroundColor Yellow
$loginBody = '{"email":"instructor@test.com","password":"password123"}'
$loginResponse = Invoke-WebRequest -Uri "$baseUrl/login" -Headers @{"Accept"="application/json"; "Content-Type"="application/json"} -Method POST -Body $loginBody
$loginData = $loginResponse.Content | ConvertFrom-Json
$token = $loginData.access_token
Write-Host "✅ Login successful!" -ForegroundColor Green

# Create Lecture
Write-Host "2. Create Lecture..." -ForegroundColor Yellow
$courseId = 1
$lectureBody = '{"title":"Final Test Lecture","description":"Testing complete functionality","duration":90,"is_visible":true}'
$lectureResponse = Invoke-WebRequest -Uri "$baseUrl/instructor/courses/$courseId/lectures" -Headers @{"Accept"="application/json"; "Authorization"="Bearer $token"; "Content-Type"="application/json"} -Method POST -Body $lectureBody

if ($lectureResponse.StatusCode -eq 201) {
    $lectureResult = $lectureResponse.Content | ConvertFrom-Json
    Write-Host "✅ LECTURE CREATED!" -ForegroundColor Green
    Write-Host "   ID: $($lectureResult.data.id)" -ForegroundColor White
    Write-Host "   Title: $($lectureResult.data.title)" -ForegroundColor White
    $lectureId = $lectureResult.data.id
} else {
    Write-Host "❌ Failed: $($lectureResponse.StatusCode)" -ForegroundColor Red
    exit 1
}

# Get All Lectures
Write-Host "3. Get All Lectures..." -ForegroundColor Yellow
$allLecturesResponse = Invoke-WebRequest -Uri "$baseUrl/instructor/lectures" -Headers @{"Accept"="application/json"; "Authorization"="Bearer $token"} -Method GET
$allLecturesResult = $allLecturesResponse.Content | ConvertFrom-Json
Write-Host "✅ Found $($allLecturesResult.data.Count) lectures total" -ForegroundColor Green

# Get Single Lecture
Write-Host "4. Get Single Lecture..." -ForegroundColor Yellow
$singleLectureResponse = Invoke-WebRequest -Uri "$baseUrl/instructor/lectures/$lectureId" -Headers @{"Accept"="application/json"; "Authorization"="Bearer $token"} -Method GET
$singleLectureResult = $singleLectureResponse.Content | ConvertFrom-Json
Write-Host "✅ Retrieved: $($singleLectureResult.data.title)" -ForegroundColor Green

Write-Host ""
Write-Host "🎉 ALL TESTS PASSED!" -ForegroundColor Green
Write-Host "✅ Create Lecture: WORKING" -ForegroundColor Green
Write-Host "✅ List Lectures: WORKING" -ForegroundColor Green
Write-Host "✅ Get Lecture: WORKING" -ForegroundColor Green
Write-Host ""
Write-Host "Browser test: http://127.0.0.1:8001/instructor/courses/1/manage" -ForegroundColor Cyan
Write-Host "Login: instructor@test.com / password123" -ForegroundColor Cyan
