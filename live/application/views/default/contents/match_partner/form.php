<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Affiliate Matchings</h1>
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
                <div id="student_supplier" class="pure-control-group">
                    <div class="label">
                        <label for="student">Student Affiliate</label>
                    </div>
                    <div class="input">
                        <select id="stu_sup" name="stu_sup[]" class="pure-input-1-2 select-partner multiple-select"  multiple="multiple" required style="width:100%">
                            <?php foreach (@$data_student_supplier as $d) { ?>
                                <option <?php echo(@in_array(array(id=>$d->id, name=>$d->name), @$selected_student_supplier) ? 'selected' : '');?> value="<?php echo($d->id); ?>"> <?php echo($d->name); ?> </option>
                                <?php
                            }
                            ?>
                        </select>
                        <input name="student_supplier_id" type="hidden" id="student_supplier_id" value="<?php if(@$class_matchmaking_id)foreach(@$selected_student_supplier as $s){echo($s['id'].',');}?>">
                        
                    </div>
                </div>
                <div id="student_group" class="pure-control-group">
                    <div class="label">
                        <label for="student">Student Group</label>
                    </div>
                    <div class="input">
                        <!-- <input type="text" name="coach" value="" id="coach" class="pure-input-1-2" /> -->
                        <select id="stu_gru" name="stu_gru[]" class="pure-input-1-2 select-partner multiple-select2"  multiple="multiple" style="width:100%">
                            <?php if($this->uri->segment(3) == 'add'){
                            }elseif($this->uri->segment(3) == 'edit'){ ?>
                            <?php foreach (@$data_student_group as $d) { ?>
                                <option <?php echo(@in_array(array(id=>$d->id, name=>$d->name), @$selected_student_group) ? 'selected' : '');?> value="<?php echo($d->id); ?>"> <?php echo($d->name); ?> </option>
                                <?php
                            } }
                            ?>      
                        </select>
                        <input name="student_group_id" type="hidden" id="student_group_id" value="<?php if(@$class_matchmaking_id)foreach(@$selected_student_group as $s){echo($s['id'].',');}?>">
                        
                    </div>
                </div>
                <div id="coach_supplier" class="pure-control-group">
                    <div class="label">
                        <label for="coach">Coach Affiliate</label>
                    </div>
                    <div class="input">
                        <!-- <input type="text" name="coach" value="" id="coach" class="pure-input-1-2" /> -->
                        <select id="coa_sup" name="coa_sup[]" class="pure-input-1-2 select-partner multiple-select3"  multiple="multiple" required style="width:100%">
                            <?php foreach (@$data_coach_supplier as $d) { ?>
                                <option <?php echo(@in_array(array(id=>$d->id, name=>$d->name), @$selected_coach_supplier) ? 'selected' : '');?> value="<?php echo($d->id); ?>"> <?php echo($d->name); ?> </option>
                                <?php
                            }
                            ?>
                        </select>
                        <input name="coach_supplier_id" type="hidden" id="coach_supplier_id" value="<?php if(@$class_matchmaking_id)foreach(@$selected_coach_supplier as $s){echo($s['id'].',');}?>">
                        
                    </div>
                </div>
                <div id="coach_group" class="pure-control-group">
                    <div class="label">
                        <label for="coach">Coach Group</label>
                    </div>
                    <div class="input">
                        <!-- <input type="text" name="coach" value="" id="coach" class="pure-input-1-2" /> -->
                        <select id="coa_gru" name="coa_gru[]" class="pure-input-1-2 select-partner multiple-select4"  multiple="multiple" style="width:100%">
                            <?php if($this->uri->segment(3) == 'add'){
                            }elseif($this->uri->segment(3) == 'edit'){ ?>
                            <?php foreach (@$data_coach_group as $d) { ?>
                                <option <?php echo(@in_array(array(id=>$d->id, name=>$d->name), @$selected_coach_group) ? 'selected' : '');?> value="<?php echo($d->id); ?>"> <?php echo($d->name); ?> </option>
                                <?php
                            } }
                            ?>      
                        </select>
                        <input name="coach_group_id" type="hidden" id="coach_group_id" value="<?php if(@$class_matchmaking_id)foreach(@$selected_coach_group as $s){echo($s['id'].',');}?>">
                        
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
            var tes = '1';

            <?php if ($this->uri->segment(3) == 'edit'){ ?>
                // $("#stu_sup").trigger("select2:select");
                // $("#coa_sup").trigger("select2:select");
                $(document).ready(function(event){
                    console.log(event);
                var student_supp_id = $(".multiple-select").val();
                document.getElementById('student_supplier_id').value = student_supp_id;
                var stu_sup_id = document.getElementById('student_supplier_id').value;
                $(".multiple-select2").empty();
                var coach_supp_id = $(".multiple-select3").val();
                document.getElementById('coach_supplier_id').value = coach_supp_id;
                var coa_sup_id = document.getElementById('coach_supplier_id').value;
                $(".multiple-select4").empty();
                $.ajax({
                        <?php if($this->auth_manager->role() == 'RAD'){ ?>
                        url: "<?php echo site_url('superadmin/match_partner/student_preview');?>",
                        <?php }else{ ?>
                        url: "<?php echo site_url('admin/match_partner/student_preview');?>",
                        <?php } ?>  
                        type: 'POST',
                        dataType: 'html',
                        data: {stu_sup_id:student_supp_id},
                        success: function(response) {
                            // console.log(response);
                            var data_student = response;
                            // console.log(data_student);
                            if(response.length){

                            <?php foreach (@$selstusup as $d) { ?>
                                var fullstudent = '<?= $d->name; ?>';
                            $("#stu_gru").append("<optgroup id= <?= $d->id; ?> label= '" + fullstudent + "'>");
                            <?php } ?>
                            // $("#stu_gru").find('optgroup#'+e.params.data.id).append('<option value='+e.params.data.id+'> Select All </option>');
                            $.each(JSON.parse(data_student), function(key, value){
                                $("#stu_gru").find('optgroup#' + value.partner_id).append('<option id=' + value.id + ' value=' + value.id + '>' + value.name + '</option>');
                                // <option <?php echo(@in_array(array(id=>$d->id, name=>$d->name), @$selected_coach_supplier) ? 'selected' : '');?> value="<?php echo($d->id); ?>"> <?php echo($d->name); ?> </option>
                                if(JSON.parse(data_student).length-1 == key){
                                    <?php foreach($selstugru as $d){ ?>
                                         $("#stu_gru").find('optgroup#' + <?= $d->partner_id; ?>).find('option[id=<?= $d->id; ?>]').prop('selected', true);
                                    <?php } ?>
                                }
                                });
                            };
                            }                
                    });

                    $.ajax({
                        <?php if($this->auth_manager->role() == 'RAD'){ ?>
                        url: "<?php echo site_url('superadmin/match_partner/coach_preview');?>",
                        <?php }else{ ?>
                        url: "<?php echo site_url('admin/match_partner/coach_preview');?>",
                        <?php } ?>  
                        type: 'POST',
                        dataType: 'html',
                        data: {coa_sup_id:coach_supp_id},
                        success: function(response) {
                            // console.log(response);
                            var data_coach = response;
                            // console.log(data_coach);
                            if(response.length){

                            <?php foreach (@$selcoasup as $d) { ?>
                                var fullcoach = '<?= $d->name; ?>';
                            $("#coa_gru").append("<optgroup id= <?= $d->id; ?> label= '" + fullcoach + "'>");
                            <?php } ?>
                            // $("#stu_gru").find('optgroup#'+e.params.data.id).append('<option value='+e.params.data.id+'> Select All </option>');
                            $.each(JSON.parse(data_coach), function(key, value){
                                $("#coa_gru").find('optgroup#' + value.partner_id).append('<option id=' + value.id + ' value=' + value.id + '>' + value.name + '</option>');
                                // <option <?php echo(@in_array(array(id=>$d->id, name=>$d->name), @$selected_coach_supplier) ? 'selected' : '');?> value="<?php echo($d->id); ?>"> <?php echo($d->name); ?> </option>
                                if(JSON.parse(data_coach).length-1 == key){
                                    <?php foreach($selcoagru as $d){ ?>
                                         $("#coa_gru").find('optgroup#' + <?= $d->partner_id; ?>).find('option[id=<?= $d->id; ?>]').prop('selected', true);
                                    <?php } ?>
                                }
                                });
                            };
                            }                
                    });  
                });
            <?php }else{ ?>
                $("#student_group").hide();
                $("#coach_group").hide();
            <?php } ?>

            $select2Elm.select2();
            //var select2 = $select2Elm.data('select2'),
            //$select2Input = $('.select2-input', select2.searchContainer),
            //$tagToRemove = $('li', select2.selection).eq(0);
            //$('.select2-selection__arrow').hide();
            //$('.select2-container--default .select2-selection--single').css('border','0','border-radius','0');
            $(".multiple-select").select2({ placeholder: "Select Affiliate"});
            $(".multiple-select").on('select2:select', function(evt){
                // console.log('select');
                // console.log(evt);
                var student_supp_id = $(".multiple-select").val();
                var student_group = $(".multiple-select2").val();
                // console.log(student_supp_id);
                // console.log(student_group);
                var that = $(this);
                if(student_supp_id != ''){
                    document.getElementById('student_supplier_id').value = student_supp_id;
                    var stu_sup_id = document.getElementById('student_supplier_id').value;
                    document.getElementById('student_group_id').value = student_group;
                    var stu_gru_id = document.getElementById('student_group_id').value;
                    //console.log(stu_sup_id);
                    $('#student_supplier').addClass('loadinggif');
                        $.ajax({
                        <?php if($this->auth_manager->role() == 'RAD'){ ?>
                        url: "<?php echo site_url('superadmin/match_partner/student_preview');?>",
                        <?php }else{ ?>
                        url: "<?php echo site_url('admin/match_partner/student_preview');?>",
                        <?php } ?>  
                        type: 'POST',
                        dataType: 'html',
                        data: {stu_sup_id:evt.params.data.id},
                        success: function(response) {
                            console.log(evt);
                            var data_student = response;
                            if(response.length){
                            $("#stu_gru").append("<optgroup id= '"+ evt.params.data.id +"' label='" + evt.params.data.text + "'>");
                            // $("#stu_gru").find('optgroup#'+evt.params.data.id).append('<option value='+evt.params.data.id+'> Select All </option>');
                            $.each(JSON.parse(data_student), function(key, value){
                                $("#stu_gru").find('optgroup#'+evt.params.data.id).append('<option id=' + value.id + ' value=' + value.id + '>' + value.name + '</option>');
                                // <option <?php echo(@in_array(array(id=>$d->id, name=>$d->name), @$selected_coach_supplier) ? 'selected' : '');?> value="<?php echo($d->id); ?>"> <?php echo($d->name); ?> </option>
                                });
                            };
                            // console.log(data_student);
                            if(data_student != ''){
                            $('#student_supplier').removeClass('loadinggif');
                            $("#student_group").show();
                            }
                        }                
                    }); 
                }
            });
            $(".multiple-select").on('select2:unselect', function(evt){
                // console.log('jangan');
                var student_supp_id = $(".multiple-select").val();
                var student_group = $(".multiple-select2").val();
                $("#stu_gru").find('optgroup[id='+evt.params.data.id+']').remove();
                if ($("#stu_gru optgroup").length==0){
                    $('#student_supplier').removeClass('loadinggif');
                            $("#student_group").hide();
                    };
                });
            
            $(".multiple-select2").select2({ placeholder: "Select Group"});
            $(".multiple-select2").change(function(){
                // document.getElementById('student_group_id').value = $(".multiple-select2").val();
            });

            $(".multiple-select3").select2({ placeholder: "Select Affiliate"});
            $(".multiple-select3").on('select2:select', function(evt){
                // console.log('select');
                // console.log(evt);
                var coach_supp_id = $(".multiple-select3").val();
                var coach_group = $(".multiple-select4").val();
                // console.log(coach_supp_id);
                // console.log(coach_group);
                var that = $(this);
                if(coach_supp_id != ''){
                    document.getElementById('coach_supplier_id').value = coach_supp_id;
                    var coa_sup_id = document.getElementById('coach_supplier_id').value;
                    document.getElementById('coach_group_id').value = coach_group;
                    var coa_gru_id = document.getElementById('coach_group_id').value;
                    //console.log(coa_sup_id);
                    $('#coach_supplier').addClass('loadinggif');
                        $.ajax({
                        <?php if($this->auth_manager->role() == 'RAD'){ ?>
                        url: "<?php echo site_url('superadmin/match_partner/coach_preview');?>",
                        <?php }else{ ?>
                        url: "<?php echo site_url('admin/match_partner/coach_preview');?>",
                        <?php } ?>  
                        type: 'POST',
                        dataType: 'html',
                        data: {coa_sup_id:evt.params.data.id},
                        success: function(response) {
                            // console.log(response);
                            var data_coach = response;
                            if(response.length){
                            $("#coa_gru").append("<optgroup id= '"+ evt.params.data.id +"' label='" + evt.params.data.text + "'>");
                            // $("#coa_gru").find('optgroup#'+evt.params.data.id).append('<option value=all> Select All </option>');
                            $.each(JSON.parse(data_coach), function(key, value){
                                $("#coa_gru").find('optgroup#'+evt.params.data.id).append('<option id=' + value.id + ' value=' + value.id + '>' + value.name + '</option>');
                                // <option <?php echo(@in_array(array(id=>$d->id, name=>$d->name), @$selected_coach_supplier) ? 'selected' : '');?> value="<?php echo($d->id); ?>"> <?php echo($d->name); ?> </option>
                                });
                            };
                            if(data_coach != ''){
                            $('#coach_supplier').removeClass('loadinggif');
                            $("#coach_group").show();
                            }
                        }                
                    }); 
                }
            });
            $(".multiple-select3").on('select2:unselect', function(evt){
                // console.log('jangan');
                var coach_supp_id = $(".multiple-select3").val();
                var coach_group = $(".multiple-select4").val();
                $("#coa_gru").find('optgroup[id='+evt.params.data.id+']').remove();
                if ($("#coa_gru optgroup").length==0){
                    $('#coach_supplier').removeClass('loadinggif');
                            $("#coach_group").hide();
                    };
                });

            $(".multiple-select4").select2({ placeholder: "Select Group"});
            $(".multiple-select4").change(function(){
                // document.getElementById('coach_group_id').value = $(".multiple-select4").val();
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

