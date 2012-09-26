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

function parser($data)
{
    $map = array('id', 'scan group', 'group', 'type', 'power', 'distance');
    $lines = preg_split('/\\r\\n?|\\n/', $data);
    foreach ($lines as $n => $str)
    {
        $sig = explode("\t",$str);
        foreach ($sig as $idx => $v)
        {
            $signal[$map[$idx]] = $v;
        }
        $lines[$n] = $signal;
    }
    return print_r($lines);
}

$example = (isset($_POST['parsedata'])) ? $_POST['parsedata'] : '';
$parsed = (isset($_POST['parsedata']) && isset($_POST['parseit'])) ? parser($example) : '';
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
<input type="submit" name="parseit" value="Parse it! >>> ">
</form>
<hr>
<?php echo $parsed; ?>
<form>

</form>