<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Message</title>
</head>
<body>
    
    <h1>Validation</h1>
    <p>Hi {{ ucfirst($data['name']) }},</p>

    <p>Your password is ***{{ $data['password'] }}***</p>

    <p>Thank you for your email. We will get back to you soon.</p>

</body>
</html>