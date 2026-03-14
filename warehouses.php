<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

// Ensure $name is available for the sidebar welcome message
$name = $_SESSION['name'] ?? 'User';

/* ADD WAREHOUSE */
if(isset($_POST['add'])){
    $name_post = $_POST['warehouse_name'];
    $location = $_POST['location'];

    mysqli_query($conn,"INSERT INTO warehouses(warehouse_name,location)
    VALUES('$name_post','$location')");
}

/* FETCH WAREHOUSES */
$data=mysqli_query($conn,"SELECT * FROM warehouses ORDER BY warehouse_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouses - CoreInventory</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
        }

        /* Glass Panel / Card Styling */
        .glass-panel {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            border-radius: 1rem;
            transition: all 0.3s ease;
        }

        /* Input Field Styling */
        .form-input {
            width: 100%;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155;
            border-radius: 0.75rem;
            padding: 0.625rem 1rem;
            transition: all 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: #f59e0b; /* Amber focus for warehouses */
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
            background-color: #ffffff;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Animation */
        .fade-in {
            opacity: 0;
            transform: translateY(10px);
            animation: fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes fadeIn { to { opacity: 1; transform: translateY(0); } }
    </style>
</head>

<body class="flex h-screen overflow-hidden text-gray-800">

    <?php include "components/sidebar.php"; ?>

    <main class="flex-1 h-full overflow-y-auto relative">
        <div class="p-6 lg:p-10 max-w-5xl mx-auto space-y-8">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 fade-in">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900">Warehouses</h1>
                    <p class="text-gray-500 mt-1">Manage your physical storage locations and facilities.</p>
                </div>
            </div>

            <div class="glass-panel p-6 lg:p-8 fade-in" style="animation-delay: 0.1s;">
                <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2"><span>🏢</span> Register New Warehouse</h3>
                
                <form method="post" class="grid grid-cols-1 md:grid-cols-2 gap-5 items-end">
                    
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Warehouse Name</label>
                        <input name="warehouse_name" placeholder="e.g. Main Hub, West Coast Facility..." class="form-input" required>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Location</label>
                        <input name="location" placeholder="e.g. City, State or Full Address..." class="form-input" required>
                    </div>

                    <div class="md:col-span-2 mt-2">
                        <button name="add" class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                            Add Warehouse Facility
                        </button>
                    </div>

                </form>
            </div>

            <div class="glass-panel overflow-hidden fade-in" style="animation-delay: 0.2s;">
                <div class="p-6 border-b border-gray-100 bg-white">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2"><span>📍</span> Facility Locations</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 text-gray-500 text-xs tracking-wider uppercase border-b border-gray-100">
                                <th class="p-4 font-bold w-16">ID</th>
                                <th class="p-4 font-bold">Warehouse Name</th>
                                <th class="p-4 font-bold">Location</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            
                            <?php if($data && mysqli_num_rows($data) > 0): ?>
                                <?php while($row=mysqli_fetch_assoc($data)){ ?>
                                <tr class="hover:bg-amber-50/30 transition-colors group">
                                    <td class="p-4 text-gray-400 font-medium">#<?php echo $row['warehouse_id']; ?></td>
                                    
                                    <td class="p-4">
                                        <div class="font-bold text-gray-900 flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                                            <?php echo htmlspecialchars($row['warehouse_name']); ?>
                                        </div>
                                    </td>
                                    
                                    <td class="p-4 text-gray-600">
                                        <?php echo htmlspecialchars($row['location']); ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="p-10 text-center text-gray-400">
                                        <div class="text-4xl mb-3">🏭</div>
                                        <p class="font-medium text-lg">No warehouses found</p>
                                        <p class="text-sm">Register your first storage facility using the form above.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

</body>
</html>