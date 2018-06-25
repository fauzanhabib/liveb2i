<script>
    // Wait until the DOM has loaded before querying the document
    $(document).ready(function(){
        $('div.tabs2').each(function(){
            // For each set of tabs, we want to keep track of
            // which tab is active and its associated content
            var $active, $content, $links = $(this).find('a');

            // If the location.hash matches one of the links, use that as the active tab.
            // If no match is found, use the first link as the initial active tab.
            $active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
            $active.addClass('active');

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
                $content.show();

                // Prevent the anchor's default click action
                e.preventDefault();
            });
        });
    });
</script>
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
            $active.addClass('active');

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
<style>
.img_neo{
  width: 70%;
  margin: 0 auto;
}
.dl_neo{
  margin-top: 10px;
  color: black;
}
.dl_neo:hover{
  color: #3aace2;
}
</style>

<div class="heading text-cl-primary border-b-1 padding15">
    <h1 class="margin0">Coach Materials</h1>
</div>

<div class="box">

  <div class="heading pure-g padding-t-30">

      <div class="left-list-tabs pure-menu pure-menu-horizontal text-center margin0">
          <ul class="pure-menu-list">
              <!-- <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('coach/ongoing_session');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Current Sessions</a></li> -->
              <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('coach/coach_material');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">B2I Scripts</a></li>
              <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('coach/coach_material/bc');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">B2C Scripts</a></li>
              <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('coach/coach_material/neo_dashboard');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue">Neo Study Dashboard</a></li>
              <!-- <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('coach/histories/class_session');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Class Session History</a></li> -->
          </ul>
      </div>

  </div>

  <div class="heading pure-g padding-t-50">
      <div class="left-list-tabs pure-menu pure-menu-horizontal text-center margin0">
        <h3 style="padding-bottom: 15px;">This document is a guideline for neo Study Dashboard</h3>
        <a target="_blank" href="<?php echo base_url();?>neo_dashboard.pdf" class="dl_neo icon icon-study-dashboard"> Download here (PDF)</a>
      </div>
  </div>

    <div class="content">
        <div class="box" style="text-align:center;">
          <img class="img_neo" src="<?php echo base_url();?>assets/images/neo_dashboard.png" />
        </div>
    </div>

</div>

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
