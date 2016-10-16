<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <script src="/js/notification.js"></script>
        <script src="{{$notification_server_url}}/socket.io/socket.io.js"></script>
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Laravel 5</div>
                <img src="" id="image">
            </div>
        </div>
    </body>
    <script>
        $(document).ready(function (argument) {
            var notification = new Notification("{{$user_secret}}", "{{$notification_server_url}}");
            notification.login();
            notification.socket.on('notification',function(data){
                $('.title').html(data['text']);
                $('#image').attr('src', data['image']);
            });
        });
    </script>
</html>
