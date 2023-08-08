<?php
include 'functions.php';


if (isset($_GET['table'])) {
    $tableName = $_GET['table'];
    include 'privateSettings.php';
    if (connectToDB()) {
        // Get the attributes for the selected table owned by the specific owner
        $owner = $glo_user; // Replace with the actual owner name
        $query = "SELECT column_name FROM all_tab_columns WHERE table_name = '" . strtoupper($tableName) . "' AND owner = '" . strtoupper($owner) . "'";
        $result = executePlainSQL($query);
        $attributes = array();

        while ($row = oci_fetch_array($result, OCI_ASSOC)) {
            $attributes[] = $row['COLUMN_NAME'];
        }

        disconnectFromDB();

        // Return the attributes as JSON
        header('Content-Type: application/json');
        echo json_encode($attributes);
    }
}
?>
