# Debug Assignment Validation
$baseUrl = "http://127.0.0.1:8001/api"

Write-Host "=== DEBUGGING ASSIGNMENT VALIDATION ===" -ForegroundColor Cyan

# Login
$loginBody = '{"email":"instructor@test.com","password":"password123"}'
$loginResponse = Invoke-WebRequest -Uri "$baseUrl/login" -Headers @{"Accept"="application/json"; "Content-Type"="application/json"} -Method POST -Body $loginBody
$loginData = $loginResponse.Content | ConvertFrom-Json
$token = $loginData.access_token
Write-Host "Login successful!" -ForegroundColor Green

$courseId = 1

# Test with minimal required data first
Write-Host "Testing with minimal data..." -ForegroundColor Yellow
$futureDate = (Get-Date).AddDays(7).ToString("yyyy-MM-ddTHH:mm:ss")

$minimalData = @{
    title = "Debug Assignment"
    description = "Testing validation"
    due_date = $futureDate
} | ConvertTo-Json

Write-Host "Sending data: $minimalData" -ForegroundColor Gray

try {
    $response = Invoke-WebRequest -Uri "$baseUrl/instructor/courses/$courseId/assignments" -Headers @{"Accept"="application/json"; "Authorization"="Bearer $token"; "Content-Type"="application/json"} -Method POST -Body $minimalData
    
    Write-Host "SUCCESS! Assignment created" -ForegroundColor Green
    $result = $response.Content | ConvertFrom-Json
    Write-Host "ID: $($result.data.id)" -ForegroundColor White
    
} catch {
    Write-Host "FAILED!" -ForegroundColor Red
    Write-Host "Error: $($_.Exception.Message)" -ForegroundColor Gray
    
    if ($_.Exception.Response) {
        $statusCode = $_.Exception.Response.StatusCode
        Write-Host "Status Code: $statusCode" -ForegroundColor Gray
        
        try {
            $errorStream = $_.Exception.Response.GetResponseStream()
            $reader = New-Object System.IO.StreamReader($errorStream)
            $errorContent = $reader.ReadToEnd()
            Write-Host "Detailed Error Response:" -ForegroundColor Yellow
            Write-Host $errorContent -ForegroundColor White
        } catch {
            Write-Host "Could not read error details" -ForegroundColor Gray
        }
    }
}

Write-Host ""
Write-Host "=== VALIDATION DEBUG COMPLETE ===" -ForegroundColor Cyan
