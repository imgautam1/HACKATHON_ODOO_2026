<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

/* ADD PRODUCT */
if(isset($_POST['add'])){
    $name=$_POST['product_name'];
    $sku=$_POST['sku'];
    $category=$_POST['category_id'];
    $unit=$_POST['unit'];

    mysqli_query($conn,"INSERT INTO products(product_name,sku,category_id,unit)
    VALUES('$name','$sku','$category','$unit')");
}

/* UPDATE PRODUCT */
if(isset($_POST['update'])){
    $id=$_POST['product_id'];
    $name=$_POST['product_name'];
    $sku=$_POST['sku'];
    $category=$_POST['category_id'];
    $unit=$_POST['unit'];

    mysqli_query($conn,"UPDATE products SET
    product_name='$name',
    sku='$sku',
    category_id='$category',
    unit='$unit'
    WHERE product_id=$id");
}

/* DELETE PRODUCT */
if(isset($_GET['delete'])){
    $id=$_GET['delete'];
    mysqli_query($conn,"DELETE FROM products WHERE product_id=$id");
}

/* SEARCH */
$search="";
if(isset($_GET['search'])){
    $search=$_GET['search'];
    $products=mysqli_query($conn,"SELECT * FROM products WHERE product_name LIKE '%$search%' OR sku LIKE '%$search%'");
}else{
    $products=mysqli_query($conn,"SELECT * FROM products");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - CoreInventory</title>

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
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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
        
        <div class="p-6 lg:p-10 max-w-7xl mx-auto space-y-8">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 fade-in">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900">Products Database</h1>
                    <p class="text-gray-500 mt-1">Manage your inventory catalog, SKUs, and categories.</p>
                </div>

                <form method="get" class="flex w-full md:w-auto gap-2">
                    <div class="relative w-full md:w-64">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">🔍</span>
                        <input type="text" name="search" placeholder="Search by name or SKU..." value="<?php echo htmlspecialchars($search); ?>" class="form-input pl-10 w-full shadow-sm">
                    </div>
                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-semibold py-2 px-5 rounded-xl shadow-md transition-colors whitespace-nowrap">
                        Search
                    </button>
                    <?php if($search != ""): ?>
                        <a href="products.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-xl transition-colors">Clear</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="glass-panel p-6 lg:p-8 fade-in" style="animation-delay: 0.1s;">
                <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2"><span>➕</span> Add New Product</h3>
                
                <form method="post" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    
                    <div class="space-y-1 lg:col-span-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Product Name</label>
                        <input type="text" name="product_name" placeholder="e.g. iPhone 15 Pro" required class="form-input">
                    </div>

                    <div class="space-y-1 lg:col-span-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">SKU Code</label>
                        <input type="text" name="sku" placeholder="IP15-PRO-BLK" required class="form-input">
                    </div>

                    <div class="space-y-1 lg:col-span-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Category ID</label>
                        <select name="category_id" required class="form-input appearance-none bg-white">
                            <option value="" disabled selected>Select Category</option>
                            <option value="1">1 - Mobile</option>
                            <option value="2">2 - Tablet</option>
                            <option value="3">3 - Accessories</option>
                            <option value="4">4 - Smart Watch</option>
                            <option value="5">5 - Audio</option>
                        </select>
                    </div>

                    <div class="space-y-1 lg:col-span-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Unit</label>
                        <select name="unit" required class="form-input appearance-none bg-white">
                            <option value="pcs">Pieces (pcs)</option>
                            <option value="kg">Kilograms (kg)</option>
                            <option value="box">Boxes (box)</option>
                        </select>
                    </div>

                    <div class="lg:col-span-1">
                        <button type="submit" name="add" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                            Add Product
                        </button>
                    </div>

                </form>
            </div>

            <div class="glass-panel overflow-hidden fade-in" style="animation-delay: 0.2s;">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 text-gray-500 text-xs tracking-wider uppercase border-b border-gray-100">
                                <th class="p-4 font-bold w-16">ID</th>
                                <th class="p-4 font-bold">Product Name</th>
                                <th class="p-4 font-bold">SKU</th>
                                <th class="p-4 font-bold">Category ID</th>
                                <th class="p-4 font-bold">Unit</th>
                                <th class="p-4 font-bold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            
                            <?php if(mysqli_num_rows($products) > 0): ?>
                                <?php while($row=mysqli_fetch_assoc($products)){ ?>
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="p-4 text-gray-400 font-medium">#<?php echo $row['product_id']; ?></td>
                                    <td class="p-4 font-bold text-gray-900"><?php echo htmlspecialchars($row['product_name']); ?></td>
                                    <td class="p-4"><span class="bg-gray-100 text-gray-600 px-2 py-1 rounded font-mono text-xs border border-gray-200"><?php echo htmlspecialchars($row['sku']); ?></span></td>
                                    <td class="p-4 text-gray-600"><?php echo htmlspecialchars($row['category_id']); ?></td>
                                    <td class="p-4 text-gray-600 capitalize"><?php echo htmlspecialchars($row['unit']); ?></td>
                                    <td class="p-4 flex justify-end gap-2">
                                        <button onclick="openEditModal(
                                            '<?php echo $row['product_id']; ?>',
                                            '<?php echo addslashes($row['product_name']); ?>',
                                            '<?php echo addslashes($row['sku']); ?>',
                                            '<?php echo $row['category_id']; ?>',
                                            '<?php echo addslashes($row['unit']); ?>'
                                            )" 
                                            class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-lg font-semibold transition-colors text-xs">
                                            ✏️ Edit
                                        </button>
                                        
                                        <a href="products.php?delete=<?php echo $row['product_id']; ?>" 
                                           onclick="return confirm('Are you sure you want to delete <?php echo addslashes($row['product_name']); ?>? This action cannot be undone.')"
                                           class="inline-flex items-center gap-1 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white px-3 py-1.5 rounded-lg font-semibold transition-colors text-xs">
                                           🗑️ Delete
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="p-10 text-center text-gray-400">
                                        <div class="text-4xl mb-3">📦</div>
                                        <p class="font-medium text-lg">No products found</p>
                                        <p class="text-sm">Add a product above or adjust your search.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <div id="editModal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex justify-center items-center transition-opacity">
        <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md mx-4 transform transition-all scale-100">
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Edit Product</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
            </div>

            <form method="post" class="space-y-4">
                <input type="hidden" name="product_id" id="edit_id">

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Product Name</label>
                    <input type="text" name="product_name" id="edit_name" class="form-input" required>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">SKU Code</label>
                    <input type="text" name="sku" id="edit_sku" class="form-input font-mono text-sm" required>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</label>
                    <select name="category_id" id="edit_category" class="form-input appearance-none bg-white">
                        <option value="1">1 - Mobile</option>
                        <option value="2">2 - Tablet</option>
                        <option value="3">3 - Accessories</option>
                        <option value="4">4 - Smart Watch</option>
                        <option value="5">5 - Audio</option>
                    </select>
                </div>

                <div class="space-y-1 mb-6">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Unit</label>
                    <select name="unit" id="edit_unit" class="form-input appearance-none bg-white">
                        <option value="pcs">Pieces (pcs)</option>
                        <option value="kg">Kilograms (kg)</option>
                        <option value="box">Boxes (box)</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeModal()" class="px-5 py-2.5 rounded-xl font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" name="update" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md transition-all">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById("editModal");

        function openEditModal(id, name, sku, category, unit){
            modal.classList.remove("hidden");
            
            document.getElementById("edit_id").value = id;
            document.getElementById("edit_name").value = name;
            document.getElementById("edit_sku").value = sku;
            document.getElementById("edit_category").value = category;
            document.getElementById("edit_unit").value = unit;
        }

        function closeModal(){
            modal.classList.add("hidden");
        }

        // Close modal if user clicks outside the modal content box
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>