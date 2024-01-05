<div class="owl-item">
    <div class="item" s>
      <div class="card">
        <div class="card-header" style="margin-top: 1px">
          @for ($i = 0; $i < count($product_imgs); $i++)
            @if ($product_imgs[$i]->product_id == $product->product_id)
              <img src="/img/product/{{$product->product_id}}/{{explode(',',$product_imgs[$i]->product_image)[0]}}" class="img-fluid prod-img">
              @break
            @endif
          @endfor
          <div class="slide-img container-fluid">
            @for ($i = 0; $i < count($product_imgs); $i++)
              @if ($product_imgs[$i]->product_id == $product->product_id)
                @foreach (explode(',',$product_imgs[$i]->product_image) as $image)
                  <img src="/img/product/{{$product->product_id}}/{{$image}}" class="small-img img-fluid rounded">
                @endforeach
              @endif
            @endfor
          </div>
        </div>
        <div class="card-body">
          <a href="/products/{{$product->product_id}}">
            <p class="prod-name">{{$product->product_name}}</p>
          </a>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="col-sm-6 div_price">
              @if ($product->discount_value)
                <h5 class="prod-price">{{number_format($product->product_price * (1-$product->discount_value/100))}}₫</h5>
                <p class="prod-price-disabled">{{number_format($product->product_price)}}₫</p>
              @else
                <h5 class="prod-price">{{number_format($product->product_price)}}₫</h5>
              @endif
            </div>
            <div class="col-sm-6">
              <a href="/products/{{$product->product_id}}">
                <button class="btn btn-dark" style="padding: 8px 15px">
                  Chi tiết
                </button>
              </a>
            </div>
          </div>
        </div>
        @if ($product->discount_value)
          <div class="promotion-tag">
            <p style="color: yellow">{{$product->discount_value}}%</p> 
            GIẢM
          </div>
        @endif
      </div>
    </div>
  </div>