<?php
if (isset($_REQUEST['firstNumber'], $_REQUEST['secondNumber'], $_REQUEST['operation'])) {
    $firstNumber = $_REQUEST['firstNumber'];
    $secondNumber = $_REQUEST['secondNumber'];
    $operation = $_REQUEST['operation'];

    if (!is_numeric($firstNumber)) {
        $error_message[] = "Первый аргумент некорректен! Аргумент должен быть числом.";
    }
    if (!is_numeric($secondNumber)) {
        $error_message[] = "Второй аргумент некорректен! Аргумент должен быть числом.";
    }

    switch ($operation) {
        case 'mult' :
            $result = $firstNumber * $secondNumber;
            break;
        case 'div' :
            if ($secondNumber != 0) {
                $result = $firstNumber / $secondNumber;
            }
            else {
                $error_message[] = "На ноль делить нельзя!";
            }
            break;
        case 'plus' :
            $result = $firstNumber + $secondNumber;
            break;
        case 'minus' :
            $result = $firstNumber - $secondNumber;
            break;
        default :
            $error_message[] = "Данная операция не поддерживается данной версией калькулятора!";
    }
}
?>
<html>
<head>
    <title>Калькулятор</title>
</head>
<body>
<h1>Калькулятор</h1>

<form action="calculator.php" name="calcForm">
    <input type="text" name="firstNumber" value="<?php echo $firstNumber;?>" size="10">
    <select name="operation" size="1">
        <option value="mult" <?php if ($operation == 'mult') echo "selected"; ?>>*</option>
        <option value="div" <?php if ($operation == 'div') echo "selected"; ?>>/</option>
        <option value="plus" <?php if ($operation == 'plus') echo "selected"; ?>>+</option>
        <option value="minus" <?php if ($operation == 'minus') echo "selected"; ?>>-</option>
    </select>
    <input type="text" name="secondNumber" value="<?php echo $secondNumber;?>" size="10">
    <input type="submit" name="resultButton" value="="><?php  echo $result; ?>
</form>
<?php if (isset($error_message)) { ?>
<div style="font-style: oblique; color: #dc143c;">
    <?php
        for ($i = 0; $i < count($error_message); $i++) {
            echo $error_message[$i]." <br>\n";
        }
    ?></div>
    <?php }?>
</body>
</html>
