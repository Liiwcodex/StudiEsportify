
document.querySelectorAll('img[data-radio]').forEach(img => {
    img.addEventListener('click', function() {
        const radioId = this.getAttribute('data-radio');
        const radio = document.getElementById(radioId);
        if (radio) {
            radio.checked = true;  /* Sélectionne le bouton radio correspondant */
        }
    });
});
