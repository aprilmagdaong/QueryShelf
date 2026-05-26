<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QueryShelf</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav>
        <a href="index.php" class="active">HOME</a>
        <a href="php/login.php">LOGIN</a>
        <a href="php/browse.php">BROWSE</a>
        <a href="html/about.html">ABOUT</a>
    </nav>

    <div class="split-container">
        <div class="left-panel">
            <div class="brand-content">
                <h1>QueryShelf</h1>
                <p>A library database management system</p>
            </div>
        </div>

        <div class="right-panel">
            <div class="form-box">
                <h2>Welcome Back!</h2>
                <p>Please select an option below to continue:</p>
                
                <div class="action-button-group">
                    <a href="/QueryShelf/php/login.php" class="continue-btn primary-btn">  Get Started </a>
                    <a href="/QueryShelf/php/browse.php" class="continue-btn secondary-btn">Browse Books</a>
                </div>
                
                <footer class="about-footer">
                    <hr class="footer-divider">
                    <a href="/QueryShelf/html/about.html" class="about-link-heading">
                        <i class="bi bi-info-circle"></i> About QueryShelf
                    </a>
                </footer>
            </div>
        </div>
    </div>

</body>
</html>