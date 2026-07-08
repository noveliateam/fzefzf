<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

// Chargement des configurations
$pays_config = include './config/pays_isp.php';
$devices = $pays_config['devices'];
$all_countries = $pays_config['pays'];




// Fonction pour déterminer le continent à partir du code pays
function getContinent($code) {
    $continentMap = [
        'EU' => ['AD', 'AL', 'AT', 'BA', 'BE', 'BG', 'BY', 'CH', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GB', 'GR', 'HR', 'HU', 'IE', 'IS', 'IT', 'LI', 'LT', 'LU', 'LV', 'MC', 'MD', 'ME', 'MK', 'MT', 'NL', 'NO', 'PL', 'PT', 'RO', 'RS', 'RU', 'SE', 'SI', 'SK', 'SM', 'UA', 'VA'],
        'AF' => ['AO', 'BF', 'BI', 'BJ', 'BW', 'CD', 'CF', 'CG', 'CI', 'CM', 'CV', 'DJ', 'DZ', 'EG', 'EH', 'ER', 'ET', 'GA', 'GH', 'GM', 'GN', 'GQ', 'GW', 'KE', 'KM', 'LR', 'LS', 'LY', 'MA', 'MG', 'ML', 'MR', 'MU', 'MW', 'MZ', 'NA', 'NE', 'NG', 'RE', 'RW', 'SC', 'SD', 'SH', 'SL', 'SN', 'SO', 'SS', 'ST', 'SZ', 'TD', 'TG', 'TN', 'TZ', 'UG', 'YT', 'ZA', 'ZM', 'ZW'],
        'AS' => ['AE', 'AF', 'AM', 'AZ', 'BD', 'BH', 'BN', 'BT', 'CC', 'CN', 'CX', 'CY', 'GE', 'HK', 'ID', 'IL', 'IN', 'IO', 'IQ', 'IR', 'JO', 'JP', 'KG', 'KH', 'KP', 'KR', 'KW', 'KZ', 'LA', 'LB', 'LK', 'MM', 'MN', 'MO', 'MV', 'MY', 'NP', 'OM', 'PH', 'PK', 'PS', 'QA', 'SA', 'SG', 'SY', 'TH', 'TJ', 'TL', 'TM', 'TR', 'TW', 'UZ', 'VN', 'YE'],
        'NA' => ['AG', 'AI', 'AW', 'BB', 'BL', 'BM', 'BQ', 'BS', 'BZ', 'CA', 'CR', 'CU', 'CW', 'DM', 'DO', 'GD', 'GL', 'GP', 'GT', 'HN', 'HT', 'JM', 'KN', 'KY', 'LC', 'MF', 'MQ', 'MS', 'MX', 'NI', 'PA', 'PM', 'PR', 'SV', 'SX', 'TC', 'TT', 'US', 'VC', 'VG', 'VI'],
        'SA' => ['AR', 'BO', 'BR', 'CL', 'CO', 'EC', 'FK', 'GF', 'GY', 'PE', 'PY', 'SR', 'UY', 'VE'],
        'OC' => ['AS', 'AU', 'CK', 'FJ', 'FM', 'GU', 'KI', 'MH', 'MP', 'NC', 'NF', 'NR', 'NU', 'NZ', 'PF', 'PG', 'PN', 'PW', 'SB', 'TK', 'TO', 'TV', 'UM', 'VU', 'WF', 'WS']
    ];
    
    foreach ($continentMap as $continent => $codes) {
        if (in_array($code, $codes)) {
            return [
                'EU' => 'Europe',
                'AF' => 'Afrique',
                'AS' => 'Asie',
                'NA' => 'Amérique du Nord',
                'SA' => 'Amérique du Sud',
                'OC' => 'Océanie'
            ][$continent];
        }
    }
    
    return 'Autre';
}

// Organiser les pays par continent
$continents = [];
foreach ($all_countries as $code => $data) {
    $continent = getContinent($code);
    if (!isset($continents[$continent])) {
        $continents[$continent] = [];
    }
    $continents[$continent][$code] = $data;
}

// Trier les continents par ordre logique
$orderedContinents = [
    'Europe' => $continents['Europe'] ?? [],
    'Afrique' => $continents['Afrique'] ?? [],
    'Asie' => $continents['Asie'] ?? [],
    'Amérique du Nord' => $continents['Amérique du Nord'] ?? [],
    'Amérique du Sud' => $continents['Amérique du Sud'] ?? [],
    'Océanie' => $continents['Océanie'] ?? [],
    'Autre' => $continents['Autre'] ?? []
];

// Chargement des paramètres existants
$config_file = './config/antibots_config.json';
$settings = file_exists($config_file) ? json_decode(file_get_contents($config_file), true) : [];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    $settings = [
        'devices' => $_POST['devices'] ?? [],
        'allowed_countries' => $_POST['allowed_countries'] ?? []
    ];

    file_put_contents($config_file, json_encode($settings, JSON_PRETTY_PRINT));
    header("Location: antibots.php?success=1");
    exit;
}



?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Antibots réglage | XCODE_OFF</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.6.6/css/flag-icons.min.css"/>
    <script src="https://kit.fontawesome.com/a2e0ad7c3e.js" crossorigin="anonymous"></script>
    <style>
        
         
    .active-country-tag {
        transition: all 0.2s ease;
    }

    .active-country-tag:hover {
        background-color: #7e22ce;
        transform: translateY(-1px);
    }

    .active-country-tag i.fa-times {
        cursor: pointer;
        transition: opacity 0.2s ease;
    }
        .custom-checkbox {
            position: absolute;
            opacity: 0;
        }
        
        .custom-checkbox + label {
            position: relative;
            cursor: pointer;
            padding-left: 2rem;
            display: inline-flex;
            align-items: center;
            user-select: none;
            transition: all 0.2s ease;
        }
        
        .custom-checkbox + label:hover {
            color: #d8b4fe;
        }
        
        .custom-checkbox + label:before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #6b7280;
            border-radius: 0.25rem;
            background-color: #1f2937;
            transition: all 0.2s ease;
        }
        
        .custom-checkbox:checked + label:before {
            border-color: #a855f7;
            background-color: #7e22ce;
        }
        
        .custom-checkbox:checked + label:after {
            content: '';
            position: absolute;
            left: 0.45rem;
            top: 50%;
            transform: translateY(-50%) rotate(45deg);
            width: 0.375rem;
            height: 0.75rem;
            border: solid white;
            border-width: 0 2px 2px 0;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        .tab-button {
            transition: all 0.3s ease;
        }
        
        .tab-button.active {
            background-color: #4c1d95;
            color: white;
            border-color: #7e22ce;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .pays-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        }
        
        @media (max-width: 640px) {
            .pays-grid {
                grid-template-columns: 1fr;
            }
            
            .continents-tabs {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            
            .tab-button {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
        }
        
        .search-box {
            transition: all 0.3s ease;
        }
        
        .search-box:focus {
            border-color: #a855f7;
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.2);
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-200 min-h-screen">
    
    <!-- Menu PC / MOBILE-->
<?php include './assets/menu.php'?>

<main class="container mx-auto px-4 py-8 max-w-6xl">
    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-900/30 border border-green-700 text-green-300 px-4 py-3 rounded-lg mb-6 flex items-center animate-fade-in">
            <i class="fas fa-check-circle mr-2"></i>Paramètres enregistrés avec succès.
        </div>
    <?php endif; ?>

    <form method="POST">  
        
   
</div>
              
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 shadow-lg mb-6">
  <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
    <i class="fas fa-lock text-purple-400 mr-3"></i>Whitelist d'IP
  </h2>

  <div class="mb-4">
    <label for="ip_input" class="block text-gray-300 mb-2">Ajouter une IP à whitelister :</label>
    <div class="flex">
      <input type="text" id="ip_input" name="ip_input" placeholder="Ex: 192.168.1.1" 
             class="flex-grow bg-gray-700 border border-gray-600 rounded-l-lg px-4 py-2 text-white focus:outline-none">
      <button type="button" onclick="addIp()" class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-r-lg transition">
        <i class="fas fa-plus"></i>
      </button>
    </div>
    <p id="message" class="text-sm mt-2"></p>
  </div>
  <h2 class="text-xl font-semibold text-white mb-6 flex items-center"><i class="fas fa-lock text-purple-400 mr-3"></i>IP Whitelister </h2>
  <br>


  <div id="ip_list" class="space-y-2">
    <!--Affichagfe ici -->
   
  </div>
  <br> <br>
  <h2 class="text-xl font-semibold text-white mb-6 flex items-center"><i class="fas fa-lock text-purple-400 mr-3"></i>Ajouter ISP  </h2>
   
  <!-- Ajout des ISP supplémentaires -->

  <a href="./isp.php" class="relative overflow-hidden bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 flex items-center group transform hover:-translate-y-1 hover:shadow-xl">
    <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></span>
    <i class="fas fa-plus mr-3 text-lg transition-transform duration-300 group-hover:rotate-90"></i>
    <span class="font-medium tracking-wide">Ajouter un ISP</span>
    <span class="absolute right-4 opacity-0 group-hover:opacity-100 group-hover:right-6 transition-all duration-300">
        <i class="fas fa-arrow-right"></i>
    </span>
</a>


</div>

    <!-- Script JS pour whitelist -->
<script src ="./config/whitelist.js"></script>
 



        <!-- Configuration Device -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 shadow-lg mb-6">
            <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                <i class="fa fa-desktop text-purple-400 mr-3"></i>Choissisez les devices à autoriser
            </h2>
          
            <div class="space-y-3">
                <?php foreach ($devices as $code => $device): ?>
                <div class="relative">
                    <input type="checkbox" id="<?= $code ?>" name="devices[]" value="<?= $code ?>" 
                           class="custom-checkbox" <?= isset($settings['devices']) && in_array($code, $settings['devices']) ? 'checked' : '' ?>>
                    <label for="<?= $code ?>" class="text-gray-300 hover:text-purple-300">
                        <?= $device ?>
                    </label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    

        <!-- Sélection PAYS -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 shadow-lg">
            <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                <i class="fas fa-globe text-purple-400 mr-3"></i>Choissisez les pays à autoriser
            </h2>


            <!-- Barre de recherche -->
            <div class="mb-6 relative">
                <input type="text" id="country-search" placeholder="Rechercher un pays..." 
                       class="search-box w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none">
                <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
            </div>
            
            <!-- Onglets Continents -->
            <div class="flex overflow-x-auto pb-2 mb-6 scrollbar-hide continents-tabs">
                <?php foreach ($orderedContinents as $continent => $pays): ?>
                    <?php if (!empty($pays)): ?>
                    <button type="button" 
                            class="tab-button flex-shrink-0 px-4 py-2 mr-2 rounded-lg border border-gray-600 text-gray-300 hover:bg-gray-700"
                            data-tab="<?= strtolower(str_replace(' ', '-', $continent)) ?>">
                        <?= $continent ?> (<?= count($pays) ?>)
                    </button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            
<!-- Mes Pays Actifs -->
<?php if (!empty($settings['allowed_countries'])): ?>
<div class="mb-6 bg-gray-700/50 border border-purple-500/30 rounded-lg p-4">
    <h3 class="text-lg font-semibold text-purple-300 mb-3 flex items-center">
        <i class="fas fa-star mr-2"></i> Mes pays actifs (<?= count($settings['allowed_countries']) ?>)
    </h3>
    <div class="flex flex-wrap gap-2" id="active-countries-container">
        <?php foreach ($settings['allowed_countries'] as $code): 
            if (isset($all_countries[$code])): 
                $data = $all_countries[$code];
        ?>
            <div class="relative active-country-item">
                
                <label for="active_<?= $code ?>" class="active-country-label text-white bg-purple-600/30 hover:bg-purple-600/50 px-3 py-1 rounded-full flex items-center text-sm transition cursor-pointer">
                    <span class="fi fi-<?= $data['flag'] ?> mr-2"></span>
                    <?= $data['nom'] ?>
                  
                    </span>
                </label>
            </div>
        <?php 
            endif;
        endforeach; ?>
    </div>
</div>
<?php endif; ?>
            
            <!-- Contenu des onglets -->
            <div class="space-y-6">
                <?php foreach ($orderedContinents as $continent => $pays): ?>
                    <?php if (!empty($pays)): ?>
                    <div id="<?= strtolower(str_replace(' ', '-', $continent)) ?>-content" class="tab-content">
                        <h3 class="text-lg font-medium text-purple-300 mb-4"><?= $continent ?></h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 pays-grid">
                            <?php foreach ($pays as $code => $data): ?>
                            <div class="relative country-item">
                                <input type="checkbox" id="country_<?= $code ?>" name="allowed_countries[]" value="<?= $code ?>" 
                                       class="custom-checkbox" <?= isset($settings['allowed_countries']) && in_array($code, $settings['allowed_countries']) ? 'checked' : '' ?>>
                                <label for="country_<?= $code ?>" class="text-gray-300 hover:text-purple-300 flex items-center">
                                    <span class="fi fi-<?= $data['flag'] ?> mr-2"></span>
                                    <?= $data['nom'] ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="pt-6">
                <button type="submit" name="save_settings" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold py-3 px-6 rounded-lg shadow-lg hover:from-purple-700 hover:to-indigo-700 transition duration-300 flex items-center justify-center group">
                    <i class="fas fa-paper-plane mr-2 group-hover:translate-x-1 transition-transform"></i>
                    <span>Enregistrer les paramètres</span>
                </button>
            </div>
        </div>
    </form>
</main>

<script>
    // Gestion des onglets
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');
        
        // Activer le premier onglet par défaut
        if (tabButtons.length > 0 && tabContents.length > 0) {
            tabButtons[0].classList.add('active');
            tabContents[0].classList.add('active');
        }
        
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                // Désactiver tous les onglets
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));
                
                // Activer l'onglet sélectionné
                this.classList.add('active');
                document.getElementById(`${tabId}-content`).classList.add('active');
            });
        });
        
        // Recherche de pays
        const searchBox = document.getElementById('country-search');
        const countryItems = document.querySelectorAll('.country-item');
        
        searchBox.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            countryItems.forEach(item => {
                const label = item.querySelector('label').textContent.toLowerCase();
                if (label.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
        
        // Sélection automatique de l'onglet si hash dans l'URL
        if (window.location.hash) {
            const tabFromHash = window.location.hash.substring(1);
            const correspondingButton = document.querySelector(`.tab-button[data-tab="${tabFromHash}"]`);
            if (correspondingButton) {
                correspondingButton.click();
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
    // Gestion de la désélection des pays actifs
    document.querySelectorAll('.remove-country').forEach(removeBtn => {
        removeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const countryCode = this.getAttribute('data-code');
            const checkbox = document.querySelector(`input[value="${countryCode}"]`);
            
            if (checkbox) {
                checkbox.checked = false;
                
                // Mise à jour visuelle immédiate
                const label = checkbox.nextElementSibling;
                label.classList.add('opacity-50', 'line-through');
                label.classList.remove('bg-purple-600/30');
                
                // Animation de disparition
                const item = checkbox.closest('.active-country-item');
                item.style.transition = 'all 0.3s ease';
                item.style.opacity = '0';
                item.style.transform = 'translateX(20px)';
                
                setTimeout(() => {
                    item.remove();
                    updateActiveCount();
                }, 300);
            }
        });
    });
    
    // Mise à jour du compteur de pays actifs
    function updateActiveCount() {
        const activeCount = document.querySelectorAll('.active-country-checkbox:checked').length;
        const counter = document.querySelector('h3 i.fa-star').parentNode;
        counter.innerHTML = `<i class="fas fa-star mr-2"></i> Mes pays actifs (${activeCount})`;
    }
    
    // Synchronisation entre les onglets et la section active
    document.querySelectorAll('.custom-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                // Si cochée dans un onglet, ajouter à la section active
                if (!this.classList.contains('active-country-checkbox')) {
                    addToActiveCountries(this.value);
                }
            } else {
                // Si décochée, supprimer de la section active
                const activeItem = document.querySelector(`.active-country-checkbox[value="${this.value}"]`);
                if (activeItem) {
                    activeItem.closest('.active-country-item').remove();
                    updateActiveCount();
                }
            }
        });
    });
    
    function addToActiveCountries(countryCode) {
        if (!document.querySelector(`.active-country-checkbox[value="${countryCode}"]`) && 
            all_countries[countryCode]) {
            const data = all_countries[countryCode];
            const container = document.getElementById('active-countries-container');
            
            const newItem = document.createElement('div');
            newItem.className = 'relative active-country-item';
            newItem.innerHTML = `
                <input type="checkbox" id="active_${countryCode}" name="allowed_countries[]" value="${countryCode}" 
                       class="custom-checkbox active-country-checkbox" checked>
                <label for="active_${countryCode}" class="active-country-label text-white bg-purple-600/30 hover:bg-purple-600/50 px-3 py-1 rounded-full flex items-center text-sm transition cursor-pointer">
                    <span class="fi fi-${data.flag} mr-2"></span>
                    ${data.nom}
                    <span class="remove-country ml-2 opacity-70 hover:opacity-100" data-code="${countryCode}">
                        <i class="fas fa-times"></i>
                    </span>
                </label>
            `;
            
            container.appendChild(newItem);
            updateActiveCount();
            
            // Ajouter l'event listener au nouveau bouton
            newItem.querySelector('.remove-country').addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const checkbox = document.querySelector(`input[value="${countryCode}"]`);
                if (checkbox) checkbox.checked = false;
                newItem.remove();
                updateActiveCount();
            });
        }
    }
    
    // Rendre la variable all_countries accessible au JS
    const all_countries = <?= json_encode($all_countries) ?>;
});


</script>

</body>
</html>