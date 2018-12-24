<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Export Data CSV</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="asset/main.css" />
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
    <script type="text/javascript" src="asset/main.js"></script>
</head>
<body>
<div class="container" style="margin-top: 1em;">
    <!-- Sign up form -->
    <form action="export_data.php" method="post" enctype="multipart/form-data">
        <!-- Sign up card -->
        <div class="card person-card">
            <div class="card-body">
                <!-- Sex image -->
                <img id="img_sex" class="person-img"
                     src="https://visualpharm.com/assets/217/Life%20Cycle-595b40b75ba036ed117d9ef0.svg">
                <h2 id="who_message">Database Information:</h2>
                <!-- First row (on medium screen) -->
                <div class="row" style="margin: 20px 0;">
                    <div class="custom-control custom-radio col-md-4">
                        <input type="radio" id="customRadio1" name="type_of_db" value="automatic" class="custom-control-input">
                        <label class="custom-control-label" for="customRadio1">Using Default Database</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="customRadio2" name="type_of_db" value="manual" class="custom-control-input" <?php if (isset($type_of_db ) && $type_of_db == 'manual') echo "checked";?>>
                        <label class="custom-control-label" for="customRadio2">Manual Database</label>
                    </div>
                </div>
                <div class="row database_manual" style="display: none">
                    <div class="form-group col-md-3">
                        <input id="hostname" name="hostname" type="text" class="form-control" placeholder="Host: 172.16.100.14" >
                        <div id="hostname_feedback" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group col-md-3">
                        <input id="username" name="username" type="text" class="form-control" placeholder="User: pwater" >
                        <div id="username_feedback" class="invalid-feedback"></div>
                    </div><div class="form-group col-md-3">
                        <input id="password" name="password" type="text" class="form-control" placeholder="Pass: pwater" >
                        <div id="password_feedback" class="invalid-feedback"></div>
                    </div><div class="form-group col-md-3">
                        <input id="database" name="database" type="text" class="form-control" placeholder="Database: pwater" >
                        <div id="database_feedback" class="invalid-feedback"></div>
                    </div>
                    <?php if(isset($result['code']) && $result['code'] == 'server_error'){
                        echo '<div class="feedback message alert alert-danger col-md-12">'. $result['message'] .'</div>';
                    } ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="export_content">
                    <div class="">
                        <h2 class="">Export Data to CSV</h2>
                        <div class="form-group">
                            <label for="customer_id" class="col-form-label">Customer ID:</label>
                            <input type="text"name="customer_id" class="form-control" id="customer_id" placeholder="123456">
                            <div class="feedback customer-id-feedback" >
                                <?php if(isset($result['code']) && $result['code'] == 'customer_id_error'){
                                    echo '<div class="message alert alert-danger">'. $result['message'] .'</div>';
                                }?>
                                <?php if(isset($result['code']) && $result['code'] == 'customer_id_not_found'){
                                    echo '<div class="message alert alert-danger">'. $result['message'] .'</div>';
                                }?>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" name="btn_export_data_csv" class="col-md-12 btn btn-success">Export Data</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="">

                    <div class="import_content">
                        <h2 class="">Import Data file CSV</h2>
                        <div class="form-group">
                            <label for="file" class="col-form-label">File import:</label>
                            <input type="file" name="csv_import" class="form-control" id="file" placeholder="Type your password" >
                            <div class="feedback file-feedback">
                                <?php if (isset($result['code']) && $result['code'] == 'file_error'){
                                    echo '<div class="message alert alert-danger">'. $result['message'] .'</div>';
                                }?>
                                <?php if (isset($result['code']) && $result['code'] == 'import_error'){
                                    echo '<div class="message alert alert-danger">'. $result['message'] .'</div>';
                                }?>
                                <?php if (isset($result['code']) && $result['code'] == 'import_success'){
                                    echo '<div class="message alert alert-success">'. $result['message'] .'</div>';
                                }?>
                            </div>
                        </div>

                        <div class="form-group form-inline col-md-12">
                            <div class="col-md-6 text-center">
                                <button type="submit" name="btn_view_data_csv" class="btn btn-primary col-md-12">View Data</button>
                            </div>
                            <div class="col-md-6 text-center">
                                <button type="submit" name="btn_import_data_csv" class="btn btn-success col-md-12">Import Data</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="text-center font-italic" style="margin-top: 10px; font-weight: 300">
        &copy;Copyright by duc_hung@lampart-vn.com
    </div>
    <?php if (!empty($dataView)){
        foreach ($dataView as $key_dataView => $value_dataView){
            echo ''.
            '<div class="col-md-12 table-responsive view_import_file">'.
                '<h2>Table: <span style="color: #d53030">' . $key_dataView .'</span></h2>'.
                '<table class="table table-hover table-bordered text-center">
                    <thead class="thead-light">
                        <tr>';
            foreach ($value_dataView['field'] as $value_field){
                echo '<th scope="col">'. $value_field .'</th>';
            }
            echo '</tr>
            </thead>';
            echo '<tbody class="table-striped">';
            if (!empty($value_dataView['data'])){
                foreach ($value_dataView['data'] as $value_data){
                    echo '<tr>';
                    foreach ($value_data as $value_data_item){
                        echo '<th scope="col">'. $value_data_item .'</th>';
                    }
                    echo '</tr>';
                }
            }else{
                echo '<td colspan="99" style="text-align: left!important; background: #4e5568; color: white" >No data</td>';
            }
            echo '</tbody>';
            echo '</table>
    </div>';
        }
    }?>
</div>
</body>
</html>