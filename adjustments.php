<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

// Ensure $name is available for the sidebar welcome message
$name = $_SESSION['name'] ?? 'User';

/* ADD ADJUSTMENT */
if(isset($_POST['add'])){
    $product=$_POST['product_id'];
    $warehouse=$_POST['warehouse_id'];
    $count=$_POST['counted_quantity'];
    $reason=$_POST['reason'];
    $date=$_POST['adjustment_date'];

    mysqli_query($conn,"INSERT INTO adjustments(product_id,warehouse_id,counted_quantity,adjustment_date,reason)
    VALUES('$product','$warehouse','$count','$date','$reason')");
}

/* FETCH PRODUCTS */
$products=mysqli_query($conn,"SELECT * FROM products");

/* FETCH DATA */
$data=mysqli_query($conn,"SELECT * FROM adjustments ORDER BY adjustment_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Adjustments - CoreInventory</title>

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
            border-color: #f43f5e; /* Rose focus for adjustments */
            box-shadow: 0 0 0 3px rgba(244, 63, 94, 0.1);
            background-color: #ffffff;
        }
        .form-input[readonly] {
            background-color: #f1f5f9;
            color: #64748b;
            cursor: not-allowed;
            border-color: #e2e8f0;
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
                    <h1 class="text-3xl font-extrabold text-gray-900">Stock Adjustments</h1>
                    <p class="text-gray-500 mt-1">Log inventory discrepancies, damages, or audit corrections.</p>
                </div>
            </div>

            <div class="glass-panel p-6 lg:p-8 fade-in" style="animation-delay: 0.1s;">
                <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2"><span>⚖️</span> Create Adjustment</h3>
                
                <form method="post" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5 items-end">
                    
                    <div class="space-y-1 lg:col-span-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Product</label>
                        <select name="product_id" class="form-input" required>
                            <option value="" disabled selected>Select a Product...</option>
                            <?php while($p=mysqli_fetch_assoc($products)){ ?>
                                <option value="<?php echo htmlspecialchars($p['product_id']); ?>">
                                    <?php echo htmlspecialchars($p['product_name']); ?> (ID: <?php echo htmlspecialchars($p['product_id']); ?>)
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Warehouse ID</label>
                        <input name="warehouse_id" placeholder="e.g. WH-C" class="form-input font-mono text-sm" required>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Counted Quantity</label>
                        <input type="number" name="counted_quantity" placeholder="Exact Count" class="form-input font-bold text-lg" required>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</label>
                        <input type="date" name="adjustment_date" value="<?php echo date('Y-m-d'); ?>" class="form-input" readonly>
                    </div>

                    <div class="space-y-1 lg:col-span-4">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Reason</label>
                        <input name="reason" placeholder="e.g., Damage, Audit, Correction, Expiry..." class="form-input" required>
                    </div>

                    <div class="lg:col-span-1 mt-2">
                        <button name="add" class="w-full h-full bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-700 hover:to-red-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                            Submit
                        </button>
                    </div>

                </form>
            </div>

            <div class="glass-panel overflow-hidden fade-in" style="animation-delay: 0.2s;">
                <div class="p-6 border-b border-gray-100 bg-white">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2"><span>📋</span> Adjustment History</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 text-gray-500 text-xs tracking-wider uppercase border-b border-gray-100">
                                <th class="p-4 font-bold w-16">ID</th>
                                <th class="p-4 font-bold">Product ID</th>
                                <th class="p-4 font-bold">Warehouse</th>
                                <th class="p-4 font-bold">Actual Count</th>
                                <th class="p-4 font-bold">Reason</th>
                                <th class="p-4 font-bold">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            
                            <?php if($data && mysqli_num_rows($data) > 0): ?>
                                <?php while($row=mysqli_fetch_assoc($data)){ ?>
                                <tr class="hover:bg-rose-50/30 transition-colors group">
                                    <td class="p-4 text-gray-400 font-medium">#<?php echo $row['adjustment_id']; ?></td>
                                    <td class="p-4 font-mono font-bold text-gray-900"><?php echo htmlspecialchars($row['product_id']); ?></td>
                                    <td class="p-4 text-gray-600"><?php echo htmlspecialchars($row['warehouse_id']); ?></td>
                                    <td class="p-4 font-extrabold text-gray-800 text-lg"><?php echo htmlspecialchars($row['counted_quantity']); ?></td>
                                    
                                    <td class="p-4">
                                        <?php 
                                            // Dynamic Badge Styling based on keywords
                                            $reasonStr = strtolower($row['reason']);
                                            $badgeColor = 'bg-gray-100 text-gray-700 border border-gray-200'; // Default
                                            
                                            if (strpos($reasonStr, 'damage') !== false || strpos($reasonStr, 'broken') !== false || strpos($reasonStr, 'expired') !== false) {
                                                $badgeColor = 'bg-red-100 text-red-700 border border-red-200';
                                            } elseif (strpos($reasonStr, 'audit') !== false || strpos($reasonStr, 'check') !== false) {
                                                $badgeColor = 'bg-blue-100 text-blue-700 border border-blue-200';
                                            } elseif (strpos($reasonStr, 'correction') !== false || strpos($reasonStr, 'found') !== false) {
                                                $badgeColor = 'bg-yellow-100 text-yellow-800 border border-yellow-200';
                                            }
                                        ?>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full <?php echo $badgeColor; ?>">
                                            <?php echo htmlspecialchars($row['reason']); ?>
                                        </span>
                                    </td>

                                    <td class="p-4 text-gray-500"><?php echo htmlspecialchars($row['adjustment_date']); ?></td>
                                </tr>
                                <?php } ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="p-10 text-center text-gray-400">
                                        <div class="text-4xl mb-3">⚖️</div>
                                        <p class="font-medium text-lg">No stock adjustments logged</p>
                                        <p class="text-sm">Use the form above to record discrepancies.</p>
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