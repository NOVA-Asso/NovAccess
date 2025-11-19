<?php
include './config.php';

// Connect to the database
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Fetch all links from the database
$orderBy = DEFAULT_SORT . ' ' . DEFAULT_ORDER;
$stmt = $pdo->query("SELECT l.id, l.url, l.name, l.description, l.needs_auth, l.created_at, c.name as category FROM links l LEFT JOIN categories c ON c.id = l.categories_id ORDER BY $orderBy");
$links = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="<?php echo SITE_LANG; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo SITE_DESCRIPTION; ?>">
    <title><?php echo SITE_NAME; ?> - Dashboard</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="css/cards.css">
</head>

<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <h1><?php echo SITE_ICON . ' ' . SITE_NAME; ?></h1>
            <p><?php echo SITE_TAGLINE; ?></p>
        </header>

        <!-- Search Bar -->
        <div class="search-container">
            <div class="search-wrapper">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" class="search-input" id="searchInput" placeholder="<?php echo SEARCH_PLACEHOLDER; ?>"
                    autocomplete="off">
                <svg class="search-clear" id="searchClear" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <div class="search-results-count" id="resultsCount"></div>
        </div>

        <!-- Links Grid -->
        <div class="links-grid" id="linksGrid">
            <?php if (empty($links)): ?>
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    <p><?php echo LABEL_NO_LINKS; ?></p>
                </div>
            <?php else: ?>
                <?php foreach ($links as $link): ?>
                    <article class="link-card" draggable="true" data-id="<?php echo $link['id']; ?>"
                        data-name="<?php echo htmlspecialchars($link['name']); ?>">
                        <div class="card-header">
                            <div class="card-icon loading" data-url="<?php echo htmlspecialchars($link['url']); ?>">
                                <img src="" alt="<?php echo htmlspecialchars($link['name']); ?> logo" style="display: none;">
                            </div>
                            <div class="card-title-wrapper">
                                <h2 class="card-name"><?php echo htmlspecialchars($link['name']); ?></h2>
                                <div class="card-url"><?php echo htmlspecialchars($link['url']); ?></div>
                            </div>
                        </div>

                        <?php if ($link['description']): ?>
                            <p class="card-description"><?php echo htmlspecialchars($link['description']); ?></p>
                        <?php endif; ?>

                        <div class="card-footer">
                            <div>
                                <?php if ($link['needs_auth']): ?>
                                    <span class="card-badge">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        <?php echo LABEL_AUTH_REQUIRED; ?>
                                    </span>
                                <?php endif; ?>

                                <?php if ($link['category'] !== null): ?>
                                    <span class="card-badge card-badge-primary">
                                        <?php echo htmlspecialchars($link['category']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <a href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank" rel="noopener noreferrer"
                                class="card-link">
                                <?php echo LABEL_OPEN_LINK; ?>
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="js/app.js"></script>
</body>

</html>