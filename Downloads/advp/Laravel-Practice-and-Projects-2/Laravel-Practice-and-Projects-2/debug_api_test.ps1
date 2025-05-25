# Debug API Test - Step by Step
$baseUrl = "http://127.0.0.1:8001/api"

Write-Host "=== DEBUGGING CREATE LECTURE API ===" -ForegroundColor Cyan

# Step 1: Test server connectivity
Write-Host "1. Testing server connectivity..." -ForegroundColor Yellow
try {
    $pingResponse = Invoke-WebRequest -Uri "http://127.0.0.1:8001" -Method GET -TimeoutSec 5
    Write-Host "✅ Server is responding (Status: $($pingResponse.StatusCode))" -ForegroundColor Green
} catch {
    Write-Host "❌ Server not responding: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

# Step 2: Test login
Write-Host "2. Testing login..." -ForegroundColor Yellow
$loginBody = '{"email":"instructor@test.com","password":"password123"}'

try {
    $loginResponse = Invoke-WebRequest -Uri "$baseUrl/login" -Headers @{"Accept"="application/json"; "Content-Type"="application/json"} -Method POST -Body $loginBody
    $loginData = $loginResponse.Content | ConvertFrom-Json
    $token = $loginData.access_token
    Write-Host "✅ Login successful!" -ForegroundColor Green
    Write-Host "   Token: $($token.Substring(0, 20))..." -ForegroundColor Gray
} catch {
    Write-Host "❌ Login failed: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

# Step 3: Test route exists
Write-Host "3. Testing route registration..." -ForegroundColor Yellow
try {
    $routeResponse = Invoke-WebRequest -Uri "$baseUrl/instructor/courses/1/lectures" -Headers @{"Accept"="application/json"; "Authorization"="Bearer $token"} -Method GET
    Write-Host "✅ Route exists (Status: $($routeResponse.StatusCode))" -ForegroundColor Green
} catch {
    Write-Host "⚠️ Route test: $($_.Exception.Message)" -ForegroundColor Yellow
}

# Step 4: Test create lecture with minimal data
Write-Host "4. Testing create lecture..." -ForegroundColor Yellow
$courseId = 1
$lectureBody = '{"title":"Debug Test Lecture","description":"Testing API","duration":60,"is_visible":true}'

try {
    $lectureResponse = Invoke-WebRequest -Uri "$baseUrl/instructor/courses/$courseId/lectures" -Headers @{"Accept"="application/json"; "Authorization"="Bearer $token"; "Content-Type"="application/json"} -Method POST -Body $lectureBody
    
    Write-Host "✅ CREATE LECTURE SUCCESS!" -ForegroundColor Green
    Write-Host "   Status: $($lectureResponse.StatusCode)" -ForegroundColor White
    Write-Host "   Response: $($lectureResponse.Content)" -ForegroundColor White
    
} catch {
    Write-Host "❌ Create lecture failed!" -ForegroundColor Red
    Write-Host "   Error: $($_.Exception.Message)" -ForegroundColor Gray
    
    if ($_.Exception.Response) {
        $statusCode = $_.Exception.Response.StatusCode
        Write-Host "   Status Code: $statusCode" -ForegroundColor Gray
        
        try {
            $errorStream = $_.Exception.Response.GetResponseStream()
            $reader = New-Object System.IO.StreamReader($errorStream)
            $errorContent = $reader.ReadToEnd()
            Write-Host "   Error Details: $errorContent" -ForegroundColor Gray
        } catch {
            Write-Host "   Could not read error details" -ForegroundColor Gray
        }
    }
}

Write-Host ""
Write-Host "=== DEBUG COMPLETE ===" -ForegroundColor Cyan
