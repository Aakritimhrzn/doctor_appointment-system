// Form validation
function validateAppointmentForm() {
    let date = document.getElementById('appointment_date').value;
    let time = document.getElementById('appointment_time').value;
    
    if (!date || !time) {
        alert('Please select both date and time');
        return false;
    }
    
    let selectedDate = new Date(date);
    let today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (selectedDate < today) {
        alert('Please select a future date');
        return false;
    }
    
    return true;
}

function validateRegistration() {
    let name = document.getElementById('name').value;
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;
    let mobile = document.getElementById('mobile').value;
    
    if (name.length < 2) {
        alert('Name must be at least 2 characters');
        return false;
    }
    
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Please enter a valid email');
        return false;
    }
    
    if (password.length < 4) {
        alert('Password must be at least 4 characters');
        return false;
    }
    
    if (mobile.length < 10) {
        alert('Please enter a valid mobile number');
        return false;
    }
    
    return true;
}

function validateLogin() {
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;
    
    if (!email || !password) {
        alert('Please enter both email and password');
        return false;
    }
    return true;
}

// Dynamic doctor loading based on department
function loadDoctorsByDepartment() {
    let departmentId = document.getElementById('department').value;
    let doctorSelect = document.getElementById('doctor');
    
    if (!departmentId) {
        doctorSelect.innerHTML = '<option value="">Select Doctor</option>';
        return;
    }
    
    fetch(`get_doctors.php?department_id=${departmentId}`)
        .then(response => response.json())
        .then(data => {
            doctorSelect.innerHTML = '<option value="">Select Doctor</option>';
            data.forEach(doctor => {
                doctorSelect.innerHTML += `<option value="${doctor.id}">${doctor.name} - ${doctor.qualification}</option>`;
            });
        });
}