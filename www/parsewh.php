<?php
namespace example;
function print_r($var,$flag=TRUE)
{
    $ret = '<pre>';
    $ret.= \print_r($var,TRUE);
    $ret.= '</pre>';
    if ($flag) echo $ret;
    else return $ret;
}

$data = (isset($_POST['data'])) ? $_POST['data'] : '';
$all_signals = preg_split('/\\r\\n?|\\n/', $data); // все сигналы в форме асс.массивов

// ищем самую длинную строку
$longest_string = 0;
$longest_string_length = 0;
foreach ($all_signals as $n => $a_string)
{
    if (($this_str_len = strlen($all_signals[$n])) > $longest_string_length)
    {
        $longest_string_length = $this_str_len;
        $longest_string = $a_string;
    }
}

// анализатор самой длинной строки (выясняем "разметку" полей)
    {
        $test_data_array = explode("\t",$longest_string);
        foreach ($test_data_array as $field_index => $field_data)
        {
            // проверка на 'id' - он всегда выгдядит как XXX-000
            if (preg_match("/[A-Z]{3}-[0-9]{3}/",$field_data)) $map['id'] = $field_index;
            else
            // проверка на 'distance' - она всегда содержит 'AU'
            if (strpos($field_data,"AU")) $map['distance'] = $field_index;
            else
            // проверка на 'group' - всегда содержит cosmic signature или cosmic anomaly
            if (strpos($field_data,"osmic")) $map['group'] = $field_index;
            else
            // проверка на 'power' - сила сигнала - она всегда содержит ",цифрацифра%"
            if (preg_match("/,[0-9]{2}%/",$field_data)) $map['power'] = $field_index;
            else
            // проверка на тип сигнала - 'type' - гравик, магнитка, радарка, неизвестно, ладарка
            if (strpos($field_data,'adar')||strpos($field_data,'ravi')||strpos($field_data,'agn')||strpos($field_data,'nkn')) $map['type'] = $field_index;
            else $map['name'] = $field_index; // I think, it is a signal title!
        }
    }
$anomalies = array(); // массив для 100%-ых сигналов (аномалек), точнее для подсчета их количества.
$anomalies_count = 0;

$signatures = array();
$signatures_count = 0;

foreach ($all_signals as $a_signal)
{
    $sig = explode("\t",$a_signal);
    switch ($sig[ $map['group']  ]){
        case 'Cosmic Anomaly': // is a 100%-by-default signal
        {
            $name = $sig[ $map['name'] ];
            $anomalies[$name]++;
            $anomalies_count++;
        };break;
        case 'Cosmic Signature':// is scannable signal
        {   // необходимо вставить обработку дополнительной информации о сигналах... да, с %, их придется все таки выводить !!!
            $key = $sig[ $map['id'] ];
            $power = $sig[ $map['power'] ];
            $signatures[$key]['text'] = " [ ".$power." ] : ";

            if (strpos($power,'00,00%')) // it is a 100% scanned signal, we can print signal title
            {
                // add data for output message
                $signatures[$key]['text'] .= ' '.$sig[ $map['type'] ].': '.$sig[ $map['name'] ];
                $signatures[$key]['text'] .= ' ('.$sig [ $map['distance'] ].')';
            }
        };break;
    } //case
}
$signatures_count = count($signatures);
ksort($signatures); // sort signatures list by alphabet (key sort)

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Parse test</title>
    <script type="text/javascript">
        function ClearData()
        {
            document.getElementById('example').value='';
            document.getElementById('output').innerHTML='';
        }
    </script>
</head>
<body>
<small>Запустите бортовой сканер. Киньте пробку (или не кидайте). Нажмите скан. В окне результатов скана нажмите<br>
Ctrl-A, Ctrl-C... и в окне ниже нажмите: Ctrl-V . Потом нежно нажмите кнопочку "Анализ" и наслаждайтесь. </small>
<form action="<? echo $SCRIPT_NAME; ?>" method="post">
    <textarea cols="80" rows="9" id="example" name="data"><? echo $data; ?></textarea><br>
    <input type="button" value="Clear data!" onclick="ClearData()">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" name="parse_wh" value="Анализ">
</form>

<div id="output">
<?php if ($anomalies_count) {
echo <<<ANOMALIES
<table border="0">
    <tr>
        <td>Total 100% signals</td>
        <th width="50" style="color:red">$anomalies_count</th>
    </tr>
ANOMALIES;

    foreach ($anomalies as $a_name=>$a_count)
        echo "<tr><td>$a_name</td><th> $a_count </th></tr>";
    echo '</table>';
}

if ($signatures_count)
{
    echo '<hr>';
    echo 'Total signatures: <span style="color:red">'.$signatures_count.'</span><br>';

    foreach ($signatures as $a_signal=>$a_data)
    {
        echo $a_signal . $a_data['text'] ."<br>\r\n";
    }
}
?>
</div>
</body>
</html>