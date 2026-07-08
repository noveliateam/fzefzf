<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
   header('Location: index.php');
    exit;
}

// Chemin vers le fichier .env
$envFile = __DIR__ . '/../.env';

// Charger les variables existantes
$envVariables = [];
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || strpos(trim($line), '=') === false) {
            continue;
        }
        
        list($name, $value) = explode('=', $line, 2);
        $envVariables[trim($name)] = trim($value);
    }
}

// Traitement du formulaire
$success_message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    // Récupération et validation des données
    $chat_id = filter_input(INPUT_POST, 'chat_id', FILTER_SANITIZE_STRING);
    $bot_token = filter_input(INPUT_POST, 'bot_token', FILTER_SANITIZE_STRING);
    
    // Mise à jour des variables
    $envVariables['CHAT_ID'] = $chat_id;
    $envVariables['BOT_TOKEN'] = $bot_token;
    
    // Préparation du contenu pour le fichier .env
    $envContent = "";
    foreach ($envVariables as $key => $value) {
        $envContent .= "$key=$value\n";
    }
    
    // Sauvegarde dans le fichier .env
    if (file_put_contents($envFile, $envContent) !== false) {
        $success_message = "Paramètres sauvegardés avec succès!";
    } else {
        $error_message = "Erreur lors de la sauvegarde des paramètres.";
    }
}

if (isset($_POST['test_bot'])) {
    $botToken = $envVariables['BOT_TOKEN'] ?? '';
    $chatId = $envVariables['CHAT_ID'] ?? '';

    if (!empty($botToken) && !empty($chatId)) {
        $message = "✅ [PANEL X CODE] Le bot Telegram est bien configuré avec succès !";
        $sendUrl = "https://api.telegram.org/bot$botToken/sendMessage";

        $response = @file_get_contents($sendUrl . '?' . http_build_query([
            'chat_id' => $chatId,
            'text' => $message
        ]));

        if ($response) {
            $success_message = "✅  Message de test envoyé avec succès !";
        } else {
            $error_message = "❌ Échec de l'envoi du message de test. Vérifiez le token et l'ID du chat.";
        }
    }
}





?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réglages API | XCODE_OFF</title>
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
    </style>
</head>
<body class="bg-gray-900 text-gray-200 min-h-screen">

      <!-- Menu PC / MOBILE-->
<?php include './assets/menu.php'?>
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            
        <div class="flex items-center justify-between mb-6">
               
            </div>
            
            <?php if (isset($success_message)): ?>
                <div class="bg-green-900/30 border border-green-700 text-green-300 px-4 py-3 rounded-lg mb-6 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?= htmlspecialchars($success_message) ?>
                </div>
            <?php endif; ?>
            
            <div class="settings-card p-6 mb-8">
                <h2 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-key text-purple-400 mr-2"></i>
                    Identifiants Telegram
                </h2>
                
                <form method="POST">
                    <div class="space-y-5">
                        <div>
                            <label for="chat_id" class="block text-sm font-medium text-gray-300 mb-1">ID du Chat Telegram</label>
                            <input 
                                type="text" 
                                id="chat_id" 
                                name="chat_id" 
                                value="<?= htmlspecialchars($envVariables['CHAT_ID'] ?? '') ?>" 
                                class="w-full input-field px-4 py-3 rounded-lg"
                                placeholder="Ex: -1001234567890"
                                required
                            >
                            <p class="mt-1 text-xs text-gray-400">
                                L'ID du chat où les notifications seront envoyées. Commence généralement par -100.
                            </p>
                        </div>
                        
                        <div>
                            <label for="bot_token" class="block text-sm font-medium text-gray-300 mb-1">Token du Bot Telegram</label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="bot_token" 
                                    name="bot_token" 
                                    value="<?= htmlspecialchars($envVariables['BOT_TOKEN'] ?? '') ?>" 
                                    class="w-full input-field px-4 py-3 rounded-lg"
                                    placeholder="Ex: 1234567890:ABCDEFGHIJKLMNOPQRSTUVWXYZ"
                                    required
                                >
                                <button type="button" onclick="toggleVisibility('bot_token')" class="absolute right-3 top-3 text-gray-400 hover:text-purple-300">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-400">
                                Le token de votre bot Telegram obtenu via @BotFather.
                            </p>
                        </div>
                        
                        <div class="pt-4">
                            <button type="submit" name="save_settings" class="w-full submit-btn font-medium py-3 px-4 rounded-lg">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Enregistrer les paramètres
                            </button>
                        </div>

                        <div class="pt-2">
                        <button type="submit" name="test_bot" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition">
                            <i class="fas fa-vial mr-2"></i>
                            Tester le bot
                        </button>
                        </div>
                        <?php if (isset($error_message)): ?>
    <div class="bg-red-900/30 border border-red-700 text-red-300 px-4 py-3 rounded-lg mb-6 flex items-center">
        <i class="fas fa-times-circle mr-2"></i>
        <?= htmlspecialchars($error_message) ?>
    </div>
<?php endif; ?>



                    </div>
                </form>
            </div>
            
            <div class="settings-card p-6">
                <h2 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-question-circle text-blue-400 mr-2"></i>
                    Guide d'utilisation
                </h2>
                
                <div class="space-y-4 text-gray-300">
                    <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-4">
                        <h3 class="font-medium text-blue-400 mb-2">
                            <i class="fas fa-robot mr-2"></i>
                            Comment créer un bot Telegram
                        </h3>
                        <ol class="list-decimal list-inside text-sm space-y-1">
                            <li>Ouvrez Telegram et recherchez @BotFather</li>
                            <li>Envoyez la commande <code class="bg-gray-700 px-1 py-0.5 rounded">/newbot</code></li>
                            <li>Suivez les instructions pour donner un nom à votre bot</li>
                            <li>À la fin, vous recevrez un token - gardez-le précieusement</li>
                        </ol>
                    </div>
                    
                    <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-4">
                        <h3 class="font-medium text-purple-400 mb-2">
                            <i class="fas fa-comments mr-2"></i>
                            Comment obtenir l'ID d'un chat
                        </h3>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            <li>Pour un groupe: ajoutez @RawDataBot au groupe et envoyez <code class="bg-gray-700 px-1 py-0.5 rounded">/start</code></li>
                            <li>Pour un canal: transférez un message du canal vers @RawDataBot</li>
                            <li>L'ID du chat se trouve dans la réponse sous "chat" → "id"</li>
                            <li>Pour les supergroupes, l'ID commence par -100</li>
                        </ul>
                    </div>
                    
                    <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-4">
                        <h3 class="font-medium text-green-400 mb-2">
                            <i class="fas fa-check-circle mr-2"></i>
                            Tester la configuration
                        </h3>
                        <p class="text-sm">
                            Après avoir sauvegardé les paramètres, vous pouvez tester en envoyant un message via le dashboard. 
                            Si tout est configuré correctement, vous devriez recevoir une notification dans votre chat Telegram.
                        </p>
                    </div>
                </div>
            </div>
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