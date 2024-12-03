document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.querySelector(".sidebar"); 
    const toggleButton = document.querySelector("#icono-sidebar"); 
    const menuLinks = document.querySelectorAll(".menu ul li a span");
    const logoutLink = document.querySelector(".logout ul li a span"); 
    const userDetails = document.querySelector(".user-details"); 

    let isMinimized = false; 

    if (!sidebar || !toggleButton) {
        console.error("Elementos esenciales no encontrados. Revisa los selectores.");
        return;
    }

    toggleButton.addEventListener("click", () => {
        isMinimized = !isMinimized; 

        if (isMinimized) {
            sidebar.style.width = "15%";
            userDetails.style.display = "none";
            menuLinks.forEach(link => {
                link.style.display = "none";
            });
            if (logoutLink) logoutLink.style.display = "none";
        } else {

            sidebar.style.width = "20%";
            userDetails.style.display = "block"; 
            menuLinks.forEach(link => {
                link.style.display = "inline"; 
            });
            if (logoutLink) logoutLink.style.display = "inline";
        }
    });
});
