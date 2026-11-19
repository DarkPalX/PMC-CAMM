<?php

$conn_a['edms']['type'] = 'sqlsrv';
$conn_a['edms']['host'] = 'HOF4B7E2A32209L\INHOUSE';
$conn_a['edms']['name'] = 'mssqlws1ph2_dims';
$conn_a['edms']['uname'] = 'sa';
$conn_a['edms']['pword'] = '@Temp123!';

// $conn_b['ims']['type'] = 'sqlsrv';
// $conn_b['ims']['host'] = 'LAPTOP-7B29UNBF\SQLEXPRESS';
// $conn_b['ims']['name'] = 'ims_v3_db';
// $conn_b['ims']['uname'] = 'sa';
// $conn_b['ims']['pword'] = 'sa';

// $conn_a['edms']['type'] = 'sqlsrv';
// $conn_a['edms']['host'] = '192.155.86.62';
// $conn_a['edms']['name'] = 'mssqlws1ph2_dims';
// $conn_a['edms']['uname'] = 'mssqlws1ph2_dims';
// $conn_a['edms']['pword'] = '@Temp123!';

$conn_b['ims']['type'] = 'sqlsrv';
$conn_b['ims']['host'] = 'HOF4B7E2A32209L\INHOUSE';
$conn_b['ims']['name'] = 'mssqlws1ph2_imsv3_p1';
$conn_b['ims']['uname'] = 'sa';
$conn_b['ims']['pword'] = '@Temp123!';


$array_emp = array();

$pdoempdata = new PDO($conn_a['edms']['type'].":server=".$conn_a['edms']['host'].";Database=".$conn_a['edms']['name'], $conn_a['edms']['uname'], $conn_a['edms']['pword']);
$pdoempdata->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$empdatasql = $pdoempdata->prepare("SELECT id,[name] AS roleName,[description] AS roleDescription, 'EDMS' AS appName  FROM roles WHERE active = 1");

$empdatasql->execute();
$empdatasql->setFetchMode(PDO::FETCH_ASSOC);

for($i=0; $rowempdata = $empdatasql->fetch(); $i++){   
	$array_emp[] .= str_replace(':',' ',$rowempdata['id']).':'.$rowempdata['roleName'].':'.$rowempdata['roleDescription'] .':'.$rowempdata['appName'];
}


$pdoempdata = new PDO($conn_b['ims']['type'].":server=".$conn_b['ims']['host'].";Database=".$conn_b['ims']['name'], $conn_b['ims']['uname'], $conn_b['ims']['pword']);
$pdoempdata->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$empdatasql = $pdoempdata->prepare("SELECT id,[name] AS roleName,[description] AS roleDescription,  'IMS' AS appName FROM roles WHERE active = 1");

$empdatasql->execute();
$empdatasql->setFetchMode(PDO::FETCH_ASSOC);

for($i=0; $rowempdata = $empdatasql->fetch(); $i++){     
	$array_emp[] .= str_replace(':',' ',$rowempdata['id']).':'.$rowempdata['roleName'].':'.$rowempdata['roleDescription'] .':'.$rowempdata['appName'];
}

	echo json_encode($array_emp);
?>

