<?php
namespace example;

class SimpleParser
{
    private $lines = array();

    private function __constructor($data)
    {
        $this->lines = preg_split('/\\r\\n?|\\n/', $data);
    }

    public static function text2lines($text)
    {
        return preg_split('/\\r\\n?|\\n/', $text);
    }

    public static function untab($str)
    {
        return explode("\t",$str);
    }

    public function simple()
    {


    }
}

function getRandomNumber()
{
    return 4;// chosen by fair dice roll.
}            // guaranteed to be random.

function print_r($var,$flag=FALSE)
{
    $ret = '<pre>';
    $ret.= \print_r($var,TRUE);
    $ret.= '</pre>';
    if ($flag) echo $ret;
    else return $ret;
}

function parser_simple($data)
{
    $lines = SimpleParser::text2lines($data);
    $ret = array();
    foreach ($lines as $n => $str)
    {
        $a_line = SimpleParser::untab($str);
        $ret[$n] = $a_line;
    }
    return print_r($ret);
}

function parser_signals($data)
{
    $map = array('id', 'scan group', 'group', 'type', 'power', 'distance');
    $lines = preg_split('/\\r\\n?|\\n/', $data);
    foreach ($lines as $n => $str)
    {
        $sig = explode("\t",$str); // delimeter is horisontal tab!
        foreach ($sig as $idx => $v)
        {
            $signal[$map[$idx]] = $v;
        }
        $lines[$n] = $signal;
    }
    return print_r($lines);
}
function parser_roids($data)
{
    $lines = preg_split('/\\r\\n?|\\n/', $data); // массив строк
    $roids = array();
    foreach($lines as $roid_str)
    {
        $roid_data = explode("\t",$roid_str);
        $key = $roid_data[0];
        $count = str_replace(',','',$roid_data[1]); // 1 - counnt
        $roids[$key] += (int)$count; // count
        // remove "," from number - http://www.cyberforum.ru/php-beginners/thread435327.html
    }
    return print_r($roids);
}

function parser_jitonomic_space_remover($data)
{
    $lines = preg_split('/\\r\\n?|\\n/', $data); // массив строк
    $minerals = array();
    foreach($lines as $mineral_str)
    {
        $mineral_data = explode("\t",$mineral_str);
        $key = $mineral_data[0];
        $count = str_replace(' ','',$mineral_data[1]); // 1 - counnt
        $minerals[$key] += (int)$count; // count
        // remove "," from number - http://www.cyberforum.ru/php-beginners/thread435327.html
    }
    return print_r($minerals);
}

function parser_prepare_signals($data)
{
    $lines = preg_split('/\\r\\n?|\\n/', $data); // массив строк
    $ret = '';
    foreach($lines as $a_signal)
    {
        $the_signal = explode("\t",$a_signal);
        $ret .= $the_signal[0]."<br>\r\n";
    }
    return $ret;
}

function parser_prepare_signals_advanced($data)
{
    $lines = preg_split('/\\r\\n?|\\n/', $data); // массив строк
    $signals = array();
    $ret = '';
    foreach($lines as $a_signal)
    {
        $the_signal = explode("\t",$a_signal);
        $key = $the_signal[0];
        $power = $the_signal[4];
        $signals[$key] = $power;

    }
    foreach ($signals as $a_signal => $a_power) {
        $ret.= $a_signal . " [".$a_power."] :  <br>\r\n";
    }
    return $ret;
}



$example = (isset($_POST['parsedata'])) ? $_POST['parsedata'] : '';
if (isset($_POST['parse_signals']))
    $parsed = parser_signals($example);
elseif (isset($_POST['parse_mining']))
    $parsed = parser_roids($example);
elseif (isset($_POST['parse_jitomineral']))
    $parsed = parser_jitonomic_space_remover($example);
elseif (isset($_POST['parse_prep_signals']))
    $parsed = parser_prepare_signals($example);
elseif (isset($_POST['parse_prep_signals_adv']))
    $parsed = parser_prepare_signals_advanced($example);
elseif (isset($_POST['parse_simple']))
    $parsed = parser_simple($example);
else $parsed = '';


?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Parse test</title>
</head>
<body>
<form action="<? echo $SCRIPT_NAME; ?>" method="post">
<textarea cols="80" rows="6" id="example" name="parsedata"><? echo $example; ?></textarea><br>
    <input type="button" value="Clear data!" onclick="this.form.reset()">
    <fieldset>
        <legend>Подсчеты</legend>
        <input type="submit" name="parse_mining" value="Gravimetric Survey Scan >>> ">
        <input type="submit" name="parse_prep_signals_adv" value="Prepare signals with %>>">
    </fieldset>
    <fieldset>
        <legend>Редкоиспользуемые парсеры</legend>
        <input type="submit" name="parse_signals" value="Printr signals >>> ">
        <input type="submit" name="parse_jitomineral" value="Jitonomic export space remove >>> ">
        <input type="submit" name="parse_prep_signals" value="Prepare signals >>">
        <input type="submit" name="parse_simple" value="Simple print_r() >>">
    </fieldset>
</form>
<hr>
<?php echo $parsed; ?>
