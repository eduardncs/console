<div class="row">
        <div class="col-md-12">
        <?php 
if(isset($_POST['action']) && $_POST['action'] == "readmail" && isset($_POST['postID']) && !empty($_POST['postID']))
{
?>
<script>
  $.ajax({
    url: "pages/mailbox/readmail.php",
    dataType: "html",
    method: "post",
    data: {"postID": "<?php echo $_POST['postID']; ?>"},
    beforeSend: function(){ $("#mailboxLoader").show(); },
    success: function(data){ $("#Header").text("Mailbox :: Read mail"); $("#mailboxLoader").hide(); $("#mailContainer").html(data); }
  })
</script>
<div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">Read Mail</h3>
              <div class="card-tools">
              <a href="dashboard/mailbox" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Back to inbox"><i class="fas fa-backspace"></i></a></div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <div id="mailboxLoader" align="center">
                  <img src="images/mailLoader.gif" width="250px" height="250px"/>
                </div>
                <div id="mailContainer"></div>
            </div>
            <!-- /.card-body -->
            </div>
            <!-- /.card-footer -->
          </div>
          <?php }else{ ?>
          <script>loadInbox("pages/mailbox/inbox");</script>
        <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title" id="contextHeader">Inbox</h3>
              <div class="card-tools">
                <div class="input-group input-group-sm">
                  <input type="text" id="search" class="form-control" placeholder="Search Mail">
                  <div class="input-group-append">
                    <div class="btn btn-primary">
                      <i class="fas fa-search"></i>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle" data-toggle='tooltip' data-placement='bottom' title='Select all'><i class="far fa-square"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm" onClick="removeMails()" data-toggle='tooltip' data-placement='bottom' title='Remove selected'><i class="far fa-trash-alt"></i></button>
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm" onClick="loadInbox('pages/mailbox/inbox');getsubpage();" data-toggle='tooltip' data-placement='bottom' title='Refresh mailbox'><i class="fas fa-sync-alt"></i></button>
                <div class="float-right">
                    <div class="btn-group">
                      <ul class="pagination pagination-sm" id="numbers">
                      </ul>
                    </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.float-right -->
              </div>
              <div class="table-responsive mailbox-messages">
                <div align="center" id="mailboxLoader">
                  <img src="images/mailLoader.gif" width="250px" height="250px"/>
                </div>
          <div id="mailboxContainer">
          
          </div>
          </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.card-body -->
          </div>
          <?php } ?>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
<script>
    $(document).ready(function(){
      $('[data-toggle=tooltip]').tooltip();
    });
    $('.checkbox-toggle').click(function () 
    {
      var clicks = $(this).data('clicks')
      if (clicks) {
        //Uncheck all checkboxes
        $('.mailbox-messages input[type=\'checkbox\']').prop('checked', false)
        $('.checkbox-toggle .far.fa-check-square').removeClass('fa-check-square').addClass('fa-square')
      } else {
        //Check all checkboxes
        $('.mailbox-messages input[type=\'checkbox\']').prop('checked', true)
        $('.checkbox-toggle .far.fa-square').removeClass('fa-square').addClass('fa-check-square')
      }
      $(this).data('clicks', !clicks)
    })
</script>