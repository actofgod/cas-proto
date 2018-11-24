<?php declare(strict_types=1); ?>
<html>
<head>
    <title>Login</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">
        (function () {
            var sendRequest = function (action, callback) {
                var self = this;
                jQuery.ajax({
                    url: 'api.php',
                    method: 'post',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: '{"action":"' + action + '"}',
                    success: function (response) {
                        if (response.success) {
                            callback.call(self, response.data);
                        } else {
                            alert(response.error);
                        }
                    },
                    error: function () {
                        alert('API request error');
                    }
                });
            };
            jQuery(document).ready(function () {
                var magicButton = jQuery('#magic');
                var resultBlock = jQuery('#result');
                magicButton.click(function () {
                    var self = this;
                    self.disabled = true;
                    sendRequest('rotate', function (data) {
                        self.disabled = false;
                        jQuery('#result-window').text(JSON.stringify(data));
                        resultBlock.css({display: 'block'});
                        magicButton.css({display: 'none'});
                    });
                });

                jQuery('#result button').click(function () {
                    var buttons = jQuery('#result-window button');
                    buttons.each(function () {
                        this.disabled = true;
                    });
                    sendRequest(this.dataset.id, function (data) {
                        buttons.each(function () {
                            this.disabled = false;
                        });
                        resultBlock.css({display: 'none'});
                        magicButton.css({display: 'block'});
                    });
                })
            });
        }) ();
    </script>
</head>
<body>
<p><a href="/logout.php">Logout</a></p>

<div>
    <button type="button" id="magic">Magic Button</button>
    <div id="result" style="display:none;">
        <div id="result-window"></div>
        <button type="button" data-id="claim">Claim reward</button>
        <button type="button" data-id="decline">Decline reward</button>
    </div>
</div>
</body>
</html>