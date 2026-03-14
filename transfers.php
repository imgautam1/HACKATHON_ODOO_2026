<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

// Ensure $name is available for the sidebar welcome message
$name = $_SESSION['name'] ?? 'User';

/* ADD TRANSFER */
if(isset($_POST['add'])){
    $from=$_POST['from_warehouse'];
    $to=$_POST['to_warehouse'];
    $date=$_POST['transfer_date'];
    $time=$_POST['transfer_time'];

    mysqli_query($conn,"INSERT INTO transfers(from_warehouse,to_warehouse,transfer_date,transfer_time,status)
    VALUES('$from','$to','$date','$time','done')");
}

/* FETCH TRANSFERS */
$data=mysqli_query($conn,"SELECT * FROM transfers ORDER BY transfer_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internal Transfers - CoreInventory</title>

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
            border-color: #06b6d4; /* Cyan focus for transfers */
            box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
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
                    <h1 class="text-3xl font-extrabold text-gray-900">Internal Transfers</h1>
                    <p class="text-gray-500 mt-1">Move stock seamlessly between your warehouse locations.</p>
                </div>
            </div>

            <div class="glass-panel p-6 lg:p-8 fade-in" style="animation-delay: 0.1s;">
                <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2"><span>🔄</span> Create New Transfer</h3>
                
                <form method="post" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 items-end">
                    
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">From Warehouse</label>
                        <input name="from_warehouse" placeholder="e.g. WH-A" class="form-input font-mono text-sm" required>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">To Warehouse</label>
                        <input name="to_warehouse" placeholder="e.g. WH-B" class="form-input font-mono text-sm" required>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Transfer Date</label>
                        <input type="text" name="transfer_date" value="<?php echo date('Y-m-d'); ?>" class="form-input" readonly>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Transfer Time</label>
                        <input type="text" id="transfer_time" name="transfer_time" class="form-input font-mono text-sm" readonly>
                    </div>

                    <div class="lg:col-span-4 mt-2">
                        <button name="add" class="w-full bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                            Authorize Transfer
                        </button>
                    </div>

                </form>
            </div>

            <div class="glass-panel overflow-hidden fade-in" style="animation-delay: 0.2s;">
                <div class="p-6 border-b border-gray-100 bg-white">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2"><span>📋</span> Transfer History</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 text-gray-500 text-xs tracking-wider uppercase border-b border-gray-100">
                                <th class="p-4 font-bold w-16">ID</th>
                                <th class="p-4 font-bold">Origin (From)</th>
                                <th class="p-4 font-bold">Destination (To)</th>
                                <th class="p-4 font-bold">Scheduled Date</th>
                                <th class="p-4 font-bold">Time</th>
                                <th class="p-4 font-bold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            
                            <?php if($data && mysqli_num_rows($data) > 0): ?>
                                <?php while($row=mysqli_fetch_assoc($data)){ ?>
                                <tr class="hover:bg-cyan-50/30 transition-colors group">
                                    <td class="p-4 text-gray-400 font-medium">#<?php echo $row['transfer_id']; ?></td>
                                    
                                    <td class="p-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-gray-100 border border-gray-200 text-gray-700 font-medium">
                                            <span class="text-red-500 text-xs">●</span> <?php echo htmlspecialchars($row['from_warehouse']); ?>
                                        </span>
                                    </td>
                                    
                                    <td class="p-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-blue-50 border border-blue-100 text-blue-700 font-medium">
                                            <span class="text-green-500 text-xs">●</span> <?php echo htmlspecialchars($row['to_warehouse']); ?>
                                        </span>
                                    </td>

                                    <td class="p-4 text-gray-700"><?php echo htmlspecialchars($row['transfer_date']); ?></td>
                                    <td class="p-4 text-gray-500 font-mono text-xs"><?php echo htmlspecialchars($row['transfer_time']); ?></td>
                                    
                                    <td class="p-4">
                                        <?php 
                                            // Dynamic Badge Styling
                                            $status = strtolower($row['status']);
                                            $badgeColor = 'bg-gray-100 text-gray-700'; // default
                                            
                                            if ($status === 'ready' || $status === 'done' || $status === 'completed') {
                                                $badgeColor = 'bg-green-100 text-green-700 border border-green-200';
                                            } elseif ($status === 'waiting' || $status === 'pending' || $status === 'in-transit') {
                                                $badgeColor = 'bg-cyan-100 text-cyan-700 border border-cyan-200';
                                            } elseif ($status === 'canceled' || $status === 'failed') {
                                                $badgeColor = 'bg-red-100 text-red-700 border border-red-200';
                                            }
                                        ?>
                                        <span class="px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-full <?php echo $badgeColor; ?>">
                                            <?php echo htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php } ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="p-10 text-center text-gray-400">
                                        <div class="text-4xl mb-3">🔄</div>
                                        <p class="font-medium text-lg">No transfers recorded</p>
                                        <p class="text-sm">Use the form above to initiate your first warehouse transfer.</p>
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
        function updateTime(){
            let now = new Date();
            let h = String(now.getHours()).padStart(2, '0');
            let m = String(now.getMinutes()).padStart(2, '0');
            let s = String(now.getSeconds()).padStart(2, '0');
            document.getElementById("transfer_time").value = h + ":" + m + ":" + s;
        }

        setInterval(updateTime, 1000);
        updateTime(); // Initial call to populate immediately
    </script>

</body>
</html>