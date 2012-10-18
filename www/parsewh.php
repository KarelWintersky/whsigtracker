<?php
namespace example;
/**
 * Created by JetBrains PhpStorm.
 * User: EVE
 * Date: 18.10.12
 * Time: 13:26
 * To change this template use File | Settings | File Templates.
 */

$data = (isset($_POST['data'])) ? $_POST['data'] : '';
$map = array('id', 'scan group', 'group', 'type', 'power', 'distance');
$all_signals = preg_split('/\\r\\n?|\\n/', $data); // все сигналы в форме асс.массивов

$anomaly = array(); // массив для 100%-ых сигналов (аномалек), точнее для подсчета их количества.
$anomaly_count = 0;

$signals = array();

foreach ($all_signals as $a_signal)
{
    $sig = explode("\t",$a_signal);
    switch ($sig[1]){
        case 'Cosmic Anomaly': // is a 100%-by-default signal
        {
            $name = $sig[3];
            $anomaly[$name]++;
            $anomaly_count++;
        };break;
        case 'Cosmic Signature':// is scannable signal
        {
        $key = $sig[0];
        $power = $sig[4];
        $signals[$key] = $power;
        };break;
    } //case
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Parse test</title>
</head>
<body>
<form action="<? echo $SCRIPT_NAME; ?>" method="post">
    <textarea cols="80" rows="9" id="example" name="data"><? echo $data; ?></textarea><br>
    <input type="button" value="Clear data!" onclick="this.form.reset()">
    <input type="submit" name="parse_wh" value="Analyze scan data">
</form>
<?php if (!$anomaly_count) exit; ?>

<table border="0">
    <tr>
        <th>Total 100% signals</th>
        <th width="50" style="color:red"><?php echo $anomaly_count; ?></th>
    </tr>
<?php
    foreach ($anomaly as $a_name=>$a_count)
        echo "<tr><td>$a_name</td><th> $a_count </th></tr>";
?>
</table>
<?php
$ret = '';
echo '<hr>';
foreach ($signals as $a_signal => $a_power) {
    $ret.= $a_signal . " [".$a_power."] :  <br>\r\n";
}
print_r($ret);
?>