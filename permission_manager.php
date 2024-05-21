<?php
if ($_SESSION['user']['type_user'] !== 'manager') {
  
    echo "You do not have permission.";
    exit;
}
?>