<?php
session_start();
require_once("../../autoload.php");
use Rosance\Data;
use Rosance\User;
use Rosance\Project;
use Rosance\Mail;
$data = new Data();
$user = $data->GetUser($_SESSION['loggedIN']);
$project = new Project($_COOKIE['NCS_PROJECT']);
$mailbox = $data->GetMailbox($user,$project,false);
if(isset($_POST['action']) && $_POST['action'] == "RemoveMails" && isset($_POST['MailsToRemove']))
{
    echo $data->RemoveMails($user, $project, $_POST['MailsToRemove']);
}
?>

                <table class="table table-hover table-striped" id="mailInbox">
                    <thead>
                        <tr>
                            <th style="width:5%;">#</th>
                            <th style="width:25%;">Sender</th>
                            <th style="width:45%;">Subject</th>
                            <th style="width:25%;">Date</th>
                        </tr>
                    </thead>
                  <tbody>

    <?php 
    if($mailbox->num_rows > 0)
    {
      while($mail = mysqli_fetch_assoc($mailbox))
        {
    ?>

                  <tr>
                    <td>
                      <div class="icheck-primary">
                        <input type="checkbox" value="<?php echo $mail['Message']; ?>" class="checkedMail">
                        <label for="check1"></label>
                      </div>
                    </td>
                    <td class="mailbox-name">
                    <?php if(!$mail['hasRead']){ ?>
                          <span class="badge bg-danger float-right">New</span>
                    <?php }?>
                    <?php if(strlen($mail['Name']) > 15) echo substr($mail['Name'],0,13)."..."; else echo $mail['Name'];
                      echo "</br> < <a href='mailto:".$mail['Email']."'>".$mail['Email']."</a> > ";
                    ?>
                    </td>
                    <td class="mailbox-subject">
                        <b><a href="dashboard/mailbox/readmail/<?php echo $mail['ID']; ?>"><?php
                         if(strlen($mail['Subject']) > 40) echo substr($mail['Subject'],0,40)."...";
                         else echo $mail['Subject'];
                        ?></a></b>
                    </td>
                    <td class="mailbox-date"><?php echo $mail['Date']; ?></td>
                  </tr>
    <?php } }else {?>
    <tr style="background-color:white;">
      <td colspan="4" class="text-center">
        <img src="images/mailbox_empty.png" class="img img-fluid" alt="No mails here ...">
      </td>
    </tr>
    <?php } ?>
                  </tbody>
                </table>
                <!-- /.table -->
<script>
$(function(){
    const rowsPerPage = 10;
	const rows = $('#mailInbox tbody tr');
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
    $("#mailInbox tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
    if(value == "") displayRows(1);
  });
});
</script>