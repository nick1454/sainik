<!DOCTYPE html>
<html>
<head>
    <title>Student Registration Confirmation</title>
</head>
<body>
    <h1>Student Registration Confirmation</h1>
    
    <p>Hello,</p>

    <p>A student has registered with the following details:</p>

    <ul>
        <li><strong>Name:</strong> {{ $name }}</li>
        <li><strong>Email:</strong> {{ $email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>
    
    <p>Thank you for using our platform!</p>
</body>
</html>