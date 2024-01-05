<!doctype html>
<html lang="en">

<head>
  <title>Title</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
   
    <link rel="stylesheet" href="{{asset('css/module/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/module/footer.css')}}">
    <link rel="stylesheet" href="{{asset('css/register.css')}}">
    <script src="{{asset('js/jquery.min.js')}}"></script>
</head>

<body style="background:#f5f5f5">
  @include('module.header')
  <main align="center" style="margin-top: 30px;margin: 60px 35%;background: white;padding: 20px 30px;border-radius: 10px" class="shadow">
    <p style="font-size: 25px;font-weight: 800">ĐĂNG NHẬP</p>
    <form id="form_login">
      @csrf
        <div class="mb-3 mt-3" align="left">
          <label for="email" class="form-label" >Email:</label>
          <input type="text" class="form-control" id="email" placeholder="Nhập Email" name="email" @required(true)>
        </div>
        <div class="mb-3" align="left">
          <label for="pwd" class="form-label">Password:</label>
          <input type="password" class="form-control" id="pwd" placeholder="Nhập mật khẩu" name="pswd" @required(true)>
        </div>
        <div class="alert alert-danger" style='display:none'></div>
    </form>
        <button id="btn_login" class="btn btn-dark btn_disabled" style="padding: 10px 20px;" disabled>Đăng nhập</button>
    </form>
    <div style="border-top: 1px solid rgb(222, 219, 219); margin-top: 20px">
        <button class="btn" style="padding: 10px 20px; background: #4CAF50; margin-top: 20px">
            <a href="/register" style="color: white">Tạo tài khoản mới</a>
        </button>
    </div>
  </main>
  @include('module.footer')
  
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
  <script src="{{asset('js/login.js')}}"></script>
    
</body>

</html>