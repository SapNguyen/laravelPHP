<body>
  <header style="background:white">
    <div class="text-center">
        <img src="https://theme.hstatic.net/200000265619/1000946504/14/banner.jpg?v=372" class="img-fluid"
          height="60">
    </div>
      <div class="container">
        <div class="row justify-content-center align-items-center g-2">
          <div class="col">
            <a href="/" class="logo">
              <img src="https://theme.hstatic.net/200000265619/1000946504/14/logo.png?v=372" alt="" class=".img-fluid"
                 height="65">
            </a>
          </div>
          <div class="col">
            <form action="/search" class="input-search">
              <input type="text" class="search-bar" name="search_value" placeholder="Tìm kiếm sản phẩm...">
              <button class="material-symbols-outlined search-icon" style="background: transparent;outline:none;border:none">
                search
              </button>
            </form>
          </div>
          <div class="col" style="display: flex">
              <div id="shopping-cart">
                <a href="/cart" >
                  <div class="inline-block icon-hotline" id="cart">
                    <span class="material-symbols-outlined" style="color:white;">
                      shopping_cart_checkout
                    </span>
                  </div>
                </a>
              </div>
              <div id="account">
                @if (session('login') != 'true')
                  <div class="hotline-bar text-center" style="border: 2px solid white">
                    <div class="inline-block icon-hotline" id="icon-hotline">
                      <span class="material-symbols-outlined" style="color:white;cursor: pointer">
                        person
                      </span>
                    </div>
                  </div>
                  <div class="account-dropdown shadow p-3 mb-5 bg-body-tertiary rounded">
                    <a href="/login" class="btn btn-dark btn-account" >
                      <span>Đăng nhập</span>
                    </a>
                    <a href="/register" class="btn btn-dark btn-account">
                      <span>Đăng ký</span>
                    </a>
                  </div>
                @else
                <div class="hotline-bar text-center">
                  <strong class="account-name" id="name_user"></strong>
                  <div class="inline-block icon-hotline" id="icon-hotline">
                    <span class="material-symbols-outlined" style="color:white;cursor: pointer">
                      person
                    </span>
                  </div>
                </div>
                <div class="account-dropdown shadow p-3 mb-5 bg-body-tertiary rounded">
                  <a href="/account/profile" class="btn btn-dark btn-account" >
                    <span>Tài khoản</span>
                  </a>
                  <a href="/logout" class="btn btn-dark btn-account">
                    <span>Đăng xuất</span>
                  </a>
                </div>
                @endif
              </div>
            </div>
        </div>    
      </div>
      <div class="nav-bar">
        <ul class="nav justify-content-center ">
            <li class="nav-item">
              <a class="nav-link {{ request()->is('/') ? 'active-nav' : '' }}" href="/">
                <strong>HOME PAGE</strong>
              </a>
            </li>
            <li class="nav-item" style="position: relative">
              <div class="link-brand">
                <a class="nav-link {{ request()->is('brand/*') ? 'active-nav' : '' }}">
                  <strong>BRAND</strong>
                  <span class="material-symbols-outlined brand-expand" >
                    expand_more
                  </span>
                </a>
              </div>
              <ul class="brand-dropdown shadow">
                @foreach ($brands as $brand)
                <li class="brand-type ">
                  <a href="/brand/{{$brand->brand_name}}">{{strtoupper($brand->brand_name)}}</a>
                </li>
                @endforeach
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->is('promotion') ? 'active-nav' : '' }}" href="/promotion">
                <strong>SPECIAL PRICE</strong>
              </a>
            </li>
            <li class="nav-item" >
              <a class="nav-link {{ request()->is('account/receipt') ? 'active-nav' : '' }}" href="/account/receipt">
                <strong>ORDER</strong>
              </a>
            </li>
        </ul>
      </div>
  </header>
  <script>
    $(document).ready(function () {
      $.ajax({
        type: "get",
        url: "/loadHeader",
        success: function (response) {
          $('#name_user').html(response.name);
        },
        error: function (response) {
          alert('error');
        }
      });
    });
  </script>
</body>
