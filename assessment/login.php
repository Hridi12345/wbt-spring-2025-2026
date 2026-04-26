<?php
$message = "";
$emailErr = $passwordErr = "";

$email = $password = "";
$demoemail = strtolower("hridi@gmail.com");
$demopass = "hridi123";

function cleanInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = strtolower(cleanInput($_POST["email"]));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = cleanInput($_POST["password"]);
        if (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters";
        }
    }

    if (!$emailErr && !$passwordErr) {
        if ($demoemail === $email && $password === $demopass) {
            $message = "success";
        } else {
            $message = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Log In</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #eef2ff, #f8fafc);
            color: #1f2937;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            margin-top: 50px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .card {
            width: 380px;
            padding: 28px;
            background: #c9d2f1;
            border-radius: 18px;
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
        }

        label {
            font-weight: 600;
            display: block;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 10px;
            border: 1px solid #acafb4;
            border-radius: 10px;
            font-size: 16px;
            outline: none;
            background: #f5f3f3;
            transition: 0.2s;
        }

        input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            cursor: pointer;
            background: #4f46e5;
            color: white;
        }

        input[type="submit"]:hover {
            background: #4338ca;
        }

        .error {
            display: block;
            color: #dc2626;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Login</h2>
                <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="card">
                <form method="post">
                    <label for="email">Email </label><br>
                    <input type="text" name="email">
                    <span class="error">
                        <?php echo $emailErr; ?>
                    </span>
                    <br>

                    <label for="password">Password </label><br>
                    <input type="password" name="password">
                    <span class="error">
                        <?php echo $passwordErr; ?>
                    </span>
                    <br>

                    <input type="submit" value="Login">
                </form>

            <?php if ($message === "success"): ?>
            <p style="color: green;">Login successful</p>
            <?php elseif ($message === "error"): ?>
            <p class="error">Invalid email or password</p>
            <?php endif; ?>
            </div>
            </div>
            </form>
</body>

</html>