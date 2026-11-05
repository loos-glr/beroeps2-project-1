<?php
/**
 * Gedeelde footer: mobiele bottom tab bar + desktop footer.
 *
 * Bepaalt zelf het actieve tabblad op basis van het huidige pad.
 */
$currentPath = '/' . trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/', '/');
?>
    </main>

    <nav class="bottom-tab-bar" aria-label="Hoofdnavigatie (mobiel)">
        <a class="tab-item <?= $currentPath === '/' ? 'active' : '' ?>" href="<?= htmlspecialchars(url('/')) ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            </svg>
            <span>Ontdek</span>
        </a>
        <a class="tab-item <?= $currentPath === '/recipes' ? 'active' : '' ?>" href="<?= htmlspecialchars(url('recipes')) ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                <rect width="18" height="18" x="3" y="3" rx="2"></rect>
                <path d="M3 9h18"></path>
                <path d="M9 21V9"></path>
            </svg>
            <span>Diner</span>
        </a>
        <a class="tab-item" href="#">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                <path d="M12 2v20"></path>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
            <span>Budget</span>
        </a>
        <a class="tab-item" href="#">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
            </svg>
            <span>Opgeslagen</span>
        </a>
    </nav>

    <footer class="site-footer">
        <p>&copy; <?= date('Y') ?> Stop de Ontkoking — Smaakmaatje</p>
    </footer>

    <script src="<?= htmlspecialchars(asset('js/app.js')) ?>" defer></script>
</body>
</html>
