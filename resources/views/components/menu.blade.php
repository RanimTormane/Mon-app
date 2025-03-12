<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Side Menu Bar</title>
    
    <style>
         html, body {
            height: 100%;
            margin: 0;
        }
     
        .main-container {
            display: flex;
            min-height: 100vh;
        }

        
        .sidebar {
            width: 250px;
            background-color: #343a53;
            color: white;
            padding: 20px;
        }


        .content {
            flex-grow: 1;
            padding: 20px;
            background-color: #f8f9fa;
        }

      
        .table {
            background-color: white;
        }
    </style>
   <style>
   
    .logo-container {
        display: flex;
        align-items: center;  }

    .logo {
        width: 50px; 
        height: auto;
        margin-right: 1px; }


</style>

</head>
<body>

     <div class="sidebar">
     
            <h4 class="logo-container"><img src="{{ asset('images/Perfometricslogo.png') }}" alt="Logo" class="logo">Perfmetrics</h4>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{route('home')}}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('APIs.index') }}">API List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Logout</a>
                </li>
            </ul>
        </div>

    
</body>
</html>
