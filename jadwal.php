<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'db_bijak';
$conn = mysqli_connect($host, $username, $password, $database);

// Handle Add/Edit Schedule
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['schedule_id'] ?? '';
    $client_id = $_POST['client_id'];
    $consultant_id = $_POST['consultant_id'];
    $tanggal_konsultasi = $_POST['tanggal_konsultasi'];
    $status = $_POST['status'];
    $catatan = $_POST['catatan'];
    
    if (empty($id)) {
        // Add new schedule
        $query = "INSERT INTO schedules (clients_id, consultant_id, tanggal_konsultasi, status, catatan) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iisss", $client_id, $consultant_id, $tanggal_konsultasi, $status, $catatan);
    } else {
        // Update existing schedule
        $query = "UPDATE schedules SET clients_id=?, consultant_id=?, tanggal_konsultasi=?, status=?, catatan=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iisssi", $client_id, $consultant_id, $tanggal_konsultasi, $status, $catatan, $id);
    }

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Handle Delete Schedule
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM schedules WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error deleting schedule: " . mysqli_error($conn);
    }
}

// Get schedule data for editing
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM schedules WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $schedule_data = mysqli_fetch_assoc($result);
    echo json_encode($schedule_data);
    exit();
}

// Fetch the count of scheduled appointments
$query = "SELECT COUNT(*) AS total_jadwal FROM schedules";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$total_jadwal = $row['total_jadwal'];

$query = "SELECT schedules.id, clients.nama_client, consultants.nama_lengkap AS nama_consultant, schedules.tanggal_konsultasi, schedules.status, schedules.catatan
          FROM schedules
          JOIN clients ON schedules.clients_id = clients.id
          JOIN consultants ON schedules.consultant_id = consultants.id
          ORDER BY schedules.tanggal_konsultasi DESC";
$result = mysqli_query($conn, $query);
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIJAK Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="src/css/jadwal.css">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="sidebar w-64 fixed h-full">
            <div class="p-6">
                <img src="src/img/bijak.png" alt="BIJAK Logo" class="h-12 mb-8">
                <nav>
                    <a href="dashboard.php" class="nav-item flex items-center p-4 rounded-lg mb-2">
                        <i class="fa-solid fa-store mr-3"></i>
                        <span>Data UMKM</span>
                    </a>
                    <a href="konsultan.php" class="nav-item flex items-center p-4 rounded-lg mb-2">
                        <i class="fas fa-users mr-3"></i>
                        <span>Data Konsultan</span>
                    </a>
                    <a href="jadwal.php" class="nav-item active flex items-center p-4 rounded-lg mb-2">
                        <i class="fa-solid fa-calendar-days mr-3"></i>
                        <span>Penjadwalan</span>
                    </a>
                    <a href="index.php" class="nav-item flex items-center p-4 rounded-lg">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        <span>Logout</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="ml-64 flex-1">
            <header class="bg-white shadow-sm sticky top-0 z-10">
                <div class="flex justify-between items-center px-8 py-4">
                    <h2 class="text-2xl font-bold text-gray-800">Kelola Penjadwalan</h2>
                    <div class="flex items-center space-x-4">
                        <img src="src/img/profile.png" alt="Admin" class="w-10 h-10 rounded-full border-2" style="border-color: var(--primary)">
                        <div>
                            <p class="font-semibold text-gray-700"><?php echo $_SESSION['username']; ?></p>
                            <p class="text-sm" style="color: var(--primary)">Administrator</p>
                        </div>
                    </div>
                </div>
            </header>

            <main class="p-8">
                <!-- Stats Card -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="card rounded-xl p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p style="color: var(--secondary)" class="text-sm">Total Jadwal</p>
                                <h3 class="text-2xl font-bold mt-1" style="color: var(--primary)">
                                    <?php echo $total_jadwal; ?>
                                </h3>
                            </div>
                            <div class="p-3 rounded-full" style="background: var(--light-bg)">
                                <i class="fa-solid fa-store text-xl" style="color: var(--primary)"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule Table -->
<div class="card rounded-xl shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <div class="flex space-x-4">
            <button onclick="openModal()" class="btn-primary px-6 py-2.5 text-white rounded-lg transition-colors flex items-center">
                <i class="fas fa-plus mr-2"></i>Add Schedule
            </button>
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search Schedules..." 
                       class="pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2"
                       style="focus:ring-color: var(--primary)">
                <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg">
        <table class="w-full">
            <thead>
                <tr class="table-header">
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase" style="color: var(--secondary)">Client Name</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase" style="color: var(--secondary)">Consultant Name</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase" style="color: var(--secondary)">Consultation Date</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase" style="color: var(--secondary)">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase" style="color: var(--secondary)">Notes</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase" style="color: var(--secondary)">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium" style="color: var(--secondary)"><?php echo $row['nama_client']; ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium" style="color: var(--secondary)"><?php echo $row['nama_consultant']; ?></div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?php echo date('d M Y H:i', strtotime($row['tanggal_konsultasi'])); ?>
                    </td>
                    <td class="px-6 py-4">
                        <span class="status-badge" style="color: white; padding: 4px 8px; border-radius: 8px; 
                            <?php 
                                if ($row['status'] == 'dijadwalkan') { echo 'background-color: #F59E0B'; }
                                elseif ($row['status'] == 'selesai') { echo 'background-color: #10B981'; }
                                elseif ($row['status'] == 'dibatalkan') { echo 'background-color: #EF4444'; }
                            ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?php echo $row['catatan']; ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-3">
                            <button style="color: var(--primary)" class="hover:opacity-75" onclick="editSchedule(<?php echo $row['id']; ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:opacity-75" onclick="deleteSchedule(<?php echo $row['id']; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Schedule Modal -->
<div id="scheduleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="modal-content rounded-xl w-full max-w-md p-6 relative z-50">
        <form id="scheduleForm" method="POST">
            <h3 class="text-lg font-bold mb-4" style="color: var(--secondary)">Add/Edit Schedule</h3>
            <input type="hidden" name="schedule_id" id="schedule_id">
            
            <!-- Client -->
            <div class="mb-4">
                <label class="block text-sm font-medium" style="color: var(--secondary)">Client</label>
                <select name="client_id" id="client_id" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-none focus:ring-2" required>
                    <option value="">Select Client</option>
                    <!-- Fetch client options from the database -->
                    <?php
                    // Fetch clients from the database
                    $client_query = "SELECT id, nama_client FROM clients";
                    $client_result = mysqli_query($conn, $client_query);
                    while ($client_row = mysqli_fetch_assoc($client_result)) {
                        echo "<option value='".$client_row['id']."'>".$client_row['nama_client']."</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Consultant -->
            <div class="mb-4">
                <label class="block text-sm font-medium" style="color: var(--secondary)">Consultant</label>
                <select name="consultant_id" id="consultant_id" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-none focus:ring-2" required>
                    <option value="">Select Consultant</option>
                    <!-- Fetch consultant options from the database -->
                    <?php
                    // Fetch consultants from the database
                    $consultant_query = "SELECT id, nama_lengkap FROM consultants";
                    $consultant_result = mysqli_query($conn, $consultant_query);
                    while ($consultant_row = mysqli_fetch_assoc($consultant_result)) {
                        echo "<option value='".$consultant_row['id']."'>".$consultant_row['nama_lengkap']."</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Date and Time -->
            <div class="mb-4">
                <label class="block text-sm font-medium" style="color: var(--secondary)">Consultation Date</label>
                <input type="datetime-local" name="tanggal_konsultasi" id="tanggal_konsultasi" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-none focus:ring-2" required>
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label class="block text-sm font-medium" style="color: var(--secondary)">Status</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-none focus:ring-2" required>
                    <option value="dijadwalkan">Dijadwalkan</option>
                    <option value="selesai">Selesai</option>
                    <option value="dibatalkan">Dibatalkan</option>
                </select>
            </div>

            <!-- Notes -->
            <div class="mb-4">
                <label class="block text-sm font-medium" style="color: var(--secondary)">Notes</label>
                <textarea name="catatan" id="catatan" rows="4" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-none focus:ring-2"></textarea>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex justify-end space-x-4">
                <button type="button" onclick="closeModal()" class="px-6 py-3 text-gray-600 hover:bg-gray-100 rounded-lg">Cancel</button>
                <button type="submit" class="btn-primary px-6 py-3 text-white rounded-lg">Save</button>
            </div>
        </form>
    </div>
</div>
<script src="src/js/jadwal.js"></script>
</body>
</html>