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
$signals_count = 0;

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
        $signals_count++;
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
<small>Запустите бортовой сканер. Киньте пробку (или не кидайте). Нажмите скан. В окне результатов скана нажмите<br>
Ctrl-A, Ctrl-C... и в окне ниже нажмите: Ctrl-V . Потом нежно нажмите кнопочку "Анализ" и наслаждайтесь. </small>
<form action="<? echo $SCRIPT_NAME; ?>" method="post">
    <textarea cols="80" rows="9" id="example" name="data"><? echo $data; ?></textarea><br>
    <input type="button" value="Clear data!" onclick="this.form.reset()">
    <input type="submit" name="parse_wh" value="Анализ">
</form>
<?php if (!$anomaly_count) exit; ?>

<table border="0">
    <tr>
        <td>Total 100% signals</td>
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
echo 'Total signatures: <span style="color:red">'.$signals_count.'</span><br>';
foreach ($signals as $a_signal => $a_power) {
    $ret.= $a_signal . " [".$a_power."] :  <br>\r\n";
}
print_r($ret);
?>