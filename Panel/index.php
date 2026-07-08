  
<?php
$error = $_GET['error'] ?? 0; 
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin | Login </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4">
    
    <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all duration-300">
        
        <div class="bg-gradient-to-r from-purple-900/40 to-indigo-900/40 p-6 border-b border-gray-700 text-center">
            <div class="mx-auto w-20 h-20 bg-purple-900/20 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-shield-alt text-3xl text-purple-400"></i>
            </div>
            <h1 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-300 to-indigo-400 mb-1">
            ADMIN PORTAL | XCODE_OFF
            </h1>
            <p class="text-sm text-gray-400">Acces sécurisé au panel Admin</p>
        </div>

    
        <div class="p-6">
            <form id="loginForm" method="POST" action="./func/auth_admin.php" class="space-y-5">


            <!-- Erreur : Login incorrecte -->
            <?php if ($error == 1): ?>
                
                <div class="mt-4 p-3 bg-gray-800/30 border border-gray-700 rounded-lg flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-400 mt-1 mr-3"></i>
                    <p class="text-xs text-gray-400">
                   Mot de passe ou identifiant incorrect regarder dans le fichier .env.
                    </p>
                </div>
            <?php endif; ?>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-400 mb-1">Admin login</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user-shield text-gray-500"></i>
                        </div>
                        <input type="text" id="email" name="email" required 
                               class="w-full bg-gray-800/50 border border-gray-700 rounded-lg pl-10 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent placeholder-gray-500"
                               placeholder="admin">
                    </div>
                </div>
                

                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-400 mb-1">Admin Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-500"></i>
                        </div>
                        <input type="password" id="password" name="password" required 
                               class="w-full bg-gray-800/50 border border-gray-700 rounded-lg pl-10 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent placeholder-gray-500"
                               placeholder="••••••••">
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-eye-slash text-gray-500 hover:text-purple-400"></i>
                        </button>
                    </div>
                </div>
               

                <button type="submit" id="loginButton" class="w-full px-6 py-3 rounded-lg bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-medium transition-all flex items-center justify-center">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </button>

               
            </form>
        </div>

       
        <div class="bg-gray-800/50 px-6 py-4 border-t border-gray-700 text-center">
            <p class="text-xs text-gray-500">© 2025 X-CODE @xcode_officiel.<span class="text-purple-400"></span></p>
        </div>
    </div>

    
    <div id="loadingOverlay" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="text-center">
            <div class="w-16 h-16 border-4 border-purple-500 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
            <p class="text-white font-medium">Authentification en cour...</p>
            <p class="text-gray-400 text-sm mt-1">Vérification de vos informations</p>
        </div>
    </div>

    <style>
        /* Animations */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }
        
        .shake {
            animation: shake 0.4s ease-in-out;
        }
    </style>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            }
        });
        
       
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            
            document.getElementById('loadingOverlay').classList.remove('hidden');
            
          
        });
    </script>
</body>
</html>