function openModal() {
    document.getElementById('clientForm').reset();
    document.getElementById('client_id').value = '';
    document.getElementById('clientModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('clientModal').classList.add('hidden');
}

function editClient(id) {
    fetch(`?edit=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('client_id').value = data.id;
            document.getElementById('nama').value = data.nama_client;
            document.getElementById('email').value = data.email;
            document.getElementById('no_telp').value = data.no_telp;
            document.getElementById('alamat').value = data.alamat;
            document.getElementById('jenis_bisnis').value = data.jenis_bisnis;
            document.getElementById('clientModal').classList.remove('hidden');
        })
        .catch(error => console.error('Error:', error));
}

function deleteClient(id) {
    if(confirm('Are you sure you want to delete this client?')) {
        window.location.href = `?delete=${id}`;
    }
}

// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        const clientName = row.querySelector('.text-sm.font-medium').textContent.toLowerCase();
        const email = row.querySelector('.text-sm.text-gray-500').textContent.toLowerCase();
        const businessType = row.querySelector('.status-badge').textContent.toLowerCase();
        
        if (clientName.includes(searchValue) || 
            email.includes(searchValue) || 
            businessType.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Form validation
document.getElementById('clientForm').addEventListener('submit', function(e) {
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
document.getElementById('clientModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});