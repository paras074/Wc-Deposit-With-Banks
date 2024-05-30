
<!--link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script--->
<?php if(isset($_REQUEST['section']) && $_REQUEST['section']=="citieswise"){
	$style="display:block;";
	
}else{
	$style="display:block;";
} 

	global $wpdb;
   
   // Get 10 most recent order ids in date descending order.
$query = new WC_Order_Query( array(

    'orderby' => 'ids',
   	'posts_per_page' => '-1'
    ) );
$orders = $query->get_orders();
 
 

 ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
 	<div class="container">
  <div class="row">
 
		<div class="col-lg-12">
			<div class="panel panel-default">
                <div class="panel-heading">
                    <h4 style="display: inline-block;">Orders List</h4>
                   
                </div>
                <div class="panel-body">
                    <table id="export_csv_table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Order Date</th>
                                <th>OrderVendor</th>
                                <th>Full Name</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Phone</th>
                            </tr>
                        </thead>
                        <tbody>
						
						<?php  
foreach($orders as $order){
	
	$order_id  = $order->get_id(); // Get the order ID
	$parent_id = $order->get_parent_id(); // Get the parent order ID (for subscriptions…)
if($parent_id=="0"){
	$user_id   = $order->get_user_id(); // Get the costumer ID
	$user      = $order->get_user(); // Get the WP_User object
	$order_status  = $order->get_status(); // Get the order status 
	//$currency      = $order->get_currency(); // Get the currency used  
	//$payment_method = $order->get_payment_method(); // Get the payment method ID
	//$payment_title = $order->get_payment_method_title(); // Get the payment method title
	$date_created  = $order->get_date_created(); // Get date created (WC_DateTime object)
	//$date_create= get_the_time(__('Y/m/d G:i', 'woocommerce'), $order);
	$date_created= gmdate( 'Y-m-d H:i',$order->get_date_created()->getOffsetTimestamp() );
	$date_modified = $order->get_date_modified(); // Get date modified (WC_DateTime object)
	$billing_country = $order->get_billing_country(); // Customer billing country
    $billing_mail= $order->get_billing_email();	
    $billing_phone= $order->get_billing_phone();;	
	 // Get and Loop Over Order Items
	$shipping_address=$order->get_formatted_shipping_address();
	$items=$order->get_items();
	foreach($items as $item){
		 $product = $item->get_product();
        
        // Author id
        $author_id = $product->post->post_author;
        
        // Shopname
        //$vendor = dokan()->vendor->get( $author_id );
		$vendor = new WP_User($author_id);
		$shop_name = $vendor->get_shop_name();
		$vendor_data = get_user_meta( $author_id, 'wcfmmp_profile_settings', true );
		$vendor_email=$vendor_data['store_email'];
		$vendor_phone=$vendor_data['phone'];
		$street_1= $vendor_data['address']['street_1'];
		$street_2= $vendor_data['address']['street_2'];
		$city= $vendor_data['address']['city'];
		$zip= $vendor_data['address']['zip'];
		$country= $vendor_data['address']['country'];
		$state= $vendor_data['address']['state'];

  $full_add=$street_1." ".$street_2." ".$city." ".$state.",".$zip.",".$country;
 
		
		
	
?>
   <tr>
                                    <td><?php echo $order_id;  ?></td>
                                    <td><?php echo $date_created; ?></td>
                                    <td><?php echo $author_id; ?></td>
                                    <td><?php echo $vendor->display_name;  ?></td>
                                    <td><?php echo $full_add;  ?></td>
                                    <td><?php echo $vendor_email;   ?></td>
                                    <td><?php echo $vendor_phone;   ?></td>
                                    
                                </tr>
                                                  
          <?php  }   ?>
		 <tr style="font-weight: bold;">
                                    <td><?php echo $order_id;  ?></td>
                                    <td><?php echo $date_created; ?></td>
                                    <td><?php //echo $user_id; ?></td>
                                    <td><?php echo $user->display_name;  ?></td>
                                    <td><?php echo $shipping_address;  ?></td>
                                     <td><?php echo $billing_mail;   ?></td>
                                    <td><?php echo $billing_phone;   ?></td>
                                </tr>  
		  
<?php 		  
}
 } ?>                                               
                                                    </tbody>
                    </table>
                </div>
            </div>
        </div>

</div>
</div>
</div>

<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
	<style>
	
	</style>
<script>
$(document).ready(function() {
    $('#export_csv_table').DataTable( {
        dom: 'lBfrtip',
		lengthMenu: [[10,20,30,40, 50, -1], [10,20,30,40, 50, "All"]],
		//lengthMenu: [ 10, 20, 30, 40, 50 ],        
       	order: [[ 0, "desc" ]],
        buttons: [
             'csv', 'excel', 'pdf', 'print'
        ],
		
    } );
} );
</script>