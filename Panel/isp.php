<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
   header('Location: index.php');
    exit;
}

$success_message = '';
$error_message = '';
$countryISPs = '';

// Chemin vers le fichier JSON
$filePath = '../antibots/allowed_isps.json';

// On vérifie si le formulaire de recherche a été soumis
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $isoCode = strtoupper(trim($_GET['search'])); // ISO Code en majuscule

    if (file_exists($filePath)) {
        $data = json_decode(file_get_contents($filePath), true);
        
        if ($data === null) {
            $error_message = "Erreur de lecture du fichier JSON";
        } else {
            // Vérifier si le pays existe dans le fichier JSON
            if (isset($data[$isoCode])) {
                $countryISPs = "
                <div class='space-y-4 text-gray-300'>
                    <div class='flex items-center mb-4'>
                        <span class='text-2xl font-semibold'>FAI déjà ajoutés pour :</span>
                        <span class='ml-2 text-2xl font-bold text-purple-400'>$isoCode</span>
                    </div>
                    <ul class='list-disc pl-5 max-h-60 overflow-y-auto mb-4'>";
                
                foreach ($data[$isoCode] as $index => $isp) {
                    $countryISPs .= "<li class='mb-2 py-1 px-2 hover:bg-gray-700 rounded flex justify-between items-center'>
                        <span>".htmlspecialchars($isp)."</span>
                        <form method='POST' action='' class='inline'>
                            <input type='hidden' name='isp_index' value='$index'>
                            <input type='hidden' name='iso_code' value='$isoCode'>
                            <button type='submit' name='remove_isp' class='text-red-400 hover:text-red-300 ml-2'>
                                <i class='fas fa-trash-alt'></i>
                            </button>
                        </form>
                    </li>";
                }

                $countryISPs .= "</ul>
                    <form method='POST' action='' class='mt-4'>
                        <div class='flex space-x-2'>
                            <input type='text' name='new_isp' placeholder='Ajouter un nouveau FAI' required 
                                   class='flex-1 p-2 bg-gray-700 border border-gray-600 rounded focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-white'>
                            <button type='submit' name='add_isp' value='$isoCode' 
                                    class='bg-purple-600 hover:bg-purple-700 text-white p-2 px-4 rounded transition duration-200'>
                                <svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' viewBox='0 0 20 20' fill='currentColor'>
                                    <path fill-rule='evenodd' d='M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z' clip-rule='evenodd' />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>";
            } else {
                $error_message = "Aucun FAI trouvé pour le pays $isoCode";
            }
        }
    } else {
        $error_message = "Fichier de configuration des FAI introuvable";
    }
}

// Ajouter un nouveau FAI dans le fichier JSON
if (isset($_POST['add_isp'])) {
    $isoCode = $_POST['add_isp']; // Code ISO du pays
    $newIsp = trim($_POST['new_isp']); // Nouveau FAI à ajouter

    if (file_exists($filePath)) {
        $data = json_decode(file_get_contents($filePath), true);
        
        if ($data === null) {
            $error_message = "Erreur de lecture du fichier JSON";
        } else {
            // Vérifier si le pays existe déjà dans le fichier
            if (isset($data[$isoCode])) {
                // Ajouter le nouveau FAI à la liste
                if (!in_array($newIsp, $data[$isoCode])) {
                    $data[$isoCode][] = $newIsp; // Ajouter le FAI
                    // Sauvegarder les données dans le fichier JSON
                    if (file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT)) !== false) {
                        $success_message = "FAI ajouté avec succès!";
                    } else {
                        $error_message = "Erreur lors de l'écriture dans le fichier";
                    }
                } else {
                    $error_message = "Le FAI est déjà présent pour ce pays";
                }
            } else {
                // Si le pays n'existe pas, le créer
                $data[$isoCode] = [$newIsp];
                // Sauvegarder les données dans le fichier JSON
                if (file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT)) !== false) {
                    $success_message = "FAI ajouté et pays créé avec succès!";
                } else {
                    $error_message = "Erreur lors de l'écriture dans le fichier";
                }
            }
        }
    } else {
        $error_message = "Fichier de configuration des FAI introuvable";
    }
}

// Supprimer un FAI existant
if (isset($_POST['remove_isp'])) {
    $isoCode = $_POST['iso_code'];
    $ispIndex = $_POST['isp_index'];

    if (file_exists($filePath)) {
        $data = json_decode(file_get_contents($filePath), true);
        
        if ($data === null) {
            $error_message = "Erreur de lecture du fichier JSON";
        } elseif (isset($data[$isoCode]) && isset($data[$isoCode][$ispIndex])) {
            // Supprimer le FAI de la liste
            $removedIsp = $data[$isoCode][$ispIndex];
            unset($data[$isoCode][$ispIndex]);
            // Réindexer le tableau
            $data[$isoCode] = array_values($data[$isoCode]);
            
            // Sauvegarder les données dans le fichier JSON
            if (file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT)) !== false) {
                $success_message = "FAI '".htmlspecialchars($removedIsp)."' supprimé avec succès!";
            } else {
                $error_message = "Erreur lors de l'écriture dans le fichier";
            }
        } else {
            $error_message = "FAI introuvable pour suppression";
        }
    } else {
        $error_message = "Fichier de configuration des FAI introuvable";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des FAI | XCODE_OFF</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        .settings-card {
            background: linear-gradient(145deg, #1F2937, #111827);
            border: 1px solid #374151;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .input-field {
            background-color: #1F2937;
            border: 1px solid #4B5563;
            color: #F3F4F6;
            transition: all 0.3s;
        }
        
        .input-field:focus {
            border-color: #7C3AED;
            box-shadow: 0 0 0 2px rgba(124, 58, 237, 0.2);
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #7C3AED 0%, #5B21B6 100%);
            color: white;
            font-weight: 500;
            letter-spacing: 0.025em;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(124, 58, 237, 0.2), 0 2px 4px -1px rgba(124, 58, 237, 0.12);
        }
        
        .submit-btn:hover {
            background: linear-gradient(135deg, #6D28D9 0%, #4C1D95 100%);
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(124, 58, 237, 0.3), 0 4px 6px -2px rgba(124, 58, 237, 0.15);
        }
        
        .submit-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px -1px rgba(124, 58, 237, 0.2);
        }
        
        .submit-btn i {
            transition: transform 0.3s ease;
        }
        
        .submit-btn:hover i {
            transform: scale(1.1);
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .delete-btn {
            transition: all 0.2s ease;
        }
        
        .delete-btn:hover {
            transform: scale(1.1);
            color: #f87171 !important;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-200 min-h-screen">

<!-- Menu PC / MOBILE-->
<?php include './assets/menu.php'?>

<!-- Main Content -->
<main class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <!-- Espace réservé pour d'autres éléments -->
        </div>
        
        <?php if (!empty($success_message)): ?>
            <div class="bg-green-900/30 border border-green-700 text-green-300 px-4 py-3 rounded-lg mb-6 flex items-center animate-fade-in">
                <i class="fas fa-check-circle mr-2"></i>
                <?= htmlspecialchars($success_message) ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="bg-red-900/30 border border-red-700 text-red-300 px-4 py-3 rounded-lg mb-6 flex items-center animate-fade-in">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>
        
        <div class="settings-card p-6 mb-8">
            <h2 class="text-xl font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-search text-purple-400 mr-2"></i>
                Rechercher un pays par son code ISO
            </h2>
            
            <!-- Formulaire de recherche -->
            <form action="" method="get">
                <div class="relative mb-4">
                    <input type="text" name="search" placeholder="Ex: FR pour la France" required 
                           class="search-box w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none pl-10">
                    <i class="fas fa-globe absolute left-3 top-3.5 text-gray-400"></i>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold py-3 px-6 rounded-lg shadow-lg hover:from-purple-700 hover:to-indigo-700 transition duration-300 flex items-center justify-center group">
                    <i class="fas fa-search mr-2 group-hover:translate-x-1 transition-transform"></i>
                    <span>Rechercher</span>
                </button>
            </form>
        </div>
        
        <?php if (isset($countryISPs)): ?>
            <div class="settings-card p-6">
                <h2 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-list text-blue-400 mr-2"></i>
                    Gestion des FAI
                </h2>
                <?= $countryISPs ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
    function toggleVisibility(elementId) {
        const element = document.getElementById(elementId);
        const btn = element.nextElementSibling;
        
        if (element.type === 'password') {
            element.type = 'text';
            btn.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            element.type = 'password';
            btn.innerHTML = '<i class="fas fa-eye"></i>';
        }
    }
</script>
</body>
</html>