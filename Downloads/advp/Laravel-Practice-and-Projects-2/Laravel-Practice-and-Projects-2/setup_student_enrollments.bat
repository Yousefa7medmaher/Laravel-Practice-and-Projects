@echo off
echo === SETTING UP STUDENT ENROLLMENTS FOR MANAGER VIEW ===
echo.

echo Step 1: Login as Student 1
curl -s -X POST "http://127.0.0.1:8001/api/login" ^
  -H "Accept: application/json" ^
  -H "Content-Type: application/json" ^
  -d "{\"email\":\"student@test.com\",\"password\":\"password123\"}" > student1_login.json

echo Student 1 Login:
type student1_login.json
echo.

echo Step 2: Extract Student 1 Token
for /f "tokens=2 delims=:" %%a in ('findstr "access_token" student1_login.json') do set STUDENT1_TOKEN_RAW=%%a
set STUDENT1_TOKEN=%STUDENT1_TOKEN_RAW:"=%
set STUDENT1_TOKEN=%STUDENT1_TOKEN:,=%
set STUDENT1_TOKEN=%STUDENT1_TOKEN: =%
echo Student 1 Token extracted
echo.

echo Step 3: Enroll Student 1 in Course 1
curl -s -X POST "http://127.0.0.1:8001/api/courses/1/enroll" ^
  -H "Accept: application/json" ^
  -H "Authorization: Bearer %STUDENT1_TOKEN%" > enroll1.json

echo Enrollment 1 Result:
type enroll1.json
echo.

echo Step 4: Enroll Student 1 in Course 2
curl -s -X POST "http://127.0.0.1:8001/api/courses/2/enroll" ^
  -H "Accept: application/json" ^
  -H "Authorization: Bearer %STUDENT1_TOKEN%" > enroll2.json

echo Enrollment 2 Result:
type enroll2.json
echo.

echo Step 5: Login as Manager
curl -s -X POST "http://127.0.0.1:8001/api/login" ^
  -H "Accept: application/json" ^
  -H "Content-Type: application/json" ^
  -d "{\"email\":\"manager@test.com\",\"password\":\"password123\"}" > manager_login.json

echo Manager Login:
type manager_login.json
echo.

echo Step 6: Extract Manager Token
for /f "tokens=2 delims=:" %%a in ('findstr "access_token" manager_login.json') do set MANAGER_TOKEN_RAW=%%a
set MANAGER_TOKEN=%MANAGER_TOKEN_RAW:"=%
set MANAGER_TOKEN=%MANAGER_TOKEN:,=%
set MANAGER_TOKEN=%MANAGER_TOKEN: =%
echo Manager Token extracted
echo.

echo Step 7: Check Students Data from Manager API
curl -s -X GET "http://127.0.0.1:8001/api/manager/students" ^
  -H "Accept: application/json" ^
  -H "Authorization: Bearer %MANAGER_TOKEN%" > manager_students.json

echo Manager Students Data:
type manager_students.json
echo.

echo Step 8: Check Student Enrollments
curl -s -X GET "http://127.0.0.1:8001/api/student/enrolled-courses" ^
  -H "Accept: application/json" ^
  -H "Authorization: Bearer %STUDENT1_TOKEN%" > student_enrollments.json

echo Student Enrollments:
type student_enrollments.json
echo.

echo === CLEANUP ===
del student1_login.json
del enroll1.json
del enroll2.json
del manager_login.json
del manager_students.json
del student_enrollments.json

echo.
echo === STUDENT ENROLLMENT SETUP COMPLETE ===
echo Now refresh the manager students page to see updated data!
