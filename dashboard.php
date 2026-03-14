<?php
session_start();
include "db.php";

/* check login */
if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}

$name = $_SESSION['name'] ?? 'User';

/* FILTER VALUES - Sanitize inputs */
$doc_type = isset($_GET['doc_type']) ? mysqli_real_escape_string($conn, $_GET['doc_type']) : '';
$status = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$warehouse = isset($_GET['warehouse']) ? mysqli_real_escape_string($conn, $_GET['warehouse']) : '';

/* SECURITY: Whitelist allowed tables so users can't query secure tables like 'users' via URL */
$allowed_tables = ['receipts', 'deliveries', 'transfers', 'adjustments'];
if ($doc_type !== '' && !in_array($doc_type, $allowed_tables)) {
    $doc_type = ''; 
}

/* KPI DATA */
$totalStockQuery = mysqli_query($conn,"SELECT SUM(quantity) as total FROM stock");
$totalStock = $totalStockQuery ? mysqli_fetch_assoc($totalStockQuery)['total'] ?? 0 : 0;

$lowStock = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM stock WHERE quantity<=10 AND quantity>0"));
$outStock = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM stock WHERE quantity=0"));
$pendingReceipts = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM receipts WHERE status='Waiting'"));
$pendingDeliveries = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM deliveries WHERE status='Waiting'"));
$scheduledTransfers = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM transfers WHERE status='Ready'"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CoreInventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; }
        .glass-panel {
            background: #ffffff; border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            border-radius: 1rem; transition: all 0.3s ease;
        }
        .glass-panel:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            transform: translateY(-2px);
        }
        .text-gradient-blue { background: linear-gradient(135deg, #2563eb, #06b6d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .text-gradient-red { background: linear-gradient(135deg, #ef4444, #f97316); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.5em 1.5em;
        }
        .fade-in { opacity: 0; transform: translateY(15px); animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes fadeIn { to { opacity: 1; transform: translateY(0); } }
    </style>
</head>

<body class="flex h-screen overflow-hidden text-gray-800">

    <?php include "components/sidebar.php"; ?>

    <main class="flex-1 h-full overflow-y-auto relative">
        <div class="p-6 lg:p-10 max-w-7xl mx-auto space-y-8">
            
            <div class="flex justify-between items-end fade-in">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900">Operations Overview</h2>
                    <p class="text-gray-500 mt-1">Real-time metrics for your warehouse facilities.</p>
                </div>
                <div class="hidden sm:block text-sm font-medium text-gray-400 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100">
                    <?php echo date("l, F j, Y"); ?>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 fade-in" style="animation-delay: 0.1s;">
                <div class="glass-panel p-6 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform text-6xl">📦</div>
                    <div class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Total Stock</div>
                    <div class="text-4xl font-extrabold text-gradient-blue"><?php echo number_format($totalStock); ?></div>
                </div>
                <div class="glass-panel p-6 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform text-6xl">⚠️</div>
                    <div class="text-sm font-bold text-orange-400 uppercase tracking-wider mb-1">Low Stock Items</div>
                    <div class="text-4xl font-extrabold text-orange-500"><?php echo number_format($lowStock); ?></div>
                </div>
                <div class="glass-panel p-6 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform text-6xl">🚨</div>
                    <div class="text-sm font-bold text-red-400 uppercase tracking-wider mb-1">Out of Stock</div>
                    <div class="text-4xl font-extrabold text-gradient-red"><?php echo number_format($outStock); ?></div>
                </div>
                <div class="glass-panel p-6 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform text-6xl">📥</div>
                    <div class="text-sm font-bold text-teal-500 uppercase tracking-wider mb-1">Pending Receipts</div>
                    <div class="text-4xl font-extrabold text-teal-600"><?php echo number_format($pendingReceipts); ?></div>
                </div>
                <div class="glass-panel p-6 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform text-6xl">🚚</div>
                    <div class="text-sm font-bold text-indigo-400 uppercase tracking-wider mb-1">Pending Deliveries</div>
                    <div class="text-4xl font-extrabold text-indigo-600"><?php echo number_format($pendingDeliveries); ?></div>
                </div>
                <div class="glass-panel p-6 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform text-6xl">🔄</div>
                    <div class="text-sm font-bold text-violet-400 uppercase tracking-wider mb-1">Scheduled Transfers</div>
                    <div class="text-4xl font-extrabold text-violet-600"><?php echo number_format($scheduledTransfers); ?></div>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-8 fade-in" style="animation-delay: 0.2s;">
                <div class="lg:col-span-2 space-y-8">
                    
                    <div class="glass-panel p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><span>🔍</span> Document Explorer</h3>
                        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            <select name="doc_type" class="form-select w-full bg-gray-50 border border-gray-200 text-gray-700 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Doc Type</option>
                                <option value="receipts" <?php if($doc_type=='receipts') echo 'selected'; ?>>Receipts</option>
                                <option value="deliveries" <?php if($doc_type=='deliveries') echo 'selected'; ?>>Deliveries</option>
                                <option value="transfers" <?php if($doc_type=='transfers') echo 'selected'; ?>>Transfers</option>
                                <option value="adjustments" <?php if($doc_type=='adjustments') echo 'selected'; ?>>Adjustments</option>
                            </select>
                            
                            <select name="status" class="form-select w-full bg-gray-50 border border-gray-200 text-gray-700 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Status</option>
                                <option <?php if($status=='Draft') echo 'selected'; ?>>Draft</option>
                                <option <?php if($status=='Waiting') echo 'selected'; ?>>Waiting</option>
                                <option <?php if($status=='Ready') echo 'selected'; ?>>Ready</option>
                                <option <?php if($status=='Done') echo 'selected'; ?>>Done</option>
                                <option <?php if($status=='Canceled') echo 'selected'; ?>>Canceled</option>
                            </select>
                            
                            <select name="warehouse" class="form-select w-full bg-gray-50 border border-gray-200 text-gray-700 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Warehouse</option>
                                <?php
                                $w = mysqli_query($conn,"SELECT * FROM warehouses");
                                if($w) {
                                    while($row = mysqli_fetch_assoc($w)){
                                        $sel = ($warehouse == $row['warehouse_id']) ? 'selected' : '';
                                        echo "<option value='".$row['warehouse_id']."' $sel>".$row['warehouse_name']."</option>";
                                    }
                                }
                                ?>
                            </select>
                            
                            <button class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 text-white font-bold py-2.5 px-4 rounded-lg shadow-md transition-all">
                                Apply Filter
                            </button>
                        </form>
                    </div>

                    <div class="glass-panel overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-white">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2"><span>📋</span> Filtered Results</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                
                                <?php
                                $excluded_columns = ['id', 'receipt_id', 'delivery_id', 'transfer_id', 'adjustment_id'];

                                if ($doc_type != "") {
                                    
                                    // Build safe query
                                    $query = "SELECT * FROM `$doc_type` WHERE 1";
                                    if($status != "") $query .= " AND status='$status'";
                                    if($warehouse != "" && $doc_type != "transfers") $query .= " AND warehouse_id='$warehouse'";
                                    
                                    $result = mysqli_query($conn, $query);
                                    
                                    if ($result && mysqli_num_rows($result) > 0) {
                                        
                                        // 1. FETCH COLUMNS DYNAMICALLY FROM DATABASE
                                        $fields = mysqli_fetch_fields($result);
                                        $columns = [];
                                        
                                        foreach ($fields as $field) {
                                            if (!in_array($field->name, $excluded_columns)) {
                                                $columns[] = $field->name;
                                            }
                                        }
                                        
                                        // 2. RENDER DYNAMIC HEADERS
                                        echo "<thead><tr class='bg-gray-50/50 text-gray-500 text-sm tracking-wide uppercase border-b border-gray-100'>";
                                        foreach ($columns as $col) {
                                            $displayName = ucwords(str_replace('_', ' ', $col));
                                            if ($displayName == 'Product Id') $displayName = 'Product';
                                            if ($displayName == 'Warehouse Id') $displayName = 'Warehouse';
                                            echo "<th class='p-4 font-semibold whitespace-nowrap'>$displayName</th>";
                                        }
                                        echo "</tr></thead>";
                                        
                                        // 3. RENDER DYNAMIC BODY DATA
                                        echo "<tbody class='divide-y divide-gray-100 text-gray-700'>";
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr class='hover:bg-blue-50/30 transition-colors'>";
                                            
                                            foreach ($columns as $col) {
                                                $val = $row[$col] ?? '';
                                                
                                                // Format status badges dynamically
                                                if (strtolower($col) == 'status') {
                                                    $badgeColor = 'bg-gray-100 text-gray-700';
                                                    if(in_array(strtolower($val), ['ready', 'done'])) $badgeColor = 'bg-green-100 text-green-700';
                                                    if(in_array(strtolower($val), ['waiting', 'draft'])) $badgeColor = 'bg-yellow-100 text-yellow-700';
                                                    if(strtolower($val) == 'canceled') $badgeColor = 'bg-red-100 text-red-700';
                                                    
                                                    echo "<td class='p-4'><span class='px-3 py-1 text-xs font-bold rounded-full $badgeColor'>".ucfirst($val)."</span></td>";
                                                } else {
                                                    // output standard cell
                                                    echo "<td class='p-4 whitespace-nowrap'>".htmlspecialchars($val)."</td>";
                                                }
                                            }
                                            echo "</tr>";
                                        }
                                        echo "</tbody>";
                                        
                                    } else {
                                        // NO RECORDS FOUND
                                        echo "<thead><tr class='bg-gray-50/50 text-gray-500 text-sm tracking-wide uppercase border-b border-gray-100'>";
                                        echo "<th class='p-4 font-semibold'>Result</th></tr></thead>";
                                        echo "<tbody><tr><td class='p-8 text-center text-gray-400'>No records found matching your filters.</td></tr></tbody>";
                                    }
                                } else {
                                    // NO TABLE SELECTED
                                    echo "<thead><tr class='bg-gray-50/50 text-gray-500 text-sm tracking-wide uppercase border-b border-gray-100'>";
                                    echo "<th class='p-4 font-semibold'>Select a document type to view data</th></tr></thead>";
                                    echo "<tbody><tr><td class='p-8 text-center text-gray-400 flex flex-col items-center justify-center gap-2'><span class='text-3xl'>📭</span> Apply filters above to populate data table.</td></tr></tbody>";
                                }
                                ?>
                                
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="glass-panel p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><span>⚡</span> Action Center</h3>
                        <div class="flex flex-col gap-3">
                            <a href="products.php" class="flex items-center gap-3 w-full bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white font-semibold py-3 px-4 rounded-xl transition-all group">
                                <span class="group-hover:scale-110 transition-transform">➕</span> Add Product
                            </a>
                            <a href="receipts.php" class="flex items-center gap-3 w-full bg-teal-50 text-teal-700 hover:bg-teal-600 hover:text-white font-semibold py-3 px-4 rounded-xl transition-all group">
                                <span class="group-hover:scale-110 transition-transform">📥</span> View Receipts
                            </a>
                            <a href="deliveries.php" class="flex items-center gap-3 w-full bg-indigo-50 text-indigo-700 hover:bg-indigo-600 hover:text-white font-semibold py-3 px-4 rounded-xl transition-all group">
                                <span class="group-hover:scale-110 transition-transform">🚚</span> Create Delivery
                            </a>
                            <a href="transfers.php" class="flex items-center gap-3 w-full bg-violet-50 text-violet-700 hover:bg-violet-600 hover:text-white font-semibold py-3 px-4 rounded-xl transition-all group">
                                <span class="group-hover:scale-110 transition-transform">🔄</span> Transfer Stock
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="mt-12 text-center text-sm text-gray-400 py-6 border-t border-gray-200 fade-in">
                CoreInventory System © <?php echo date("Y"); ?>
            </footer>
        </div>
    </main>

</body>
</html>