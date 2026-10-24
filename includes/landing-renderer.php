<?php
declare(strict_types=1);

require_once __DIR__ . '/landing-manager.php';

class LandingRenderer
{
    private $manager;
    private array $page;
    private array $site;
    private string $pageUrl;
    private string $waLink;

    public function __construct(LandingManager $manager, array $page)
    {
        $this->manager = $manager;
        $this->page = $page;
        $this->site = $manager->getSite();
        $this->pageUrl = $manager->pageUrl($page);
        $this->waLink = $manager->whatsappLink($page);
    }

    public function renderHead(): string
    {
        $p = $this->page;
        $title = htmlspecialchars($p['title'] ?? '');
        $desc = htmlspecialchars($p['description'] ?? '');
        $kw = htmlspecialchars($p['keywords'] ?? '');
        $auth = htmlspecialchars($p['author'] ?? '');
        $url = htmlspecialchars($this->pageUrl);
        return <<<HTML
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$title}</title>
<meta name="description" content="{$desc}">
<meta name="keywords" content="{$kw}">
<meta name="author" content="{$auth}">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{$url}">
<meta property="og:type" content="website">
<meta property="og:title" content="{$title}">
<meta property="og:description" content="{$desc}">
<meta property="og:url" content="{$url}">
<link rel="icon" type="image/svg+xml" href="/assets/svg/favicon.svg">
<link rel="stylesheet" href="/assets/css/main.css">
<link rel="stylesheet" href="/assets/css/landing.css">
HTML;
    }

    public function renderHeader(): string
    {
        $p = $this->page;
        $city = htmlspecialchars($p['city'] ?? '');
        $country = htmlspecialchars($p['country'] ?? '');
        $wa = $this->waLink;
        $nav = '';
        foreach ($this->manager->sitelinks($p) as $l) {
            $nav .= "<li><a href=\"{$l['url']}\">{$l['title']}</a></li>";
        }
        return <<<HTML
<div class="top-bar">
  <div class="container top-bar__inner">
    <span>📍 {$city} - {$country}</span>
    <a href="{$wa}" class="top-bar__wa" target="_blank">💬 واتساب</a>
  </div>
</div>
<header class="site-header">
  <div class="container site-header__inner">
    <a href="#hero"><img src="/assets/svg/logo.svg" alt="شعار" height="50"></a>
    <nav class="site-nav"><ul>{$nav}</ul></nav>
    <a href="{$wa}" class="btn btn--wa" target="_blank">تواصل الآن</a>
  </div>
</header>
HTML;
    }

    public function renderHero(): string
    {
        $p = $this->page;
        $h1 = htmlspecialchars(($p['name'] ?? '') . ' في ' . ($p['city'] ?? ''));
        $author = htmlspecialchars($p['author'] ?? '');
        $wa = $this->waLink;
        return <<<HTML
<section class="hero" id="hero">
  <div class="container hero__grid">
    <div class="hero__content">
      <h1>{$h1}</h1>
      <p class="hero__lead">الدكتورة <strong>{$author}</strong> — خبرة تتجاوز 15 عاماً في العلاج الروحاني الشرعي.</p>
      <ul class="hero__badges">
        <li>✅ خبرة +15 سنة</li>
        <li>🔒 خصوصية تامة</li>
        <li>⚡ استجابة فورية</li>
      </ul>
      <div class="hero__cta">
        <a href="{$wa}" class="btn btn--primary btn--lg" target="_blank">💬 اطلب استشارتك</a>
        <a href="#services" class="btn btn--outline btn--lg">تعرف على الخدمات</a>
      </div>
      <p class="hero__trust">⭐⭐⭐⭐⭐ أكثر من 5,000 عميل راضٍ</p>
    </div>
    <div class="hero__media">
      <img src="/assets/svg/hero-placeholder.svg" alt="شيخة روحانية" class="hero__img">
    </div>
  </div>
</section>
HTML;
    }

    public function renderServices(): string
    {
        $services = $this->page['services'] ?? [];
        if (empty($services)) return '';
        $cards = '';
        foreach ($services as $s) {
            $title = htmlspecialchars($s['title'] ?? '');
            $desc = htmlspecialchars($s['desc'] ?? '');
            $price = htmlspecialchars($s['price'] ?? '');
            $wa = $this->waLink;
            $cards .= <<<HTML
<article class="card">
  <img src="/assets/svg/service-placeholder.svg" alt="{$title}" class="card__img">
  <div class="card__body">
    <h3 class="card__title">{$title}</h3>
    <p class="card__desc">{$desc}</p>
    <span class="card__price">{$price}</span>
    <a href="{$wa}" class="btn btn--primary btn--block" target="_blank">اطلب الخدمة</a>
  </div>
</article>
HTML;
        }
        $name = htmlspecialchars($this->page['name'] ?? '');
        return <<<HTML
<section class="section" id="services">
  <div class="container">
    <h2 class="section__title">خدمات {$name}</h2>
    <div class="grid grid--3">{$cards}</div>
  </div>
</section>
HTML;
    }

    public function renderAbout(): string
    {
        $p = $this->page;
        $author = htmlspecialchars($p['author'] ?? '');
        $name = htmlspecialchars($p['name'] ?? '');
        $city = htmlspecialchars($p['city'] ?? '');
        return <<<HTML
<section class="section section--alt" id="about">
  <div class="container">
    <h2 class="section__title">من هي {$author}؟</h2>
    <p style="text-align:center;max-width:800px;margin:0 auto;color:var(--text-light);font-size:1.05rem;line-height:1.9">
      <strong>{$name}</strong> مقيمة في مدينة {$city}، حاصلة على شهادات معتمدة في علوم الرقية الشرعية والعلاج بالقرآن الكريم.
      قدّمت خدماتها لأكثر من 5,000 حالة على مدار 15 عاماً، وتضمن السرية التامة لجميع عملائها.
    </p>
  </div>
</section>
HTML;
    }

    public function renderFaq(): string
    {
        $faq = $this->page['faq'] ?? [];
        if (empty($faq)) return '';
        $items = '';
        foreach ($faq as $f) {
            $q = htmlspecialchars($f['q'] ?? '');
            $a = htmlspecialchars($f['a'] ?? '');
            $items .= "<details class=\"faq__item\"><summary>{$q}</summary><p>{$a}</p></details>";
        }
        return <<<HTML
<section class="section" id="faq">
  <div class="container">
    <h2 class="section__title">الأسئلة الشائعة</h2>
    <div class="faq">{$items}</div>
  </div>
</section>
HTML;
    }

    public function renderCtaFinal(): string
    {
        $wa = $this->waLink;
        return <<<HTML
<section class="section cta-final" id="contact">
  <div class="container">
    <h2>هل أنت مستعد لحل مشكلتك؟</h2>
    <p>تواصل الآن واحصل على استشارتك عبر واتساب</p>
    <a href="{$wa}" class="btn btn--primary btn--xl" target="_blank">💬 تواصل عبر واتساب</a>
  </div>
</section>
HTML;
    }

    public function renderFooter(): string
    {
        $p = $this->page;
        $siteName = htmlspecialchars($this->site['name'] ?? '');
        $author = htmlspecialchars($p['author'] ?? '');
        $city = htmlspecialchars($p['city'] ?? '');
        $wa = $this->waLink;
        $year = date('Y');
        $social = '';
        foreach ($p['social'] ?? [] as $platform => $url) {
            if (!$url) continue;
            $u = htmlspecialchars($url);
            $l = htmlspecialchars(ucfirst($platform));
            $social .= "<a href=\"{$u}\" class=\"social__icon\" target=\"_blank\" rel=\"noopener\">{$l}</a>";
        }
        return <<<HTML
<footer class="site-footer">
  <div class="container site-footer__grid">
    <div>
      <h3>{$siteName}</h3>
      <p>{$author} — {$city}</p>
    </div>
    <div>
      <h4>تابعنا</h4>
      <div class="social">{$social}</div>
    </div>
  </div>
  <div class="site-footer__bottom">
    <p>© {$year} {$siteName} — جميع الحقوق محفوظة</p>
  </div>
</footer>
<a href="{$wa}" class="wa-float" target="_blank">💬</a>
HTML;
    }

    public function renderTrackingScript(): string
    {
        return '';
    }
}
