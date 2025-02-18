### Backend (PHP + MySQL)

**Database Structure:**
- Table: `users`
- Columns: `id (INT, AUTO_INCREMENT, PRIMARY KEY)`, `email (VARCHAR)`, `password (VARCHAR)`

```php
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "auth_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Signup
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signup"])) {
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $password);
    
    if ($stmt->execute()) {
        echo "User created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        if (password_verify($password, $hashedPassword)) {
            echo "Login successful";
        } else {
            echo "Invalid credentials";
        }
    } else {
        echo "User not found";
    }
    $stmt->close();
}
$conn->close();
?>
```

### Frontend (HTML + CSS + JavaScript)

```html
<!DOCTYPE html>
<html>
<head>
    <title>Login & Signup</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        form { margin: 20px auto; width: 300px; }
        input { display: block; width: 100%; padding: 10px; margin: 10px 0; }
        button { padding: 10px; width: 100%; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Signup</h2>
    <form action="backend.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="signup">Signup</button>
    </form>
    
    <h2>Login</h2>
    <form action="backend.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>
```
