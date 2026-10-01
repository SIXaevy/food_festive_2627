<?php
    require "db/db.php";

    function login_account($email, $password) {
        global $conn;
        $user = [];

        $query = "SELECT * FROM `users` WHERE `email` = '".$conn->real_escape_string($email)."'";

        if ($result = $conn->query($query)) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
        }

        if(!empty($row)) {
            $hashed_password = md5(md5($row['id'].$password));
            if ($hashed_password == $row['password']) {
                $user = [
                    'id' => $row['id'],
                    'name' => $row['name']
                ];
            }
        }

        return $user;
    }
?>