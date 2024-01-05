<!doctype html>
<html lang="en">

<head>
  <title>Title</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="_token" content="{{ csrf_token() }}"/>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/account.css')}}">
    <link rel="stylesheet" href="{{asset('css/module/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/module/footer.css')}}">
    @yield('css')
    <script src="{{asset('js/jquery.min.js')}}"></script>
    
    
</head>

<body style="background:#f5f5f5">
  @include('module.header')
  <main>
    <div style="display: flex">
        <div class="space"></div>
        <div class="container-fluid mt-4 " align="center">
            <div class="row">
                <div class="tab col-lg-3">
                    <div class="dropdown">
                        <div class="account">
                            <span class="material-symbols-outlined" style="color: #2673dd">
                                person
                            </span>
                            <span class="ml-1">Tài khoản của tôi</span>
                        </div>
                        <div class="account-drop">
                            <a href="/account/profile">
                                <p class="mt-1 ml-1 choice {{ request()->is('account/profile') ? 'tab-active' : ''}}">
                                    Hồ sơ
                                </p>
                            </a>
                            <a href="/account/password">
                                <p class="mt-1 ml-1 choice {{request()->is('account/password') ? 'tab-active' : ''}}">
                                    Đổi mật khẩu
                                </p>
                            </a>
                        </div>
                    </div>
                    <div class="account mt-3">
                        <span class="material-symbols-outlined" style="color: #2673dd">
                            receipt
                        </span>
                        <a href="/account/receipt">
                            <p class="ml-1 {{request()->is('account/receipt') ? 'tab-active' : ''}}">Đơn mua</p>
                        </a>
                    </div>
                </div>
                @yield('content')
            </div>
        </div>
        <div class="space"></div>
    <div class='success-screen'>
        <div class='success-overlay'>
        </div>
        <div class='success-box shadow'>
            <span class="material-symbols-outlined" style='color:rgb(8, 211, 8);font-size: 50px'>
                check_circle
            </span>
            <p style='font-size: 18px'>Cập nhật thành công</p>
        </div>
    </div>
  </main>
  @include('module.footer')
  
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>

    <script src="{{asset('js/account.js')}}"></script>
    @yield('script')
</body>

</html>