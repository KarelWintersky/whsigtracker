<?php
namespace example;

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


$example = (isset($_POST['parsedata'])) ? $_POST['parsedata'] : '';
$parsed = (isset($_POST['parsedata']) && isset($_POST['parseit'])) ? parser($example) : '';
if (isset($_POST['parse_signals']))
    $parsed = parser_signals($example);
elseif (isset($_POST['parse_mining']))
    $parsed = parser_roids($example);
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
<textarea cols="80" rows="6" resizeable name="parsedata"><? echo $example; ?></textarea><br>
    <input type="submit" name="parse_signals" value="Parse signals >>> ">
    <input type="submit" name="parse_mining" value="Parse Asteroid Data >>> ">
    <input type="reset" value="Clear data!">
</form>
<hr>
<?php echo $parsed; ?>
<form>

</form>