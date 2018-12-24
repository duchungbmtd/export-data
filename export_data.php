<?php
/**
 * Created by PhpStorm.
 * User: duc_hung
 * Date: 20-Nov-18
 * Time: 1:36 PM
 */
error_reporting(E_ERROR | E_PARSE);
include ('config/table.php');
include ('config/database.php');
CONST START_TABLE   = "start_table: ";
CONST END_TABLE     = "end_table: ";
$result = array(
    'code' => '',
    'message' => ''
);

$server_info = $config_database['default'];
$type_of_db = '';
if (isset($_POST['type_of_db']) && $_POST['type_of_db'] == 'manual'){
    $type_of_db = 'manual';
    if (isset($_POST['hostname']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['database'])){
        $server_info['hostname'] = $_POST['hostname'];
        $server_info['username'] = $_POST['username'];
        $server_info['password'] = $_POST['password'];
        $server_info['database'] = $_POST['database'];
    }
}
if (!empty($server_info)){
    $connect = mysqli_connect($server_info['hostname'], $server_info['username'], $server_info['password'], $server_info['database']);
    if ($connect){
        $GLOBALS['connect'] = $connect;
        if (isset($_POST['btn_export_data_csv'])){
            if (!empty($_POST['customer_id'])){
                $customer_id = $_POST['customer_id'];
                $current_date = date('YmdHis');
                $fpath = "storage/".$customer_id . "_" . $current_date . '_data.csv';
                $fp = fopen($fpath, 'w+');

                $config_export_data_csv = $config_table['export_data_csv'];
                $id_list = array();
                if (!$export_result = export_data_csv($config_export_data_csv, $id_list, $customer_id)){
                    $result = set_message('customer_id_not_found', 'Customer ID not exist');
                }else{
                    fclose($fp);
                    header('Content-Type: application/csv');
                    header('Content-Disposition: attachment; filename='.$customer_id . "_" . $current_date . '_data.csv');
                    header('Pragma: no-cache');
                    readfile($fpath);
                    exit();
                }
            }else{
                $result = set_message('customer_id_error', 'Input Customer ID please!!!');
            }
        }else if (isset($_POST['btn_import_data_csv']) || isset($_POST['btn_view_data_csv'])){
            if(!empty($_FILES['csv_import']['name'])){
                $table = '';
                $data = array();
                $data_item = array();
                $field_list = array();
                $map_id_list = array();
                $config_export_data_csv = $config_table['export_data_csv'];

                $filename = $_FILES['csv_import']['tmp_name'];
                $handle = fopen($filename, "rb");
                $contents = fread($handle, filesize($filename));
                $array_content = preg_split("/((\r?\n)|(\r\n?))/", $contents);
                foreach($array_content as $line){
                    if (substr($line, 0, 13) == START_TABLE){
                        $table = substr($line, 13);
                        $data[$table] = array();
                        $map_id_list[$table] = array();
                        $field_list[$table] = array();
                    }else if (substr($line, 0, 11) == END_TABLE || $line == ''){
                        continue;
                    }else{
                        $line = substr($line, 1,  -1);
                        $items = explode('","', $line);
                        $count_item = count($items);
                        if (substr($items[0], 0) == 'id'){
                            foreach ($items as $item){
                                array_push($field_list[$table], $item);
                            }
                        }else{
                            for ($i = 0; $i < $count_item; $i++){
                                //convert string to import and show table html
                                if (isset($_POST['btn_import_data_csv'])){
                                    $items[$i] = data_string_to_new_line($items[$i]);
                                }else{
                                    $items[$i] = data_string_to_new_line($items[$i], true);
                                }
                                $data_item += array(
                                    $field_list[$table][$i] => $items[$i]
                                );
                            }
                            array_push($data[$table], $data_item);
                            $map_id_list[$table] += array(
                                $data_item['id'] => ''
                            );
                            $data_item = array();
                        }
                    }
                }
                if (isset($_POST['btn_import_data_csv'])){
                    try{
                        mysqli_query($GLOBALS['connect'], "START TRANSACTION");
                        if ($result =  import_data_csv($config_export_data_csv, $field_list, $data, $map_id_list)){
                            $result = set_message('import_success', "Import file success");
                        }else{
                            throw new Exception("ERROR IMPORT DATABASE");
                        }
                        mysqli_query($GLOBALS['connect'], "COMMIT");
                    }catch (Exception $e){
                        $result = set_message('import_error', "Error while importing data!");
                        mysqli_query($GLOBALS['connect'], "ROLLBACK");
                    }
                    fclose($handle);
                }elseif (isset($_POST['btn_view_data_csv'])){
                    $dataView = array();
                    foreach ($field_list as $key_field_list => $value_field_list){
                        $dataView[$key_field_list] = array(
                            'field'     => $value_field_list,
                            'data'     => $data[$key_field_list],
                        );
                    }
                }
            }else{
                $result = set_message('file_error', 'Cannot find File Import!');
            }
        }
    }else{
        $result = set_message('server_error', 'Cannot connect to server '. $server_info['hostname']);
    }
}


require ('index.php');

function export_data_csv($config, $id_list = array(), $customer_id = ''){
    $check_data = false;
    global $fp;
    foreach ($config as $key => $value){
        $data = '';
        set_header($key);
        $sql = "SELECT ". $key . '.* FROM ' . $key;
        $sql .= " WHERE 1 = 1 ";
        if ($customer_id != ''){
            $sql .= "AND " . $key . ".id = " . $customer_id;
        }else if (!empty($value['column'])){
            $numItem = count($value['column']);
            $i = 0;
            foreach ($value['column'] as $key_column => $value_column){
               if (!empty($id_list[$key_column])){
                   if ($i == 0){
                       $sql .= " AND ";
                   }else if($i != $numItem){
                       $sql .= " OR ";
                   }
                   $sql .= " ". $key . "." . $value_column . " IN (" . implode(", ", $id_list[$key_column]) . ") ";
                   $i++;
               }else{
                   $sql .= "AND true = false";
               }
            }
        }

        if (!empty($value['condition'])){
            foreach ($value['condition'] as $key_condition => $value_condition){
                $sql .= "AND ". $key. "." . $key_condition . " = " . $value_condition . " ";
            }
        }
        $result = mysqli_query($GLOBALS['connect'], $sql);
        if ($result){
            while ($row = mysqli_fetch_assoc($result)){
                foreach ($row as &$item_row){
                    $item_row = data_new_line_to_string($item_row);
                }
                $check_data = true;
                $data .= '"';
                $data .= implode('","', $row);
                $data .= '"';
                $data .= "\r\n";

                if (isset($id_list[$key])){
                    if (!in_array( $row['id'], $id_list[$key])){
                        array_push($id_list[$key], $row['id']);
                    }
                }else{
                    $id_list[$key] = array(
                        $row['id']
                    );
                }
            }
        }

        if ($customer_id != '' && $check_data == false){
            return false;
        }

        if (!empty($data)){
            fwrite($fp, $data);
        }
        set_footer($key);
        if (!empty($value['table_related'])){
            export_data_csv($value['table_related'], $id_list);
        }
    }
    return true;
}

function set_header($table_name){
    global $fp;
    $column_name = get_columns_from_table($table_name, false);
    if (!empty($column_name)){
        $header = "start_table: " . $table_name . "\r\n";
        $header .= "\"";
        $header .= implode("\",\"", $column_name);
        $header .= "\"\r\n";
        fwrite($fp, $header);
    }
}

function set_footer($table_name){
    global $fp;
    $footer = "end_table: " . $table_name . "\r\n";
    $footer .= "\r\n";
    fwrite($fp, $footer);
}

function import_data_csv($config, $field_data = array(), $data = array(), &$map_id_list = array()){
    try{
        foreach ($config as $table => $value_config){
            $foregn_key = array();
            if (!empty($value_config['column'])){
                $foregn_key = $value_config['column'];
            }
            if (!insert_and_map_id($table, $field_data[$table], $data[$table], $map_id_list, $foregn_key)){
                throw new Exception("ERROR WHEN INSERT DATABASE");
            }
            if (!empty($value_config['table_related'])){
                if (!import_data_csv($value_config['table_related'], $field_data, $data, $map_id_list)){
                    throw new Exception("ERROR WHEN IMPORT");
                }
            }
        }
        return true;
    }catch (Exception $exception){
        return false;
    }
}

function insert_and_map_id($table, $field = array(), $data = array(), &$map_id, $foregn_key = array()){
    if (!empty($data)){
        unset($field[0]);
        $column =  '`';
        $column .=  implode("`, `", $field);
        $column .=  '`';
        $insert = 'INSERT INTO ' . $table . ' ('. $column .') ';
        $insert .= 'VALUES ';
        foreach ($data as $item){
            $old_id = $item['id'];
            if (!empty($foregn_key)){
                foreach ($foregn_key as $key_foregn_key => $value_foregn_key){
                    $item[$value_foregn_key] = $map_id[$key_foregn_key][$item[$value_foregn_key]];
                }
            }
            unset($item['id']);
            $value = "'";
            $value .=  implode("', '", $item);
            $value .= "'";
            $sql = $insert .'(' . $value . ')';
            $result = mysqli_query($GLOBALS['connect'], $sql);
            if ($result){
                $last_id = mysqli_insert_id($GLOBALS['connect']);
                $map_id[$table][$old_id] = $last_id;
            }
           else{
                return false;
           }
        }
    }
    return true;
}

function get_columns_from_table($table, $remove_id = true)
{
    $columns = "DESCRIBE $table";
    $result = mysqli_query($GLOBALS['connect'], $columns);
    $col = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $col[] = $row['Field'];
        }
    }
    if ($remove_id) {
        unset($col[0]);
    }
    return $col;
}

function set_message($code, $message){
    $result = array();
    $result['code'] = $code;
    $result['message'] = $message;
    return $result;
}

function data_new_line_to_string($string){
    $string = str_replace(array("\n", "\r"), array("\\n", "\\r"), $string);
    return $string;
}

function data_string_to_new_line($string, $is_html = false){
    if ($is_html){
        $string = str_replace(array("\\n", "\\r"), array("<br>", "<br>"), $string);
    }else{
        $string = str_replace(array("\\n", "\\r"), array("\n", "\r"), $string);
    }
    return $string;
}
?>