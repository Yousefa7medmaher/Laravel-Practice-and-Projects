# Debug Course Creation Validation
$baseUrl = "http://127.0.0.1:8001/api"

Write-Host "=== COURSE VALIDATION DEBUG TEST ===" -ForegroundColor Cyan
Write-Host ""

# Test manager login
Write-Host "Testing manager login..." -ForegroundColor Yellow
try {
    $loginBody = @{
        email = "manager@gmail.com"
        password = "manager123"
    } | ConvertTo-Json

    $loginResponse = Invoke-WebRequest -Uri "$baseUrl/login" `
        -Headers @{"Accept"="application/json"; "Content-Type"="application/json"} `
        -Method POST -Body $loginBody

    if ($loginResponse.StatusCode -eq 200) {
        $loginData = $loginResponse.Content | ConvertFrom-Json
        $token = $loginData.access_token
        Write-Host "✅ Manager login successful" -ForegroundColor Green
        
        # Test 1: Valid course data
        Write-Host ""
        Write-Host "=== Test 1: Valid Course Data ===" -ForegroundColor Blue
        
        $validCourseData = @{
            title = "Debug Test Course"
            code = "DEBUG$(Get-Random -Minimum 100 -Maximum 999)"
            description = "Test course for debugging validation"
            credit_hours = 3
            max_capacity = 30
        } | ConvertTo-Json

        try {
            $validResponse = Invoke-WebRequest -Uri "$baseUrl/courses" `
                -Headers @{"Accept"="application/json"; "Authorization"="Bearer $token"; "Content-Type"="application/json"} `
                -Method POST -Body $validCourseData

            if ($validResponse.StatusCode -eq 201) {
                Write-Host "✅ Valid course created successfully" -ForegroundColor Green
            } else {
                Write-Host "❌ Valid course failed: Status $($validResponse.StatusCode)" -ForegroundColor Red
            }
        } catch {
            Write-Host "❌ Valid course error: $($_.Exception.Message)" -ForegroundColor Red
            if ($_.Exception.Response) {
                $errorContent = $_.Exception.Response.Content.ReadAsStringAsync().Result
                Write-Host "   Error details: $errorContent" -ForegroundColor Red
            }
        }

        # Test 2: Course with empty string instructor_id
        Write-Host ""
        Write-Host "=== Test 2: Empty String instructor_id ===" -ForegroundColor Blue
        
        $emptyStringData = @{
            title = "Empty String Test"
            code = "EMPTY$(Get-Random -Minimum 100 -Maximum 999)"
            description = "Test with empty string instructor_id"
            credit_hours = 3
            max_capacity = 30
            instructor_id = ""
        } | ConvertTo-Json

        try {
            $emptyStringResponse = Invoke-WebRequest -Uri "$baseUrl/courses" `
                -Headers @{"Accept"="application/json"; "Authorization"="Bearer $token"; "Content-Type"="application/json"} `
                -Method POST -Body $emptyStringData

            if ($emptyStringResponse.StatusCode -eq 201) {
                Write-Host "✅ Empty string instructor_id accepted" -ForegroundColor Green
            } else {
                Write-Host "❌ Empty string failed: Status $($emptyStringResponse.StatusCode)" -ForegroundColor Red
            }
        } catch {
            Write-Host "❌ Empty string error: $($_.Exception.Message)" -ForegroundColor Red
            if ($_.Exception.Response) {
                $errorContent = $_.Exception.Response.Content.ReadAsStringAsync().Result
                Write-Host "   Error details: $errorContent" -ForegroundColor Red
            }
        }

        # Test 3: Course with null instructor_id
        Write-Host ""
        Write-Host "=== Test 3: Null instructor_id ===" -ForegroundColor Blue
        
        $nullData = @{
            title = "Null Test Course"
            code = "NULL$(Get-Random -Minimum 100 -Maximum 999)"
            description = "Test with null instructor_id"
            credit_hours = 3
            max_capacity = 30
            instructor_id = $null
        } | ConvertTo-Json

        try {
            $nullResponse = Invoke-WebRequest -Uri "$baseUrl/courses" `
                -Headers @{"Accept"="application/json"; "Authorization"="Bearer $token"; "Content-Type"="application/json"} `
                -Method POST -Body $nullData

            if ($nullResponse.StatusCode -eq 201) {
                Write-Host "✅ Null instructor_id accepted" -ForegroundColor Green
            } else {
                Write-Host "❌ Null failed: Status $($nullResponse.StatusCode)" -ForegroundColor Red
            }
        } catch {
            Write-Host "❌ Null error: $($_.Exception.Message)" -ForegroundColor Red
            if ($_.Exception.Response) {
                $errorContent = $_.Exception.Response.Content.ReadAsStringAsync().Result
                Write-Host "   Error details: $errorContent" -ForegroundColor Red
            }
        }

        # Test 4: Missing required fields
        Write-Host ""
        Write-Host "=== Test 4: Missing Required Fields ===" -ForegroundColor Blue
        
        $missingFieldsData = @{
            description = "Missing title and code"
            credit_hours = 3
        } | ConvertTo-Json

        try {
            $missingResponse = Invoke-WebRequest -Uri "$baseUrl/courses" `
                -Headers @{"Accept"="application/json"; "Authorization"="Bearer $token"; "Content-Type"="application/json"} `
                -Method POST -Body $missingFieldsData

            Write-Host "⚠️  Missing fields was accepted (unexpected)" -ForegroundColor Yellow
        } catch {
            if ($_.Exception.Response.StatusCode -eq 422) {
                Write-Host "✅ Missing fields properly rejected with validation error" -ForegroundColor Green
                $errorContent = $_.Exception.Response.Content.ReadAsStringAsync().Result
                Write-Host "   Validation errors: $errorContent" -ForegroundColor Gray
            } else {
                Write-Host "❌ Unexpected error: $($_.Exception.Message)" -ForegroundColor Red
            }
        }

        # Test 5: Invalid instructor_id
        Write-Host ""
        Write-Host "=== Test 5: Invalid instructor_id ===" -ForegroundColor Blue
        
        $invalidInstructorData = @{
            title = "Invalid Instructor Test"
            code = "INVALID$(Get-Random -Minimum 100 -Maximum 999)"
            description = "Test with invalid instructor_id"
            credit_hours = 3
            max_capacity = 30
            instructor_id = 99999  # Non-existent ID
        } | ConvertTo-Json

        try {
            $invalidInstructorResponse = Invoke-WebRequest -Uri "$baseUrl/courses" `
                -Headers @{"Accept"="application/json"; "Authorization"="Bearer $token"; "Content-Type"="application/json"} `
                -Method POST -Body $invalidInstructorData

            Write-Host "⚠️  Invalid instructor_id was accepted (unexpected)" -ForegroundColor Yellow
        } catch {
            if ($_.Exception.Response.StatusCode -eq 422) {
                Write-Host "✅ Invalid instructor_id properly rejected" -ForegroundColor Green
                $errorContent = $_.Exception.Response.Content.ReadAsStringAsync().Result
                Write-Host "   Validation errors: $errorContent" -ForegroundColor Gray
            } else {
                Write-Host "❌ Unexpected error: $($_.Exception.Message)" -ForegroundColor Red
            }
        }

    } else {
        Write-Host "❌ Manager login failed" -ForegroundColor Red
    }
} catch {
    Write-Host "❌ Error during testing: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""
Write-Host "=== DEBUG SUMMARY ===" -ForegroundColor Cyan
Write-Host "This test helps identify which specific validation rule is failing." -ForegroundColor Gray
Write-Host "Check the error details above to see the exact validation messages." -ForegroundColor Gray
