<?php
session_start();
include('dbconnect.php');
if (!isset($_SESSION['user_array'])) {
    header('location:login.php');
} else {
    if ($_SESSION['user_array']['role'] != 'user') {
        header('location:admin-dashboard.php');
    }
}

//authorized user data
$auth_id = $_SESSION['user_array']['id'];
$sql = "SELECT * FROM users WHERE id=:auth_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':auth_id', $auth_id);
$stmt->execute();
$auth_result = $stmt->fetch(PDO::FETCH_ASSOC);

//edit user
$edit_user_status = false;
if (isset($_GET['user_edit_id'])) {
    $edit_user_status = true;
    $id = $_GET['user_edit_id'];

    $sql = "SELECT * FROM users WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $editUser = $stmt->fetch(PDO::FETCH_ASSOC);
}

//update user
if (isset($_POST['user_update'])) {
    $uid = $_POST['edit_id'];
    $uname = $_POST['edit_name'];
    $uemail = $_POST['edit_email'];
    $uaddress = $_POST['edit_address'];

    $sqlresult = "SELECT * FROM users WHERE id=$uid";
    $sqlresult = $pdo->prepare($sqlresult);
    $sqlresult->execute();
    $user_array = $sqlresult->fetch(PDO::FETCH_ASSOC);
    $old_password = $user_array['password'];

    $input_password = $_POST['edit_password'];
    if ($old_password == $input_password) {
        $new_password = $input_password;
    } else {
        $new_password = md5($input_password);
    }
    // echo $uid.$uname.$uemail.$uaddress.$input_password.$urole;

    try {
        $sql = "UPDATE users SET name=:uname,email=:uemail,address=:uaddress,password=:upassword WHERE id=:edit_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':uname', $uname);
        $stmt->bindParam(':uemail', $uemail);
        $stmt->bindParam(':uaddress', $uaddress);
        $stmt->bindParam(':upassword', $new_password);
        $stmt->bindParam(':edit_id', $uid);
        $stmt->execute();
        header('location:user-dashboard.php');
    } catch (PDOException $e) {
        echo "Error" . $e->getMessage();
    }

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
                                    <a href="admin-dashboard.php" class="text-decoration-none">
                                        <h6 class="text-white">User-Dashboard</h6>
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <form action="logout.php" method="post">
                                        <button type="submit" name="logout" class="btn btn-danger btn-sm float-end" onclick="return confirm('Are you sure you want to logout?')">Logout</button>
                                    </form>

                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mt-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h4>User Info</h4>
                                        <div>
                                            Role:
                                            <span class="badge bg-success"><?= $_SESSION['user_array']['role'] ?></span>
                                        </div>
                                        <div>
                                            Name: <?= $auth_result['name'] ?>
                                        </div>
                                        <div>
                                            Email: <?= $auth_result['email'] ?>
                                        </div>
                                        <div>
                                            Address: <?= $auth_result['address'] ?>
                                        </div>
                                        <div class="mt-2">
                                            <a href="user-dashboard.php?user_edit_id=<?= $_SESSION['user_array']['id'] ?>" class="btn btn-success btn-sm">Edit Your Profile</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <?php if ($edit_user_status == true) : ?>
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <div class="card-title">User Edit Form</div>
                                        </div>
                                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                                            <div class="card-body">
                                                <input type="text" name="edit_id" value="<?= $editUser['id'] ?>" />
                                                <div class="mb-2">
                                                    <label for="name">Name</label>
                                                    <input type="text" name="edit_name" id="name" class="form-control" value="<?= $editUser['name'] ?>" />
                                                </div>
                                                <div class="mb-2">
                                                    <label for="email">Email</label>
                                                    <input type="email" name="edit_email" id="email" class="form-control" value="<?= $editUser['email'] ?>" />
                                                </div>
                                                <div class="mb-2">
                                                    <label for="address">Address</label>
                                                    <textarea name="edit_address" id="address" class="form-control"><?= $editUser['address'] ?></textarea>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="password">Password</label>
                                                    <input type="text" name="edit_password" id="email" class="form-control" value="<?= $editUser['password'] ?>" />
                                                </div>
                                                
                                            </div>

                                            <div class="card-footer">
                                                <button type="submit" name="user_update" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>

                                    </div>
                                <?php endif ?>
                            </div>
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