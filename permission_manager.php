<?php
if ($_SESSION['user']['type_user'] !== 'manager') {
    // Redirect or display an error message
    echo "You do not have permission.";
    exit;
}
?>