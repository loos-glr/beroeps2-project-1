/**
 * Smaakmaatje — frontend-gedrag (issue #13).
 *
 * Twee dingen, allebei alleen actief als de bijbehorende elementen op de
 * pagina staan (op andere pagina's gebeurt er dus niets):
 *   1. Recept bewaren  -> localStorage. Er zijn nog geen accounts
 *      (issue #15), daarom bewaren we voorlopig in de browser zelf.
 *   2. Recept delen    -> native share (mobiel) en "kopieer link".
 *
 * Alles in vanilla JavaScript, geen libraries (zie docs/04-prototype.md §3).
 */
(function () {
    'use strict';

    var STORAGE_KEY = 'smaakmaatje-saved-recipes';
    var toastTimer = null;

    /* ---------- Bewaarde recepten (localStorage) ---------- */

    function getSaved() {
        try {
            var parsed = JSON.parse(window.localStorage.getItem(STORAGE_KEY) || '[]');

            return Array.isArray(parsed) ? parsed.map(String) : [];
        } catch (error) {
            return [];
        }
    }

    function setSaved(ids) {
        try {
            window.localStorage.setItem(STORAGE_KEY, JSON.stringify(ids));
        } catch (error) {
            /* localStorage niet beschikbaar (bijv. privémodus): stil negeren. */
        }
    }

    /* ---------- Klein toastje voor feedback ---------- */

    function showToast(message) {
        var toast = document.querySelector('[data-toast]');

        if (!toast) {
            toast = document.createElement('div');
            toast.className = 'toast';
            toast.setAttribute('data-toast', '');
            toast.setAttribute('role', 'status');
            toast.setAttribute('aria-live', 'polite');
            document.body.appendChild(toast);
        }

        toast.textContent = message;
        toast.classList.add('is-visible');

        window.clearTimeout(toastTimer);
        toastTimer = window.setTimeout(function () {
            toast.classList.remove('is-visible');
        }, 2200);
    }

    /* ---------- Bewaren ---------- */

    function initSaveButton() {
        var button = document.querySelector('[data-save-recipe]');

        if (!button) {
            return;
        }

        var id = String(button.getAttribute('data-recipe-id') || '');
        var label = button.querySelector('[data-save-label]') || button;

        function render(isSaved) {
            button.classList.toggle('is-saved', isSaved);
            button.setAttribute('aria-pressed', isSaved ? 'true' : 'false');
            label.textContent = isSaved ? 'Bewaard' : 'Bewaar';
        }

        render(getSaved().indexOf(id) !== -1);

        button.addEventListener('click', function () {
            var saved = getSaved();
            var index = saved.indexOf(id);
            var isSaved = index === -1;

            if (isSaved) {
                saved.push(id);
            } else {
                saved.splice(index, 1);
            }

            setSaved(saved);
            render(isSaved);
            showToast(isSaved ? 'Toegevoegd aan je bewaarde recepten' : 'Verwijderd uit je bewaarde recepten');
        });
    }

    /* ---------- Delen ---------- */

    function copyToClipboard(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            return navigator.clipboard.writeText(text);
        }

        // Fallback voor oudere browsers (en http).
        var input = document.createElement('input');
        input.value = text;
        document.body.appendChild(input);
        input.select();

        try {
            document.execCommand('copy');
        } catch (error) {
            /* negeren */
        }

        document.body.removeChild(input);

        return Promise.resolve();
    }

    function initShareButtons() {
        var copyButton = document.querySelector('[data-share-copy]');

        if (copyButton) {
            copyButton.addEventListener('click', function () {
                var url = copyButton.getAttribute('data-share-url') || window.location.href;

                copyToClipboard(url).then(function () {
                    showToast('Link gekopieerd');
                });
            });
        }

        var nativeButton = document.querySelector('[data-share-native]');

        if (nativeButton && navigator.share) {
            nativeButton.hidden = false;
            nativeButton.addEventListener('click', function () {
                navigator.share({
                    title: nativeButton.getAttribute('data-share-title') || document.title,
                    url: nativeButton.getAttribute('data-share-url') || window.location.href
                }).catch(function () {
                    /* Gebruiker annuleert het deelvenster: negeren. */
                });
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        initSaveButton();
        initShareButtons();
    });
})();
