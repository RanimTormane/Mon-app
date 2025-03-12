<!DOCTYPE html>
<html lang="en">
<head>





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
            background-color:rgb(228, 68, 159);
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


<!--display status created , updated, activated ,deleted...-->
@if (session('status'))
    <div >{{session ('status')}}</div>
@endif
{{ $slot }}    
</body>
</html>