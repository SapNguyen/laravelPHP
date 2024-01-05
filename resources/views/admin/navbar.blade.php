<aside class="main-sidebar sidebar-dark-danger elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.page') }}" class="brand-link">
      <span class="brand-text font-weight-light">Group 7 - 65PM2<br>Onine Shoes Store</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="/dist/img/Admin.png" class="img-circle elevation-2" alt="Admin Icon">
        </div>
        <div class="info">
          <h5 class="d-block" style="color: white;">Admin</h5>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

            <!-- Home -->
            <li class="nav-item">
                <a href="{{ route('admin.page') }}" class="nav-link {{ request()->is('admin_page') ? 'active' : '' }}">
                    <i class="fa-solid fa-house nav-icon" style="color: #ffffff;"></i>
                    <p>
                        Home
                    </p>
                </a>
            </li>

            <!-- Brand -->
            <li class="nav-item {{ request()->is('admin/brand*') ? 'menu-open' : '' }} ">
                <a href="#" class="nav-link {{ request()->is('admin/brand*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tags nav-icon" style="color: #ffffff;"></i>
                    <p>
                        Brands
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('a.b.list') }}" class="nav-link {{ request()->is('admin/brands') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>List</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('a.b.add.red') }}" class="nav-link {{ request()->is('admin/brand/add') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Add</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('a.b.del.red') }}" class="nav-link {{ request()->is('admin/brand/delete') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Delete</p>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Product -->
            <li class="nav-item {{ request()->is('admin/product*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('admin/product*') ? 'active' : '' }}">
                <i class="fas fa-boxes-stacked nav-icon" style="color: #ffffff;"></i>
                    <p>
                        Products
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('a.p.list') }}" class="nav-link {{ request()->is('admin/products') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>List</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('a.p.add.red') }}" class="nav-link {{ request()->is('admin/product/add') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Add</p>
                    </a>
                </li>
                <li class="nav-item">
                        <a href="{{ route('a.p.del.red') }}" class="nav-link {{ request()->is('admin/product/delete') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Delete</p>
                    </a>
                </li>
                </ul>
            </li>

            <!-- Discount -->
            <li class="nav-item {{ request()->is('admin/discount*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('admin/discount*') ? 'active' : '' }}">
                    <i class="fa-solid fa-percent nav-icon" style="color: #ffffff;"></i>
                    <p>
                        Discounts
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('a.d.list') }}" class="nav-link {{ request()->is('admin/discounts') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>List</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('a.d.add.red') }}" class="nav-link {{ request()->is('admin/discount/add') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Add</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('a.d.del.red') }}" class="nav-link {{ request()->is('admin/discount/delete') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Delete</p>
                    </a>
                </li>
                </ul>
            </li>

            <!-- Receipt -->
            <li class="nav-item {{ request()->is('admin/receipt*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('admin/receipt*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-invoice-dollar nav-icon" style="color: #ffffff;"></i>
                <p>
                    Receipts
                    <i class="right fas fa-angle-left"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('a.r.list.0') }}" class="nav-link {{ request()->is('admin/receipt_unconfimred') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Unconfirmed</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('a.r.list.1') }}" class="nav-link {{ request()->is('admin/receipt') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Confirmed</p>
                    </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('a.r.list.2') }}" class="nav-link {{ request()->is('admin/receipt_canceled') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Canceled</p>
                  </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('a.r.list.3') }}" class="nav-link {{ request()->is('admin/receipt_finished') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Finished</p>
                    </a>
                  </li>
              
                </ul>
            </li>

            <!-- Revenue -->
            <li class="nav-item">
                <a href="{{ route('a.revenue') }}" class="nav-link {{ request()->is('admin/revenue') ? 'active' : '' }}">
                    <i class="fa-sharp fa-solid fa-chart-simple nav-icon" style="color: #ffffff;"></i>
                    <p>
                        Revenue
                    </p>
                </a>
            </li>

            <!-- Account -->
            <li class="nav-item {{ request()->is('admin/account*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('admin/account*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users nav-icon" style="color: #ffffff;"></i>
                    <p>
                        Accounts
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('a.a.list') }}" class="nav-link {{ request()->is('admin/accounts/admin') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Admin</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('a.s.list') }}" class="nav-link {{ request()->is('admin/accounts/staff') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Staff</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('a.m.list') }}" class="nav-link {{ request()->is('admin/accounts/member') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Member</p>
                    </a>
                </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="/" class="nav-link" target="_blank">
                    <i class="fa-globe fa-solid fa-chart-simple nav-icon" style="color: #ffffff;"></i>
                    <p>
                        Web
                    </p>
                </a>
            </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>