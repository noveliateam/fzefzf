   function addIp() {
       const ip = document.getElementById('ip_input').value.trim();
       const msg = document.getElementById('message');

       if (!ip) {
           msg.innerText = "Veuillez entrer une IP.";
           msg.className = "text-red-400 text-sm mt-2";
           return;
       }

       const formData = new FormData();
       formData.append("ip", ip);

       fetch("./config/save_ip.php", {
               method: "POST",
               body: formData
           })
           .then(res => res.text())
           .then(response => {
               msg.innerText = response;
               msg.className = response.includes("succès") ? "text-green-400 text-sm mt-2" : "text-yellow-400 text-sm mt-2";
               document.getElementById('ip_input').value = "";
               loadIps();
           })
           .catch(() => {
               msg.innerText = "Erreur lors de l'enregistrement.";
               msg.className = "text-red-400 text-sm mt-2";
           });
   }

   function loadIps() {
       fetch("./config/get_ips.php")
           .then(res => res.json())
           .then(ips => {
               const container = document.getElementById('ip_list');
               container.innerHTML = "";

               if (ips.length === 0) {
                   container.innerHTML = "<p class='text-gray-400'>Aucune IP whitelister.</p>";
                   return;
               }

               ips.forEach(ip => {
                   const wrapper = document.createElement("div");

                   wrapper.className = "flex items-center justify-between bg-green-900/30 border border-green-700 text-green-300 px-4 py-3 rounded-lg mb-6 flex items-center animate-fade-in px-4 py-2 rounded text-white mb-2";

                   const ipText = document.createElement("span");
                   ipText.innerText = ip;

                   const deleteBtn = document.createElement("button");
                   deleteBtn.innerHTML = '<i class="fas fa-trash-alt"></i>';
                   deleteBtn.className = "text-red-400 hover:text-red-600 ml-4";
                   deleteBtn.onclick = () => deleteIp(ip);

                   wrapper.appendChild(ipText);
                   wrapper.appendChild(deleteBtn);
                   container.appendChild(wrapper);
               });
           });



   }

   window.addEventListener("DOMContentLoaded", loadIps);

   function deleteIp(ip) {
       const formData = new FormData();
       formData.append("ip", ip);

       fetch("./config/delete_ip.php", {
               method: "POST",
               body: formData
           })
           .then(res => res.text())
           .then(msg => {
               alert(msg); // ou affichage propre si tu préfères
               loadIps(); // Recharge les IPs après suppression
           });
   }