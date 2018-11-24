<?php declare(strict_types=1); ?>
<html>
<head>
    <title>Login</title>
</head>
<body>
<form method="post">
    <div>
        <label for="username">Username</label>
        <input name="username" value="<?= $username ?>" id="username" type="text" />
    </div>
    <div>
        <label for="password">Password</label>
        <input name="password" value="" id="password" type="password" />
    </div>
    <?php if ($error): ?>
    <div>Invalid username or password</div>
    <?php endif; ?>
    <div>
        <button type="submit">Login</button>
    </div>
</form>
</body>
</html>
