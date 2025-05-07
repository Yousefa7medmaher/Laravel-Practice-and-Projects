<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile Update Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }
        .response {
            margin-top: 20px;
            padding: 15px;
            background-color: #f5f5f5;
            border-radius: 5px;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <h1>Profile Update Test</h1>
    
    <form id="profileForm" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter your name">
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email">
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter new password">
        </div>
        
        <div class="form-group">
            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
        </div>
        
        <div class="form-group">
            <label for="imgProfile">Profile Image:</label>
            <input type="file" id="imgProfile" name="imgProfile">
        </div>
        
        <button type="submit">Update Profile</button>
    </form>
    
    <div class="response" id="response"></div>
    
    <script>
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get the token from localStorage
            const token = localStorage.getItem('token');
            if (!token) {
                document.getElementById('response').textContent = 'Error: No authentication token found. Please login first.';
                return;
            }
            
            // Create FormData object
            const formData = new FormData(this);
            
            // Log the FormData entries for debugging
            console.log('FormData entries:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            // Send the request
            fetch('/api/profile/update', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    // Do NOT set Content-Type header when using FormData
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('response').textContent = JSON.stringify(data, null, 2);
            })
            .catch(error => {
                document.getElementById('response').textContent = 'Error: ' + error.message;
            });
        });
        
        // Check if user is logged in
        window.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('token');
            const user = localStorage.getItem('user');
            
            if (token && user) {
                const userData = JSON.parse(user);
                document.getElementById('name').value = userData.name || '';
                document.getElementById('email').value = userData.email || '';
            } else {
                document.getElementById('response').textContent = 'Warning: You are not logged in. Please login first to test profile updates.';
            }
        });
    </script>
</body>
</html>
