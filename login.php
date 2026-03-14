<?php
session_start();
include "db.php";

$error="";
$success="";

/* LOGIN */
if(isset($_POST['login'])){
    $email=$_POST['email'];
    $password=$_POST['password'];

    $query="SELECT * FROM users WHERE email='$email'";
    $result=mysqli_query($conn,$query);

    if(mysqli_num_rows($result)==1){
        $row=mysqli_fetch_assoc($result);

        if(password_verify($password,$row['password'])){
            $_SESSION['user_id']=$row['user_id'];
            $_SESSION['name']=$row['name'];

            header("Location: dashboard.php");
            exit();
        }else{
            $error="Invalid Email or Password";
        }
    }else{
        $error="Invalid Email or Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CoreInventory</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Glass Panel Styling */
        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.05), 0 10px 20px -10px rgba(0, 0, 0, 0.02);
        }

        /* Form Input Enhancements */
        .form-input {
            width: 100%;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
        }
        .form-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            background-color: #ffffff;
        }

        /* Ambient Background Animations */
        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.6;
            animation: float 10s infinite ease-in-out alternate;
        }
        @keyframes float {
            0% { transform: translateY(0px) scale(1); }
            100% { transform: translateY(-20px) scale(1.05); }
        }
    </style>
</head>

<body class="bg-slate-50 flex items-center justify-center min-h-screen relative overflow-hidden text-gray-800">

    <div class="blob bg-blue-300 w-96 h-96 rounded-full top-[-10%] left-[-10%]"></div>
    <div class="blob bg-indigo-300 w-96 h-96 rounded-full bottom-[-10%] right-[-10%]" style="animation-delay: -5s;"></div>

    <div class="w-full max-w-md px-6 relative z-10">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 shadow-lg mb-4 text-white text-3xl">
                📦
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900">Welcome back</h2>
            <p class="text-gray-500 mt-2 font-medium">Please enter your details to sign in.</p>
        </div>

        <div class="glass-panel p-8 sm:p-10 rounded-2xl">

            <?php if($error!=""): ?>
                <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if($success!=""): ?>
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Email Address</label>
                    <input 
                        type="email" 
                        name="email"
                        placeholder="you@company.com" 
                        class="form-input" 
                        required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password"
                            id="password"
                            placeholder="••••••••"
                            class="form-input pr-12"
                            required>
                        
                        <button 
                            type="button"
                            onclick="togglePassword()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-indigo-600 transition-colors focus:outline-none p-1 rounded-md">
                            
                            <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-2">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" id="remember" class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2 transition duration-200">
                        <span class="text-sm font-medium text-gray-600 group-hover:text-gray-900 transition-colors">Remember me</span>
                    </label>
                    <a href="#" class="text-sm font-bold text-indigo-600 hover:text-indigo-500 transition-colors">Forgot Password?</a>
                </div>

                <button 
                    name="login"
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 mt-2">
                    Sign In
                </button>

                <div class="flex items-center py-2">
                    <div class="flex-grow h-px bg-gray-200"></div>
                    <span class="mx-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Or continue with</span>
                    <div class="flex-grow h-px bg-gray-200"></div>
                </div>

                <div class="space-y-3">
                    <a href="google-login.php" class="w-full flex items-center justify-center gap-3 bg-white border border-gray-200 rounded-xl py-2.5 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm text-gray-700 font-semibold text-sm">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
                        Google
                    </a>

                </div>

                <p class="text-center text-sm font-medium text-gray-600 mt-6 pt-4 border-t border-gray-100">
                    Don't have an account? 
                    <a href="signup.php" class="text-indigo-600 hover:text-indigo-500 font-bold hover:underline transition-colors">Sign up</a>
                </p>

            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const eyeIcon = document.getElementById("eye-icon");
            
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                // Change icon to 'eye-off'
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                `;
            } else {
                passwordInput.type = "password";
                // Change back to 'eye'
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }
    </script>

</body>
</html>