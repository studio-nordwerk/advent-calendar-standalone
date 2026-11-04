<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adventskalender</title>
    <link rel="stylesheet" href="adventskalender/advent-calendar.css">
    <style>
        /* Nur für diese Beispielseite – auf Ihrer Seite gelten Ihre eigenen Stile */
        body { margin: 0; padding: 1rem; font: 16px/1.5 system-ui, sans-serif; background: #f6f2e7; color: #3a342b; }
        main { max-width: 64rem; margin: 0 auto; }
        h1 { font-family: Georgia, serif; font-weight: 600; }
    </style>
</head>
<body>
<main>
    <h1>Unser Adventskalender</h1>

    <?php include __DIR__.'/adventskalender/einbinden.php'; ?>

</main>
<script src="adventskalender/advent-calendar.js" defer></script>
</body>
</html>
