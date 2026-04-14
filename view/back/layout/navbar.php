<nav class="top-nav">
    <div class="nav-container">

        <!-- LEFT -->
        <div class="nav-left">

            <a href="dashboard.php" class="logo">
                <div class="logo-icon">✔</div>
                Gestion Formation
            </a>

            <div class="nav-menu">

                <div class="nav-item">
                    <a href="dashboard.php" class="nav-link active">
                        Dashboard
                    </a>
                </div>

                <div class="nav-item">
                    <a href="listFormation.php" class="nav-link">
                        Formations
                    </a>
                </div>

                <div class="nav-item">
                    <a href="addFormation.php" class="nav-link">
                        Ajouter
                    </a>
                </div>

            </div>
        </div>

        <!-- RIGHT -->
        <div class="nav-right">

            <!-- THEME SWITCH -->
            <div class="theme-toggle">
                <button class="theme-btn" onclick="setTheme('snow')">☀</button>
                <button class="theme-btn" onclick="setTheme('carbon')">🌙</button>
            </div>

            <!-- USER -->
            <div class="user-menu">
                <div class="user-avatar">A</div>
                <div class="user-name">Admin</div>
            </div>

            <!-- LOGOUT -->
            <a href="logout.php" class="btn-logout">⎋</a>

        </div>

    </div>
</nav>