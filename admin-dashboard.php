<?php
session_start();
include('dbconnect.php');

if (!isset($_SESSION['user_array'])) {
    header('location:login.php');
} else {
    if ($_SESSION['user_array']['role'] != 'admin') {
        header('location:user-dashboard.php');
    }
}
//authorized user data
$auth_id = $_SESSION['user_array']['id'];
$sql = "SELECT * FROM users WHERE id=:auth_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':auth_id', $auth_id);
$stmt->execute();
$auth_result = $stmt->fetch(PDO::FETCH_ASSOC);

//select all users records
$sql = "SELECT * FROM users";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

//edit user
$edit_user_status = false;
if (isset($_GET['id'])) {
    $edit_user_status = true;

    $id = $_GET['id'];

    $sql = "SELECT * FROM users WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $editUser = $stmt->fetch(PDO::FETCH_ASSOC);
}

//update user
if (isset($_POST['update'])) {
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
    $urole = $_POST['edit_role'];
    // echo $id.$uname.$uemail.$upassword.$urole;
    $sql = "UPDATE users SET name=:uname,email=:uemail,address=:uaddress,password=:upassword,role=:urole WHERE id=:edit_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':uname', $uname);
    $stmt->bindParam(':uemail', $uemail);
    $stmt->bindParam(':uaddress', $uaddress);
    $stmt->bindParam(':upassword', $new_password);
    $stmt->bindParam(':urole', $urole);
    $stmt->bindParam(':edit_id', $uid);
    $stmt->execute();

    if ($stmt) {
        // echo "<script>alert('A User Updated Successfully')</script>";
        header('location:admin-dashboard.php');
    } else {
        echo "Error";
    }
}

//delete user

if (isset($_GET['delete_id'])) {
    $did = $_GET['delete_id'];
    $sql = "DELETE FROM users WHERE id=:did";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':did', $did);
    $stmt->execute();
    if ($stmt) {
        header('location:admin-dashboard.php');
    } else {
        echo "Error";
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
                                        <h6 class="text-white">Admin-Dashboard</h6>
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <form action="logout.php" method="get">
                                        <button type="submit" name="logout" class="btn btn-danger btn-sm float-end" onclick="return confirm('Are you sure you want to logout?')">Logout</button>
                                    </form>

                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="card-body">


                        <div class="row">
                            <div class="col-md-4 mt-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h4>Admin Info</h4>
                                        <div>
                                            Role:
                                            <span class="badge bg-success"><?= $auth_result['role'] ?></span>
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
                                    </div>
                                </div>
                                <?php if ($edit_user_status == true) : ?>
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <div class="card-title">User Edit Form</div>
                                        </div>
                                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                                            <div class="card-body">
                                                <input type="hidden" name="edit_id" value="<?= $editUser['id'] ?>" />
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

                                                <div class="mb-2">
                                                    <label for="role">Role</label>
                                                    <select class="form-select" name="edit_role">
                                                        <option value="">Select role</option>
                                                        <option value="admin" <?php if ($editUser['role'] == 'admin') {
                                                                                    echo "selected";
                                                                                } ?>>Admin</option>
                                                        <option value="user" <?php if ($editUser['role'] == 'user') {
                                                                                    echo "selected";
                                                                                } ?>>User</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <button type="subimit" name="update" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="col-md-8">
                                <h5>User Lists</h5>
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>Role</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($results as $user) : ?>
                                            <tr>
                                                <td><?= $user['id'] ?></td>
                                                <td><?= $user['name']; ?></td>
                                                <td><?= $user['email']; ?></td>
                                                <td><?= $user['address'] ?></td>
                                                <td><?= $user['role'] ?></td>
                                                <td>
                                                    <a href="admin-dashboard.php?id=<?= $user['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                                    <a href="admin-dashboard.php?delete_id=<?= $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>

                                    </tbody>
                                </table>
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