@extends('layouts.layout-new')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
	.row{
		padding-top: 25px; 
	}
	.error-msg {
		color: red;
	}
</style>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <h4>Add products here</h4>
            </div>
            @if (Session::has('message'))
   				<div class="alert alert-warning">{{ Session::get('message') }}</div>
			@endif
        </div>
        <div class="col-md-12" style="padding-top: 15px;">
        <form role="form" class="form" id="js-add-product" method="post" action="{{url('save-product')}}" enctype="multipart/form-data">
			<input type="hidden" id="js-token" name="_token" value="<?php echo csrf_token(); ?>">
			@if(isset($id) && $id)
			<input type="hidden" name="id" value="{{$id}}">
			@endif
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="js-name">Product Name</label>
						<input type="text" name="name" id="js-name" class="form-control" @if(isset($product['name'])) value="{{$product['name']}}" @endif required="">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="js-price">Product Price</label>
						<input type="text" name="price" id="js-price" class="form-control allow_decimal" @if(isset($product['price'])) value="{{$product['price']}}" @endif required="">
					</div>
				</div>
				
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="js-category">Category</label>
						<select name="category[]" id="js-category" class="form-control" multiple="" required="">
							
							@foreach($category as $cat)
								<option value="{{$cat->id}}" @if(isset($selected_cat) && in_array($cat->id,$selected_cat))selected @endif>
									{{$cat->category_name}}
								</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="js-unit">Unit Type</label>
						<select name="unit" id="js-unit" class="form-control" required="">
							<option value="">Select Unit type</option>
							<option value="Quantity" @if(isset($product['unit'])&& $product['unit'] =='Quantity') selected="" @endif>Quantity</option>
							<option value="Liter" @if(isset($product['unit'])&& $product['unit'] =='Liter') selected="" @endif>Liter</option>
							<option value="Kilogram" @if(isset($product['unit'])&& $product['unit'] =='Kilogram') selected="" @endif>Kilogram</option>
							<option value="Meter" @if(isset($product['unit'])&& $product['unit'] =='Meter') selected="" @endif>Meter</option>
						</select>
					</div>
				</div>

			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="js-discount_rate">Discount Rate (%)</label>
						<input type="text" name="discount_rate" id="js-discount_rate" class="form-control allow_decimal"  @if(isset($product['discount_rate'])) value="{{$product['discount_rate']}}" @endif required="">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="js-tax_rate">Tax Rate (%)</label>
						<input type="text" name="tax_rate" id="js-tax_rate" class="form-control allow_decimal"  @if(isset($product['tax_rate'])) value="{{$product['tax_rate']}}" @endif required="">
					</div>
				</div>
				
				
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="js-discount_from_date">Discount Start Date</label>
						<input type="date" name="discount_from_date" id="js-discount_from_date" class="form-control"  @if(isset($product['discount_from_date'])) value="{{$product['discount_from_date']}}" @endif>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="js-discount_to_date">Discount End Date</label>
						<input type="date" name="discount_to_date" id="js-discount_to_date" class="form-control"  @if(isset($product['discount_to_date'])) value="{{$product['discount_to_date']}}" @endif>
					</div>
				</div>
				
			</div>
			<div class="row">
				
				<!-- <div class="col-md-6">
					<div class="form-group">
						<label for="js-tax_amount">Tax Amount</label>
						<input type="text" name="tax_amount" id="js-tax_amount" class="form-control allow_decimal" required="">
					</div>
				</div> -->
				<!-- <div class="col-md-6">
					<div class="form-group">
						<label for="js-discount_amount">Discount Amount</label>
						<input type="text" name="discount_amount" id="js-discount_amount" class="form-control allow_decimal" required="">
					</div>
				</div> -->
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="js-stock_status">Stock Available?</label>
						<select name="stock_status" id="js-stock_status" class="form-control" required="">
							<option value="1" @if(isset($product['stock_status']) && $product['stock_status'] ==1)  selected="" @endif>Yes</option>
							<option value="0" @if(isset($product['stock_status']) && $product['stock_status'] ==0)  selected="" @endif>No</option>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="js-stock_quantity">Stock Quantity</label>
						<input type="text" name="stock_quantity" id="js-stock_quantity" class="form-control allow_decimal"  @if(isset($product['stock_quantity'])) value="{{$product['stock_quantity']}}" @endif required="">
					</div>
				</div>
				
			</div>
			
			<div id="image_div">
				@if(isset($id) && isset($product['images']) && !empty($product['images']))
                    <div class="row js-each">
                    	@foreach($product['images'] as $image)
                    	<div class="col-md-3 image-card" id="image-card-{{$image['id']}}" style="padding-top: 10px;">
                        <a href="{{ asset('/uploads/'.$image['image']) }}" target="_blank">
                            <div class="card" style="width: 10rem;">
                              <img class="card-img-top" src="{{ asset('/uploads/'.$image['image']) }}" alt="Card image cap">
                            </div>
                            <div class="card" style="width: 10rem;">
                            	<button class="btn btn-danger js-dlt-img" data-id="{{$image['id']}}"><i class="fa fa-trash"></i></button>
                            </div>
                        </a>
                    	</div>
                    	@endforeach
                    </div>
                @else
                <div class="row js-each">
					<div class="form-group">
						<label for="js-stock_quantity">Product image 1</label>
	                 <input id="js-image-1" type="file" accept=".jpg,.gif,.png" name="image[]" required="">
	               </div>
	        	</div>
	        	<div class="row js-each">
					<div class="form-group">
						<label for="js-stock_quantity">Product image 2</label>
	                     <input id="js-image-1" type="file" accept=".jpg,.gif,.png" name="image[]" required="">
	                 </div>
	        	</div>
	        	<div class="row js-each">
					<div class="form-group">
						<label for="js-stock_quantity">Product image 3</label>
	                     <input id="js-image-1" type="file" accept=".jpg,.gif,.png" name="image[]" required="">
	                     
	                 </div>
	        	</div>
				@endif

				
        	</div> 
        	<br>
        	<br>
	        	<button class="btn btn-success" id="add_more_image">Add more image<i class="fa fa-plus"></i></button>

	        	<hr>
			<button class="btn btn-primary" style="margin-top: 30px;" value="Submit" id="js-save-element">Save </button>

	</form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){

    var basePath = $('#base_path').val();

    $('#js-category').select2({multiple: true, placeholder: 'Select category'});

    $("#js-add-product").validate({
        rules: {
           name: {
              required: true,
              minlength: 2,
              maxlength:150,
          },
          price: {
              required:true,
          },
          category: {
              required:true,
          },
          unit: {
              required:true,
          },discount_rate: {
              required:true,
          // },discount_amount: {
          //     required:true,
          },discount_from_date: {
              required:function(element){
            	return $("#js-discount_rate").val() > 0;
        	}
             
          },discount_to_date: {
              required:function(element){
            	return $("#js-discount_rate").val() > 0;
        	}
          },tax_rate: {
              required:true,
          // },tax_amount: {
          //     required:true,
          },stock_quantity: {
              required:true,
          },
          

      },
      messages: {
       name: "Product Name Required",
       price:"Product Price Required",
       category:"Product Category Required",
       unit:"Product Unit Required",
   },
   errorElement:"span",
   errorClass: "error-msg",
   errorPlacement:function( error, element ){
      error.insertAfter(element.parent());
  },
});

$(".allow_decimal").on("input", function(evt) {
   var self = $(this);
   self.val(self.val().replace(/[^0-9.]/g, ''));
   if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
   {
     evt.preventDefault();
   }
 });


	//add more image upload
	$('#add_more_image').on('click', function(e){
		e.preventDefault();
		var count = $("#image_div").children().length;
		var html = '<div class="row js-each"><div class="form-group"><label for="js-stock_quantity">Product image '+ (count+1) +'</label>';
		html +='<input id="js-image-'+(count+1)+'"  type="file" accept=".jpg,.gif,.png" name="image[]" required="">';
		html +='<button class="btn btn-danger js-remove" ><i class="fa fa-trash"></i></button></div></div>';	
		$('#image_div').append(html);		
	})

	//remove image
	$(document).on("click", ".js-remove", function(e) {
		e.preventDefault();
		$(this).closest(".js-each").remove();
	});
	$(document).on("click", ".js-dlt-img", function(e) {
		e.preventDefault();
		var id = $(this).attr('data-id');
		if(confirm("Are you sure to delete this Image???")){	
			$.ajax({
			type: 'post',
			url: '{{ route("deleteProductImage") }}',
			dataType: "json",
			data: {id: id},
			success: function(res) {
				$("#image-card-"+id).remove();
				alert('Image Deleted Successfully');
			},
			error : function(error) {
				alert('Request Error');
			}
		});

		}//end confirm
		});//end js-dlt-img

});//end ready


</script>
@endsection