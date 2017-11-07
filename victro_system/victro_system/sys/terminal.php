<?php
if (!defined('PROTECT')) {
    exit('NO ACCESS');
}
$victro_logout = "true";
if (!isset($_SESSION['iduser'])) {
    $victro_logout = "false";
}
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8" />
        <title><?php echo SITE_NAME; ?></title>
        <link href="<?php echo SITE_URL; ?>victro_system/victro_system/js/jquery.terminal.min.css" rel="stylesheet"/>
        <script src="<?php echo SITE_URL; ?>victro_system/victro_system/js/jquery-1.7.1.min.js"></script>
        <script src="<?php echo SITE_URL; ?>victro_system/victro_system/js/jquery.mousewheel-min.js"></script>
        <script src="<?php echo SITE_URL; ?>victro_system/victro_system/js/keyboard.js"></script>
        <script src="<?php echo SITE_URL; ?>victro_system/victro_system/js/jquery.terminal-1.9.0.js"></script>

        <script>
            var isAlreadyConnected = <?php echo $victro_logout; ?>;
            jQuery(document).ready(function ($) {
                var victro_terminal = $('body');
                var victro_json = "";
                victro_terminal.terminal(function (command, term) {
                    var victro_array_command = command.split(" ");
                    var victro_method = victro_array_command[0];
                    var victro_params = [];
                    if (victro_array_command.length > 1) {
                        var victro_cont = 1;
                        for (victro_cont = 1; victro_cont < victro_array_command.length; victro_cont++) {
                            victro_params.push(victro_array_command[victro_cont]);
                            console.log(victro_array_command[victro_cont]);
                        }
                    }
                    $.jrpc("<?php echo SITE_URL; ?>sys/command", victro_method, victro_params, function (data) {
                        if (data.error) {
                            if (data.error.error && data.error.error.message) {
                                term.error(data.error.error.message); // php error
                            } else {
                                term.error(data.error.message); // json rpc error
                            }
                        } else {
                            term.echo(data.result);
                        }
                    });
                    if (term.isFrozen) {
                        term.echo("ERRO");
                    }
                    if (command == 'logout') {
                        victro_terminal.terminal().logout();
                        window.location.reload();
                    }
                    if (command == 'design') {
                        window.location = "<?php echo SITE_URL; ?>sys/home";
                    }
                    if (victro_method == 'update_sys') {
                        window.location.reload();
                    }
                    if (command == 'reload') {
                        window.location.reload();
                    }



                }, {
                    //exit: false,
                    onInit: function (terminal) {
                        if (isAlreadyConnected) {
                            terminal.login = true_func;
                        } else {
                            terminal.logout();
                            window.location.reload();
                        }
                    },
                    login: function (user, passwd, finalize) {
                        $.jrpc("<?php echo SITE_URL; ?>sys/command", "login", [user, passwd],
                                function (data) {
                                    if (data.result) {
                                        isAlreadyConnected = true;
                                        finalize(data.result);
                                    } else {
                                        finalize(null);
                                    }
                                }, function (xhr, status) {
                            var msg = '[AJAX] ' + status +
                                    ' server response: \n' + xhr.responseText;
                            if (terminal) {
                                terminal.error(msg);
                            } else {
                                alert(msg);
                            }
                            callback(null);
                        });
                    },
                    greetings: 'VictroBrain',
                    onBlur: function () {
                        return false;
                    }
                });
            });
            function true_func() {
                return true;
            }
            function loginvictro(user, passwd, finalize) {
                $.jrpc("<?php echo SITE_URL; ?>sys/command", "login", [user, passwd],
                        function (data) {
                            if (data.result) {
                                isAlreadyConnected = true;
                                finalize(data.result);
                            } else {
                                finalize(null);
                            }
                        }, function (xhr, status) {
                    var msg = '[AJAX] ' + status +
                            ' server response: \n' + xhr.responseText;
                    if (terminal) {
                        terminal.error(msg);
                    } else {
                        alert(msg);
                    }
                    callback(null);
                });
            }
        </script>
    </head>
    <body>
    </body>
