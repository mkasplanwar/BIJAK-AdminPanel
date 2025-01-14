<?php
session_start();

// Menyertakan file koneksi
require 'connection.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// $host = 'localhost';
// $username = 'root';
// $password = '';
// $database = 'db_bijak';
// $conn = mysqli_connect($host, $username, $password, $database);

// Handle Add/Edit Client
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['client_id'] ?? '';
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];
    $alamat = $_POST['alamat'];
    $jenis_bisnis = $_POST['jenis_bisnis'];
    
    if (empty($id)) {
        // Add new client
        $query = "INSERT INTO clients (nama_client, email, no_telp, alamat, jenis_bisnis, tanggal_bergabung) 
                  VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssss", $nama, $email, $no_telp, $alamat, $jenis_bisnis);
    } else {
        // Update existing client
        $query = "UPDATE clients SET nama_client=?, email=?, no_telp=?, alamat=?, jenis_bisnis=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssssi", $nama, $email, $no_telp, $alamat, $jenis_bisnis, $id);
    }
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

// Handle Delete Client
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM clients WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

// Get client data for editing
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM clients WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $client_data = mysqli_fetch_assoc($result);
    echo json_encode($client_data);
    exit();
}

// Fetch all clients
$query = "SELECT * FROM clients ORDER BY tanggal_bergabung DESC";
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
    <link rel="stylesheet" href="src/css/dashboard.css">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="sidebar w-64 fixed h-full">
            <div class="p-6">
                <img src="src/img/bijak.png" alt="BIJAK Logo" class="h-12 mb-8">
                <nav>
                    <a href="dashboard.php" class="nav-item active flex items-center p-4 rounded-lg mb-2">
                        <i class="fa-solid fa-store mr-3"></i>
                        <span>Data UMKM</span>
                    </a>
                    <a href="konsultan.php" class="nav-item flex items-center p-4 rounded-lg mb-2">
                        <i class="fas fa-users mr-3"></i>
                        <span>Data Konsultan</span>
                    </a>
                    <a href="jadwal.php" class="nav-item flex items-center p-4 rounded-lg mb-2">
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
                    <h2 class="text-2xl font-bold text-gray-800">Kelola Data UMKM</h2>
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
                                <p style="color: var(--secondary)" class="text-sm">Total UMKM</p>
                                <h3 class="text-2xl font-bold mt-1" style="color: var(--primary)">
                                    <?php echo mysqli_num_rows($result); ?>
                                </h3>
                            </div>
                            <div class="p-3 rounded-full" style="background: var(--light-bg)">
                                <i class="fa-solid fa-store text-xl" style="color: var(--primary)"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Client Table -->
                <div class="card rounded-xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex space-x-4">
                            <button onclick="openModal()" class="btn-primary px-6 py-2.5 text-white rounded-lg transition-colors flex items-center">
                                <i class="fas fa-plus mr-2"></i>Add UMKM
                            </button>
                            <div class="relative">
                                <input type="text" id="searchInput" placeholder="Search UMKM..." 
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
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase" style="color: var(--secondary)">Pelaku Usaha</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase" style="color: var(--secondary)">Contact</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase" style="color: var(--secondary)">Business Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase" style="color: var(--secondary)">Join Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase" style="color: var(--secondary)">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="avatar-circle h-10 w-10 flex-shrink-0 mr-4 rounded-full flex items-center justify-center">
                                                <span class="font-semibold text-lg">
                                                    <?php echo substr($row['nama_client'], 0, 1); ?>
                                                </span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium" style="color: var(--secondary)"><?php echo $row['nama_client']; ?></div>
                                                <div class="text-sm text-gray-500"><?php echo $row['email']; ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm" style="color: var(--secondary)"><?php echo $row['no_telp']; ?></div>
                                        <div class="text-sm text-gray-500"><?php echo $row['alamat']; ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="status-badge">
                                            <?php echo $row['jenis_bisnis']; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <?php echo date('d M Y', strtotime($row['tanggal_bergabung'])); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-3">
                                            <button style="color: var(--primary)" class="hover:opacity-75" onclick="editClient(<?php echo $row['id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="text-red-600 hover:opacity-75" onclick="deleteClient(<?php echo $row['id']; ?>)">
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
            </main>
        </div>
    </div>


    <!-- Add/Edit Client Modal -->
    <div id="clientModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="modal-content rounded-xl w-full max-w-md p-6 relative z-50">
            <form id="clientForm" method="POST">
                <h3 class="text-lg font-bold mb-4" style="color: var(--secondary)">Add/Edit Client</h3>
                <input type="hidden" name="client_id" id="client_id">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium" style="color: var(--secondary)">Name</label>
                        <input type="text" name="nama" id="nama" required
                               class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-none focus:ring-2"
                               style="focus:ring-color: var(--primary)">
                    </div>
                    <div>
                        <label class="block text-sm font-medium" style="color: var(--secondary)">Email</label>
                        <input type="email" name="email" id="email" required
                               class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-none focus:ring-2"
                               style="focus:ring-color: var(--primary)">
                    </div>
                    <div>
                        <label class="block text-sm font-medium" style="color: var(--secondary)">Phone Number</label>
                        <input type="tel" name="no_telp" id="no_telp" required
                               class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-none focus:ring-2"
                               style="focus:ring-color: var(--primary)">
                    </div>
                    <div>
                        <label class="block text-sm font-medium" style="color: var(--secondary)">Address</label>
                        <input type="text" name="alamat" id="alamat" required
                               class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-none focus:ring-2"
                               style="focus:ring-color: var(--primary)">
                    </div>
                    <div>
                        <label class="block text-sm font-medium" style="color: var(--secondary)">Business Type</label>
                        <input type="text" name="jenis_bisnis" id="jenis_bisnis" required
                               class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-none focus:ring-2"
                               style="focus:ring-color: var(--primary)">
                    </div>
                    <!-- Repeat for other form fields with same styling -->
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Cancel</button>
                    <button type="submit" class="btn-primary px-4 py-2 text-white rounded-lg">Save</button>
                </div>
            </form>
        </div>
    </div>
    <script src="src/js/dashboard.js"></script>
</body>
</html>