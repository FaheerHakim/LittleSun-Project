<?php
if ($_SESSION['user']['type_user'] !== 'employee') {
   
    echo "You do not have permission.";
    exit;
}
?>