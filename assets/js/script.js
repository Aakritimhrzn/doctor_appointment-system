// Form validation
function validateAppointmentForm() {
    let doctor = document.getElementById('doctor').value;
    let date = document.getElementById('appointment_date').value;
    let time = document.getElementById('appointment_time').value;
    
    if (!doctor) {
        alert('Please select a doctor');
        return false;
    }
    
    if (!date) {
        alert('Please select a date');
        return false;
    }
    
    if (!time) {
        alert('Please select a time');
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
            if (data.length === 0) {
                doctorSelect.innerHTML += '<option value="">No doctors available</option>';
            } else {
                data.forEach(doctor => {
                    doctorSelect.innerHTML += `<option value="${doctor.id}">${doctor.name} - ${doctor.qualification}</option>`;
                });
            }
        })
        .catch(error => {
            console.error('Error loading doctors:', error);
            doctorSelect.innerHTML = '<option value="">Error loading doctors</option>';
        });
}

// Real-time availability check
function checkAvailability() {
    let doctor = document.getElementById('doctor')?.value;
    let date = document.getElementById('appointment_date')?.value;
    let time = document.getElementById('appointment_time')?.value;
    
    if(doctor && date && time) {
        fetch(`check_booking.php?doctor_id=${doctor}&date=${date}&time=${time}`)
            .then(response => response.json())
            .then(data => {
                let msg = document.getElementById('availabilityMsg');
                if(!msg) {
                    msg = document.createElement('div');
                    msg.id = 'availabilityMsg';
                    msg.style.padding = '10px';
                    msg.style.marginBottom = '10px';
                    msg.style.borderRadius = '5px';
                    let form = document.querySelector('form');
                    let button = form.querySelector('button');
                    form.insertBefore(msg, button);
                }
                if(data.available) {
                    msg.innerHTML = '✅ Time slot available!';
                    msg.style.background = '#d4edda';
                    msg.style.color = '#155724';
                } else {
                    msg.innerHTML = '❌ This time slot is already booked! Please select another.';
                    msg.style.background = '#f8d7da';
                    msg.style.color = '#721c24';
                }
            });
    }
}

// Set min date to today
document.addEventListener('DOMContentLoaded', function() {
    let dateInput = document.getElementById('appointment_date');
    if(dateInput) {
        dateInput.min = new Date().toISOString().split("T")[0];
        
        // Add event listeners for availability check
        let doctorSelect = document.getElementById('doctor');
        let timeSelect = document.getElementById('appointment_time');
        
        if(doctorSelect) doctorSelect.addEventListener('change', checkAvailability);
        if(dateInput) dateInput.addEventListener('change', checkAvailability);
        if(timeSelect) timeSelect.addEventListener('change', checkAvailability);
    }
});