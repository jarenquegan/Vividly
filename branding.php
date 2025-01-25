<?php
include("config.php");

$sql = "SELECT * FROM branding WHERE brand_id = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$brand = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($brand as $brand1) {
    $brand_name = $brand1['brand_name'];
    $brand_logo = $brand1['brand_logo'];

    if (strlen($brand_name) >= 5) {
        $first_part = substr($brand_name, 0, 5);
        $last_part = substr($brand_name, 5);
        $brandname = $first_part . '<span>' . $last_part . '</span>';
    } else {
        $brandname = $brand_name;
    }
}
?>