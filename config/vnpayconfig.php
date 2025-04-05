<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

$vnp_TmnCode = "1U3WYIGD"; // Mã định danh merchant
$vnp_HashSecret = "WX0ZNXG7UM3UEQ1TOAYJFXYPCS3A0K00"; // Secret key
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = "http://localhost/DoAnPHP/app/views/orders/vnpay_return.php";
$startTime = date("YmdHis");
$expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));

//Config input format
//Expire
$startTime = date("YmdHis");
$expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));
