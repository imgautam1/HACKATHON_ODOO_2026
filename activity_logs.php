<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
// Ensure $name is available for the sidebar welcome message
$name = $_SESSION['name'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs - CoreInventory</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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
            border-color: #6366f1; /* Indigo focus for logs */
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
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

    <main class="flex-1 flex flex-col h-full overflow-hidden relative">
        
        <div class="flex-1 overflow-y-auto p-6 lg:p-10">
            <div class="max-w-7xl mx-auto space-y-8">

                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 fade-in">
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900 flex items-center gap-3">
                            <span class="text-indigo-500">🕒</span> Activity Logs
                        </h1>
                        <p class="text-gray-500 mt-1">Track system events, user actions, and data modifications.</p>
                    </div>
                </div>

                <div class="glass-panel overflow-hidden fade-in flex flex-col" style="animation-delay: 0.1s;">
                    
                    <div class="p-5 border-b border-gray-100 bg-white flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <h3 class="text-md font-bold text-gray-900">System Event History</h3>
                        
                        <div class="relative w-full md:w-80">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400">🔍</span>
                            </div>
                            <input type="text" id="searchLogs" class="form-input pl-10" placeholder="Search by action or table...">
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="logsTable">
                            <thead class="bg-gray-50/80 text-gray-500 text-xs tracking-wider uppercase border-b border-gray-100">
                                <tr>
                                    <th class="p-4 font-bold w-16">#</th>
                                    <th class="p-4 font-bold">Action Taken</th>
                                    <th class="p-4 font-bold">Database Table</th>
                                    <th class="p-4 font-bold">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" class="divide-y divide-gray-100 text-sm">
                                </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>

        <footer class="bg-white border-t border-gray-200 p-4 text-center text-sm text-gray-500 flex-shrink-0">
            CoreInventory System &copy; <?php echo date("Y"); ?>. All rights reserved.
        </footer>

    </main>

    <script>
        function loadData() {
            $.ajax({
                url: "ajax_crud.php",
                method: "POST",
                data: { action: "fetch_user_logs" },
                success: function(data){
                    try {
                        let res = JSON.parse(data);
                        // Make sure the AJAX rows get Tailwind hover styles if possible,
                        // otherwise we can just inject the raw HTML safely.
                        $("#tableBody").html(res.body);
                        
                        // Optional: Apply generic hover classes to injected rows if ajax_crud.php doesn't
                        $("#tableBody tr").addClass("hover:bg-indigo-50/30 transition-colors");
                        $("#tableBody td").addClass("p-4 text-gray-700");
                        $("#tableBody td:first-child").addClass("text-gray-400 font-medium");
                        
                    } catch(e) {
                        console.error("Error parsing JSON response", e);
                    }
                }
            });
        }

        $(document).ready(function(){
            loadData();

            $("#searchLogs").on("keyup", function(){
                const value = $(this).val().toLowerCase();
                
                $("#logsTable tbody tr").filter(function(){
                    // Note: If your ajax_crud.php doesn't output `.action` or `.tableName` classes,
                    // we can safely fallback to searching the entire row text.
                    let rowText = $(this).text().toLowerCase();
                    let actionText = $(this).find(".action").text().toLowerCase();
                    let tableText = $(this).find(".tableName").text().toLowerCase();
                    
                    if (actionText || tableText) {
                        $(this).toggle(actionText.includes(value) || tableText.includes(value));
                    } else {
                        // Fallback search if specific classes aren't found
                        $(this).toggle(rowText.includes(value));
                    }
                });
            });
        });
    </script>

</body>
</html>