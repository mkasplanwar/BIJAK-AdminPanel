function openModal() {
    // Reset the form and prepare modal for adding new consultant
    document.getElementById('consultantForm').reset();
    document.getElementById('consultant_id').value = '';
    document.getElementById('consultantModal').classList.remove('hidden');
}

function closeModal() {
    // Close the modal
    document.getElementById('consultantModal').classList.add('hidden');
}

function editConsultant(id) {
    // Fetch consultant data from the backend for editing
    fetch(`?edit=${id}`)
        .then(response => response.json())
        .then(data => {
            // Fill the form with consultant data
            document.getElementById('consultant_id').value = data.id;
            document.getElementById('nama_lengkap').value = data.nama_lengkap;
            document.getElementById('spesialisasi').value = data.spesialisasi;
            document.getElementById('email').value = data.email;
            document.getElementById('no_telp').value = data.no_telp;
            document.getElementById('alamat').value = data.alamat;
            document.getElementById('tanggal_bergabung').value = data.tanggal_bergabung;
            // Show the modal
            document.getElementById('consultantModal').classList.remove('hidden');
        })
        .catch(error => console.error('Error:', error));
}

function deleteConsultant(id) {
    // Confirm deletion and redirect to delete action
    if (confirm('Are you sure you want to delete this consultant?')) {
        window.location.href = `?delete=${id}`;
    }
}

// Search functionality for consultants
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        const fullName = row.querySelector('.text-sm.font-medium').textContent.toLowerCase();
        const email = row.querySelector('.text-sm.text-gray-500').textContent.toLowerCase();
        const specialization = row.querySelector('.status-badge').textContent.toLowerCase();
        
        // Filter rows based on search value
        if (fullName.includes(searchValue) || 
            email.includes(searchValue) || 
            specialization.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Form validation for consultant form
document.getElementById('consultantForm').addEventListener('submit', function(e) {
    const form = this;
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('border-red-500');
        } else {
            field.classList.remove('border-red-500');
        }
    });

    if (!isValid) {
        e.preventDefault();
        alert('Please fill in all required fields');
    }
});

// Close modal when clicking outside
document.getElementById('consultantModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});