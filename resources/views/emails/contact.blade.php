<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact message</title>
</head>
<body>
<h2>New Contact Message</h2>

<p><strong>Name:</strong> {{ $data['name'] }}</p>
<p><strong>Email:</strong> {{ $data['email'] }}</p>

<hr>

<p><strong>Message:</strong></p>
<p>{{ $data['message'] }}</p>
</body>
</html>
