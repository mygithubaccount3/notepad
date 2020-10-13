<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login -- Notepad</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="../resources/css/normalize.css">

    <style>
        body {
            font-family: 'Nunito';
        }
    </style>
</head>
<body>
    <h1>Please, log in</h1>
    @php
        $message = session('message');
        if(isset($message) && strlen($message) > 0) {
            echo '<p style="color: pink;">' . htmlentities($message)."</p>\n";
        }
    @endphp
    <form method="post" action="/login">
        @csrf
        <label for="username">Username:</label>
        <input type="text" name="username" id="username">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password">
        <input type="submit" name="submit" value="Log in">
    </form>
    Don't have an account? <a href="/signup">Sign up</a>
</body>
</html>
