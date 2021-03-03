<?php
if(!isset($_SESSION))
    session_start();
require_once("../../System/Database.class.php");
require_once("../../System/Data.class.php");
require_once("../../System/Project.class.php");
require_once("../../System/User.class.php");
require_once("../../System/Shop.class.php");
$data = new Data();
$user = $data->GetUser($_SESSION['loggedIN']);
$project = new Project($_COOKIE['NCS_PROJECT']);
$shop = new Shop();
$products = $shop->S_GetProducts($user, $project);
?>
<div id="requests"></div>
<div class="row">
    <div class="col-md-12">
        <div class="card card-solid card-outline card-primary">
            <div class="card-header">
                <h4 class="card-title">Browse products</h4>
                <div class="card-tools">
                <div class="input-group input-group-sm">
                  <input type="text" id="search" class="form-control" placeholder="Search Product">
                  <div class="input-group-append">
                    <div class="btn btn-primary">
                      <i class="fas fa-search"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body p-0">
                <div class="mailbox-controls">
                    <div class="float-right">
                        <div class="btn-group">
                        <ul class="pagination pagination-sm" id="numbers">
                        </ul>
                        </div>
                    <!-- /.btn-group -->
                    </div>
                    <!-- /.float-right -->
                </div>
            <table class="table table-hover table-striped" id="productsTable">
                <thead>
                    <tr>
                        <th style="width:5%;"></th>
                        <th style="width:5%;">ID</th>
                        <th style="width:35%;">Name</th>
                        <th style="width:10%;">Inventory</th>
                        <th style="width:15%;">Prices</th>
                        <th style="width:25%;">Date added</th>
                        <th style="width:10%;" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if($products->num_rows == 0)
                    {
                ?>
                  <tr style="background-color:white;">
                      <td colspan="8" class="text-center">
                        <img src="images/no_product_list.svg" class="img img-fluid" style="width:640px; height:450px;" alt="No products here ...">
                      </td>
                  </tr>
                <?php }else{
                while($row = mysqli_fetch_assoc($products))
                    {
                        $product = new product($row);
                        ?>
                    <tr>
                      <td>
                        <img src="<?php echo $product->GetMainPicture(); ?>" alt="Product 1" class="img-size-64 mr-2 img-circle" style="height:64px;">
                      </td>
                      <td><?php echo $product->id; ?></td>
                      <td><?php echo $product->StripName(); ?></td>
                      <td><?php echo $product->GetVisibility();?></td>
                      <td><?php echo $product->GetPrices($user,$project); ?></td>
                      <td><?php echo $product->date; ?></td>
                      <td class="text-center">
                      <a href="javascript:void(0)" onClick="deleteProduct('<?php echo $product->id; ?>','<?php echo $product->descriptionid; ?>')" class="text-muted" data-toggle="tooltip" data-placement="bottom" title="Remove this product"><i class="fas fa-trash"></i></a>&nbsp;&nbsp;&nbsp;
                      <a href="products/manage/<?php echo $product->id; ?>" class="text-muted" data-toggle="tooltip" data-placement="bottom" title="Edit this product"><i class="fas fa-edit"></i></a>
                      </td>
                  </tr>
                    <?php }} ?>
            </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
  $("[data-toggle*=tooltip]").tooltip();
})

function deleteProduct(id , did)
{
    Swal.fire
    ({
        title: 'Are you sure do you want to remove this product ?',
        icon: 'question',
        cancelButtonText: 'No',
        cancelButtonColor: 'red',
        showConfirmButton: true,
        confirmButtonText: 'Yes!',
        showCancelButton: true
      }).then((result) => {
      if (result.value) {
          var values = {"action":"remove","ncs_id": id,"ncs_did": did};
          $.ajax({
              url:"System/Shop.class.php",
              method: "post",
              data: values,
              success: function(data){$("#requests").html(data); getpage("pages/shop/overview", "E-Commence :: Overview"); getsubpage();}
          });
          }
        });
}

$(function(){
    const rowsPerPage = 10;
	const rows = $('#productsTable tbody tr');
	const rowsCount = rows.length;
	const pageCount = Math.ceil(rowsCount / rowsPerPage); // avoid decimals
	const numbers = $('#numbers');
    
    $("#numbers").empty();
	// Generate the pagination.
	for (var i = 0; i < pageCount; i++) {
		numbers.append('<li class="page-item"><a href="javascript:void(0)" class="page-link">'+ (i+1) +'</a></li>');
	}
		
	// Mark the first page link as active.
	$('#numbers li:first-child a').addClass('active');

	// Display the first set of rows.
	displayRows(1);
	
	// On pagination click.
	$('#numbers li a').click(function(e) {
		var $this = $(this);
		
		e.preventDefault();
		
		// Remove the active class from the links.
		$('#numbers li a').removeClass('active');
		
		// Add the active class to the current link.
		$this.addClass('active');
		
		// Show the rows corresponding to the clicked page ID.
		displayRows($this.text());
	});
	
	// Function that displays rows for a specific page.
	function displayRows(index) {
		var start = (index - 1) * rowsPerPage;
		var end = start + rowsPerPage;
		
		// Hide all rows.
		rows.hide();
		
		// Show the proper rows for this page.
		rows.slice(start, end).show();
    }
    $("#search").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#productsTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
    if(value == "") displayRows(1);
  });
});

</script>