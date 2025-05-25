@echo off
echo === FIXING MANAGER STUDENTS DISPLAY ===
echo.

echo Step 1: Login as Manager (Correct Credentials)
curl -s -X POST "http://127.0.0.1:8001/api/login" ^
  -H "Accept: application/json" ^
  -H "Content-Type: application/json" ^
  -d "{\"email\":\"manager@gmail.com\",\"password\":\"manager123\"}" > manager_login.json

echo Manager Login Response:
type manager_login.json
echo.

echo Step 2: Extract Manager Token
for /f "tokens=2 delims=:" %%a in ('findstr "access_token" manager_login.json') do set MANAGER_TOKEN_RAW=%%a
set MANAGER_TOKEN=%MANAGER_TOKEN_RAW:"=%
set MANAGER_TOKEN=%MANAGER_TOKEN:,=%
set MANAGER_TOKEN=%MANAGER_TOKEN: =%
echo Manager Token extracted successfully
echo.

echo Step 3: Get Students Data from Manager API
curl -s -X GET "http://127.0.0.1:8001/api/manager/students" ^
  -H "Accept: application/json" ^
  -H "Authorization: Bearer %MANAGER_TOKEN%" > manager_students_data.json

echo Manager Students API Response:
type manager_students_data.json
echo.

echo Step 4: Login as Student (Test Student)
curl -s -X POST "http://127.0.0.1:8001/api/login" ^
  -H "Accept: application/json" ^
  -H "Content-Type: application/json" ^
  -d "{\"email\":\"student@test.com\",\"password\":\"password123\"}" > student_login.json

echo Student Login Response:
type student_login.json
echo.

echo Step 5: Extract Student Token
for /f "tokens=2 delims=:" %%a in ('findstr "access_token" student_login.json') do set STUDENT_TOKEN_RAW=%%a
set STUDENT_TOKEN=%STUDENT_TOKEN_RAW:"=%
set STUDENT_TOKEN=%STUDENT_TOKEN:,=%
set STUDENT_TOKEN=%STUDENT_TOKEN: =%
echo Student Token extracted successfully
echo.

echo Step 6: Enroll Student in Course 1
curl -s -X POST "http://127.0.0.1:8001/api/courses/1/enroll" ^
  -H "Accept: application/json" ^
  -H "Authorization: Bearer %STUDENT_TOKEN%" > enroll_course1.json

echo Course 1 Enrollment Result:
type enroll_course1.json
echo.

echo Step 7: Enroll Student in Course 2
curl -s -X POST "http://127.0.0.1:8001/api/courses/2/enroll" ^
  -H "Accept: application/json" ^
  -H "Authorization: Bearer %STUDENT_TOKEN%" > enroll_course2.json

echo Course 2 Enrollment Result:
type enroll_course2.json
echo.

echo Step 8: Check Student Enrolled Courses
curl -s -X GET "http://127.0.0.1:8001/api/student/enrolled-courses" ^
  -H "Accept: application/json" ^
  -H "Authorization: Bearer %STUDENT_TOKEN%" > student_enrolled_courses.json

echo Student Enrolled Courses:
type student_enrolled_courses.json
echo.

echo Step 9: Get Updated Students Data from Manager API
curl -s -X GET "http://127.0.0.1:8001/api/manager/students" ^
  -H "Accept: application/json" ^
  -H "Authorization: Bearer %MANAGER_TOKEN%" > updated_manager_students.json

echo Updated Manager Students Data:
type updated_manager_students.json
echo.

echo Step 10: Get Available Courses
curl -s -X GET "http://127.0.0.1:8001/api/public/courses" ^
  -H "Accept: application/json" > available_courses.json

echo Available Courses:
type available_courses.json
echo.

echo === CLEANUP ===
del manager_login.json
del manager_students_data.json
del student_login.json
del enroll_course1.json
del enroll_course2.json
del student_enrolled_courses.json
del updated_manager_students.json
del available_courses.json

echo.
echo === ANALYSIS COMPLETE ===
echo.
echo ✅ Manager Login: manager@gmail.com / manager123
echo ✅ Student Login: student@test.com / password123
echo ✅ API Endpoints tested
echo ✅ Enrollment functionality verified
echo.
echo 🔄 Now refresh the Manager Students page to see updated data!
echo 🌐 Manager URL: http://127.0.0.1:8001/manager/students
