@extends('layouts.layout-new')
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
                <h4>List products here</h4>
            
        </div>
        <div class="row">
        	@if($products && !empty($products))
        	<div class="col-md-12">
        		<table class="table">
        			<tr>
        			<th>#</th>
        			<th>Product</th>
        			<th>Category</th>
        			<th>Price</th>
        			<th>Unit</th>
        			<th>Stock Available</th>
        			<th>Actions</th>
        		</tr>
        		
        			@foreach($products as $key=>$prd)
        			<tr>
        			<td>{{$key + 1}}</td>
        			<td>{{$prd['name']}}</td>
        			<td>
        				@foreach($prd['category'] as $cat)
        					{{$cat['category_name']}},
        				@endforeach
        			</td>
        			<td>{{$prd['price']}}</td>
        			<td>{{$prd['unit']}}</td>
        			<td>{{($prd['stock_status'])?'Yes':'No'}}</td>
        			<td>
        				
        				<a class="btn btn-success js-show" data-id="{{$prd['id']}}" id="js-show-{{$prd['id']}}" href="{{url('view-product/'.$prd['id'])}}">
        					<i class="fa fa-eye"></i>
        				</a>
        				@if(Auth::user()->role_type == 1)
        				<a class="btn btn-info js-edit" data-id="{{$prd['id']}}" id="js-edit-{{$prd['id']}}" href="{{url('edit-product/'.$prd['id'])}}">
        					<i class="fa fa-edit"></i>
        				</a>
        				<a class="btn btn-danger js-delete" data-id="{{$prd['id']}}" id="js-delete-{{$prd['id']}}"><i class="fa fa-trash"></i></a>
        				@endif
        			</td>
        			</tr>
        			@endforeach
        			
        		
        		</table>
        	</div>
        	@else
        	<h6>No products Added yet. </h6>
        	@endif
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">

	$(document).ready(function(){
		
		$('.js-delete').on('click',function(){
			var id = $(this).attr('data-id');

			if(confirm("Are you sure to delete this product???")) { 

				$.ajax({
	                    url: "{{ route('deleteProduct') }}", // Replace with your API endpoint
	                    method: "post",
	                    dataType: "json", // Expect JSON response
	                    data: {id: id},
	                    success: function(data) {
	                        // Update the content of the data-container div with the response data
	                       alert('Product Deleted Successfully');
	                       location.reload();
	                    },
	                    error: function(xhr, status, error) {
	                        // Handle errors
	                        console.error("AJAX Request Error:", status, error);
	                    }
	            });
			}//end confirm

		});
	});//end ready
</script>
@endsection
