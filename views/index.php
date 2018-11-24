<?php declare(strict_types=1); ?>
<html>
<head>
    <title>Login</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#magic').click(function () {
                $.ajax({
                    url: 'api.php',
                    method: 'post',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: '{"action":"rotate"}',
                    success: function (response) {
                        if (response.success) {
                            $('#result').text(JSON.stringify(response.data));
                        } else {
                            alert(response.error);
                        }
                    },
                    error: function () {
                        alert('API request error');
                    }
                })
            });
        });
    </script>
</head>
<body>
<p><a href="/logout.php">Logout</a></p>

<p>
    <button type="button" id="magic">Magic Button</button>
    <div id="result"></div>
</p>
</body>
</html>