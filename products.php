<?php
session_start();
include "db.php";

// Ensure user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* -----------------------
   Helper function to log activity
------------------------ */
function logActivity($conn, $user_id, $action, $module){
    $stmt = $conn->prepare("INSERT INTO activity_logs (user_id, action, module, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $user_id, $action, $module);
    $stmt->execute();
    $stmt->close();
}

/* -----------------------
   ADD PRODUCT
------------------------ */
if(isset($_POST['add'])){
    $name = $_POST['product_name'];
    $sku = $_POST['sku'];
    $category = $_POST['category_id'];
    $unit = $_POST['unit'];

    $stmt = $conn->prepare("INSERT INTO products (product_name, sku, category_id, unit) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $name, $sku, $category, $unit);

    if($stmt->execute()){
        logActivity($conn, $user_id, "Added product: $name", "products");
    }
    $stmt->close();
}

/* -----------------------
   UPDATE PRODUCT
------------------------ */
if(isset($_POST['update'])){
    $id = $_POST['product_id'];
    $name = $_POST['product_name'];
    $sku = $_POST['sku'];
    $category = $_POST['category_id'];
    $unit = $_POST['unit'];

    $stmt = $conn->prepare("UPDATE products SET product_name=?, sku=?, category_id=?, unit=? WHERE product_id=?");
    $stmt->bind_param("ssisi", $name, $sku, $category, $unit, $id);

    if($stmt->execute()){
        logActivity($conn, $user_id, "Updated product ID $id: $name", "products");
    }
    $stmt->close();
}

/* -----------------------
   DELETE PRODUCT
------------------------ */
if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    // Fetch product name for logging
    $res = $conn->query("SELECT product_name FROM products WHERE product_id=$id");
    $row = $res->fetch_assoc();
    $productName = $row ? $row['product_name'] : "";

    $stmt = $conn->prepare("DELETE FROM products WHERE product_id=?");
    $stmt->bind_param("i", $id);

    if($stmt->execute()){
        logActivity($conn, $user_id, "Deleted product ID $id: $productName", "products");
    }
    $stmt->close();
}

/* -----------------------
   SEARCH PRODUCTS
------------------------ */
$search = "";
if(isset($_GET['search'])){
    $search = $_GET['search'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_name LIKE ? OR sku LIKE ?");
    $like = "%$search%";
    $stmt->bind_param("ss", $like, $like);
}else{
    $stmt = $conn->prepare("SELECT * FROM products");
}

$stmt->execute();
$products = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Products - CoreInventory</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="flex">
<?php include "components/sidebar.php"; ?>

<div class="flex-1 p-8">
<!-- Top bar -->
<div class="flex justify-between items-center mb-6">
<h1 class="text-3xl font-bold">Products</h1>
<form method="get" class="flex gap-2">
<input type="text" name="search" placeholder="Search product..." class="border p-2 rounded">
<button class="bg-gray-700 text-white px-4 rounded">Search</button>
</form>
</div>

<!-- Add Product Card -->
<div class="bg-white p-6 rounded shadow mb-8">
<h3 class="text-lg font-semibold mb-4">Add Product</h3>
<form method="post" class="grid grid-cols-4 gap-4">
<input name="product_name" placeholder="Product Name" required class="border p-2 rounded">
<input name="sku" placeholder="SKU Code" required class="border p-2 rounded">
<input name="category_id" placeholder="Category ID" required class="border p-2 rounded">
<input name="unit" placeholder="Unit (pcs/kg)" required class="border p-2 rounded">
<button name="add" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-700 col-span-4">Add Product</button>
</form>
</div>

<!-- Products Table -->
<div class="bg-white rounded shadow overflow-hidden">
<table class="w-full text-left">
<thead class="bg-gray-200">
<tr>
<th class="p-3">ID</th>
<th class="p-3">Name</th>
<th class="p-3">SKU</th>
<th class="p-3">Category</th>
<th class="p-3">Unit</th>
<th class="p-3">Action</th>
</tr>
</thead>
<tbody>
<?php while($row = $products->fetch_assoc()){ ?>
<tr class="border-b hover:bg-gray-50">
<td class="p-3"><?php echo $row['product_id']; ?></td>
<td class="p-3 font-medium"><?php echo $row['product_name']; ?></td>
<td class="p-3"><?php echo $row['sku']; ?></td>
<td class="p-3"><?php echo $row['category_id']; ?></td>
<td class="p-3"><?php echo $row['unit']; ?></td>
<td class="p-3 flex gap-3">
<button onclick="openEditModal('<?php echo $row['product_id']; ?>','<?php echo $row['product_name']; ?>','<?php echo $row['sku']; ?>','<?php echo $row['category_id']; ?>','<?php echo $row['unit']; ?>')" class="text-blue-600 hover:underline">Edit</button>
<a href="products.php?delete=<?php echo $row['product_id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Delete this product?')">Delete</a>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center">
<div class="bg-white p-6 rounded shadow w-96">
<h3 class="text-xl font-bold mb-4">Edit Product</h3>
<form method="post">
<input type="hidden" name="product_id" id="edit_id">
<input type="text" name="product_name" id="edit_name" class="border p-2 w-full mb-3 rounded" placeholder="Product Name">
<input type="text" name="sku" id="edit_sku" class="border p-2 w-full mb-3 rounded" placeholder="SKU">
<select name="category_id" id="edit_category" class="border p-2 w-full mb-3 rounded">
<option value="1">Mobile</option>
<option value="2">Tablet</option>
<option value="3">Accessories</option>
<option value="4">Smart Watch</option>
<option value="5">Audio</option>
</select>
<select name="unit" id="edit_unit" class="border p-2 w-full mb-4 rounded">
<option value="pcs">pcs</option>
<option value="kg">kg</option>
<option value="box">box</option>
</select>
<div class="flex justify-end gap-3">
<button type="button" onclick="closeModal()" class="border px-4 py-2 rounded">Cancel</button>
<button name="update" class="bg-blue-900 text-white px-4 py-2 rounded">Update</button>
</div>
</form>
</div>
</div>

<script>
function openEditModal(id,name,sku,category,unit){
    document.getElementById("editModal").classList.remove("hidden")
    document.getElementById("edit_id").value=id
    document.getElementById("edit_name").value=name
    document.getElementById("edit_sku").value=sku
    document.getElementById("edit_category").value=category
    document.getElementById("edit_unit").value=unit
}
function closeModal(){
    document.getElementById("editModal").classList.add("hidden")
}
</script>
</body>
</html>