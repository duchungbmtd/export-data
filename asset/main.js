$(document).ready(function() {
    var input_name_list = [
        "hostname",
        "username",
        "password",
        "database"
    ];
    $('input[name=type_of_db]').on('change', function () {
        if ($(this).val() == 'manual'){
            addAttrRequired(input_name_list);
            $('.database_manual').show();
        }else{
            removeAttrRequired(input_name_list);
            $('.database_manual').hide();
        }
    });

    if (getCookie('tool_export_customer_id') !== ''){
        var cookie_tool_export_customer_id = getCookie('tool_export_customer_id');
        $('input[name=customer_id]').val(cookie_tool_export_customer_id);
    }

    if ($('input[name=type_of_db]:checked').length == 0){
        $('input[name=type_of_db][value=automatic]').prop('checked', true);
    }
    $('input[name=type_of_db]:checked').trigger('change');

    if ($('input[name=type_of_db]:checked').val() == 'manual'){
        if (getCookie('tool_export_hostname') !== ''){
            var cookie_tool_export_hostname         = getCookie('tool_export_hostname');
            var cookie_tool_export_username         = getCookie('tool_export_username');
            var cookie_tool_export_password         = getCookie('tool_export_password');
            var cookie_tool_export_database         = getCookie('tool_export_database');

            $('input[name=hostname]').val(cookie_tool_export_hostname);
            $('input[name=username]').val(cookie_tool_export_username);
            $('input[name=password]').val(cookie_tool_export_password);
            $('input[name=database]').val(cookie_tool_export_database);
        }
    }

    $('form').on('submit', function () {
        var customer_id         = $('input[name=customer_id]').val();
        setCookie('tool_export_customer_id', customer_id, 1);
        if ($('input[name=type_of_db]:checked').val() == 'manual'){
            var hostname            = $('input[name=hostname]').val();
            var username            = $('input[name=username]').val();
            var password            = $('input[name=password]').val();
            var database            = $('input[name=database]').val();
            setCookie('tool_export_hostname', hostname, 1);
            setCookie('tool_export_username', username, 1);
            setCookie('tool_export_password', password, 1);
            setCookie('tool_export_database', database, 1);
        }else{
            deleteAllCookies([
                "tool_export_hostname",
                "tool_export_username",
                "tool_export_password",
                "tool_export_database"
            ]);
        }
        $('.message').remove();
        $('.view_import_file').remove();
    });


    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires;
    }

    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length,c.length);
            }
        }
        return "";
    }
    function deleteAllCookies(cookies_list) {
        for (var i = 0; i < cookies_list.length; i++) {
            var name = cookies_list[i];
            document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
        }
    }
    function addAttrRequired(input_name_list) {
        for (var i = 0; i< input_name_list.length; i++){
            var input_name = input_name_list[i];
            $('input[name='+ input_name + ']').attr('required', true);
        }

    }
    function removeAttrRequired(input_name_list) {
        for (var i = 0; i< input_name_list.length; i++){
            var input_name = input_name_list[i];
            $('input[name='+ input_name + ']').removeAttr('required');
        }
    }
});
