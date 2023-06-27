<?php
function getFieldsFromDb(string $sql, mysqli $conn, array $fields = []): array
{
    $array = [];
    if($result = mysqli_query($conn, $sql)) {
        foreach($result as $row){
            $resRow = [];
            if (!empty($fields)) {
                foreach ($fields as $field) {
                    $resRow[$field] = $row[$field];
                }
            } else {
                $resRow = $row;
            }
            $array[] = $resRow;
        }
    }
    return $array;
}