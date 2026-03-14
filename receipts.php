<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

$name = $_SESSION['name'] ?? 'User';

/* ADD RECEIPT */
if(isset($_POST['add'])){
    $supplier=$_POST['supplier_id'];
    $warehouse=$_POST['warehouse_id'];
    $sku=$_POST['sku'];
    $product_name=$_POST['product_name'];
    $category=$_POST['category_id'];
    $quantity=$_POST['quantity'];
    $date=$_POST['receipt_date'];
    $time=$_POST['receipt_time'];

    mysqli_query($conn,"INSERT INTO receipts
    (supplier_id,warehouse_id,sku,product_name,category_id,quantity,receipt_date,receipt_time,status)
    VALUES('$supplier','$warehouse','$sku','$product_name','$category','$quantity','$date','$time','done')");
}

/* FETCH PRODUCTS FOR SKU DROPDOWN */
$products=mysqli_query($conn,"SELECT * FROM products");

/* FETCH RECEIPTS */
$data=mysqli_query($conn,"SELECT * FROM receipts ORDER BY receipt_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipts - CoreInventory</title>
    
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
            border-color: #10b981; /* Emerald focus for receipts */
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
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
                    <h1 class="text-3xl font-extrabold text-gray-900">Goods Receipts</h1>
                    <p class="text-gray-500 mt-1">Log incoming inventory from your suppliers.</p>
                </div>
            </div>

            <div class="glass-panel p-6 lg:p-8 fade-in" style="animation-delay: 0.1s;">
                <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2"><span>📥</span> Create New Receipt</h3>
                
                <form method="post" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 items-end">
                    
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Supplier ID</label>
                        <input type="text" name="supplier_id" placeholder="e.g. SUP-001" class="form-input font-mono text-sm" required>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Warehouse ID</label>
                        <input type="text" name="warehouse_id" placeholder="e.g. WH-A" class="form-input font-mono text-sm" required>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">SKU</label>
                        <input type="text" name="sku" id="sku" list="skuList" class="form-input font-mono text-sm" placeholder="Search or Type SKU" oninput="fillProductDetails()" required>
                        <datalist id="skuList">
                            <?php
                            mysqli_data_seek($products,0);
                            while($p=mysqli_fetch_assoc($products)){
                            ?>
                                <option value="<?php echo htmlspecialchars($p['sku']); ?>" 
                                        data-name="<?php echo htmlspecialchars($p['product_name']); ?>" 
                                        data-category="<?php echo htmlspecialchars($p['category_id']); ?>">
                                </option>
                            <?php } ?>
                        </datalist>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Product Name</label>
                        <input type="text" name="product_name" id="product_name" placeholder="Auto-fills from SKU" class="form-input" readonly>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Category ID</label>
                        <input type="text" name="category_id" id="category_id" placeholder="Auto-fills" class="form-input" readonly>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Quantity</label>
                        <input type="number" name="quantity" placeholder="0" min="1" class="form-input font-bold text-lg" required>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</label>
                        <input type="date" name="receipt_date" value="<?php echo date('Y-m-d'); ?>" class="form-input">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Time</label>
                        <input type="time" name="receipt_time" value="<?php echo date('H:i:s'); ?>" class="form-input">
                    </div>

                    <div class="lg:col-span-4 mt-2">
                        <button name="add" class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                            Submit Receipt Record
                        </button>
                    </div>

                </form>
            </div>

            <div class="glass-panel overflow-hidden fade-in" style="animation-delay: 0.2s;">
                <div class="p-6 border-b border-gray-100 bg-white">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2"><span>📋</span> Receipt History</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 text-gray-500 text-xs tracking-wider uppercase border-b border-gray-100">
                                <th class="p-4 font-bold w-16">ID</th>
                                <th class="p-4 font-bold">SKU</th>
                                <th class="p-4 font-bold">Product</th>
                                <th class="p-4 font-bold">Supplier</th>
                                <th class="p-4 font-bold">Warehouse</th>
                                <th class="p-4 font-bold text-right">Qty</th>
                                <th class="p-4 font-bold">Date & Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            
                            <?php if(mysqli_num_rows($data) > 0): ?>
                                <?php while($row=mysqli_fetch_assoc($data)){ ?>
                                <tr class="hover:bg-emerald-50/30 transition-colors group">
                                    <td class="p-4 text-gray-400 font-medium">#<?php echo $row['receipt_id']; ?></td>
                                    <td class="p-4"><span class="bg-gray-100 text-gray-600 px-2 py-1 rounded font-mono text-xs border border-gray-200"><?php echo htmlspecialchars($row['sku']); ?></span></td>
                                    <td class="p-4 font-bold text-gray-900">
                                        <?php echo htmlspecialchars($row['product_name']); ?>
                                        <div class="text-xs font-normal text-gray-400 mt-0.5">Cat: <?php echo htmlspecialchars($row['category_id']); ?></div>
                                    </td>
                                    <td class="p-4 font-medium text-gray-700"><?php echo htmlspecialchars($row['supplier_id']); ?></td>
                                    <td class="p-4 text-gray-600"><?php echo htmlspecialchars($row['warehouse_id']); ?></td>
                                    <td class="p-4 font-extrabold text-emerald-600 text-right">+<?php echo htmlspecialchars($row['quantity']); ?></td>
                                    <td class="p-4 text-gray-500">
                                        <div><?php echo htmlspecialchars($row['receipt_date']); ?></div>
                                        <div class="text-xs text-gray-400"><?php echo htmlspecialchars($row['receipt_time']); ?></div>
                                    </td>
                                </tr>
                                <?php } ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="p-10 text-center text-gray-400">
                                        <div class="text-4xl mb-3">📭</div>
                                        <p class="font-medium text-lg">No receipts recorded yet</p>
                                        <p class="text-sm">Fill out the form above to add your first stock receipt.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <script>
        function fillProductDetails(){
            let skuInput = document.getElementById("sku").value;
            let options = document.getElementById("skuList").options;

            for(let i=0; i < options.length; i++){
                if(options[i].value === skuInput){
                    document.getElementById("product_name").value = options[i].getAttribute("data-name");
                    document.getElementById("category_id").value = options[i].getAttribute("data-category");
                    break; // stop searching once found
                }
            }
        }
    </script>
</body>
</html>