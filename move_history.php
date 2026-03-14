<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

// Ensure $name is available for the sidebar welcome message
$name = $_SESSION['name'] ?? 'User';

/* FETCH MOVE HISTORY */
$data=mysqli_query($conn,"SELECT * FROM move_history ORDER BY move_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Move History - CoreInventory</title>

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
        <div class="p-6 lg:p-10 max-w-7xl mx-auto space-y-8">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 fade-in">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900">Inventory Move History</h1>
                    <p class="text-gray-500 mt-1">A complete audit log of all stock movements across your warehouses.</p>
                </div>
                
                <button onclick="window.print()" class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-lg shadow-sm hover:bg-gray-50 transition-colors text-sm font-semibold flex items-center gap-2">
                    🖨️ Print Log
                </button>
            </div>

            <div class="glass-panel overflow-hidden fade-in" style="animation-delay: 0.1s;">
                <div class="p-6 border-b border-gray-100 bg-white flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2"><span>🗄️</span> Master Audit Trail</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 text-gray-500 text-xs tracking-wider uppercase border-b border-gray-100">
                                <th class="p-4 font-bold w-16">ID</th>
                                <th class="p-4 font-bold">Product Details</th>
                                <th class="p-4 font-bold">Movement Type</th>
                                <th class="p-4 font-bold">Origin (From)</th>
                                <th class="p-4 font-bold">Destination (To)</th>
                                <th class="p-4 font-bold text-right">Quantity</th>
                                <th class="p-4 font-bold">Date & Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            
                            <?php if($data && mysqli_num_rows($data) > 0): ?>
                                <?php while($row=mysqli_fetch_assoc($data)){ 
                                    
                                    // Dynamic Styling Engine based on Movement Type
                                    $type = strtolower($row['movement_type']);
                                    $badgeColor = 'bg-gray-100 text-gray-700 border-gray-200';
                                    $qtyPrefix = '';
                                    $qtyColor = 'text-gray-900';
                                    
                                    if (strpos($type, 'receipt') !== false || strpos($type, 'in') !== false) {
                                        $badgeColor = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                                        $qtyPrefix = '+';
                                        $qtyColor = 'text-emerald-600';
                                    } elseif (strpos($type, 'delivery') !== false || strpos($type, 'out') !== false) {
                                        $badgeColor = 'bg-violet-100 text-violet-700 border-violet-200';
                                        $qtyPrefix = '-';
                                        $qtyColor = 'text-violet-600';
                                    } elseif (strpos($type, 'transfer') !== false) {
                                        $badgeColor = 'bg-cyan-100 text-cyan-700 border-cyan-200';
                                        $qtyPrefix = '↔ ';
                                        $qtyColor = 'text-cyan-600';
                                    } elseif (strpos($type, 'adjust') !== false) {
                                        $badgeColor = 'bg-rose-100 text-rose-700 border-rose-200';
                                        $qtyPrefix = '± ';
                                        $qtyColor = 'text-rose-600';
                                    }
                                ?>
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="p-4 text-gray-400 font-medium">#<?php echo $row['move_id']; ?></td>
                                    
                                    <td class="p-4">
                                        <div class="font-bold text-gray-900"><?php echo htmlspecialchars($row['product_name']); ?></div>
                                        <div class="text-xs font-mono text-gray-500 mt-0.5 border border-gray-200 bg-gray-50 inline-block px-1.5 py-0.5 rounded">
                                            SKU: <?php echo htmlspecialchars($row['sku']); ?>
                                        </div>
                                    </td>
                                    
                                    <td class="p-4">
                                        <span class="px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider rounded-full border <?php echo $badgeColor; ?>">
                                            <?php echo htmlspecialchars($row['movement_type']); ?>
                                        </span>
                                    </td>

                                    <td class="p-4 text-gray-600">
                                        <?php echo !empty($row['from_location']) ? htmlspecialchars($row['from_location']) : '<span class="text-gray-300 italic">External</span>'; ?>
                                    </td>
                                    
                                    <td class="p-4 text-gray-600">
                                        <?php echo !empty($row['to_location']) ? htmlspecialchars($row['to_location']) : '<span class="text-gray-300 italic">External</span>'; ?>
                                    </td>

                                    <td class="p-4 font-extrabold text-right text-base <?php echo $qtyColor; ?>">
                                        <?php echo $qtyPrefix . htmlspecialchars($row['quantity']); ?>
                                    </td>
                                    
                                    <td class="p-4 text-gray-500">
                                        <div><?php echo htmlspecialchars($row['movement_date']); ?></div>
                                        <div class="text-xs text-gray-400 font-mono mt-0.5"><?php echo htmlspecialchars($row['movement_time']); ?></div>
                                    </td>
                                </tr>
                                <?php } ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="p-10 text-center text-gray-400">
                                        <div class="text-4xl mb-3">📭</div>
                                        <p class="font-medium text-lg">No movements recorded yet</p>
                                        <p class="text-sm">When stock enters, leaves, or moves within your system, it will appear here.</p>
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