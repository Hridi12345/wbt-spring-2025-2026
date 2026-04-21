<?php
$fnameErr = $lnameErr = $emailErr = $genderErr =  $reasonErr = $topicErr = "";
$fname = $lname = $email = $gender = $company = $comment = "";
$reason = $topic = [];


function cleanInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Name
    if (empty($_POST["fname"])) {
        $fnameErr = "First Name is required";
    } else {
        $fname = cleanInput($_POST["fname"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $fname)) {
            $fnameErr = "Only letters and white space allowed";
        }
    }
    if (empty($_POST["lname"])) {
        $lnameErr = "Last Name is required";
    } else {
        $lname = cleanInput($_POST["lname"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $lname)) {
            $lnameErr = "Only letters and white space allowed";
        }
    }

    // Email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = cleanInput($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }
    $company = cleanInput($_POST["company"] ?? "");

    // Gender
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = cleanInput($_POST["gender"]);
    }

    if (empty($_POST["reason"])) {
        $reasonErr = "Reason is required";
    } else {
        $reason = $_POST["reason"];
    }

    if (empty($_POST["topic"])) {
        $topicErr = "Topic is required";
    } else {
        $topic = $_POST["topic"];
    }

}
?>

<!DOCTYPE html>
<html>

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <link rel="stylesheet" type="text/css" href="../css/contact.css">
</head>


<body>
    <header>
        <nav>
            <div class="boxmodel-nav">
                <ul>
                    <li><a href="../index.html">Home</a></li>
                    <li><a href="educations.html">Education</a></li>
                    <li><a href="experience.html">Experience</a></li>
                    <li><a href="projects.html">Projects</a></li>
                    <li>Contact</li>
                </ul>
            </div>
        </nav>
    </header>
     
    <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

        <fieldset>
            <legend>Contact Me Form</legend>
            <h2>Contact Me</h2>
            <table class="form-table">

                <tr>
                    <td><label for="fname">First Name:</label><span class="required">*</span></td>
                    <td><input type="text" id="fname" placeholder="Enter your first name" name="fname" value="<?= $fname ?>">
                    <span class="error"><?= $fnameErr ?></span> 
                    </td>
                </tr>
                <tr>
                    <td><label for="lname">Last Name:</label><span class="required">*</span></td>
                    <td><input type="text" id="lname" placeholder="Enter your last name" name="lname" value="<?= $lname ?>">
                    <span class="error"><?= $lnameErr ?></span>  
                    </td>
                </tr>

                <tr>
                    <td><label>Gender:</label><span class="required">*</span></td>
                    <td>
                        <input type="radio" name="gender" value="male" <?= ($gender == "male") ? "checked" : "" ?>> Male &nbsp;
                        <input type="radio" name="gender" value="female" <?= ($gender == "female") ? "checked" : "" ?>> Female 
                        <span class="error"><?= $genderErr ?></span>
                    </td>
                </tr>

                <tr>
                    <td><label for="email">Email:</label><span class="required">*</span></td>
                    <td><input type="text" id="email" name="email" value="<?= $email ?>">
                    <span class="error"><?= $emailErr ?></span> 
                    </td>
                </tr>
                <tr>
                    <td><label for="company">Company:</label></td>
                    <td><input type="text" id="company" placeholder="Enter your company name" name="company" value="<?= $company ?>">
                    </td>
                </tr>

                <tr>
                    <td><label>Reason of contact:</label><span class="required">*</span>
                    <td>
                        <input type="checkbox" name = "reason[]" value="project" <?= in_array("project", $reason) ? "checked" : "" ?>> Projects
                        <input type="checkbox" name = "reason[]" value="thesis" <?= in_array("thesis", $reason) ? "checked" : "" ?>> Thesis
                        <input type="checkbox" name = "reason[]" value="job" <?= in_array("job", $reason) ? "checked" : "" ?>> Job
                        <span class="error"><?= $reasonErr ?></span>
                    </td>
                </tr>

                <tr>
                    <td><label>Topics:</label><span class="required">*</span></td>
                    <td>
                        <input type="checkbox" name = "topic[]" value="Web Development" <?= in_array("Web Development", $topic) ? "checked" : "" ?>> Web Development
                        <input type="checkbox" name = "topic[]" value="Mobile Development" <?= in_array("Mobile Development", $topic) ? "checked" : "" ?>> Mobile Development
                        <input type="checkbox" name = "topic[]" value="AI/ML Development" <?= in_array("AI/ML Development", $topic) ? "checked" : "" ?>> AI/ML Development
                        <input type="checkbox" name = "topic[]" value="Research" <?= in_array("Research", $topic) ? "checked" : "" ?>> Research
                        <span class="error"><?= $topicErr ?></span>
                    </td>
                </tr>

                <tr>
                    <td><label for="date">Consultation Date:</label></td>
                    <td><input type="date" id="date" name="date"></td>
                </tr>
                <tr>
                    <td><label for="time">Consultation Time:</label></td>
                    <td><input type="time" id="time" name="time"></td>

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

        </fieldset>

    </form>
     <br>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" &&
        !$fnameErr && !$fnameErr && !$emailErr && !$genderErr): ?>
        <h3>Submitted values</h3>
        <table class="result-table">
            <tr><td>First Name</td><td><?= $fname ?></td></tr>
            <tr><td>Last Name</td><td><?= $lname ?></td></tr>
            <tr><td>Email</td><td><?= $email ?></td></tr>
            <tr><td>Gender</td><td><?= $gender ?></td></tr>
            <tr><td>Company Name</td><td><?= $company ?></td></tr>
            <tr><td>Reason </td><td><?php foreach($reason as $r) echo "$r "; ?></td></tr>
            <tr><td>Topic </td><td><?php foreach($topic as $t) echo "$t "; ?></td></tr>
        </table>
    <?php endif; ?>

    <footer>
        <a href="https://github.com/Hridi12345">
            <img src="../data/github.png" width="50" height="50" alt="Github">
        </a>
        <a href="https://www.linkedin.com/in/fatima-hridi-9274a1361?trk=contact-info">
            <img src="../data/linkedin.png" width="30" height="30" alt="Linkedin">
        </a>

    </footer>
</body>

</html>