<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        body {
            padding-top: 2px;
        }
    </style>
</head>
<?php
include('dbconnect.php');
$nameerror = "";
$emailerror = "";
$addressrror = "";
$passworderror = "";
$cpassworderror = "";
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    // echo $name.$email.$address.$password.$cpassword;



    if ($name == null || $email == null || $address == null || $password == null || $cpassword == null || $password != $cpassword) {
        if (empty($name)) {
            $nameerror = "The name field is requried";
        } elseif (empty($email)) {
            $emailerror = "The email field is requried";
        } elseif (empty($address)) {
            $addresserror = "The address field is requried";
        } elseif (empty($password)) {
            $passworderror = "The password field is requried";
        } elseif (empty($cpassword)) {
            $cpassworderror = "The comfirm-password field is requried";
        } elseif ($password != $cpassword) {
            $notEqualPwd = "The Password does not match";
        }
    } else {
        // $epassword=md5($password);
        $sql = "INSERT INTO users (name,email,address,password) VALUES (:name,:email,:address,:password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':password', md5($password));
        $stmt->execute();
        // echo "<script>alert('Registration Successfull')</script>";
        header('location:login.php');
    }
}

?>

<body>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-success">
                        <div class="card-title">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="index.php" class="text-decoration-none">
                                        <h6 class="text-white">Home</h6>
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="index.php" class="text-decoration-none float-end text-white ms-3">
                                        << Back</a>

                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Register</h5>
                                    </div>
                                    <form action="register.php" method="post">
                                        <div class="card-body">

                                            <div class="mb-2">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" id="name" class="form-control <?php if (!empty($nameerror)) { ?>is-invalid<?php } ?>" value="<?= $name ?>" />
                                                <span class="text-danger"><?php echo $nameerror ?></span>
                                            </div>
                                            <div class="mb-2">
                                                <label for="name">Email</label>
                                                <input type="email" name="email" id="email" class="form-control <?php if (!empty($emailerror)) { ?>is-invalid<?php } ?>" value="<?= $email ?>" />
                                                <span class="text-danger"><?php echo $emailerror ?></span>
                                            </div>
                                            <div class="mb-2">
                                                <label for="address">Address</label>
                                                <textarea name="address" id="address" rows="3" class="form-control <?php if (!empty($addresserror)) { ?>is-invalid<?php } ?>"><?= $address ?></textarea>
                                                <span class="text-danger"><?php echo $addresserror ?></span>
                                            </div>
                                            <div class="mb-2">
                                                <label for="password">Password</label>
                                                <input type="password" name="password" id="password" class="form-control <?php if (!empty($passworderror)) { ?>is-invalid<?php } ?>" value="<?= $password ?>" />
                                                <span class="text-danger"><?php echo $passworderror ?></span>
                                            </div>
                                            <div class="mb-2">
                                                <label for="cpassword">Confirm Password</label>
                                                <input type="password" name="cpassword" id="cpassword" class="form-control <?php if (!empty($cpassworderror)) { ?>is-invalid<?php } ?>" />
                                                <span class="text-danger"><?php echo $cpassworderror ?></span>
                                                <span class="text-danger"><?php echo $notEqualPwd; ?></span>
                                            </div>

                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" name="register" class="btn btn-primary">Register</button>
                                            <span class="float-end">If you had already account <a href="login.php">login here</a></span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</body>

</html>