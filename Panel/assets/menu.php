<?php
// Récupération du nom d'utilisateur si disponible
$admin = $_SESSION['admin_name'] ?? 'Admin';
?>
<!-- Header -->
<header class="bg-gradient-to-r from-gray-800 to-gray-900 border-b border-gray-700 shadow-lg sticky top-0 z-40">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-2">
            <i class="fas fa-shield-alt text-purple-400 text-2xl"></i>
            <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-300 to-indigo-400">XCODE_OFF</span>
        </div>
        <!-- Desktop Nav -->
        <nav class="hidden md:flex space-x-6">
            <a href="dashboard.php" class="menu-link"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</a>
            <a href="stats.php" class="menu-link"><i class="fas fa-chart-line mr-2"></i>Statistiques</a>
            <a href="reglage.php" class="menu-link"><i class="fas fa-cog mr-2"></i>Réglages</a>
            <a href="antibots.php" class="menu-link"><i class="fa-solid fa-robot mr-2"></i>Antibots</a>
            <a href="click.php" class="menu-link"><i class="fa-solid fa-arrow-pointer mr-2"></i>Click</a>
            <a href="info_ndd.php" class="menu-link"><i class="fa-solid fa-info mr-2"></i>Info ndd</a>
            <a href="ip_ban.php" class="menu-link"><i class="fa-solid fa-ban mr-2"></i>IP bannies</a>
        </nav>
        <!-- Profil + Déconnexion -->
        <div class="flex items-center space-x-4">
            <div class="relative group">
                <div class="h-10 w-10 rounded-full bg-purple-900/30 border border-purple-700 flex items-center justify-center">
                    <i class="fas fa-user text-purple-400"></i>
                </div>
                <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-gray-900"></span>
                <div class="absolute left-1/2 -translate-x-1/2 mt-2 px-3 py-1 bg-gray-800 text-xs text-gray-200 rounded shadow-lg opacity-0 group-hover:opacity-100 transition pointer-events-none">
                    <?= htmlspecialchars($admin) ?>
                </div>
            </div>
            <button onclick="location.href='logout.php'" class="text-gray-400 hover:text-purple-300 transition" title="Déconnexion">
                <i class="fas fa-sign-out-alt"></i>
            </button>
            <!-- Burger -->
            <button id="burgerBtn" class="md:hidden text-gray-400 hover:text-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-500 rounded p-2" aria-label="Ouvrir le menu">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </div>
    <!-- Mobile Menu -->
    <div id="mobileMenu" class="fixed inset-0 bg-black/60 z-50 hidden">
        <div class="absolute top-0 right-0 w-64 h-full bg-gray-900 shadow-lg flex flex-col p-6 animate-slide-in">
            <div class="flex justify-between items-center mb-6">
                <span class="text-lg font-bold text-purple-400 flex items-center"><i class="fas fa-shield-alt mr-2"></i>XCODE_OFF</span>
                <button id="closeMenu" class="text-gray-400 hover:text-purple-300 text-2xl focus:outline-none"><i class="fas fa-times"></i></button>
            </div>
            <nav class="flex flex-col gap-4">
                <a href="dashboard.php" class="menu-link-mobile"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</a>
                <a href="stats.php" class="menu-link-mobile"><i class="fas fa-chart-line mr-2"></i>Statistiques</a>
                <a href="reglage.php" class="menu-link-mobile"><i class="fas fa-cog mr-2"></i>Réglages</a>
                <a href="antibots.php" class="menu-link-mobile"><i class="fa-solid fa-robot mr-2"></i>Antibots</a>
                <a href="click.php" class="menu-link-mobile"><i class="fa-solid fa-arrow-pointer mr-2"></i>Click</a>
                <a href="info_ndd.php" class="menu-link-mobile"><i class="fa-solid fa-info mr-2"></i>Info ndd</a>
                <a href="ip_ban.php" class="menu-link-mobile"><i class="fa-solid fa-ban mr-2"></i>IP bannies</a>
                <a href="logout.php" class="menu-link-mobile text-red-400"><i class="fas fa-sign-out-alt mr-2"></i>Déconnexion</a>
            </nav>
        </div>
    </div>
    <style>
        .menu-link {
            @apply text-gray-400 hover:text-purple-300 transition font-medium px-2 py-1 rounded-lg relative;
        }
        .menu-link-mobile {
            @apply text-gray-200 hover:text-purple-400 transition text-base px-2 py-2 rounded-lg flex items-center;
        }
        @media (max-width: 767px) {
            .container { padding-left: 0.5rem; padding-right: 0.5rem; }
        }
        @keyframes slide-in {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .animate-slide-in { animation: slide-in 0.3s cubic-bezier(.4,0,.2,1); }
    </style>
    <script>
    // Burger menu
    const burgerBtn = document.getElementById('burgerBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const closeMenu = document.getElementById('closeMenu');
    burgerBtn && burgerBtn.addEventListener('click', () => {
        mobileMenu.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    });
    closeMenu && closeMenu.addEventListener('click', () => {
        mobileMenu.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    });
    mobileMenu && mobileMenu.addEventListener('click', (e) => {
        if (e.target === mobileMenu) {
            mobileMenu.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    });
    </script>
    <!-- Toast notifications -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2"></div>
    <script>
    function showToast(message, type = 'info') {
        const container = document.getElementById('toast-container');
        if (!container) return;
        const colors = {
            info: 'bg-blue-700',
            success: 'bg-green-700',
            warning: 'bg-yellow-700',
            error: 'bg-red-700'
        };
        const icons = {
            info: 'fa-info-circle',
            success: 'fa-check-circle',
            warning: 'fa-exclamation-triangle',
            error: 'fa-times-circle'
        };
        const toast = document.createElement('div');
        toast.className = `flex items-center px-4 py-3 rounded shadow-lg text-white ${colors[type] || colors.info} animate-fade-in`;
        toast.innerHTML = `<i class='fas ${icons[type] || icons.info} mr-3'></i><span>${message}</span>`;
        container.appendChild(toast);
        setTimeout(() => {
            toast.classList.add('opacity-0');
            setTimeout(() => toast.remove(), 400);
        }, 4000);
    }
    </script>
</header>