<?php
$firstNumber=$_REQUEST['firstNumber'];
$secondNumber=$_REQUEST['secondNumber'];
$operation=$_REQUEST['operation'];
$result="";

if ($operation == 'mult') {
	$result = $firstNumber * $secondNumber;
}
if ($operation == 'div') {

	if ($secondNumber != 0) {
		$result = $firstNumber / $secondNumber;
	}
	else {
		$result = "На ноль делить нельзя!";
	}
}
if ($operation == 'plus') {
	$result = $firstNumber + $secondNumber;
}
if ($operation == 'minus') {
	$result = $firstNumber - $secondNumber;
}
?>
<html>
<title>
Калькулятор
</title>
<body>
<h1>Калькулятор</h1>
<p>
<form action="calculator.php" name="calcForm">
<input type="text" name="firstNumber" value="<?php echo $firstNumber;?>" size="10">
<select name="operation" size="1">
<option value="mult" <?php if ($operation == 'mult') echo "selected"; ?>>*</option>
<option value="div" <?php if ($operation == 'div') echo "selected"; ?>>/</option>
<option value="plus" <?php if ($operation == 'plus') echo "selected"; ?>>+</option>
<option value="minus" <?php if ($operation == 'minus') echo "selected"; ?>>-</option>
<input type="text" name="secondNumber" value="<?php echo $secondNumber;?>" size="10">
<input type="submit" name="resultButton" value="="><?php  echo $result; ?>
<form>
</p>
</body>
</html>
