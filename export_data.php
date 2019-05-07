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
    mysqli_set_charset($connect,"utf8");
    if ($connect){
        $GLOBALS['connect'] = $connect;
        if (isset($_POST['btn_export_data_csv'])){
            if (!empty($_POST['customer_id'])){
                $foldername = date('YmdHis');
                $config_export_data_csv = $config_table['export_data_csv'];
                if (!file_exists("storage/download/". $foldername)) {
                    mkdir("storage/download/". $foldername, 0777, true);
                }
                $id_list = array();
                $flag_error = false;
                $customer_list = explode(',', $_POST['customer_id']);
                foreach ($customer_list as $customer_id){
                    $customer_id = trim($customer_id);
                    $current_date = date('YmdHis');
                    $fpath = "storage/download/". $foldername . "/" . $customer_id . "_" . $current_date . '_data.csv';
                    $fp = fopen($fpath, 'w+');

                    if (!$export_result = export_data_csv($config_export_data_csv, $id_list, $customer_id)){
                        $result = set_message('customer_id_not_found', 'Customer ID "' . $customer_id . '" not exist');
                        $flag_error = true;
                        break;
                    }else{
                        fclose($fp);
                    }
                }

                if (!$flag_error) {
                    $zip = new ZipArchive();
                    create_zip_file_to_download("storage/download/". $foldername, "storage/download/". $foldername . '.zip');
                    header("Content-type: application/zip");
                    header("Content-Disposition: attachment; filename=" .$foldername . ".zip");
                    header("Pragma: no-cache");
                    header("Expires: 0");
                    readfile("storage/download/". $foldername . ".zip");
                    deleteDir("storage/download/". $foldername);
                    exit;
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
                $accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/s-compressed');
                if(!in_array($_FILES['csv_import']['type'], $accepted_types)){
                    $result = set_message('type error', 'Sai format file');
                }else{
                    $zip = new ZipArchive;
                    $res = $zip->open($filename);
                    if ($res === TRUE) {
                        $current_date = date('YmdHis');
                        $folder_upload = "storage/upload/temp_". $current_date . "/";
                        if (!file_exists($folder_upload)) {
                            mkdir($folder_upload, 0777, true);
                        }
                        $zip->extractTo($folder_upload);
                        $zip->close();

                        if ($file_list = opendir($folder_upload)) {
                            $viewResult = array();
                            while (($file = readdir($file_list)) !== false) {
                                $pathinfo = pathinfo($file);
                                if ($file != "." && $file != ".." && $pathinfo['extension'] == 'csv') {
                                    $filename = $folder_upload . $file;
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
                                            $result = set_message('import_error', "Error while importing data with file: ". $pathinfo['basename'] ."!");
                                            mysqli_query($GLOBALS['connect'], "ROLLBACK");
                                            break;
                                        }
                                        fclose($handle);
                                    }elseif (isset($_POST['btn_view_data_csv'])){
                                        $name = $pathinfo['filename'];
                                        $contract_id = explode("_", $name)[0];
                                        $dataView = array();
                                        foreach ($field_list as $key_field_list => $value_field_list){
                                            $dataView[$key_field_list] = array(
                                                'field'     => $value_field_list,
                                                'data'     => $data[$key_field_list],
                                            );
                                        }
                                        $viewResult[$contract_id] = $dataView;
                                    }

                                }
                            }
                            closedir($file_list);
                        }
                    } else {
                        echo 'failed, code:' . $res;
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

function create_zip_file_to_download($file_path = '', $file_name = 'test'){
    $rootPath  = realpath($file_path);

// Initialize archive object
    $zip = new ZipArchive();
    $zip->open($file_name, ZipArchive::CREATE | ZipArchive::OVERWRITE);

// Create recursive directory iterator
    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file)
    {
        // Skip directories (they would be added automatically)
        if (!$file->isDir())
        {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);

            // Add current file to archive
            $zip->addFile($filePath, $relativePath);
        }
    }

// Zip archive will be created only after closing object
    $zip->close();
}

function deleteDir($dirPath) {
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            self::deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

?>
