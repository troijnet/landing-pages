<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/landing-manager.php';

try {
    $m = new LandingManager();
    $site = $m->getSite();
    $pages = $m->getPublishedPages();
} catch (Throwable $e) {
    http_response_code(500);
    die("خطأ: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($site['name']) ?></title>
<meta name="description" content="<?= htmlspecialchars($site['description']) ?>">
<link rel="icon" type="image/svg+xml" href="assets/svg/favicon.svg">
<style>
body{font-family:Tahoma,Arial,sans-serif;background:#FFFBF5;color:#2C1810;margin:0;line-height:1.75}
.container{max-width:1200px;margin:0 auto;padding:0 20px}
header{background:#fff;padding:20px 0;box-shadow:0 2px 10px rgba(0,0,0,.08)}
header h1{color:#8B4513;margin:0;font-size:1.75rem}
.hero{padding:60px 0;text-align:center;background:linear-gradient(135deg,#FFFBF5,#F5EAD9)}
.hero h2{color:#5C2E0C;font-size:2.5rem;margin-bottom:20px}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:30px;padding:60px 0}
.card{background:#fff;padding:30px;border-radius:12px;box-shadow:0 4px 20px rgba(139,69,19,.1)}
.card h3{color:#5C2E0C;margin-bottom:15px}
.card p{color:#6B5A4E;margin-bottom:15px}
.card a{display:inline-block;padding:10px 24px;background:#8B4513;color:#fff;border-radius:8px;text-decoration:none;font-weight:700}
footer{background:#5C2E0C;color:#fff;padding:30px 0;text-align:center}
</style>
</head>
<body>

<header><div class="container"><h1><?= htmlspecialchars($site['name']) ?></h1></div></header>

<div class="hero"><div class="container">
<h2>دليل الشيخات الروحانيات</h2>
<p><?= htmlspecialchars($site['description']) ?></p>
</div></div>

<div class="container"><div class="grid">
<?php foreach ($pages as $p): ?>
<div class="card">
<h3><?= htmlspecialchars($p['name']) ?></h3>
<p><?= htmlspecialchars(mb_substr($p['description'] ?? '', 0, 140)) ?>...</p>
<p><strong>📍 <?= htmlspecialchars($p['city'] ?? '') ?></strong></p>
<a href="<?= htmlspecialchars($m->pageUrl($p)) ?>">زيارة الصفحة →</a>
</div>
<?php endforeach; ?>
</div></div>

<footer><p>© <?= date('Y') ?> <?= htmlspecialchars($site['name']) ?></p></footer>

</body>
</html>
