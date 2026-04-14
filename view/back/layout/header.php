<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion Formation</title>

    <!-- 🌙 DARK MODE -->
    <script>
        if (localStorage.getItem('daynight-theme') === 'carbon') {
            document.documentElement.classList.add('carbon');
        }
    </script>

    <!-- ✅ TEMPLATE CSS (CORRECT PATH) -->
<link rel="stylesheet" href="/view/back/template/templatemo-daynight-style.css">
    <!-- ✅ CUSTOM ONLY (LIGHT) -->
    <style>

        /* DON'T OVERRIDE TEMPLATE TOO MUCH */
        .main-content {
            padding: 2rem; /* match template */
        }

        .form-container {
            max-width: 600px;
            margin: 2rem auto;
        }

        .error {
            color: var(--danger);
            font-size: 0.75rem;
            margin-top: 5px;
        }

    </style>
</head>

<body>

<div class="app-container">

    <!-- ✅ NAVBAR -->
    <?php include 'navbar.php'; ?>

    <!-- ✅ CONTENT START -->
    <main class="main-content">