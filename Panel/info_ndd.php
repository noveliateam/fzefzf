<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
   header('Location: index.php');
    exit;
}

// Configuration de l'API AbuseIPDB
define('ABUSEIPDB_API_KEY', '5d615634f4876bb0be99024f9598ca14047abbe557bd5994457a20287abf7a6983bcd33b8f51d0aa');
define('ABUSEIPDB_API_URL', 'https://api.abuseipdb.com/api/v2/check');

// Fonction pour interroger l'API AbuseIPDB
function checkAbuseIPDB($ip) {
    $curl = curl_init();
    
    curl_setopt_array($curl, [
        CURLOPT_URL => ABUSEIPDB_API_URL . '?ipAddress=' . urlencode($ip) . '&maxAgeInDays=90',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Key: ' . ABUSEIPDB_API_KEY
        ]
    ]);
    
    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);
    
    if ($error) {
        return ['error' => $error];
    }
    
    return json_decode($response, true);
}


function checkDomain($domain) {
    
    $domain = preg_replace('#^https?://#', '', $domain);
    
   
    if (!preg_match('/^([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,}$/i', $domain)) {
        return ['error' => 'Format de domaine invalide'];
    }
    
 
    $ips = gethostbynamel($domain);
    
    if ($ips === false) {
        return ['error' => 'Le domaine ne résout pas vers une IP'];
    }
    
    return [
        'domain' => $domain,
        'ips' => $ips,
        'ip_count' => count($ips)
    ];
}


$searchResult = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_term'])) {
    $searchTerm = trim($_POST['search_term']);
    $searchType = $_POST['search_type'] ?? 'ip';
    
    if ($searchType === 'ip') {
        if (filter_var($searchTerm, FILTER_VALIDATE_IP)) {
            $searchResult = checkAbuseIPDB($searchTerm);
        } else {
            $searchResult = ['error' => 'Adresse IP invalide'];
        }
    } elseif ($searchType === 'domain') {
        $searchResult = checkDomain($searchTerm);
        
       
        if (!isset($searchResult['error']) && !empty($searchResult['ips'])) {
            $ipReports = [];
            foreach ($searchResult['ips'] as $ip) {
                $ipReport = checkAbuseIPDB($ip);
                if (isset($ipReport['data'])) {
                    $ipReports[$ip] = $ipReport['data'];
                }
            }
            $searchResult['ip_reports'] = $ipReports;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification IP/Domaine | XCODE_OFF</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .online-dot { width: 10px; height: 10px; background-color: #10B981; border-radius: 50%; display: inline-block; margin-right: 6px; }
        .offline-dot { width: 10px; height: 10px; background-color: #EF4444; border-radius: 50%; display: inline-block; margin-right: 6px; }
        .status-authorized { color: #10B981; }
        .status-unauthorized { color: #EF4444; }
        .flagged-badge { background-color: #FEE2E2; color: #B91C1C; }
        .clean-badge { background-color: #DCFCE7; color: #166534; }
        .api-badge { background-color: #EFF6FF; color: #1E40AF; }
        .domain-badge { background-color: #F5F3FF; color: #5B21B6; }
        
        @media (max-width: 1024px) {
            .responsive-table thead { display: none; }
            .responsive-table tr { display: block; margin-bottom: 1rem; border: 1px solid #374151; border-radius: 0.5rem; }
            .responsive-table td { 
                display: flex; 
                justify-content: space-between;
                align-items: center;
                padding: 0.75rem;
                border-bottom: 1px solid #374151;
            }
            .responsive-table td:before {
                content: attr(data-label);
                font-weight: 600;
                color: #9CA3AF;
                margin-right: 1rem;
                flex: 0 0 120px;
            }
            .responsive-table td:last-child { border-bottom: none; }
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-200 min-h-screen">
    <?php include './assets/menu.php'?>
    
    <main class="container mx-auto px-4 py-6">
    <!-- partie de la vérif d'ip (xcode designer ) -->
        <div class="mb-8 bg-gray-800 rounded-lg p-6 shadow">
            <h2 class="text-xl font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-search mr-2 text-blue-400"></i> Vérificateur IP/Domaine
            </h2>
            
            <form method="POST" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-3">
                        <input type="text" name="search_term" placeholder="Entrez une IP (ex: 192.168.1.1) ou un domaine (ex: example.com)" 
                               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required
                               value="<?= isset($_POST['search_term']) ? htmlspecialchars($_POST['search_term']) : '' ?>">
                    </div>
                    <div>
                        <select name="search_type" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white">
                            <option value="ip" <?= ($_POST['search_type'] ?? 'ip') === 'ip' ? 'selected' : '' ?>>IP</option>
                            <option value="domain" <?= ($_POST['search_type'] ?? 'ip') === 'domain' ? 'selected' : '' ?>>Domaine</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="mt-4 px-6 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-medium transition">
                    <i class="fas fa-search mr-2"></i> Vérifier
                </button>
            </form>

            <?php if (isset($searchResult)): ?>
                <div class="bg-gray-700 rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-3">Résultats de la vérification</h3>
                    
                    <?php if (isset($searchResult['error'])): ?>
                        <div class="text-red-400 p-3 bg-gray-800 rounded">
                            <i class="fas fa-exclamation-triangle mr-2"></i> <?= htmlspecialchars($searchResult['error']) ?>
                        </div>
                    
                    <?php elseif (isset($searchResult['data'])): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-800 p-4 rounded-lg">
                                <div class="flex items-center mb-3">
                                    <h4 class="font-medium">Adresse IP:</h4>
                                    <span class="ml-2 font-mono"><?= htmlspecialchars($searchResult['data']['ipAddress']) ?></span>
                                </div>
                                
                                <div class="flex items-center mb-3">
                                    <h4 class="font-medium">Statut:</h4>
                                    <span class="ml-2 <?= $searchResult['data']['isPublic'] ? 'text-green-400' : 'text-yellow-400' ?>">
                                        <?= $searchResult['data']['isPublic'] ? 'Public' : 'Privé' ?>
                                    </span>
                                </div>
                                
                                <div class="flex items-center mb-3">
                                    <h4 class="font-medium">Score d'abus:</h4>
                                    <span class="ml-2 font-bold <?= $searchResult['data']['abuseConfidenceScore'] > 25 ? 'text-red-400' : 'text-green-400' ?>">
                                        <?= $searchResult['data']['abuseConfidenceScore'] ?>%
                                    </span>
                                </div>
                                
                                <div class="flex items-center mb-3">
                                    <h4 class="font-medium">Signalements:</h4>
                                    <span class="ml-2"><?= $searchResult['data']['totalReports'] ?> (derniers 90 jours)</span>
                                </div>
                            </div>
                            
                            <div class="bg-gray-800 p-4 rounded-lg">
                                <div class="mb-3">
                                    <h4 class="font-medium">ISP:</h4>
                                    <p><?= htmlspecialchars($searchResult['data']['isp'] ?? 'Inconnu') ?></p>
                                </div>
                                
                                <div class="mb-3">
                                    <h4 class="font-medium">Pays:</h4>
                                    <p><?= htmlspecialchars($searchResult['data']['countryName'] ?? 'Inconnu') ?></p>
                                </div>
                                
                                <div class="mb-3">
                                    <h4 class="font-medium">Dernier signalement:</h4>
                                    <p><?= isset($searchResult['data']['lastReportedAt']) 
                                        ? date('d/m/Y H:i', strtotime($searchResult['data']['lastReportedAt'])) 
                                        : 'Jamais' ?></p>
                                </div>
                                
                                <div class="mt-4">
                                    <?php if ($searchResult['data']['abuseConfidenceScore'] > 25): ?>
                                        <span class="px-3 py-1 rounded-full flagged-badge">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> IP signalée
                                        </span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 rounded-full clean-badge">
                                            <i class="fas fa-check-circle mr-1"></i> IP propre
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-sm text-gray-400 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i> Données fournies par AbuseIPDB
                        </div>
                    
                    <?php elseif (isset($searchResult['domain'])): // Shootout ?>
                        <div class="bg-gray-800 p-4 rounded-lg mb-4">
                            <div class="flex items-center mb-3">
                                <h4 class="font-medium">Domaine:</h4>
                                <span class="ml-2 font-mono"><?= htmlspecialchars($searchResult['domain']) ?></span>
                                <span class="ml-3 px-2 py-1 rounded-full domain-badge text-sm">
                                    <i class="fas fa-globe mr-1"></i> Domaine
                                </span>
                            </div>
                            
                            <div class="mb-3">
                                <h4 class="font-medium">IPs associées:</h4>
                                <p class="text-gray-300"><?= $searchResult['ip_count'] ?> IP(s) trouvée(s)</p>
                            </div>
                            
                            <?php if (!empty($searchResult['ip_reports'])): ?>
                                <h4 class="font-medium mt-4 mb-2">Détails des IPs:</h4>
                                <div class="overflow-x-auto">
                                    <table class="w-full responsive-table">
                                        <thead class="bg-gray-700">
                                            <tr>
                                                <th class="px-4 py-2 text-left">IP</th>
                                                <th class="px-4 py-2 text-left">Score</th>
                                                <th class="px-4 py-2 text-left">Signalements</th>
                                                <th class="px-4 py-2 text-left">ISP</th>
                                                <th class="px-4 py-2 text-left">Pays</th>
                                                <th class="px-4 py-2 text-left">Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($searchResult['ip_reports'] as $ip => $report): ?>
                                                <tr class="border-b border-gray-700 hover:bg-gray-750">
                                                    <td class="px-4 py-3 font-mono" data-label="IP"><?= htmlspecialchars($ip) ?></td>
                                                    <td class="px-4 py-3 font-bold <?= $report['abuseConfidenceScore'] > 25 ? 'text-red-400' : 'text-green-400' ?>" data-label="Score">
                                                        <?= $report['abuseConfidenceScore'] ?>%
                                                    </td>
                                                    <td class="px-4 py-3" data-label="Signalements"><?= $report['totalReports'] ?></td>
                                                    <td class="px-4 py-3" data-label="ISP"><?= htmlspecialchars($report['isp'] ?? 'Inconnu') ?></td>
                                                    <td class="px-4 py-3" data-label="Pays"><?= htmlspecialchars($report['countryName'] ?? 'Inconnu') ?></td>
                                                    <td class="px-4 py-3" data-label="Statut">
                                                        <?php if ($report['abuseConfidenceScore'] > 25): ?>
                                                            <span class="px-2 py-1 text-xs rounded-full flagged-badge">
                                                                <i class="fas fa-exclamation-triangle mr-1"></i> Risque
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="px-2 py-1 text-xs rounded-full clean-badge">
                                                                <i class="fas fa-check-circle mr-1"></i> Sûr
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>