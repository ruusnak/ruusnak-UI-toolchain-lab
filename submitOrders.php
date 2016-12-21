<?php

$data = file_get_contents('php://input');
$application = getenv("VCAP_APPLICATION");
$application_json = json_decode($application, true);
$applicationName = $application_json["name"];
$ordersAppName = str_replace("ui-", "orders-api-", $applicationName);
$ordersAppName = str_replace("-ui", "-orders-api", $ordersAppName);
$applicationURI = $application_json["application_uris"][0];
$ordersHost = substr_replace($applicationURI, $ordersAppName, 0, strlen($applicationName));
$ordersRoute = "http://" . $ordersHost;
$ordersURL = $ordersRoute . "/rest/orders";
$ordersURL = "http://jouko.ruuskanen-dev-orders-api-toolchain-lab.mybluemix.net/rest/orders";

/*
function debug_to_console( $data ) {

    if ( is_array( $data ) )
        $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

    echo $output;
}
*/

function httpPost($data,$url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_POST, true);  
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	$output = curl_exec ($ch);
	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close ($ch);
	return $code;
}

// debug_to_console("JR ORDERS URL:");
// alert($ordersURL);

echo json_encode(array("httpCode" => httpPost($data,$ordersURL), "ordersURL" => $ordersURL));

?>
