<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Document</title>
    <style>
        /* Custom CSS to center the button */
        .center {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>
</head>
<body>
    <div class="container center"> <!-- Wrap the button in a container and apply the "center" class -->
        <form action="{{ route('front.getProducts')}}" method="get">
            <button class="btn btn-primary">Get Products</button>
        </form>
        <form action="{{ route('front.getOrders') }}" method="get">
            <button class="btn btn-primary" type="submit">Get Orders</button>
        </form>
        <form action="{{ route('front.getCustomers') }}" method="get">
            <button class="btn btn-primary" type="submit">Get Customers</button>
        </form>
    </div>
</body>
</html>