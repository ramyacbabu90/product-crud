@extends('layouts.layout-new')
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
                <h4>Product Detail View</h4>
                <div class="col-md-3">
                    @if(Auth::user()->role_type == 1)
                <a class="btn btn-info" href="{{url('edit-product/'.$product->id)}}">
                            <i class="fa fa-edit"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="row" style="padding-top: 35px;">
        	<div class="col-md-12">
        		<table class="table">
        			<tr>
                        <th>Product Name:</th>  
                        <td>{{$product->name}} </td>      
                    </tr>
                    <tr>
                        <th>Product Category:</th>  
                        <td>
                            @foreach($product->category as $cat)
                                {{$cat->category_name}},
                            @endforeach
                         </td>      
                    </tr>
                    <tr>
                        <th>Product Unit:</th>  
                        <td>{{$product->unit}} </td>      
                    
                        <th>Product Price:</th>  
                        <td>{{$product->price}} </td>      
                    </tr>
                    <tr>
                        <th>Discount Rate(%):</th>  
                        <td>{{$product->discount_rate}} </td>      
                    
                        <th>Discount Amount:</th>  
                        <td>{{$product->discount_amount}} </td>      
                    </tr>
                    <tr>
                        <th>Discount Starts From:</th>  
                        <td>{{$product->discount_from_date}} </td>      
                   
                        <th>Discount Ends on:</th>  
                        <td>{{$product->discount_to_date}} </td>      
                    </tr>
                    <tr>
                        <th>Tax Rate(%):</th>  
                        <td>{{$product->tax_rate}} </td>      
                    
                        <th>Tax Amount:</th>  
                        <td>{{$product->tax_amount}} </td>      
                    </tr>
                    <tr>
                        <th>Stock Status:</th>  
                        <td>{{ ($product->stock_status ==1 )?'Yes':'No'; }} </td>      
                    
                        <th>Stock Item:</th>  
                        <td>{{$product->stock_quantity}} </td>      
                    </tr>
        		
                <tr>
                    
                    
                </tr>
        		</table>
                <div class="row" >
                    <h4>Product Images</h4>
                    @foreach($product->images as $image)
                    <div class="col-md-3" style="padding: 10px;">
                        <a href="{{ asset('/uploads/'.$image->image) }}" target="_blank">
                            <div class="card" style="width: 14rem;">
                              <img class="card-img-top" src="{{ asset('/uploads/'.$image->image) }}" alt="Card image cap">
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
        	</div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">

	$(document).ready(function(){
		$('.js-edit').on('click',function(){
			var id = $(this).attr('data-id');
			console.log(id);
		});
		$('.js-delete').on('click',function(){
			var id = $(this).attr('data-id');
			
		});
	});//end ready
</script>
@endsection
