<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/raty/jquery.raty.css">
<script src="<?php echo base_url(); ?>assets/vendor/raty/jquery.raty.js"></script>

<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Rate a Coach</h1>
</div>

<div class="box">

    <div class="content padding0" style="border-top:1px solid #f3f3f3">
        <?php
        if(!@$data) {
            ?>
                <div class="padding15">
                    <div class="no-result">
                        No Data
                    </div>
                </div>
            <?php
        }
        else {
        ?>
        <div class="b-pad">
        <table class="table-session"> 
            <thead>
                <tr>
                    <th class="padding15">SESSION</th>
                    <th class="padding15">START TIME</th>
                    <th class="padding15">COACH</th>
                    <th class="padding15">RATING</th>
                    <th class="padding15">ACTION</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach (@$data as $d) { ?>
                    <tr>
                        <td class="padding15" data-label="SESSION">
                            <?php echo date('F d, Y', strtotime(@$d->date)); ?>
                        </td>
                        <td class="padding15" data-label="TIME"><span class="text-cl-green"><?php echo(date('H:i', strtotime(@$d->start_time))); ?> - <?php echo(date('H:i', strtotime(@$d->end_time))); ?></span></td>
                        <td class="padding15" data-label="COACH"><a href="#" class="text-cl-secondary"><?php echo @$d->fullname; ?></a></td>
                        <td class="padding15" data-label="RATING">
                            <span class="<?php echo (@round($rating[@$d->coach_id], 2) == 0 ? 'unrating-div':'rating-div');?>" style="width:50px">
                                <?php echo (@round($rating[@$d->coach_id], 2) == 0 ? ('-') : '<i class="icon icon-star-full"></i> ' . @round($rating[@$d->coach_id], 2)); ?>
                            </span>
                        </td>
                        <td class="padding15 t-center"><a href="#" data-toggle="modal" data-target="#divModal" data-id="<?php echo(@$d->id);?>" class="text-cl-green">RATE NOW</a></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        </div>
        <?php } ?>
    </div>	
</div>

<!-- Modal -->
<div class="modal hide fade" id="divModal" tabindex="-1" role="dialog" aria-labelledby="divModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="icon icon-close"></i></button>
                <h3 class="modal-title text-cl-primary" id="myModalLabel">Rate The Coach Now !</h3>
            </div>
            <div class="modal-body text-center">
                <div class="pure-form">
                    <div class="rating"></div>
                    <input type="hidden" name="rating" id="score">
                    <div class="id_coach"></div>
                    <textarea id="description" name="description" style="width: 80%;height: 80px;resize: none;"></textarea>
                    <ul class="parsley-errors-list filled"><li class="parsley-required">Please input your comment.</li></ul>
                </div>
            </div>
            <div class="modal-footer text-center">
                <button type="submit" class="pure-button btn-primary btn-small btn-expand submit">SUBMIT</button>
            </div>
        </div>
    </div>  
</div>
<?php echo $pagination;?>
<script type="text/javascript">
    $(document).ready(function () {

        $('.parsley-errors-list').hide();
        $('#divModal').appendTo("body");

        $('#divModal').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget) // Button that triggered the modal
          var recipient = button.data('id') // Extract info from data-* attributes
          var modal = $(this)
          modal.find('.submit').val(recipient);
          $("html,body").css("overflow","hidden");
        });

        $('.rating').raty({
            starHalf: 'icon icon-star-half',
            starOff: 'icon icon-star-full color-grey',
            starOn: 'icon icon-star-full',
            starType: 'i',
            targetScore: '#score',
            score: 1
        });


        $('.submit').click(function(){
            
            if ($.trim($('#description').val()) != '') {
                window.location = '<?php echo site_url('student/rate_coaches/update_rate');?>'+'/'+this.value+'/'+$('#score').val()+'/'+$('#description').val();
            }
            else {
                $('.parsley-errors-list').show();
            }


            
        });

        $('.close').click(function(){
            $('.rating').raty({
              cancel: false,
              starHalf: 'icon icon-star-half',
              starOff: 'icon icon-star-full color-grey',
              starOn: 'icon icon-star-full',
              starType: 'i',
              targetScore: '#score',
              score: 1
            });
            $("html,body").css("overflow","auto");
            $('#description').val('');
        });

    });
    
</script>

<style type="text/css">
.rating i {
    width: 30px;
    height: 30px;
    display: inline-block;
}
</style>