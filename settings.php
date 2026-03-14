<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

// Ensure $name is available for the sidebar welcome message
$name = $_SESSION['name'] ?? 'User';

/* ADD USER */
if(isset($_POST['add_user'])){
    $new_name=$_POST['name'];
    $email=$_POST['email'];
    $password=md5($_POST['password']); // Note: In a production environment, consider using password_hash() instead of md5
    $role=$_POST['role'];

    mysqli_query($conn,"INSERT INTO users(name,email,password,role)
    VALUES('$new_name','$email','$password','$role')");
}

/* ADD CATEGORY */
if(isset($_POST['add_category'])){
    $category=$_POST['category_name'];

    mysqli_query($conn,"INSERT INTO categories(category_name)
    VALUES('$category')");
}

/* FETCH DATA */
$users=mysqli_query($conn,"SELECT * FROM users ORDER BY user_id DESC");
$categories=mysqli_query($conn,"SELECT * FROM categories ORDER BY category_id DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - CoreInventory</title>

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
            border-color: #64748b; /* Slate focus for settings */
            box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.1);
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
                    <h1 class="text-3xl font-extrabold text-gray-900 flex items-center gap-3">
                        ⚙️ System Settings
                    </h1>
                    <p class="text-gray-500 mt-1">Manage user access and product taxonomy.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <div class="space-y-8">
                    
                    <div class="glass-panel p-6 lg:p-8 fade-in" style="animation-delay: 0.1s;">
                        <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2"><span>👥</span> Add New User</h3>
                        
                        <form method="post" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Full Name</label>
                                    <input name="name" placeholder="John Doe" class="form-input" required>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Email Address</label>
                                    <input type="email" name="email" placeholder="john@company.com" class="form-input" required>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Password</label>
                                    <input type="password" name="password" placeholder="••••••••" class="form-input" required>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">System Role</label>
                                    <select name="role" class="form-input" required>
                                        <option value="Admin">Admin</option>
                                        <option value="Manager">Manager</option>
                                        <option value="Staff" selected>Staff</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button name="add_user" class="w-full bg-gradient-to-r from-slate-700 to-slate-900 hover:from-slate-800 hover:to-black text-white font-bold py-2.5 px-4 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                    Create User Account
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="glass-panel overflow-hidden fade-in" style="animation-delay: 0.2s;">
                        <div class="p-5 border-b border-gray-100 bg-white">
                            <h3 class="text-md font-bold text-gray-900">Active Users</h3>
                        </div>
                        <div class="overflow-x-auto max-h-[500px]">
                            <table class="w-full text-left border-collapse">
                                <thead class="sticky top-0 bg-gray-50/95 backdrop-blur shadow-sm z-10">
                                    <tr class="text-gray-500 text-xs tracking-wider uppercase">
                                        <th class="p-4 font-bold">User Details</th>
                                        <th class="p-4 font-bold">Role</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    <?php while($u=mysqli_fetch_assoc($users)){ ?>
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="p-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold">
                                                    <?php echo strtoupper(substr($u['name'], 0, 1)); ?>
                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-900"><?php echo htmlspecialchars($u['name']); ?></div>
                                                    <div class="text-xs text-gray-500"><?php echo htmlspecialchars($u['email']); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <?php 
                                                $role = strtolower($u['role']);
                                                $badgeColor = 'bg-gray-100 text-gray-700 border-gray-200';
                                                if($role === 'admin') $badgeColor = 'bg-purple-100 text-purple-700 border-purple-200';
                                                if($role === 'manager') $badgeColor = 'bg-blue-100 text-blue-700 border-blue-200';
                                            ?>
                                            <span class="px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider rounded-full border <?php echo $badgeColor; ?>">
                                                <?php echo htmlspecialchars($u['role']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <div class="space-y-8">
                    
                    <div class="glass-panel p-6 lg:p-8 fade-in" style="animation-delay: 0.15s;">
                        <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2"><span>🏷️</span> Add Product Category</h3>
                        
                        <form method="post" class="space-y-4">
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Category Name</label>
                                <input name="category_name" placeholder="e.g. Electronics, Apparel, Raw Materials..." class="form-input" required>
                            </div>

                            <div class="mt-4">
                                <button name="add_category" class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                    Save Category
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="glass-panel overflow-hidden fade-in" style="animation-delay: 0.25s;">
                        <div class="p-5 border-b border-gray-100 bg-white">
                            <h3 class="text-md font-bold text-gray-900">Category Taxonomy</h3>
                        </div>
                        <div class="overflow-x-auto max-h-[500px]">
                            <table class="w-full text-left border-collapse">
                                <thead class="sticky top-0 bg-gray-50/95 backdrop-blur shadow-sm z-10">
                                    <tr class="text-gray-500 text-xs tracking-wider uppercase">
                                        <th class="p-4 font-bold w-16">ID</th>
                                        <th class="p-4 font-bold">Category Name</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    <?php while($c=mysqli_fetch_assoc($categories)){ ?>
                                    <tr class="hover:bg-emerald-50/30 transition-colors group">
                                        <td class="p-4 text-gray-400 font-medium">#<?php echo $c['category_id']; ?></td>
                                        <td class="p-4">
                                            <div class="font-bold text-gray-800 group-hover:text-emerald-700 transition-colors">
                                                <?php echo htmlspecialchars($c['category_name']); ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </main>

</body>
</html>