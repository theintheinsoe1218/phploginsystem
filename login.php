<?php
session_start();
include('dbconnect.php');
$error = "";
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = md5(trim($_POST['password']));
    // echo $email.$password;
    $sql = "SELECT * FROM users WHERE email=:email AND password=:password";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    $user_array=$stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_array) {
        $_SESSION['user_array']=$user_array;
        if($_SESSION['user_array']['role']=='user'){
            header('location:user-dashboard.php');

        }else{
            header('location:admin-dashboard.php');

        }
    } else {
        $error = "Invalid Email or Password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        body {
            padding-top: 2px;
        }
    </style>
</head>

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
                                        <h5 class="card-title">Login</h5>
                                    </div>
                                    <form action="login.php" method="post">

                                        <div class="card-body">
                                            <?php if ($error != null) : ?>
                                                <div class="alert alert-danger alert-dismissible fade show">
                                                    <strong><?php echo $error ?></strong>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                                                </div>
                                            <?php endif ?>

                                            <div class="my-2">
                                                <label for="email">Email</label>
                                                <input type="email" name="email" id="email" class="form-control" />
                                            </div>

                                            <div class="mb-2">
                                                <label for="password">Password</label>
                                                <input type="password" name="password" id="password" class="form-control" />
                                            </div>

                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-primary" type="submit" name="login">Login</button>
                                            <span class="float-end">If you have no account yet <a href="register.php">register here</a></span>
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