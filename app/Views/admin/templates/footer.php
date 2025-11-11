    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<!-- Custom JS -->
<script>
// Global SweetAlert functions
function showSuccess(message) {
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: message,
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6'
    });
}

function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        confirmButtonText: 'OK',
        confirmButtonColor: '#d33'
    });
}

function showConfirm(message, callback) {
    Swal.fire({
        title: 'Are you sure?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!'
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}

// Confirm before delete
function confirmDelete(item, url) {
    showConfirm(`Are you sure you want to delete this ${item}? This action cannot be undone.`, function() {
        window.location.href = url;
    });
}

// Show alerts
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 150);
        }, 5000);
    });
});
// Sidebar dropdown positioning
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap dropdowns
    const dropdowns = document.querySelectorAll('.sidebar .dropdown');
    
    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');
        
        // Adjust dropdown position on show
        dropdown.addEventListener('show.bs.dropdown', function() {
            // Reset position first
            menu.style.position = 'absolute';
            menu.style.left = '100%';
            menu.style.top = '0';
            menu.style.marginLeft = '1px';
            menu.style.zIndex = '1001';
        });
        
        // Prevent dropdown from closing when clicking inside
        menu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.sidebar .dropdown')) {
            const openDropdowns = document.querySelectorAll('.sidebar .dropdown-menu.show');
            openDropdowns.forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });
    
    // Auto-close other dropdowns when one opens
    document.querySelectorAll('.sidebar .dropdown-toggle').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            const currentDropdown = this.closest('.dropdown');
            const allDropdowns = document.querySelectorAll('.sidebar .dropdown');
            
            allDropdowns.forEach(dropdown => {
                if (dropdown !== currentDropdown) {
                    const menu = dropdown.querySelector('.dropdown-menu');
                    if (menu) {
                        menu.classList.remove('show');
                    }
                }
            });
        });
    });
});
</script>
</body>
</html>