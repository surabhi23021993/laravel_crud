<!-- <html>
	<body>
		<h3> The status of fund request"{{$data->fund_request_title}} " on "{{$data->fund_request_date}} " is as follow:</h3>
	</body>
</html> -->
<?php

echo 'Hello Approver,';
echo '<br>';
echo 'The status of fund request "' . $fund_request_title . '" on "' . $fund_request_date . '" of users "
';
$username = '';
foreach ($approver_list as $al) {
    $username .= '<span> ' . $al->name . '</span>,';
}

echo $username . '" is as follow:';
echo '<br><br>';
$particularTable = '<table style="border:1px solid black"><thead><th style="text-align:center;border:1px solid black">Sr No.</th><th style="border:1px solid black">Particular Name</th><th style="text-align:center;border:1px solid black">Amount</th><th style="border:1px solid black">Status</th></thead><tbody>';
foreach ($particular_list as $key => $fn) {
    $particularTable .= '<tr><td style="text-align:center;border:1px solid black">' . ($key + 1) . '</td><td style="border:1px solid black">' . $fn->title . '</td><td style="text-align:center;border:1px solid black">' . $fn->particular_amount . '</td><td style="border:1px solid black">' . $fn->status . '</td></tr>';
}
$particularTable .= '</tbody></table>';
echo $particularTable;

?>