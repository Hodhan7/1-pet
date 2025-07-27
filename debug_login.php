<!DOCTYPE html>
<html>
<head>
    <title>Debug Login</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .debug { background: #f5f5f5; padding: 10px; margin: 10px 0; border-left: 4px solid #007cba; }
        .error { background: #ffe6e6; border-left-color: #d32f2f; }
        .success { background: #e8f5e8; border-left-color: #4caf50; }
    </style>
</head>
<body>
    <h2>Debug Login Form</h2>
    
    <?php
    require 'db.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        echo "<div class='debug'>";
        echo "<h3>Login Process Debug</h3>";
        echo "Email: " . htmlspecialchars($email) . "<br>";
        echo "Password: " . htmlspecialchars($password) . "<br>";
        echo "Session ID before login: " . session_id() . "<br>";
        echo "</div>";
        
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($user = $result->fetch_assoc()) {
            echo "<div class='debug success'>";
            echo "✅ User found in database<br>";
            echo "User ID: " . $user['id'] . "<br>";
            echo "Role: " . $user['role'] . "<br>";
            echo "Name: " . $user['first_name'] . " " . $user['last_name'] . "<br>";
            echo "</div>";
            
            if (password_verify($password, $user['password'])) {
                echo "<div class='debug success'>";
                echo "✅ Password verification successful<br>";
                echo "</div>";
                
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_email'] = $user['email'];
                
                echo "<div class='debug success'>";
                echo "✅ Session variables set:<br>";
                echo "user_id: " . $_SESSION['user_id'] . "<br>";
                echo "role: " . $_SESSION['role'] . "<br>";
                echo "user_name: " . $_SESSION['user_name'] . "<br>";
                echo "Session ID after setting: " . session_id() . "<br>";
                echo "</div>";
                
                // Determine redirect
                $redirect = '';
                if ($user['role'] === 'pet_owner') {
                    $redirect = 'owner_dashboard.php';
                } elseif ($user['role'] === 'veterinarian') {
                    $redirect = 'vet_dashboard.php';
                } elseif ($user['role'] === 'admin') {
                    $redirect = 'admin/admin_dashboard.php';
                }
                
                echo "<div class='debug'>";
                echo "📍 Would redirect to: " . $redirect . "<br>";
                echo "<a href='$redirect'>🔗 Manual redirect to dashboard</a><br>";
                echo "<a href='debug_dashboard.php'>🔍 Check session status</a><br>";
                echo "</div>";
                
                echo "<script>";
                echo "setTimeout(function() { window.location.href = '$redirect'; }, 3000);";
                echo "</script>";
                echo "<p>Redirecting in 3 seconds...</p>";
                
            } else {
                echo "<div class='debug error'>";
                echo "❌ Password verification failed<br>";
                echo "</div>";
            }
        } else {
            echo "<div class='debug error'>";
            echo "❌ No user found with email: " . htmlspecialchars($email) . "<br>";
            echo "</div>";
        }
    } else {
        ?>
        <form method="POST">
            <p>
                <label>Email:</label><br>
                <input type="email" name="email" value="owner1@email.com" style="width: 300px; padding: 5px;">
            </p>
            <p>
                <label>Password:</label><br>
                <input type="password" name="password" value="demo123" style="width: 300px; padding: 5px;">
            </p>
            <p>
                <input type="submit" value="Debug Login" style="padding: 10px 20px; background: #007cba; color: white; border: none; cursor: pointer;">
            </p>
        </form>
        
        <div class="debug">
            <h3>Current Session Status:</h3>
            Session ID: <?php echo session_id(); ?><br>
            Session Data: <pre><?php print_r($_SESSION); ?></pre>
        </div>
        
        <h3>Available Test Accounts:</h3>
        <ul>
            <li><strong>Pet Owner:</strong> owner1@email.com / demo123</li>
            <li><strong>Veterinarian:</strong> dr.smith@vetclinic.com / demo123</li>
            <li><strong>Admin:</strong> admin@pethealthtracker.com / demo123</li>
        </ul>
        <?php
    }
    ?>
</body>
</html>
