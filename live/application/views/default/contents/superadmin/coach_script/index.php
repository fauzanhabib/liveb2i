<script>
    // Wait until the DOM has loaded before querying the document
    $(document).ready(function(){
        $('ul.tabs').each(function(){
            // For each set of tabs, we want to keep track of
            // which tab is active and its associated content
            var $active, $content, $links = $(this).find('a');

            // If the location.hash matches one of the links, use that as the active tab.
            // If no match is found, use the first link as the initial active tab.
            $active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
            // $active.addClass('active');

            $content = $($active[0].hash);

            // Hide the remaining content
            $links.not($active).each(function () {
                $(this.hash).hide();
            });

            // Bind the click event handler
            $(this).on('click', 'a', function(e){
                // Make the old tab inactive.
                $active.removeClass('active');
                $content.hide();

                // Update the variables with the new link and content
                $active = $(this);
                $content = $(this.hash);

                // Make the tab active.
                $active.addClass('active');
                $('#tabs-content1').show();
                $('.tabs2').show();
                $content.show();

                // Prevent the anchor's default click action
                e.preventDefault();
            });
        });
    });
</script>

<div class="heading text-cl-primary border-b-1 padding15">
    <h1 class="margin0">Coach Materials</h1>
    <div class="delete-add-btn right">
      <div class="btn-noborder btn-normal bg-white-fff left" id="btn_addnew">
        <a href="<?php echo site_url('superadmin/coach_script/index');?>">
          <em class="textDec-none text-cl-green" style="font-weight: 600;border-bottom: solid;">List Materials</em>
        </a>
      </div>
      <div class="btn-noborder btn-normal bg-white-fff left" id="btn_addnew">
        <a href="<?php echo site_url('superadmin/coach_script/add_new');?>">
          <em class="textDec-none text-cl-tertiary">Add Materials</em>
        </a>
      </div>
    </div>
</div>

<div class="box clear-both padding-t-10">

    <div class="heading hidden" id="coachnoteupdated" style="background: #d3ffe6;border-left: solid 5px #4fa574;padding: 5px;
    margin: 10px;">
      <div style="color: #419c68;font-weight: 400;height: 27px;padding-top: 6px;">
        Coach materials are updated. You need to reload the list to see the result</span></b>
        <button style="float:right;color: #419c68; background:none;border:none;margin:0;font-size: 20px;padding-right: 7px;" id="closenotealert">
        X</button>
      </div>
    </div>

    <div class="heading pure-g">
        <h4 style="margin: auto;font-weight: 600;">Stored Coach Materials:</h4>
        <div class="left-list-tabs pure-menu pure-menu-horizontal text-center margin0">
            <ul class='tabs padding0 clearfix'>
            <?php
              $count = count($a1);
              $width = 100 / $count;
              foreach($a1 as $a){
            ?>
              <li style="width: <?php echo $width?>%;"><a id="<?php echo $a->certificate_plan ?>" class="width87perc"><?php echo $a->certificate_plan ?></a></li>
              <script type="text/javascript">
                $("#<?php echo $a->certificate_plan ?>").click(function(){
                    var cert = '<?php echo $a->certificate_plan ?>';
                    $.ajax({
                      type:"POST",
                      url:"<?php echo site_url('superadmin/coach_script/unit_list');?>",
                      data: {'cert':cert},
                      success: function(data){
                        $("#unit_list").html(data);
                        // console.log(data);
                      }
                     });
                });
              </script>
            <?php } ?>
            </ul>


        </div>
    </div>

    <div id="unit_list"></div>

</div>

<script type="text/javascript">
  $('#closenotealert').click(function(){
    $("#coachnoteupdated").addClass("hidden");
  });
</script>

<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].onclick = function(){
        this.classList.toggle("active");
        this.nextElementSibling.classList.toggle("show");
    }
}
</script>
