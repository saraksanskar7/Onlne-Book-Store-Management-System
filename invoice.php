<?php
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

include 'config.php';

$dompdf = new Dompdf();

$fetch_order = [];
$fetch_details = [];

if (isset($_GET['order_id'])) {

    $order_id = $_GET['order_id'];

    // Fetch order confirmation details
    $order_query = mysqli_query(
        $conn,
        "SELECT * FROM confirm_order WHERE order_id = '$order_id'"
    ) or die('Order query failed');

    if (mysqli_num_rows($order_query) > 0) {
        $fetch_order = mysqli_fetch_assoc($order_query);
    }

    // Fetch order items & address details
    $details_query = mysqli_query(
        $conn,
        "SELECT * FROM orders WHERE id = '$order_id'"
    ) or die('Details query failed');

    if (mysqli_num_rows($details_query) > 0) {
        $fetch_details = mysqli_fetch_assoc($details_query);
    }
}

$html = '
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Invoice</title>
<style>
body{font-family:Arial, Helvetica, sans-serif;font-size:14px}
.invoice{text-align:center}
.invoice-title{font-weight:bold;font-size:18px;margin:10px 0}
.logo span{font-size:30px;font-weight:bold}
.logo .me{color:black;font-weight:500}
.section-mid{width:100%}
table{width:100%;border-collapse:collapse}
th,td{padding:6px;text-align:center}
hr{margin:15px 0}
<style>
body{
    font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
    font-size:13px;
    color:#222;
    background:#fff;
}

.invoice{
    width:100%;
    padding:15px 30px;
    box-sizing:border-box;
}

/* ================= HEADER ================= */
.logo{
    margin-bottom:4px;
}
.logo span{
    font-size:34px;
    font-weight:800;
    letter-spacing:1px;
}
.logo .me{
    color:#000;
}

.invoice-title{
    font-size:19px;
    font-weight:600;
    letter-spacing:0.5px;
    margin-top:4px;
    margin-bottom:14px;
}

/* ================= DIVIDER ================= */
hr{
    border:none;
    height:2px;
    background:linear-gradient(to right,#333,#999,#333);
    margin:18px 0;
}

/* ================= TOP INFO TABLE ================= */
table{
    width:100%;
    border-collapse:collapse;
}

table h3{
    font-size:14px;
    font-weight:700;
    margin-bottom:8px;
    border-left:4px solid brown;
    padding-left:6px;
}

table p{
    margin:3px 0;
    line-height:1.45;
}

/* Make address blocks look like cards */
table tr th{
    vertical-align:top;
    padding:10px;
}

/* ================= ITEMS TABLE ================= */
thead th{
    background:#f4f4f4;
    border-top:2px solid #333;
    border-bottom:2px solid #333;
    padding:9px;
    font-size:13px;
    text-transform:uppercase;
    letter-spacing:0.5px;
}

tbody td{
    border-bottom:1px dashed #aaa;
    padding:8px;
    font-size:13px;
}

/* Zebra rows */
tbody tr:nth-child(even){
    background:#fafafa;
}

/* ================= NET TOTAL ================= */
tbody tr:last-child td{
    border-top:2px solid #333;
    border-bottom:2px solid #333;
    font-weight:700;
    font-size:15px;
    background:#f9f9f9;
}

/* ================= FOOTER ================= */
.invoice > p{
    margin-top:14px;
    font-size:15px;
    font-weight:700;
    letter-spacing:0.5px;
}

/* ================= WATERMARK STYLE (OPTIONAL) ================= */
/*
.invoice::after{
    content:"BOOKFLIX & CHILL";
    position:fixed;
    top:45%;
    left:15%;
    font-size:60px;
    color:rgba(0,0,0,0.05);
    transform:rotate(-30deg);
}
*/
</style>
</style>
</head>

<body>
<div class="invoice">

<div class="logo">
<span style="color:brown">Bookflix &</span>
<span class="me">Chill</span>
</div>

<div class="invoice-title">Invoice Details</div>
<hr>

<table>
<tr>

<th align="left">
<h3>SHIPPING ADDRESS:</h3>
<p>To, '.$fetch_order['name'].'</p>
<p>'.$fetch_details['address'].'</p>
<p>'.$fetch_details['city'].'</p>
<p>'.$fetch_details['state'].'</p>
<p>'.$fetch_details['country'].'</p>
<p>'.$fetch_details['pincode'].'</p>
</th>

<th align="left">
<h3>SOLD BY:</h3>
<p>Bookflix & Chill</p>
<p>Chandapuri Tal.Malshiras Dist.Solapur 413310</p>
<p>Maharashtra, India</p>
<p>Phone: +91 7447755732</p>
<p>Email: support@bookflixchill.com</p>
</th>

<th align="left">
<h3>DETAILS:</h3>
<p>Invoice Date: '.$fetch_order['date'].'</p>
<p>Order ID: '.$fetch_order['order_id'].'</p>
<p>Order Date: '.$fetch_order['order_date'].'</p>
<p>From: Read Me</p>
<p>Payment Method: '.$fetch_order['payment_method'].'</p>
</th>

</tr>
</table>

<hr>

<table>
<thead>
<tr>
<th>S.No.</th>
<th>BOOK NAME</th>
<th>QTY</th>
<th>UNIT PRICE</th>
<th>TOTAL</th>
</tr>
</thead>
<tbody>';

$items_query = mysqli_query(
    $conn,
    "SELECT * FROM orders WHERE id = '$order_id'"
) or die('Items query failed');

$s = 1;
if (mysqli_num_rows($items_query) > 0) {
    while ($item = mysqli_fetch_assoc($items_query)) {
        $html .= '
        <tr>
            <td>'.$s.'</td>
            <td>'.$item['book'].'</td>
            <td>'.$item['quantity'].'</td>
            <td>'.$item['unit_price'].'</td>
            <td>'.$item['sub_total'].'</td>
        </tr>';
        $s++;
    }
}

$html .= '
<tr>
<td></td>
<td colspan="2"><b>NET TOTAL</b></td>
<td colspan="2"><b>'.$fetch_order['total_price'].'</b></td>
</tr>

</tbody>
</table>

<hr>
<p><b>Bookflix & Chill</b></p>

</div>
</body>
</html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('invoice', ['Attachment' => 0]);
?>