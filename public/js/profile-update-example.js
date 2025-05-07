/**
 * Example of how to update a user profile using FormData
 * 
 * This file demonstrates the correct way to send profile update data
 * to the Laravel backend using FormData instead of JSON.
 */

// Function to update user profile
function updateUserProfile() {
    // Get the authentication token from localStorage
    const token = localStorage.getItem('token');
    if (!token) {
        console.error('No authentication token found. Please login first.');
        return;
    }
    
    // Create a new FormData object
    const formData = new FormData();
    
    // Add text fields to FormData
    formData.append('name', 'New User Name');
    formData.append('email', 'newemail@example.com');
    
    // If updating password, include both password and confirmation
    formData.append('password', 'newpassword123');
    formData.append('password_confirmation', 'newpassword123');
    
    // Add a file to FormData (if you have a file input)
    const fileInput = document.querySelector('input[type="file"]');
    if (fileInput && fileInput.files.length > 0) {
        formData.append('imgProfile', fileInput.files[0]);
    }
    
    // Log FormData entries for debugging
    console.log('FormData entries:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
    
    // Send the request to the API
    fetch('/api/profile/update', {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + token,
            // IMPORTANT: Do NOT set Content-Type header when using FormData
            // The browser will automatically set the correct Content-Type with boundary
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Profile updated successfully:', data);
    })
    .catch(error => {
        console.error('Error updating profile:', error);
    });
}

// Example of how to handle form submission
document.addEventListener('DOMContentLoaded', function() {
    const profileForm = document.getElementById('profileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Create FormData from the form
            const formData = new FormData(this);
            
            // Get the token
            const token = localStorage.getItem('token');
            
            // Send the request
            fetch('/api/profile/update', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Profile updated:', data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }
});
