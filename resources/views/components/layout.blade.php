<!DOCTYPE html>
<html lang="en">
<head>
<!--classeless style sheet-->
<!--<link rel="stylesheet" 
href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">-->
<style>
body {
  background: linear-gradient(135deg,rgb(208, 207, 221),rgb(222, 207, 217),rgb(204, 156, 184));
  color: #ffffff;
}

h1, h2 {
  text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.4);
}

button {
  background:rgb(197, 239, 237);
  border: none;
  padding: 10px 20px;
  border-radius: 20px;
  box-shadow: 0 0 20px rgba(242, 204, 226, 0.8);
}


</style>
<style>
           
        form {
         
            background-color: #fff;
            margin-left:200px;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
           
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color:rgb(8, 0, 0);
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        textarea {
            resize: vertical;
            height: 100px;
        }
        input[type="checkbox"] {
            margin-bottom: 16px;

        }
        button {
            background-color:rgb(212, 154, 187);
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color:rgb(191, 48, 107);
        }
    </style>
    <title>Laravel App</title>
</head>
<body>



@if (session('status'))
    <div >{{session ('status')}}</div>
@endif
{{ $slot }}    
</body>
</html>