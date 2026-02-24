<?php
$stories = [
    [
        'title' => 'City Hall Opens Digital Service Center',
        'summary' => 'Residents can now access permits and records from a one-stop center downtown.',
        'district' => 'Downtown',
        'lat' => 40.7128,
        'lng' => -74.0060,
        'category' => 'Civic'
    ],
    [
        'title' => 'Riverside Park Cleanup Completed',
        'summary' => 'Volunteers removed over 2 tons of waste and planted 300 native trees.',
        'district' => 'Riverside',
        'lat' => 40.7215,
        'lng' => -73.9974,
        'category' => 'Environment'
    ],
    [
        'title' => 'Metro Line Extension Nears Completion',
        'summary' => 'The new extension is expected to reduce commute time by 18 minutes.',
        'district' => 'East Borough',
        'lat' => 40.7308,
        'lng' => -73.9861,
        'category' => 'Transport'
    ],
    [
        'title' => 'Community Health Fair This Weekend',
        'summary' => 'Free screenings and consultations will be available at the sports ground.',
        'district' => 'North Square',
        'lat' => 40.7412,
        'lng' => -74.0021,
        'category' => 'Health'
    ],
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metro e-Paper Map Edition</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="topbar">
        <h1>Metro e-Paper</h1>
        <p>Local headlines with live location mapping</p>
    </header>

    <main class="layout">
        <section class="paper">
            <h2>Today's Headlines</h2>
            <div class="stories">
                <?php foreach ($stories as $story): ?>
                    <article class="story" data-lat="<?= htmlspecialchars((string)$story['lat']) ?>" data-lng="<?= htmlspecialchars((string)$story['lng']) ?>">
                        <h3><?= htmlspecialchars($story['title']) ?></h3>
                        <p><?= htmlspecialchars($story['summary']) ?></p>
                        <div class="meta">
                            <span class="tag"><?= htmlspecialchars($story['category']) ?></span>
                            <span><?= htmlspecialchars($story['district']) ?></span>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <aside class="map-panel">
            <h2>Story Map</h2>
            <div id="map" role="application" aria-label="Map with story locations"></div>
            <p class="hint">Tip: Click a headline to center the map.</p>
        </aside>
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        const stories = <?= json_encode($stories, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;

        const map = L.map('map').setView([40.726, -73.996], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const markers = [];
        stories.forEach((story, index) => {
            const marker = L.marker([story.lat, story.lng]).addTo(map)
                .bindPopup(`<strong>${story.title}</strong><br>${story.district}`);
            markers.push(marker);

            const card = document.querySelectorAll('.story')[index];
            if (card) {
                card.addEventListener('click', () => {
                    map.flyTo([story.lat, story.lng], 14, { duration: 0.8 });
                    marker.openPopup();
                });
            }
        });

        if (markers.length > 1) {
            const group = L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.2));
        }
    </script>
</body>
</html>
