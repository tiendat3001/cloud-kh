<?php
// Thông tin kết nối MySQL
$hostname = 'localhost'; // Địa chỉ máy chủ MySQL (mặc định là localhost)
$username = 'root'; // Tên người dùng MySQL
$password = ''; // Mật khẩu MySQL
$database = 'atn-store'; // Tên cơ sở dữ liệu

// Kết nối đến MySQL
$conn = mysqli_connect($hostname, $username, $password, $database);

// Kiểm tra kết nối
if (!$conn) {
    die('Lỗi kết nối đến MySQL: ' . mysqli_connect_error());
}

//echo 'Kết nối thành công đến MySQL!';