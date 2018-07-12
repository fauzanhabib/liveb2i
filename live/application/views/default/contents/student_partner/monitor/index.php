<style>
    .table-session td, .table-sessions td{
        padding: 5px 0px !important;
    }
    .table-session, .table-sessions {
        padding: 0px !important;
    }
    .dataTables_wrapper .dataTables_filter {
        padding: 8px !important;
        overflow-x: scroll !important;
    }
    table.dataTable thead .sorting, table.dataTable thead .sorting_asc, table.dataTable thead .sorting_desc {
        background-image: none;
    }
    label {
        display: inline;
        font-size: 19px !important;
        font-weight: 600;
        color: #585858;
    }
    label input {
        /*display: inline;*/
        font-size: 16px !important;
        font-weight: 100 !important;
    }
    #large_wrapper{
        overflow-x: auto !important;
    }

    @media screen and (max-width: 425px) {
        .dataTables_wrapper .dataTables_filter {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: end;
            -ms-flex-pack: end;
                    justify-content: flex-end;
            padding-right: 0!important;
        }
    }
</style>

<div class="box clear-both">

  <div class="heading text-cl-primary padding-l-20">
      <div class="breadcrumb-tabs pure-g">
          <div class="left-breadcrumb">
              <ul class="breadcrumb toolbar padding-l-0">
                  <li id="breadcrum-home"><a href="http://localhost:8181/index.php/account/identity/detail/profile">
                      <div id="home-icon">
                          <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve">
                          <g>
                              <path d="M2.7,14.1c0,0,0,0.3,0.3,0.3c0.4,0,3.7,0,3.7,0l0-3c0,0-0.1-0.5,0.4-0.5h1.5c0.6,0,0.5,0.5,0.5,0.5l0,3
                                  c0,0,3.1,0,3.6,0c0.4,0,0.4-0.4,0.4-0.4V8.5L8.1,4L2.7,8.5L2.7,14.1z"></path>
                              <path d="M0.7,8.1c0,0,0.5,0.8,1.5,0l5.9-5l5.6,5c1.2,0.8,1.6,0,1.6,0L8.1,1.5L0.7,8.1z"></path>
                              <polygon points="13.6,3 12.1,3 12.1,4.8 13.6,6  "></polygon>
                          </g>
                          </svg>
                      </div>
                  </a></li>
                  <li><a>Monitor Accounts</a></li>
              </ul>
          </div>
      </div>
      <h1 class="margin0">Add Monitor Accounts</h1>
    </div>

    <div class="box">
        <div class="content">
            <div class="box pure-g">
              <div class="pure-u-24-24 profile-detail prelative">
                <form name ="leaving" action="<?php echo(site_url('partner/monitor/add_monitor_acc/'));?>" method="post">
                  <table class="table-no-border2">
                      <tbody>
                          <tr>
                              <td class="pad15">Email</td>
                              <td>
                                  <input type="name" name="email" class="border-none" required>
                              </td>
                          </tr>
                          <tr>
                              <td class="pad15">Name</td>
                              <td>
                                  <input type="name" name="name" class="border-none" required>
                              </td>
                          </tr>
                      </tbody>
                  </table>

                  <div class="pure-u-1" style="padding: 15px 0px;margin-top:15px;display:flex;justify-content:flex-end;">
                      <div class="label">
                          <input type="submit" value="Submit" class="pure-button btn-small btn-tertiary">
                      </div>
                  </div>
                </form>
              </div>
            </div>
        </div>
    </div>

    <div class="content padding-t-10">
       <div class="box">
         <div class="heading text-cl-primary padding-l-20">
           <h3 class="margin0" style="font-size: 28px;font-weight: 600;color: #2b89b9;">Accounts List</h3>
         </div>
            <script>
                $(document).ready(function() {
                    // $('#large_wrapper').hide();
                    // $('#large_wrapper').css('overflow-x','auto !important');

                    $('#large').DataTable( {
                      "bLengthChange": false,
                      "searching": true,
                      "userTable": false,
                      "bInfo" : false,
                      "bPaginate": true,
                      "pageLength": 10,
                      "scrollX": true
                    });

                    // $('#large_wrapper').addClass("asdf");
                    // $('#large_wrapper').css('overflow-x','auto !important');

                } );
            </script>
            <table id="large" class="display table-session tablesorter" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <!-- <th class="bg-secondary bg-none text-cl-white border-none" style="width:30px;"></th> -->
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Email</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Name</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Status</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Action</th>
                    </tr>
                </thead>
                <tbody>
                  <?php
                    foreach ($monitor_list as $d) {
                  ?>
                    <tr onclick="myFunction(this)">
                        <td><?php echo $d->email; ?></td>
                        <td><?php echo ucfirst($d->fullname); ?></td>
                        <td><?php echo ucfirst($d->status); ?></td>
                        <td>
                          <?php if($d->status == "active"){ ?>
                            <button class="pure-button btn-red btn-small mondis" userid="<?php echo $d->id?>">Disable</button>
                          <?php }else{ ?>
                            <button class="pure-button btn-blue btn-small monena" userid="<?php echo $d->id?>">Enable</button>
                          <?php } ?>
                            <button class="pure-button btn-red btn-small mondel" userid="<?php echo $d->id?>">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/__jquery.tablesorter.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.js"></script>

<script type="text/javascript">
    function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
</script>
<script>
function myFunction(x) {
    console.log(x.rowIndex);

    $('input[id=input'+x.rowIndex+']').keyup(function(){
      if($(this).val().length){
        $('#buttonadd'+x.rowIndex).show();
      }
      else{
        $('#buttonadd'+x.rowIndex).hide();
      }
    });
}
</script>
<script type="text/javascript">
  $('.mondis').click(function(){
      var userid = $(this).attr('userid');
      $.ajax({
         type:"POST",
         url:"<?php echo site_url('student_partner/monitor/dis_mon');?>",
         data: {'userid':userid},
         success: function(data){
           location.reload();
         }
      });
   });

   $('.monena').click(function(){
       var userid = $(this).attr('userid');
       $.ajax({
          type:"POST",
          url:"<?php echo site_url('student_partner/monitor/ena_mon');?>",
          data: {'userid':userid},
          success: function(data){
            location.reload();
          }
       });
    });

    $('.mondel').click(function(){
        var userid = $(this).attr('userid');
        $.ajax({
           type:"POST",
           url:"<?php echo site_url('student_partner/monitor/del_mon');?>",
           data: {'userid':userid},
           success: function(data){
             location.reload();
           }
        });
     });
</script>

<script type="text/javascript">
            $(function() {
                $("table").tablesorter({debug: true});
            });
</script>

<script>
  $('tr').each(function(e){
      var inputs = $(this);

      $('input',inputs).on('blur', function () {
          $('td',inputs).removeClass('inline').addClass('no-inline');
      }).on('focus', function () {
          $('td',inputs).removeClass('no-inline').addClass('inline');
      });

      $('textarea',inputs).on('blur', function () {
          $('td',inputs).removeClass('inline').addClass('no-inline');
      }).on('focus', function () {
          $('td',inputs).removeClass('no-inline').addClass('inline');
      });

      $('td',inputs).css({'position':'relative'});
  })
</script>
