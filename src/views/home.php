<?php
/**
 * Homepagina.
 */
?>
<section class="hero">
    <h1>Kook vandaag nog!</h1>
    <p>Snel, makkelijk en betaalbaar koken — recepten op maat voor Generatie Z.</p>
    <a class="button" href="<?= htmlspecialchars(url('recipes')) ?>">Bekijk recepten</a>
</section>

<?php if (isDevelopment()): ?>
    <section class="status" aria-label="Systeemstatus">
        <h2>Status</h2>
        <?php
        try {
            db();
            $dbOk = true;
        } catch (PDOException $e) {
            $dbOk = false;
            $dbError = $e->getMessage();
        }
        ?>
        <?php if ($dbOk): ?>
            <p class="status__ok">✔ Databaseverbinding werkt.</p>
        <?php else: ?>
            <p class="status__error">✘ Databaseverbinding mislukt:</p>
            <pre><?= htmlspecialchars($dbError ?? '') ?></pre>
        <?php endif; ?>
    </section>
<?php endif; ?>
