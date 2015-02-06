<?php
require_once 'inc.db.php';
if (isset($_POST['sql'])) {

    $sql = $_POST['sql'];

    $rs = mysql_query($sql) or print_r(mysql_error());

    if (strstr(strtoupper($sql), strtoupper("select"))) {
        while ($row = mysql_fetch_array($rs)) {
            print_r($row);
        }
    }
}
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <textarea type="text" id="sql" name="sql" /><?php echo $_POST['sql']; ?></textarea>
<input type="submit"/>
</form>


