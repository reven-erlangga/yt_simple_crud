<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Simple TODO</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
        include "connect.php";

        if (isset($_GET['action'])) {
            $id = $_GET['id'];

            if ($_GET['action'] == 'delete') {
                $sql = "delete from todo where id=$id";
                $hasil = mysqli_query($kon, $sql);

                if ($hasil) {
                    header("Location:index.php");
                } else {
                    echo "<div class='container alert alert-danger'>Data gagal dihapus</div>";
                }
            } else if ($_GET['action'] == 'edit') {
                $sql = "select * from todo where id=$id";
                $hasil = mysqli_query($kon, $sql);
                $data = mysqli_fetch_assoc($hasil);
            }
        } else if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['id'] != null) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            
            $sql = "update todo set name='$name', description='$description' where id=$id";
            $hasil=mysqli_query($kon,$sql);

            if ($hasil) {
                header("Location:index.php");
            } else {
                echo "<div class='container alert alert-danger'>Data gagal diperbaharui</div>";
            }

        } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'];
            $description = $_POST['description'];

            // Insert query
            $sql = "insert into todo (name, description) values ('$name', '$description')";

            $hasil = mysqli_query($kon, $sql);

            if ($hasil) {
                header("Location:index.php");
            } else {
                echo "<div class='container alert alert-danger'>Data gagal dibuat</div>";
            }
        }
    ?>

    <div class="container my-4">
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            <input type="hidden" id="id" name="id" value="<?php echo isset($data) ? $data['id'] : '' ?>">
            <div class="form-group">
                <label for="input-name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo isset($data) ? $data['name'] : '' ?>">
            </div>

            <div class="form-group">
                <label for="input-description">Description</label>
                <textarea name="description" id="description" cols="30" rows="10" class="form-control"><?php echo isset($data) ? $data['description'] : '' ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>

    <br>

    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            
            <?php 
                include "connect.php";
                $sql = "select * from todo";

                $hasil = mysqli_query($kon, $sql);
                $no = 0;

                while ($data = mysqli_fetch_array($hasil)) {
                    $no++;
            ?>

                <tbody>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $data['name']; ?></td>
                        <td><?php echo $data['description']; ?></td>
                        <td>
                            <a href="<?php ($_SERVER['PHP_SELF']); ?>?action=edit&id=<?php echo $data['id'] ?>" class="btn btn-warning" role="button">
                                Edit
                            </a>
                            <a href="<?php ($_SERVER['PHP_SELF']); ?>?action=delete&id=<?php echo $data['id'] ?>" class="btn btn-danger" role="button">
                                Delete
                            </a>
                        </td>
                    </tr>
                </tbody>

            <?php
                }
            ?>
        </table>
    </div>
</body>
</html>