<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/landing-manager.php';
require_once dirname(__DIR__) . '/includes/landing-renderer.php';

$m = new LandingManager();
$page = $m->getPage(basename(__DIR__));

if (!$page) {
    http_response_code(404);
    echo "<h1>404 - الصفحة غير موجودة</h1>";
    exit;
}

$r = new LandingRenderer($m, $page);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head><?= $r->renderHead() ?></head>
<body>
<?= $r->renderHeader() ?>
<main>
<?= $r->renderHero() ?>
<?= $r->renderServices() ?>
<?= $r->renderAbout() ?>
<?= $r->renderFaq() ?>
<?= $r->renderCtaFinal() ?>
</main>
<?= $r->renderFooter() ?>
</body>
</html>
