document.addEventListener("DOMContentLoaded", function() {
    const buttons = document.querySelectorAll("button");

    buttons.forEach(button => {
        button.addEventListener("click", function() {
            const projectName = this.parentElement.firstChild.textContent.trim();
            alert(`Ouverture de ${projectName}`);
        });
    });
});
