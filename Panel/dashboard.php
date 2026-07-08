<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Configuration
$perPage = 50;
$page = max(1, intval($_GET['page'] ?? 1));
$filterCountry = trim($_GET['country'] ?? '');

// Réinitialisation des stats
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_visitors'])) {
    $files = [
        __DIR__ . '/logs/visitors.json',
        __DIR__ . '/logs/banned_visits.txt'
    ];
    
    foreach ($files as $file) {
        if (file_exists($file)) {
            file_put_contents($file, $file === $files[0] ? json_encode([]) : '');
        }
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Chargement des données
$visitorsFile = __DIR__ . '/logs/visitors.json';
$allVisitorsRaw = is_file($visitorsFile) ? json_decode(file_get_contents($visitorsFile), true) : [];
$allVisitorsRaw = is_array($allVisitorsRaw) ? $allVisitorsRaw : [];

// Supprimer les doublons par IP (la dernière occurrence est conservée)
$uniqueVisitors = [];
foreach (array_reverse($allVisitorsRaw) as $visitor) {
    if (!isset($visitor['ip'])) continue;
    $ip = $visitor['ip'];
    if (!isset($uniqueVisitors[$ip])) {
        $uniqueVisitors[$ip] = $visitor;
    }
}
$allVisitors = array_values($uniqueVisitors);

// Filtrage par pays
if ($filterCountry) {
    $allVisitors = array_filter($allVisitors, function ($v) use ($filterCountry) {
        return isset($v['country']) && strcasecmp($v['country'], $filterCountry) === 0;
    });
}

// Statistiques
$now = time();
$stats = [
    'online' => 0,
    'mobile' => 0,
    'bots' => 0,
    'lastActivity' => 0,
    'countries' => []
];

foreach ($allVisitors as &$visitor) {
    $timestamp = isset($visitor['timestamp']) ? strtotime($visitor['timestamp']) : 0;

    // Online
    if ($timestamp && ($now - $timestamp) <= 60) {
        $stats['online']++;
    }

    // Dernière activité
    if ($timestamp > $stats['lastActivity']) {
        $stats['lastActivity'] = $timestamp;
    }

    // Mobile
    if (!empty($visitor['mobile'])) {
        $stats['mobile']++;
    }

    // Bots
    if (!empty($visitor['proxy']) || !empty($visitor['bot'])) {
        $stats['bots']++;
    }

    // Pays uniques
    if (!empty($visitor['country'])) {
        $countryKey = strtolower($visitor['country']);
        $stats['countries'][$countryKey] = $visitor['country'];
    }

    // Drapeaux
    if (!empty($visitor['countryCode'])) {
        $visitor['country_flag'] = 'https://flagcdn.com/h20/' . strtolower($visitor['countryCode']) . '.png';
    }
}
unset($visitor); // Bonnes pratiques lors de l’utilisation de références dans foreach

// Pagination
$totalVisitors = count($allVisitors);
$totalPages = max(1, ceil($totalVisitors / $perPage));
$visitors = array_slice($allVisitors, ($page - 1) * $perPage, $perPage);

// Temps depuis dernière activité
$lastActivityMinutesAgo = $stats['lastActivity'] ? floor(($now - $stats['lastActivity']) / 60) : '-';
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Dashboard Admin | XCODE_OFF</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
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
    <?php include './assets/menu.php' ?>
    
    <main class="container mx-auto px-3 py-4 sm:px-4 sm:py-6">
        <!-- Cartes stats -->
        <div class="grid gap-4 mb-8 stats-grid">
            <div class="stat-card bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-5 flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400">En ligne</p>
                        <h3 class="text-2xl font-bold text-green-400 flex items-center gap-2">
                            <i class="fas fa-circle text-green-400 text-xs"></i> <?= $stats['online'] ?>
                        </h3>
                    </div>
                    <i class="fas fa-users stat-icon text-purple-400 bg-purple-900/20 p-3 rounded-full"></i>
                </div>
            </div>
            <div class="stat-card bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-5 flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400">Robots</p>
                        <h3 class="text-2xl font-bold text-blue-400 flex items-center gap-2">
                            <i class="fas fa-robot text-blue-400 text-xs"></i> <?= $stats['bots'] ?>
                        </h3>
                    </div>
                    <i class="fas fa-robot stat-icon text-blue-400 bg-blue-900/20 p-3 rounded-full"></i>
                </div>
            </div>
            <div class="stat-card bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-5 flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400">Mobiles</p>
                        <h3 class="text-2xl font-bold text-yellow-400 flex items-center gap-2">
                            <i class="fas fa-mobile-alt text-yellow-400 text-xs"></i> <?= $stats['mobile'] ?>
                        </h3>
                    </div>
                    <i class="fas fa-mobile-alt stat-icon text-yellow-400 bg-yellow-900/20 p-3 rounded-full"></i>
                </div>
            </div>
            <div class="stat-card bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-5 flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400">Dernière activité</p>
                        <h3 class="text-2xl font-bold text-green-400 flex items-center gap-2">
                            <i class="fas fa-clock text-green-400 text-xs"></i> <?= $lastActivityMinutesAgo !== '-' ? $lastActivityMinutesAgo . ' min' : '-' ?>
                        </h3>
                    </div>
                    <i class="fas fa-clock stat-icon text-green-400 bg-green-900/20 p-3 rounded-full"></i>
                </div>
            </div>
        </div>

        <!-- Contrôles -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4 controls-container">
            <!-- Filtre pays -->
            <div class="flex items-center flex-1 min-w-[200px]">
                <label for="country" class="mr-2 text-xs text-gray-400 whitespace-nowrap">Pays :</label>
                <select id="country" onchange="filterByCountry(this)" class="bg-gray-800 border border-gray-700 text-gray-200 rounded px-2 py-1 text-xs flex-1">
                    <option value="">Tous</option>
                    <?php foreach ($stats['countries'] as $country): ?>
                        <option value="<?= htmlspecialchars($country) ?>" <?= $filterCountry === $country ? 'selected' : '' ?>><?= htmlspecialchars($country) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- Pagination -->
            <div class="flex flex-col items-center gap-1 w-full sm:w-auto">
                <span class="text-xs text-gray-400">
                    <?= min(($page - 1) * $perPage + 1, $totalVisitors) ?>-<?= min($page * $perPage, $totalVisitors) ?> sur <?= $totalVisitors ?>
                </span>
                <nav class="flex gap-1 pagination-nav">
                    <a href="?page=1<?= $filterCountry ? '&country=' . urlencode($filterCountry) : '' ?>" class="px-2 py-1 rounded border border-gray-700 bg-gray-800 text-xs <?= $page == 1 ? 'opacity-50' : 'hover:bg-gray-700' ?>">&laquo;</a>
                    <?php for ($i = max(1, $page - 2); $i <= min($page + 2, $totalPages); $i++): ?>
                        <a href="?page=<?= $i ?><?= $filterCountry ? '&country=' . urlencode($filterCountry) : '' ?>" class="px-2 py-1 rounded border text-xs <?= $i == $page ? 'bg-purple-600 border-purple-600' : 'border-gray-700 bg-gray-800 hover:bg-gray-700' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                    <a href="?page=<?= $totalPages ?><?= $filterCountry ? '&country=' . urlencode($filterCountry) : '' ?>" class="px-2 py-1 rounded border border-gray-700 bg-gray-800 text-xs <?= $page == $totalPages ? 'opacity-50' : 'hover:bg-gray-700' ?>">&raquo;</a>
                </nav>
            </div>
            <!-- Bouton Reset -->
            <button onclick="openResetModal()" class="reset-btn px-3 py-1 rounded-lg text-xs whitespace-nowrap flex items-center gap-2"><i class="fas fa-sync-alt"></i> Reset stats</button>
        </div>

        <!-- Tableau visiteurs -->
        <div class="bg-gray-800 rounded-lg overflow-x-auto mt-4">
            <table class="min-w-full divide-y divide-gray-700 responsive-table">
                <thead>
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Date/Heure</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">IP</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Pays</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Région</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Ville</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Device</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">ISP</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-900 divide-y divide-gray-700">
                    <?php if (!empty($visitors)): ?>
                        <?php foreach ($visitors as $visitor): ?>
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap" data-label="Date/Heure">
                                    <span class="text-xs text-gray-300 font-mono"><?= htmlspecialchars($visitor['timestamp'] ?? '-') ?></span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap" data-label="IP">
                                    <span class="text-xs text-white font-mono font-bold"><?= htmlspecialchars($visitor['ip'] ?? '-') ?></span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap" data-label="Pays">
                                    <?php if (!empty($visitor['country'])): ?>
                                        <div class="flex items-center gap-1">
                                            <?php if (!empty($visitor['country_flag'])): ?>
                                                <img src="<?= htmlspecialchars($visitor['country_flag']) ?>" alt="<?= htmlspecialchars($visitor['country']) ?>" class="w-5 h-3 rounded shadow mr-1">
                                            <?php endif; ?>
                                            <span class="text-xs truncate-cell"><?= htmlspecialchars($visitor['country']) ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-500">Inconnu</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap" data-label="Région">
                                    <span class="text-xs text-gray-300 truncate-cell" title="<?= htmlspecialchars($visitor['regionName'] ?? '') ?>">
                                        <?= htmlspecialchars($visitor['regionName'] ?? '-') ?>
                                    </span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap" data-label="Ville">
                                    <span class="text-xs text-gray-300 truncate-cell" title="<?= htmlspecialchars($visitor['city'] ?? '') ?>">
                                        <?= htmlspecialchars($visitor['city'] ?? '-') ?>
                                    </span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap" data-label="Device">
                                    <span class="text-xs text-gray-300 truncate-cell" title="<?= htmlspecialchars($visitor['device_type'] ?? '') ?> <?= htmlspecialchars($visitor['device_model'] ?? '') ?>">
                                        <?= htmlspecialchars($visitor['device_type'] ?? '-') ?> - <?= htmlspecialchars($visitor['device_model'] ?? '-') ?>
                                    </span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap" data-label="ISP">
                                    <span class="text-xs text-gray-300 truncate-cell" title="<?= htmlspecialchars($visitor['isp'] ?? '') ?>">
                                        <?= htmlspecialchars($visitor['isp'] ?? '-') ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-4 py-3 text-center text-gray-400 text-xs">Aucun visiteur enregistré</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Modal -->
    <div id="resetModal" class="modal">
        <div class="modal-content">
            <div class="flex items-center text-base">
                <i class="fas fa-exclamation-triangle text-yellow-400 mr-2"></i>
                Confirmation requise
            </div>
            <div class="text-sm text-gray-300 my-3">
                Réinitialiser toutes les statistiques ? Cette action est irréversible.
            </div>
            <div class="flex justify-end gap-2 mt-2">
                <button onclick="closeResetModal()" class="px-3 py-1 rounded bg-gray-700 hover:bg-gray-600 text-sm">Annuler</button>
                <form method="post">
                    <button type="submit" name="reset_visitors" class="px-3 py-1 rounded reset-btn text-sm">Confirmer</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Filtrage par pays
        function filterByCountry(select) {
            const country = select.value;
            const url = new URL(window.location.href);
            
            if (country) {
                url.searchParams.set('country', country);
            } else {
                url.searchParams.delete('country');
            }
            
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }
        
        // Gestion du modal
        function openResetModal() {
            document.getElementById('resetModal').style.display = 'block';
        }
        
        function closeResetModal() {
            document.getElementById('resetModal').style.display = 'none';
        }
        
        window.onclick = function(e) {
            if (e.target === document.getElementById('resetModal')) {
                closeResetModal();
            }
        };
        
        // Rafraîchissement auto
        async function refreshStats() {
            try {
                const response = await fetch('./func/refresh.php');
                if (response.ok) {
                    const data = await response.json();
                    document.querySelectorAll('[data-stat="online"]').forEach(el => {
                        el.textContent = data.online || 0;
                    });
                }
            } catch (e) {
                console.error('Refresh error:', e);
            }
        }
        
        setInterval(refreshStats, 5000);

        // Exemple : notification de nouvelle connexion (à remplacer par un vrai événement)
        // showToast('Nouvelle connexion détectée : 192.168.1.1', 'success');

        // Polling pour détecter de nouveaux événements (connexion ou ban)
        setInterval(async () => {
            try {
                const res = await fetch('./func/events.php');
                if (!res.ok) return;
                const events = await res.json();
                if (Array.isArray(events)) {
                    events.forEach(ev => {
                        if (!window.lastEventIds) window.lastEventIds = {};
                        if (!window.lastEventIds[ev.id]) {
                            showToast(ev.message, ev.type);
                            window.lastEventIds[ev.id] = true;
                        }
                    });
                }
            } catch (e) {}
        }, 5000);
    </script>
</body>
</html>