// Fungsi untuk membuka modal
function openModal(scheduleId = '') {
    document.getElementById('scheduleModal').classList.remove('hidden');
    if (scheduleId) {
        // Edit Schedule - Mengambil data jadwal untuk diisi ke form
        fetch(`jadwal.php?edit=${scheduleId}`)  // Ganti schedule.php menjadi jadwal.php
            .then(response => response.json())
            .then(data => {
                document.getElementById('schedule_id').value = data.id;
                document.getElementById('client_id').value = data.client_id;
                document.getElementById('consultant_id').value = data.consultant_id;
                document.getElementById('tanggal_konsultasi').value = data.tanggal_konsultasi;
                document.getElementById('status').value = data.status;
                document.getElementById('catatan').value = data.catatan;
            });
    }
}

// Fungsi untuk menutup modal
function closeModal() {
    document.getElementById('scheduleModal').classList.add('hidden');
}

// Fungsi untuk membuka modal edit dengan data yang sudah ada
function editSchedule(scheduleId) {
    // Mengambil data jadwal dari server untuk diedit
    fetch('jadwal.php?edit=' + scheduleId)  // Ganti schedule.php menjadi jadwal.php
        .then(response => response.json())
        .then(data => {
            // Isi form dengan data yang diambil
            document.getElementById('schedule_id').value = data.id;
            document.getElementById('client_id').value = data.client_id;
            document.getElementById('consultant_id').value = data.consultant_id;
            document.getElementById('tanggal_konsultasi').value = data.tanggal_konsultasi;
            document.getElementById('status').value = data.status;
            document.getElementById('catatan').value = data.catatan;
            // Tampilkan modal
            document.getElementById('scheduleModal').classList.remove('hidden');
        })
        .catch(error => console.error('Error:', error));
}

// Fungsi untuk menghapus jadwal
function deleteSchedule(scheduleId) {
    if (confirm('Are you sure you want to delete this schedule?')) {
        // Menghapus jadwal melalui GET request
        window.location.href = 'jadwal.php?delete=' + scheduleId;  // Ganti schedule.php menjadi jadwal.php
    }
}

// Fungsi untuk melakukan pencarian pada tabel
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('table tbody tr');

    rows.forEach(row => {
        const clientName = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
        const consultantName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const status = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
        const notes = row.querySelector('td:nth-child(5)').textContent.toLowerCase();

        // Menyembunyikan atau menampilkan baris sesuai dengan hasil pencarian
        if (clientName.includes(searchTerm) || consultantName.includes(searchTerm) || status.includes(searchTerm) || notes.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
