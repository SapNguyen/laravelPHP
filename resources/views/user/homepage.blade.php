{{
  session(['prePage' => '/'])
}}
<!doctype html>
<html lang="en">

<head>
  <title>Admin</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    
    <link rel="stylesheet" href="{{asset('css/module/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/module/footer.css')}}">
    <link rel="stylesheet" href="{{asset('css/module/product-card.css')}}">
    <link rel="stylesheet" href="{{asset('css/user/homepage.css')}}">
    <link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/owl.theme.default.min.css')}}">
    
    <script src="{{asset('js/jquery.min.js')}}"></script>
    {{-- js jquey.min.js dành cho slider beand --}}

    <script src="{{asset('js/owl.carousel.min.js')}}"></script>
    
    
</head>

<body class="container">

  <main align="center" style="margin-top: 30px;background: white;padding: 20px 30px;border-radius: 10px" class="shadow">
    <p style="font-size: 25px;font-weight: 800">Admin</p>
        
    
    <div style="border-top: 1px solid rgb(222, 219, 219); margin-top: 20px">
        <button class="btn" style="padding: 20px 30px; background: #4CAF50; margin-top: 20px">
            <a href="/login" style="color: white">LOGIN</a>
        </button>
    </div>
  </main>
  
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>
  <script src="{{asset('js/user/homepage.js')}}"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
  <script src="{{asset('js/user/homepage.js')}}"></script>


</body>

</html>