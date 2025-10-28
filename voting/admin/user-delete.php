        <?php

        $phone = $_GET['ph'];
        $file_path = $_GET['file_path'];
        unlink("../" . $file_path);
        $con = mysqli_connect('localhost', 'root', '', 'voting');
        $query = "DELETE FROM register WHERE phone='$phone'";
        $data = mysqli_query($con, $query);

        if ($data) {
            echo "<script> 
                        alert('Voter deleted!')
                        window.location.href = 'voters.php';
                        </script>";
        }
        ?>