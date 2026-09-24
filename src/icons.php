<?php
declare(strict_types=1);

/**
 * Small inline-SVG icon set (stroke-based, currentColor) so the site never
 * depends on an icon font or emoji glyphs. Output is trusted static markup,
 * safe to echo directly.
 */
function icon(string $name, string $class = 'h-6 w-6'): string
{
    // Real brand marks (filled logos, own viewBox): WhatsApp, Facebook, Instagram.
    $brandIcons = [
        'whatsapp' => [
            'viewBox' => '0 0 448 512',
            'path' => 'M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z',
        ],
        'facebook' => [
            'viewBox' => '0 0 320 512',
            'path' => 'M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z',
        ],
        'instagram' => [
            'viewBox' => '0 0 448 512',
            'path' => 'M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z',
        ],
    ];

    if (isset($brandIcons[$name])) {
        $b = $brandIcons[$name];
        $fill = $b['color'] ?? 'currentColor';
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="' . e($b['viewBox']) . '" fill="' . e($fill) . '" class="' . e($class) . '" aria-hidden="true"><path d="' . $b['path'] . '"/></svg>';
    }

    $paths = [
        'compass'    => '<circle cx="12" cy="12" r="9" stroke-width="1.5"/><path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M15.5 8.5l-2.2 5.3a1 1 0 01-.5.5L8.5 15.5l2.2-5.3a1 1 0 01.5-.5l4.3-1.2z"/>',
        'plane'      => '<path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.77 59.77 0 0121.485 12 59.77 59.77 0 013.27 20.876L6 12zm0 0h7.5"/>',
        'home'       => '<path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M3.75 10.5L12 3.75l8.25 6.75M5.25 9.75V19.5a.75.75 0 00.75.75h4.5v-5.25h3v5.25h4.5a.75.75 0 00.75-.75V9.75"/>',
        'document'   => '<path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M8.25 3.75h5.379a1.5 1.5 0 011.06.44l3.622 3.62a1.5 1.5 0 01.44 1.061V19.5a1.5 1.5 0 01-1.5 1.5h-9a1.5 1.5 0 01-1.5-1.5V5.25a1.5 1.5 0 011.5-1.5z"/><path stroke-width="1.5" stroke-linecap="round" d="M9.75 12h4.5M9.75 15.5h4.5"/>',
        'passport'   => '<rect x="5.25" y="3.75" width="13.5" height="16.5" rx="1.5" stroke-width="1.5"/><circle cx="12" cy="10" r="2.1" stroke-width="1.5"/><path stroke-width="1.5" stroke-linecap="round" d="M9 15.25c.7-.9 1.85-1.4 3-1.4s2.3.5 3 1.4M8.25 7.25h.01M15.75 7.25h.01"/>',
        'flag-us'    => '<rect x="3.75" y="5.25" width="16.5" height="13.5" rx="1" stroke-width="1.5"/><path stroke-width="1.2" d="M3.75 8.35h16.5M3.75 11.15h16.5M3.75 13.95h16.5M3.75 16.75h16.5"/><rect x="3.75" y="5.25" width="7" height="6.6" stroke-width="1.2" fill="currentColor" fill-opacity=".12"/>',
        'flag-uk'    => '<rect x="3.75" y="5.25" width="16.5" height="13.5" rx="1" stroke-width="1.5"/><path stroke-width="1.3" d="M3.75 5.25l16.5 13.5M20.25 5.25L3.75 18.75M12 5.25v13.5M3.75 12h16.5"/>',
        'card'       => '<rect x="3" y="5.75" width="18" height="12.5" rx="1.75" stroke-width="1.5"/><path stroke-width="1.5" d="M3 9.75h18"/><path stroke-width="1.5" stroke-linecap="round" d="M6.5 14.75h4"/>',
        'briefcase'  => '<rect x="3" y="7.5" width="18" height="11.25" rx="1.5" stroke-width="1.5"/><path stroke-width="1.5" d="M8.25 7.5V6a1.5 1.5 0 011.5-1.5h4.5A1.5 1.5 0 0115.75 6v1.5M3 12.75h18"/>',
        'graduate'   => '<path stroke-width="1.5" stroke-linejoin="round" d="M12 4.5l9 4.5-9 4.5-9-4.5 9-4.5z"/><path stroke-width="1.5" stroke-linecap="round" d="M6.75 11.25V16c0 1.24 2.35 2.25 5.25 2.25s5.25-1.01 5.25-2.25v-4.75M21 9v5.25"/>',
        'shield'     => '<path stroke-width="1.5" stroke-linejoin="round" d="M12 3.75l7.5 2.75v5.4c0 4.4-3.15 7.9-7.5 9.35-4.35-1.45-7.5-4.95-7.5-9.35V6.5L12 3.75z"/><path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M9 12.25l2 2 4-4.25"/>',
        'phone'      => '<path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M7.05 4.5h2.4l1.2 3.6-1.8 1.35a9.75 9.75 0 004.65 4.65l1.35-1.8 3.6 1.2v2.4a1.5 1.5 0 01-1.6 1.5A15 15 0 015.55 6.1a1.5 1.5 0 011.5-1.6z"/>',
        'whatsapp'   => '<path stroke-width="1.4" stroke-linejoin="round" d="M6.3 17.7L4.5 19.5l1.85-.55A7.5 7.5 0 1012 19.5a7.4 7.4 0 01-5.7-1.8z"/><path stroke-width="1.4" stroke-linecap="round" d="M9.3 8.7c-.2.7 0 1.6.9 2.7 1 1.3 1.9 1.9 3 2.2.6.2 1.2-.1 1.5-.6l.3-.5"/>',
        'mail'       => '<rect x="3" y="5.25" width="18" height="13.5" rx="1.5" stroke-width="1.5"/><path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.5L12 12.75 20.25 6.5"/>',
        'location'   => '<path stroke-width="1.5" stroke-linejoin="round" d="M12 21s6.75-6.44 6.75-11.25a6.75 6.75 0 10-13.5 0C5.25 14.56 12 21 12 21z"/><circle cx="12" cy="9.75" r="2.25" stroke-width="1.5"/>',
        'menu'       => '<path stroke-width="1.7" stroke-linecap="round" d="M3.75 6.5h16.5M3.75 12h16.5M3.75 17.5h16.5"/>',
        'close'      => '<path stroke-width="1.7" stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/>',
        'chevron-down' => '<path stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>',
        'chevron-right' => '<path stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" d="M9 6l6 6-6 6"/>',
        'check-circle' => '<circle cx="12" cy="12" r="9" stroke-width="1.5"/><path stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" d="M8.5 12.25l2.4 2.4 4.6-5"/>',
        'star'       => '<path stroke-width="1.4" stroke-linejoin="round" d="M12 3.75l2.47 5.13 5.53.68-4.05 3.87 1.04 5.57L12 16.35l-4.99 2.65 1.04-5.57-4.05-3.87 5.53-.68L12 3.75z"/>',
        'users'      => '<circle cx="9" cy="8.25" r="2.75" stroke-width="1.5"/><path stroke-width="1.5" stroke-linecap="round" d="M3.75 19c0-2.9 2.35-4.75 5.25-4.75S14.25 16.1 14.25 19M16 8.5a2.5 2.5 0 110-5M16.5 14.5c2.2.3 3.75 1.9 3.75 4.5"/>',
        'globe'      => '<circle cx="12" cy="12" r="9" stroke-width="1.5"/><path stroke-width="1.5" d="M3 12h18M12 3c2.4 2.4 3.6 5.4 3.6 9s-1.2 6.6-3.6 9c-2.4-2.4-3.6-5.4-3.6-9S9.6 5.4 12 3z"/>',
        'sparkles'   => '<path stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" d="M9.5 3.75l1 3 3 1-3 1-1 3-1-3-3-1 3-1 1-3zM17.5 12l.75 2.25 2.25.75-2.25.75-.75 2.25-.75-2.25-2.25-.75 2.25-.75.75-2.25z"/>',
        'arrow-right' => '<path stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15M13.5 5.75L19.75 12l-6.25 6.25"/>',
        'grid'       => '<rect x="3.75" y="3.75" width="7" height="7" rx="1.25" stroke-width="1.5"/><rect x="13.25" y="3.75" width="7" height="7" rx="1.25" stroke-width="1.5"/><rect x="3.75" y="13.25" width="7" height="7" rx="1.25" stroke-width="1.5"/><rect x="13.25" y="13.25" width="7" height="7" rx="1.25" stroke-width="1.5"/>',
        'inbox'      => '<path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h4.5l1.5 2.75h4.5L15.75 12h4.5M3.75 12l1.35-6.05A1.5 1.5 0 016.55 4.75h10.9a1.5 1.5 0 011.45 1.2L20.25 12M3.75 12v5.25a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5V12"/>',
        'lock'       => '<rect x="5.25" y="10.5" width="13.5" height="9" rx="1.5" stroke-width="1.5"/><path stroke-width="1.5" d="M8.25 10.5V7.5a3.75 3.75 0 117.5 0v3"/>',
        'logout'     => '<path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M15.75 8.25V6.5a1.5 1.5 0 00-1.5-1.5h-6.5a1.5 1.5 0 00-1.5 1.5v11a1.5 1.5 0 001.5 1.5h6.5a1.5 1.5 0 001.5-1.5v-1.75M9.75 12h10.5m0 0l-3-3m3 3l-3 3"/>',
        'trash'      => '<path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.75h15M9.75 6.75V5.25a1.5 1.5 0 011.5-1.5h1.5a1.5 1.5 0 011.5 1.5v1.5m-8.25 0l.75 12a1.5 1.5 0 001.5 1.4h6a1.5 1.5 0 001.5-1.4l.75-12"/>',
        'plus'       => '<path stroke-width="1.75" stroke-linecap="round" d="M12 4.5v15M4.5 12h15"/>',
        'pencil'     => '<path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M16.5 4.5l3 3L7.5 19.5H4.5v-3L16.5 4.5z"/>',
        'link'       => '<path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M9.5 14.5l5-5M8.25 15.75L6 18a3 3 0 104.24 4.24l2.25-2.25M15.75 8.25L18 6a3 3 0 10-4.24-4.24l-2.25 2.25"/>',
        'toggle-on'  => '<rect x="3" y="7" width="18" height="10" rx="5" stroke-width="1.5"/><circle cx="16" cy="12" r="3.25" fill="currentColor" stroke="none"/>',
        'bell'       => '<path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M6 8.25a6 6 0 1112 0c0 4.5 1.5 6 1.5 6h-15s1.5-1.5 1.5-6z"/><path stroke-width="1.5" stroke-linecap="round" d="M10.25 18.25a1.85 1.85 0 003.5 0"/>',
        'toggle-off' => '<rect x="3" y="7" width="18" height="10" rx="5" stroke-width="1.5"/><circle cx="8" cy="12" r="3.25" stroke-width="1.5"/>',
    ];

    $body = $paths[$name] ?? $paths['sparkles'];

    return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="' . e($class) . '" aria-hidden="true">' . $body . '</svg>';
}

/** Curated icon keys offered in the admin "Services" icon picker. */
function availableServiceIcons(): array
{
    return [
        'compass', 'home', 'document', 'passport', 'flag-us', 'flag-uk', 'card',
        'briefcase', 'graduate', 'shield', 'phone', 'mail', 'location', 'star',
        'users', 'globe', 'sparkles', 'plane', 'check-circle',
    ];
}
