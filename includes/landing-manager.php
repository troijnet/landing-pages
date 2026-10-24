<?php
declare(strict_types=1);

class LandingManager
{
    private array $data;

    public function __construct(?string $file = null)
    {
        $file = $file ?? dirname(__DIR__) . '/data/landing-pages.json';
        $json = file_get_contents($file);
        $this->data = json_decode($json, true) ?: [];
    }

    public function getSite(): array { return $this->data['site'] ?? []; }
    public function getDefaults(): array { return $this->data['defaults'] ?? []; }

    public function getAllPages(): array
    {
        $p = $this->data['pages'] ?? [];
        usort($p, fn($a, $b) => ($a['order'] ?? 999) <=> ($b['order'] ?? 999));
        return $p;
    }

    public function getPublishedPages(): array
    {
        return array_values(array_filter(
            $this->getAllPages(),
            fn($p) => ($p['status'] ?? 'draft') === 'published'
        ));
    }

    public function getPage(string $slug): ?array
    {
        foreach ($this->getAllPages() as $p) {
            if (($p['slug'] ?? '') === $slug) {
                return array_merge($this->getDefaults(), $p);
            }
        }
        return null;
    }

    public function pageUrl(array $p): string
    {
        $base = rtrim($this->getSite()['url'] ?? '', '/');
        return $base . '/' . rawurlencode($p['slug']) . '/';
    }

    public function whatsappLink(array $p): string
    {
        $phone = preg_replace('/[^0-9]/', '', $p['whatsapp'] ?? '');
        $msg = urlencode($p['whatsapp_msg'] ?? '');
        return "https://wa.me/{$phone}" . ($msg ? "?text={$msg}" : '');
    }

    public function sitelinks(array $p): array
    {
        $links = [
            ['title' => 'خدماتنا', 'url' => '#services'],
            ['title' => 'من نحن', 'url' => '#about'],
            ['title' => 'الأسئلة الشائعة', 'url' => '#faq'],
            ['title' => 'تواصل معنا', 'url' => '#contact'],
        ];
        if (empty($p['services'])) {
            $links = array_values(array_filter($links, fn($l) => $l['url'] !== '#services'));
        }
        return $links;
    }
}
