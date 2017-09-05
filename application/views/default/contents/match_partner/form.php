<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Partner Matchings</h1>
</div>

<?php
$role_link = '';
if($this->auth_manager->role() == 'RAD'){
    $role_link = 'superadmin';
} else {
    $role_link = 'admin';
}
?>

<div class="box">
    <div class="heading pure-g">
        <div class="left-list-tabs pure-menu pure-menu-horizontal text-right padding-r-20">
            <ul class="pure-menu-list m-l-20">
                <li class="pure-menu-item pure-menu-selected no-hover "><a href="<?php echo site_url($role_link.'/match_partner'); ?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5">Matching List</a></li>
                <li class="pure-menu-item pure-menu-selected no-hover tabs-blue-active"><a href="<?php echo site_url($role_link.'/match_partner/add'); ?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5">New Matching</a></li>
            </ul>
        </div>
    </div>
    <div class="content tab-content">
        <form action="<?php echo site_url($role_link.'/match_partner/' .$action); ?>" role="form" class="pure-form pure-form-aligned" method="post" accept-charset="utf-8" id="form-match">
            <input name="class_matchmaking_id" type="hidden" value="<?php echo(@$class_matchmaking_id); ?>">
            <fieldset>
                <div class="pure-control-group">
                    <div class="label">
                        <label for="student">Student Partner</label>
                    </div>
                    <div class="input">
                        <select class="pure-input-1-2 select-partner multiple-select"  multiple="multiple" required style="width:100%">
                            <?php foreach (@$data_student_supplier as $d) { ?>
                                <option <?php echo(@in_array(array(id=>$d->id, name=>$d->name), @$selected_student_supplier) ? 'selected' : '');?> value="<?php echo($d->id); ?>"> <?php echo($d->name); ?> </option>
                                <?php
                            }
                            ?>
                        </select>
                        <input name="student_supplier_id" type="hidden" id="student_supplier_id" value="<?php if(@$class_matchmaking_id)foreach(@$selected_student_supplier as $s){echo($s['id'].',');}?>">
                        
                    </div>
                </div>
                <div class="pure-control-group">
                    <div class="label">
                        <label for="coach">Coach Partner</label>
                    </div>
                    <div class="input">
                        <!-- <input type="text" name="coach" value="" id="coach" class="pure-input-1-2" /> -->
                        <select class="pure-input-1-2 select-partner multiple-select2"  multiple="multiple" required style="width:100%">
                            <?php foreach (@$data_coach_supplier as $d) { ?>
                                <option <?php echo(@in_array(array(id=>$d->id, name=>$d->name), @$selected_coach_supplier) ? 'selected' : '');?> value="<?php echo($d->id); ?>"> <?php echo($d->name); ?> </option>
                                <?php
                            }
                            ?>
                        </select>
                        <input name="coach_supplier_id" type="hidden" id="coach_supplier_id" value="<?php if(@$class_matchmaking_id)foreach(@$selected_coach_supplier as $s){echo($s['id'].',');}?>">
                        
                    </div>
                </div>
                <div class="pure-control-group" style="border-top:1px solid #f3f3f3;padding: 15px 0px;">
                    <div class="label">
                        <input type="submit" name="__submit" value="SUBMIT" class="pure-button btn-blue btn-small" />    <a class="pure-button btn-small btn-red" href="<?php echo site_url($role_link.'/match_partner');?>">CANCEL</a>
                    </div>
                </div>
            </fieldset>
        </form> 

    </div>

    <script type="text/javascript">
        $(function () {

            var $select2Elm = $('.select-partner');

            $select2Elm.select2();
            //var select2 = $select2Elm.data('select2'),
            //$select2Input = $('.select2-input', select2.searchContainer),
            //$tagToRemove = $('li', select2.selection).eq(0);
            //$('.select2-selection__arrow').hide();
            //$('.select2-container--default .select2-selection--single').css('border','0','border-radius','0');
            $(".multiple-select").select2({ placeholder: "Select Partner"});
            $(".multiple-select").change(function(){
                document.getElementById('student_supplier_id').value = $(".multiple-select").val();
            });
            
            $(".multiple-select2").select2({ placeholder: "Select Partner"});
            $(".multiple-select2").change(function(){
                document.getElementById('coach_supplier_id').value = $(".multiple-select2").val();
            });

            $('#form-match').parsley();

            if($(document).width() < 616) {
                $('.parsley-errors-list').css({'margin-left':'0'});
            }
            else {
                $('.parsley-errors-list').css({'margin-left':'11.5em'});
            }
            

//            $('.save_click').click(function(){
//                document.getElementById('spoken_language').value = $(".multiple-select").val();
//            });
        })
        
        
    </script>
