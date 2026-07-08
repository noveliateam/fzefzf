<?php
header('Content-Type: text/html; charset=utf-8');
session_start();


if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

$clicks = [];
$clicksFile = __DIR__ . '/logs/click.json';


if (file_exists($clicksFile)) {
    $rawClicks = json_decode(file_get_contents($clicksFile), true) ?: [];

    $seenHashes = [];
    foreach ($rawClicks as $click) {
        $ip = $click['ip'] ?? '';
        $dateMinute = date('Y-m-d H:i', strtotime($click['date'] ?? ''));
        $status = !empty($click['authorized']) ? 'auth' : 'block';

        $hash = md5($ip . '_' . $dateMinute . '_' . $status);
        if (!isset($seenHashes[$hash])) {
            $seenHashes[$hash] = true;
            array_unshift($clicks, $click);
        }
    }
}


if (isset($_POST['reset_stats'])) {
    file_put_contents($clicksFile, json_encode([]));
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}


$filterCountry = $_GET['country'] ?? null;
$filterStatus  = $_GET['status'] ?? null;

$filteredClicks = $clicks;
if ($filterCountry || $filterStatus) {
    $filteredClicks = array_filter($clicks, function ($click) use ($filterCountry, $filterStatus) {
        $countryMatch = !$filterCountry || (isset($click['country']) && strcasecmp($click['country'], $filterCountry) === 0);
        $statusMatch  = !$filterStatus || (
            isset($click['authorized']) &&
            (
                ($filterStatus === 'authorized' && $click['authorized']) ||
                ($filterStatus === 'blocked' && !$click['authorized'])
            )
        );
        return $countryMatch && $statusMatch;
    });
}


$now               = time();
$activeThreshold   = 60;
$usersOnlineCount  = 0;
$mobileCount       = 0;
$botsCount         = 0;
$lastActivity      = 0;
$totalClicks       = count($clicks);
$authorizedCount   = 0;
$blockedCount      = 0;
$countries         = [];

foreach ($clicks as $click) {
    $clickTime = strtotime($click['date'] ?? '');
    if ($clickTime && ($now - $clickTime) <= $activeThreshold) {
        $usersOnlineCount++;
        $lastActivity = max($lastActivity, $clickTime);
    }

    $click['authorized'] ? $authorizedCount++ : $blockedCount++;

    if (!empty($click['device']) && $click['device'] === 'mobile') {
        $mobileCount++;
    }

    // 🗺️ Comptage par pays
    if (!empty($click['country'])) {
        $country = $click['country'];
        if (!isset($countries[$country])) {
            $countries[$country] = ['total' => 0, 'authorized' => 0, 'blocked' => 0];
        }
        $countries[$country]['total']++;
        $click['authorized'] ? $countries[$country]['authorized']++ : $countries[$country]['blocked']++;
    }
}

arsort($countries);

$lastActivityMinutesAgo = $lastActivity ? floor(($now - $lastActivity) / 60) : '-';
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stats click | XCODE_OFF</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .controls-container { gap: 1rem; }
        .reset-btn { background: linear-gradient(90deg, #7c3aed 0%, #5b21b6 100%); color: #fff; border: none; transition: background 0.2s; }
        .reset-btn:hover { background: linear-gradient(90deg, #5b21b6 0%, #7c3aed 100%); }
    </style>
</head>

<body class="bg-gray-900 text-gray-200 min-h-screen">
    <?php include './assets/menu.php' ?>
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6">
        <!-- Cartes stats -->
        <div class="grid gap-4 mb-8 stats-grid">
            <div class="stat-card bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-5 flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400">Total des clics</p>
                        <h3 class="text-2xl font-bold text-purple-400 flex items-center gap-2">
                            <i class="fas fa-mouse-pointer text-purple-400 text-xs"></i> <?= $totalClicks ?>
                        </h3>
                    </div>
                    <i class="fas fa-mouse-pointer stat-icon text-purple-400 bg-purple-900/20 p-3 rounded-full"></i>
                </div>
            </div>
            <div class="stat-card bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-5 flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400">Autorisé</p>
                        <h3 class="text-2xl font-bold text-green-400 flex items-center gap-2">
                            <i class="fas fa-check-circle text-green-400 text-xs"></i> <?= $authorizedCount ?>
                        </h3>
                    </div>
                    <i class="fas fa-check-circle stat-icon text-green-400 bg-green-900/20 p-3 rounded-full"></i>
                </div>
            </div>
            <div class="stat-card bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-5 flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400">Bloqué</p>
                        <h3 class="text-2xl font-bold text-red-400 flex items-center gap-2">
                            <i class="fas fa-times-circle text-red-400 text-xs"></i> <?= $blockedCount ?>
                        </h3>
                    </div>
                    <i class="fas fa-times-circle stat-icon text-red-400 bg-red-900/20 p-3 rounded-full"></i>
                </div>
            </div>
            <div class="stat-card bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-5 flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400">Mobiles</p>
                        <h3 class="text-2xl font-bold text-yellow-400 flex items-center gap-2">
                            <i class="fas fa-mobile-alt text-yellow-400 text-xs"></i> <?= $mobileCount ?>
                        </h3>
                    </div>
                    <i class="fas fa-mobile-alt stat-icon text-yellow-400 bg-yellow-900/20 p-3 rounded-full"></i>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4 controls-container">
            <div class="flex items-center flex-1 min-w-[200px]">
                <label for="countryFilter" class="mr-2 text-xs text-gray-400 whitespace-nowrap">Pays :</label>
                <select id="countryFilter" class="bg-gray-800 border border-gray-700 text-gray-200 rounded px-2 py-1 text-xs flex-1">
                    <option value="">Tous</option>
                    <?php
                    $allCountries = array_unique(array_column($filteredClicks, 'country'));
                    foreach ($allCountries as $c) {
                        if ($c) echo '<option value="' . htmlspecialchars($c) . '">' . htmlspecialchars($c) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="flex items-center flex-1 min-w-[200px]">
                <label for="statusFilter" class="mr-2 text-xs text-gray-400 whitespace-nowrap">Statut :</label>
                <select id="statusFilter" class="bg-gray-800 border border-gray-700 text-gray-200 rounded px-2 py-1 text-xs flex-1">
                    <option value="">Tous</option>
                    <option value="authorized">Autorisé</option>
                    <option value="blocked">Bloqué</option>
                </select>
            </div>
            <button id="applyFilter" class="reset-btn px-3 py-1 rounded-lg text-xs whitespace-nowrap flex items-center gap-2"><i class="fas fa-filter"></i> Filtrer</button>
            <button id="resetFilter" class="px-3 py-1 rounded-lg bg-gray-700 text-gray-200 border border-gray-600 hover:bg-gray-600 transition text-xs whitespace-nowrap flex items-center gap-2"><i class="fas fa-sync-alt"></i> Reset</button>
        </div>

        <!-- Bouton Reset -->
        <div class="mb-6 flex justify-between items-center">
            <div class="text-sm text-gray-400">
                Affichage des requêtes uniques par IP (même IP autorisée et bloquée conservées)
            </div>
            <form method="post">
                <button type="submit" name="reset_stats" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-trash-alt mr-2"></i> Reset stats
                </button>
            </form>
        </div>

        <!-- Tableau clics -->
        <div class="bg-gray-800 rounded-lg overflow-x-auto mt-4">
            <table class="min-w-full divide-y divide-gray-700 responsive-table">
                <thead>
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Date</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">IP</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Pays</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">ISP</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Device</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Statut</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Raison</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-900 divide-y divide-gray-700">
                    <?php if (!empty($filteredClicks)): ?>
                        <?php foreach ($filteredClicks as $click): ?>
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap" data-label="Date">
                                    <span class="text-xs text-gray-300 font-mono"><?= htmlspecialchars($click['date'] ?? '-') ?></span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap" data-label="IP">
                                    <span class="text-xs text-white font-mono font-bold"><?= htmlspecialchars($click['ip'] ?? '-') ?></span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap" data-label="Pays">
                                    <span class="text-xs truncate-cell"><?= htmlspecialchars($click['country'] ?? '-') ?></span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap" data-label="ISP">
                                    <span class="text-xs text-gray-300 truncate-cell" title="<?= htmlspecialchars($click['isp'] ?? '-') ?>">
                                        <?= htmlspecialchars($click['isp'] ?? '-') ?>
                                    </span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap" data-label="Device">
                                    <span class="text-xs text-gray-300 truncate-cell" title="<?= htmlspecialchars($click['device'] ?? '-') ?>">
                                        <?= htmlspecialchars($click['device'] ?? '-') ?>
                                    </span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap" data-label="Statut">
                                    <span class="font-medium <?= ($click['authorized'] ?? false) ? 'text-green-400' : 'text-red-400' ?>">
                                        <i class="fas <?= ($click['authorized'] ?? false) ? 'fa-check-circle' : 'fa-times-circle' ?> mr-1"></i>
                                        <?= ($click['authorized'] ?? false) ? 'Autorisé' : 'Bloqué' ?>
                                    </span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap" data-label="Raison">
                                    <span class="text-xs text-purple-300 truncate-cell"><?= !empty($click['reason']) ? htmlspecialchars($click['reason']) : 'Non spécifiée' ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-4 py-3 text-center text-gray-400 text-xs">Aucun click enregistré.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion des filtres
            const countryFilter = document.getElementById('countryFilter');
            const statusFilter = document.getElementById('statusFilter');
            const applyFilter = document.getElementById('applyFilter');
            const resetFilter = document.getElementById('resetFilter');

            applyFilter.addEventListener('click', function() {
                const country = countryFilter.value;
                const status = statusFilter.value;

                let url = window.location.pathname;
                const params = [];

                if (country) params.push(`country=${encodeURIComponent(country)}`);
                if (status) params.push(`status=${encodeURIComponent(status)}`);

                if (params.length > 0) {
                    url += '?' + params.join('&');
                }

                window.location.href = url;
            });

            resetFilter.addEventListener('click', function() {
                window.location.href = window.location.pathname;
            });
        });
    </script>
</body>

</html>