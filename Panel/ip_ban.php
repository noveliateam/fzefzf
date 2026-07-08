<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

$bannedFile = __DIR__ . '/logs/banned_visits.txt';
$lines = file_exists($bannedFile) ? file($bannedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

// Suppression d'une IP
if (isset($_POST['delete_ip']) && isset($_POST['delete_line'])) {
    $deleteLine = intval($_POST['delete_line']);
    if (isset($lines[$deleteLine])) {
        unset($lines[$deleteLine]);
        file_put_contents($bannedFile, implode(PHP_EOL, $lines) . PHP_EOL);
        echo json_encode(['success' => true]);
        exit;
    }
    echo json_encode(['success' => false]);
    exit;
}

// Reset du fichier
if (isset($_POST['reset_ban'])) {
    file_put_contents($bannedFile, '');
    echo json_encode(['success' => true]);
    exit;
}

// Parsing des lignes
$entries = [];
$countries = [];
foreach ($lines as $i => $line) {
    if (preg_match('/\[(.*?)\] IP: ([^ ]+) \| Country: ([^|]+) \| ISP: ([^|]+) \| Reason: (.*)/', $line, $m)) {
        $entries[] = [
            'line' => $i,
            'date' => $m[1],
            'ip' => $m[2],
            'country' => trim($m[3]),
            'isp' => trim($m[4]),
            'reason' => trim($m[5])
        ];
        $countries[] = trim($m[3]);
    }
}
$countries = array_unique($countries);
sort($countries);
$filterCountry = $_GET['country'] ?? '';
if ($filterCountry) {
    $entries = array_filter($entries, fn($e) => $e['country'] === $filterCountry);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP bannies | XCODE_OFF</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-900 text-gray-200 min-h-screen">
<?php include './assets/menu.php'; ?>
<main class="container mx-auto px-4 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <h1 class="text-2xl font-bold text-purple-400 flex items-center"><i class="fa-solid fa-ban mr-2"></i> IP bannies</h1>
        <form id="filterForm" class="flex items-center gap-2">
            <select name="country" class="bg-gray-800 border border-gray-700 rounded px-3 py-2 text-white" onchange="this.form.submit()">
                <option value="">Tous les pays</option>
                <?php foreach ($countries as $c): ?>
                    <option value="<?= htmlspecialchars($c) ?>" <?= $filterCountry === $c ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <button id="resetBtn" class="bg-red-700 hover:bg-red-800 text-white px-4 py-2 rounded shadow flex items-center"><i class="fa-solid fa-trash mr-2"></i>Reset tout</button>
    </div>
    <div class="overflow-x-auto rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-700 bg-gray-800">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">IP</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Pays</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">ISP</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Raison</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                <?php if (empty($entries)): ?>
                    <tr><td colspan="6" class="text-center text-gray-400 py-6">Aucune IP bannie.</td></tr>
                <?php else: ?>
                    <?php foreach ($entries as $e): ?>
                        <tr>
                            <td class="px-4 py-2 text-xs text-gray-400 font-mono"><?= htmlspecialchars($e['date']) ?></td>
                            <td class="px-4 py-2 text-sm font-bold text-white"><?= htmlspecialchars($e['ip']) ?></td>
                            <td class="px-4 py-2 text-sm flex items-center gap-2">
                                <span><?= htmlspecialchars($e['country']) ?></span>
                            </td>
                            <td class="px-4 py-2 text-sm"><?= htmlspecialchars($e['isp']) ?></td>
                            <td class="px-4 py-2 text-sm text-purple-300"><?= htmlspecialchars($e['reason']) ?></td>
                            <td class="px-4 py-2">
                                <button class="deleteBtn bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded" data-line="<?= $e['line'] ?>" data-ip="<?= htmlspecialchars($e['ip']) ?>"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
<script>
// Suppression d'une IP
const deleteBtns = document.querySelectorAll('.deleteBtn');
deleteBtns.forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const line = this.dataset.line;
        const ip = this.dataset.ip;
        Swal.fire({
            title: 'Supprimer ?',
            text: `Supprimer l'IP ${ip} ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `delete_ip=1&delete_line=${line}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Supprimé !', 'L\'IP a été supprimée.', 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Erreur', 'Impossible de supprimer.', 'error');
                    }
                });
            }
        });
    });
});
// Reset du fichier
const resetBtn = document.getElementById('resetBtn');
resetBtn.addEventListener('click', function(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Réinitialiser ?',
        text: 'Toutes les IP bannies seront supprimées.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, reset',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'reset_ban=1'
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Réinitialisé !', 'Toutes les IP bannies ont été supprimées.', 'success').then(() => location.reload());
                } else {
                    Swal.fire('Erreur', 'Impossible de réinitialiser.', 'error');
                }
            });
        }
    });
});
</script>
</body>
</html> 