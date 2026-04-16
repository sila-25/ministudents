// Smooth interactions and AJAX for better UX
document.addEventListener('DOMContentLoaded', function() {
    // Add student with AJAX
    const addBtn = document.getElementById('addStudentBtn');
    if (addBtn) {
        addBtn.addEventListener('click', async function() {
            const name = document.getElementById('studentName').value.trim();
            const email = document.getElementById('studentEmail').value.trim();
            
            if (!name || !email) {
                showNotification('Please fill both fields', 'error');
                return;
            }
            
            const formData = new FormData();
            formData.append('name', name);
            formData.append('email', email);
            
            try {
                const response = await fetch('index.php?page=students', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    location.reload();
                } else {
                    showNotification(result.message || 'Error adding student', 'error');
                }
            } catch (error) {
                showNotification('Network error', 'error');
            }
        });
    }
    
    // Delete student with AJAX
    const deleteBtns = document.querySelectorAll('.delete-btn');
    deleteBtns.forEach(btn => {
        btn.addEventListener('click', async function() {
            const id = this.dataset.id;
            const studentItem = this.closest('.student-item');
            
            if (confirm('Remove this student?')) {
                try {
                    const response = await fetch('index.php?page=students', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `id=${id}`
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        studentItem.style.animation = 'fadeSlideUp 0.2s reverse';
                        setTimeout(() => location.reload(), 200);
                    } else {
                        showNotification('Error deleting student', 'error');
                    }
                } catch (error) {
                    showNotification('Network error', 'error');
                }
            }
        });
    });
    
    // Form animations
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.01)';
        });
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });
});

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.textContent = message;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.animation = 'fadeSlideUp 0.3s ease';
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}