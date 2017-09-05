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

<div class="heading text-cl-primary border-b-1 padding15">
    <h1 class="margin0">Coach Materials</h1>
</div>

<div class="box">

    <div class="heading pure-g padding-t-30">
        <div class="left-list-tabs pure-menu pure-menu-horizontal text-center margin0">
            <ul class='tabs padding0 clearfix'>
                <?php if(@$a1){ ?>
                <li class="width33perc"><a href='#tab-right1' class="width87perc">A1</a></li>
                <?php } ?>
                <?php if(@$a2){ ?>
                <li class="width33perc"><a href='#tab-right2' class="width87perc">A2</a></li>
                <?php } ?>
                <?php if(@$b1){ ?>
                <li class="width33perc"><a href='#tab-right3' class="width87perc">B1</a></li>
                <?php } ?>
                <?php if(@$b2){ ?>
                <li class="width33perc"><a href='#tab-right4' class="width87perc">B2</a></li>
                <?php } ?>
                <?php if(@$c1){ ?>
                <li class="width33perc"><a href='#tab-right5' class="width87perc">C1</a></li>
                <?php } ?>
                <?php if(@$c2){ ?>
                <li class="width33perc"><a href='#tab-right6' class="width87perc">C2</a></li>
                <?php } ?>
            </ul>

            
        </div>
    </div>

    <div class="content">
        <div class="box">

            <div id='tab-right1'>
                <div class="pure-u-1 text-center m-t-20 w3-animate-bottom" style="margin-bottom: 20px;">
                    <div class="pure-button btn-small btn-tertiary height-32">Cerification Plan: A1</div>
                </div>

                <?php foreach ($a1 as $as) { ?>
                <div class="accordion w3-animate-opacity text-center"><?php echo $as->unit; ?></div>
                    <div class="panel"> 
                     <?php
                        $wm = $as->unit;

                        $av = $this->db->select('*')
                                    ->from('script s')
                                    ->where('s.unit', $wm)
                                    ->where('s.certificate_plan', 'A1')
                                    ->get()->result();

                        $no = 2;
                        foreach ($av as $v){
                     ?>
                     <table>
                        <td style="width: 90%;border-bottom: 1px solid #eee;">
                        <p style="font-size: 14px; color:black;">
                          <?php 
                              echo $v->script;
                          ?>
                        </p>
                        </td>
                     </table>
                     <?php $no++; } ?>
                    </div>
                  <?php } ?>
                </div>

            </div>

            <div id='tab-right2'>
                <div class="pure-u-1 text-center m-t-20 w3-animate-bottom" style="margin-bottom: 20px;">
                    <div class="pure-button btn-small btn-tertiary height-32">Cerification Plan: A2</div>
                </div>

                <?php foreach ($a2 as $as) { ?>
                <div class="accordion w3-animate-opacity text-center"><?php echo $as->unit; ?></div>
                    <div class="panel"> 
                     <?php
                        $wm = $as->unit;

                        $av = $this->db->select('*')
                                    ->from('script s')
                                    ->where('s.unit', $wm)
                                    ->where('s.certificate_plan', 'A2')
                                    ->get()->result();

                        $no = 2;
                        foreach ($av as $v){
                     ?>
                     <table>
                        <td style="width: 90%;border-bottom: 1px solid #eee;">
                        <p style="font-size: 14px; color:black;">
                          <?php 
                              echo $v->script;
                          ?>
                        </p>
                        </td>
                     </table>
                     <?php $no++; } ?>
                    </div>
                  <?php } ?>
                </div>
            </div>

            <div id='tab-right3'>
                <div class="pure-u-1 text-center m-t-20 w3-animate-bottom" style="margin-bottom: 20px;">
                    <div class="pure-button btn-small btn-tertiary height-32">Cerification Plan: B1</div>
                </div>

                <?php foreach (@$b1 as $as) { ?>
                <div class="accordion w3-animate-opacity text-center"><?php echo $as->unit; ?></div>
                    <div class="panel"> 
                     <?php
                        $wm = $as->unit;

                        $av = $this->db->select('*')
                                    ->from('script s')
                                    ->where('s.unit', $wm)
                                    ->where('s.certificate_plan', 'B1')
                                    ->get()->result();

                        $no = 2;
                        foreach ($av as $v){
                     ?>
                     <table>
                        <td style="width: 90%;border-bottom: 1px solid #eee;">
                        <p style="font-size: 14px; color:black;">
                          <?php 
                              echo $v->script;
                          ?>
                        </p>
                        </td>
                     </table>
                     <?php $no++; } ?>
                    </div>
                  <?php } ?>
                </div>

            <div id='tab-right4'>
                <div class="pure-u-1 text-center m-t-20 w3-animate-bottom" style="margin-bottom: 20px;">
                    <div class="pure-button btn-small btn-tertiary height-32">Cerification Plan: B2</div>
                </div>

                <?php foreach (@$b2 as $as) { ?>
                <div class="accordion w3-animate-opacity text-center"><?php echo $as->unit; ?></div>
                    <div class="panel"> 
                     <?php
                        $wm = $as->unit;

                        $av = $this->db->select('*')
                                    ->from('script s')
                                    ->where('s.unit', $wm)
                                    ->where('s.certificate_plan', 'B2')
                                    ->get()->result();

                        $no = 2;
                        foreach ($av as $v){
                     ?>
                     <table>
                        <td style="width: 90%;border-bottom: 1px solid #eee;">
                        <p style="font-size: 14px; color:black;">
                          <?php 
                              echo $v->script;
                          ?>
                        </p>
                        </td>
                     </table>
                     <?php $no++; } ?>
                    </div>
                  <?php } ?>
                </div>
            </div>

            <div id='tab-right5'>
                <div class="pure-u-1 text-center m-t-20 w3-animate-bottom" style="margin-bottom: 20px;">
                    <div class="pure-button btn-small btn-tertiary height-32">Cerification Plan: B2</div>
                </div>

                <?php foreach (@$c1 as $as) { ?>
                <div class="accordion w3-animate-opacity text-center"><?php echo $as->unit; ?></div>
                    <div class="panel"> 
                     <?php
                        $wm = $as->unit;

                        $av = $this->db->select('*')
                                    ->from('script s')
                                    ->where('s.unit', $wm)
                                    ->where('s.certificate_plan', 'C1')
                                    ->get()->result();

                        $no = 2;
                        foreach ($av as $v){
                     ?>
                     <table>
                        <td style="width: 90%;border-bottom: 1px solid #eee;">
                        <p style="font-size: 14px; color:black;">
                          <?php 
                              echo $v->script;
                          ?>
                        </p>
                        </td>
                     </table>
                     <?php $no++; } ?>
                    </div>
                  <?php } ?>
                </div>
            </div>

            <div id='tab-right6'>
                <div class="pure-u-1 text-center m-t-20 w3-animate-bottom" style="margin-bottom: 20px;">
                    <div class="pure-button btn-small btn-tertiary height-32">Cerification Plan: B2</div>
                </div>

                <?php foreach (@$c2 as $as) { ?>
                <div class="accordion w3-animate-opacity text-center"><?php echo $as->unit; ?></div>
                    <div class="panel"> 
                     <?php
                        $wm = $as->unit;

                        $av = $this->db->select('*')
                                    ->from('script s')
                                    ->where('s.unit', $wm)
                                    ->where('s.certificate_plan', 'C2')
                                    ->get()->result();

                        $no = 2;
                        foreach ($av as $v){
                     ?>
                     <table>
                        <td style="width: 90%;border-bottom: 1px solid #eee;">
                        <p style="font-size: 14px; color:black;">
                          <?php 
                              echo $v->script;
                          ?>
                        </p>
                        </td>
                     </table>
                     <?php $no++; } ?>
                    </div>
                  <?php } ?>
                </div>
            </div>

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