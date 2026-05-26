<?php
session_start();
require '../db.php'; 

$error = '';
$selected_role = isset($_POST['role']) ? trim($_POST['role']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($selected_role)) {
        $error = "Please select your staff position from the dropdown menu.";
    } else {
        if ($selected_role === 'Librarian') {
            $stmt = $pdo->prepare('
                SELECT u.user_id, u.user_fname, u.user_lname, u.user_type, l.librarian_pass AS operational_password 
                FROM user u
                LEFT JOIN librarian l ON u.user_id = l.user_id
                WHERE u.email = ? AND u.user_type = ?
            ');
        } else {
            $stmt = $pdo->prepare('
                SELECT u.user_id, u.user_fname, u.user_lname, u.user_type, c.libclerk_pass AS operational_password 
                FROM user u
                LEFT JOIN `library clerk` c ON u.user_id = c.user_id
                WHERE u.email = ? AND u.user_type = ?
            ');
        }

        $stmt->execute([$email, $selected_role]);
        $user = $stmt->fetch();

        if ($user) {
            if ($password === $user['operational_password']) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['user_fname'] . ' ' . $user['user_lname'];
                $_SESSION['user_type'] = $user['user_type'];

                if ($user['user_type'] === 'Librarian') {
                    header('Location: /QueryShelf/php/librarian_dashboard.php');
                    exit;
                } elseif ($user['user_type'] === 'Library Clerk') {
                    header('Location: /QueryShelf/php/clerk_dashboard.php');
                    exit;
                }
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No account is registered to this email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/QueryShelf/css/login.css">
</head>
<body>

    <div class="split-container">
        <div class="left-panel">
            <div class="brand-content">
                <h1>QueryShelf</h1>
                <p>A library database management system</p>
            </div>
        </div>

        <div class="right-panel">
            
            <a href="../index.php" class="corner-back-btn" aria-label="Back">
                <i class="bi bi-arrow-left"></i> Back
            </a>

            <div class="form-box login-box">
                <h2>Welcome, Staff!</h2>
                <p>Please login with your authorized account:</p>

                <form id="loginForm" action="login.php" method="POST">
        
                    <?php if (!empty($error)): ?>
                        <p class="error-msg">
                            <?php echo htmlspecialchars($error); ?>
                        </p>
                    <?php endif; ?>

                    <div class="input-group-container">
                        <label class="form-label-custom">Select Staff Position</label>
                        <div class="custom-dropdown-wrapper">
                            <div class="form-input-custom dropdown-trigger" id="dropdownTrigger">
                                <span id="triggerText"></span>
                                <i class="bi bi-chevron-down" style="color: #aa06e6;"></i>
                            </div>
        
                            <ul class="dropdown-menu-list" id="dropdownMenuList">
                                <li data-value="Librarian">Librarian</li>
                                <li data-value="Library Clerk">Library Clerk</li>
                            </ul>

                            <select name="role" id="roleSelect" style="display: none !important;">
                                <option value="" disabled selected></option>
                                <option value="Librarian">Librarian</option>
                                <option value="Library Clerk">Library Clerk</option>
                            </select>

                        </div>
                    </div>

                    <div class="input-group-container">
                        <label class="form-label-custom">Email Address</label>
                        <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required placeholder="Enter your email" class="form-input-custom">
                    </div>

                    <div class="input-group-container password-margin">
                        <label class="form-label-custom">Password</label>
                        <div class="password-wrapper">
                        <input type="password" id="password" name="password" required placeholder="Enter your password" class="form-input-custom">

                        <span class="toggle-password" onclick="togglePassword()">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </span>
                    </div>
                    </div>
    
                    <button type="submit" class="continue-btn primary-btn">Login</button>
                </form>
            </div>
        </div>
    </div>

    <script src="/QueryShelf/js/login.js"></script>
</body>
</html>