<?php
$fnameErr = $lnameErr = $emailErr = $contactErr = $passErr = "";
$fname = $lname = $email = $contact = $password = "";

function cleanInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["fname"])) {
        $fnameErr = "First Name is required";
    } else {
        $fname = strtolower(cleanInput($_POST["fname"]));
        if (!preg_match("/^[a-zA-Z-' ]*$/", $fname)) {
            $fnameErr = "Only letters and white space allowed";
        }
    }

    if (empty($_POST["lname"])) {
        $lnameErr = "Last Name is required";
    } else {
        $lname = strtolower(cleanInput($_POST["lname"]));
        if (!preg_match("/^[a-zA-Z-' ]*$/", $lname)) {
            $lnameErr = "Only letters and white space allowed";
        }
    }

    if (!empty($_POST["contact"])) {
        $contact = cleanInput($_POST["contact"]);
        if (!preg_match("/^[0-9]+$/", $contact)) {
            $contactErr = "Only numbers allowed";
        }
    }
    

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = strtolower(cleanInput($_POST["email"]));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }
    if (empty($_POST["password"])) {
        $passErr = "Password is required";
    } else {
        $password = cleanInput($_POST["password"]);
        if (strlen($password) < 8) {
            $passErr = "Password must be at least 8 characters long";
        }
    }

}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Sign Up</title>
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

        fieldset {
            border: none;
        }

        legend {
            display: none;
        }

        .card {
            width: 450px;
            padding: 28px;
            background: #c9d2f1;
            border-radius: 18px;
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
        }

        .form-table td {
            padding: 12px 10px;
            vertical-align: middle;
        }

        .form-table td:first-child {
            width: 35%;
            text-align: right;
            padding-right: 15px;
        }

        label {
            font-weight: 600;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 14px;
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

        input[type="submit"],
        input[type="reset"] {
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            cursor: pointer;
            margin-right: 10px;
        }

        input[type="submit"] {
            background: #4f46e5;
            color: white;
        }

        input[type="submit"]:hover {
            background: #4338ca;
        }

        input[type="reset"] {
            background: #e5e7eb;
        }

        .error {
            display: block;
            margin-top: 6px;
            color: #dc2626;
            font-size: 14px;
        }

        .required {
            color: red;
        }
    </style>
</head>

<body>

    <header>

    </header>
    <div class="container">
        <h1>Sign Up</h1>

        <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <fieldset>
                <div class="card">
                    <table class="form-table">

                        <tr>
                            <td> <label for="firstname">First Name </label><span class="required">*</span></td>
                            <td><input type="text" id="firstname" placeholder="Enter your first name" name="fname"
                                    value="<?= $fname ?>">
                                <span class="error">
                                    <?= $fnameErr ?>
                                </span>

                            </td>
                        </tr>

                        <tr>
                            <td> <label for="lastname">Last Name </label><span class="required">*</span></td>
                            <td><input type="text" id="lastname" placeholder="Enter your last name" name="lname"
                                    value="<?= $lname ?>">
                                <span class="error">
                                    <?= $lnameErr ?>
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td><label>Contact No </label></td>
                            <td><input type="text" id="contact" placeholder="Enter your contact number" name="contact"
                                    value="<?= $contact ?>">
                                <span class="error">
                                    <?= $contactErr ?>
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="email">Email </label><span class="required">*</span></td>
                            <td><input type="text" id="email" placeholder="Enter your Email Address" name="email"
                                    value="<?= $email ?>">
                                <span class="error">
                                    <?= $emailErr ?>
                                </span>
                            </td>

                        </tr>


                        <tr>
                            <td><label for="password">Password </label><span class="required">*</span></td>
                            <td> <input type="password" id="password" placeholder="Enter your password" name="password"
                                    value="<?= $password ?>">
                                <span class="error">
                                    <?= $passErr ?>
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td></td>
                            <td>
                                <br>
                                <input type="submit" value="Submit">
                                <input type="reset">
                            </td>
                        </tr>

                    </table>
                </div>

            </fieldset>

        </form>
    </div>

</body>

</html>