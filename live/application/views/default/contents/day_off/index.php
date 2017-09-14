
<div class="heading text-cl-primary border-b-1 padding15">

    <h2 class="margin0">Days Off</h2>

</div>

<div class="box clear-both">
    <div class="heading pure-g padding-t-30">

        <div class="left-list-tabs pure-menu pure-menu-horizontal padding-l-25 margin0">
            <ul class="pure-menu-list">
                <li class="pure-menu-item pure-menu-selected text-center width150 no-hover"><a href="<?php echo site_url('coach/schedule'); ?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Schedules</a></li>
                <li class="pure-menu-item pure-menu-selected text-center width150 no-hover"><a href="<?php echo site_url('coach/day_off'); ?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue">Days Off</a></li>
            </ul>
        </div>

    </div>

    <div class="content">
        <div class="box">
            <div class="content tab-content" style="margin-top: -18px">
                <div class="text-right padding-tb-15 ">
                    <div class="add add-manage text-cl-green" style="display: inline;"><i class="icon icon-add"></i> Add Days Off</div>
                </div>
                <div id="form-add" style="display: none;">
                    <h3 style="margin: 0 0 20px;">Add Days Off</h3>        
                    <?php echo form_open('coach/day_off/create', 'role="form" id="form-add" class="pure-form pure-form-aligned" data-parsley-validate'); ?>            
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="start_date">Start Date</label>
                        </div>
                        <div class="input">
                            <div class="frm-date" style="display:inline-block">
                                <input name="start_date" id="start_date" class="datepicker frm-date margin0" type="text" required="" data-parsley-no-focus="" data-parsley-required-message="Please pick start date" data-parsley-id="8207">
                                <ul class="parsley-errors-list" id="parsley-id-8207"></ul>  
                                <span class="icon dyned-icon-coach-schedules"></span>
                            </div>
                        </div>
                    </div>

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="end_date">End Date</label>
                        </div>
                        <div class="input">
                            <div class="frm-date" style="display:inline-block">
                                <input name="end_date" id="end_date" class="datepicker2 frm-date margin0" type="text" readonly="" data-parsley-no-focus="" required="" data-parsley-required-message="Please pick end date" data-parsley-id="2182">
                                <ul class="parsley-errors-list" id="parsley-id-2182"></ul>  
                                <span class="icon dyned-icon-coach-schedules"></span>
                            </div>
                        </div>
                    </div>


                    <div class="pure-control-group">
                        <div class="label">
                            <label for="remark">Remark</label>
                        </div>
                        <div class="input">
                            <input type="text" name="remark" id="remark" class="margin0" style="width:175px" required="" data-parsley-required-message="Please input your remark" data-parsley-id="8455">
                            <ul class="parsley-errors-list" id="parsley-id-8455"></ul>
                        </div>
                    </div>

                    <div class="pure-control-group" style="border-top:1px solid #f3f3f3;padding: 15px 0px;">
                        <div class="label">
                            <?php echo form_submit('__submit', @$data->id ? 'Update' : 'SUBMIT', 'class="pure-button btn-small btn-tertiary"'); ?>             
                            <button class="cancle-manage pure-button btn-small btn-red" type="button" style="display: none;">CANCEL</button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>        
                </div>

                <?php if (!@$data) { ?>
            <div class="no-result">
                No data
            </div>
            <?php
        } else {
            ?>
                <div id="form-edit" class="pure-form">
                    <table class="table-add-day-off" style="border-top: 1px solid #f3f3f3;">
                        <thead>
                            <tr>
                                <th>START DATE</th>
                                <th>END DATE</th>
                                <th colspan="2">REMARK</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input class="datepicker frm-date" type="text">
                                </td>
                                <td>
                                    <input class="datepicker frm-date" type="text">
                                </td>
                                <td>
                                    <textarea name="" id="" cols="30" rows="3"></textarea>
                                </td>
                                <td>
                                    <button type="button" class="pure-button frm-small btn-book">SUBMIT</button>
                                    <button type="button" class="pure-button frm-small btn-book">SUBMIT</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="data-dayoff">
                    <script>
                        $(document).ready(function() {
                            $('#userTable').DataTable( {
                              "bLengthChange": false,
                              "searching": false,
                              "userTable": false,
                              "bInfo" : false,
                              "bPaginate": false
                            });
                        } );
                    </script>

                    <table id="userTable" class="display table-session padding-t-20" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="text-cl-tertiary font-light font-16 border-none">START DATE</th>
                                <th class="text-cl-tertiary font-light font-16 border-none">END DATE</th>
                                <th class="text-cl-tertiary font-light font-16 border-none">REMARK</th>
                                <th class="text-cl-tertiary font-light font-16 border-none">STATUS</th>
                                <th class="text-cl-tertiary font-light font-16 border-none">ACTION</th>               
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        foreach (@$data as $d) {
                            ?>
                            <tr class="list-dayoff">
                                <td><?php echo date('M d, Y', strtotime($d->start_date)); ?></td>
                                <td><?php echo date('M d, Y', strtotime($d->end_date)); ?></td>
                                <td>
                                    <div class="status-disable">
                                        <span class="<?php echo $d->status; ?>"><?php echo $d->remark; ?></span>
                                    </div>
                                </td>
                               <td>
                                    <div class="status-disable">
                                        <span class="text-cl-white active labels <?php echo $d->status; ?>"><?php echo $d->status; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    if(($d->status != 'booked') && ($d->status != 'pending')) {
                                    ?>
                                    <div class="rw">
                                        <div class="b-50">
                                            <a class="pure-button btn-tiny btn-expand-tiny btn-red" disabled>DELETE</a>
                                        </div>    
                                        <div class="b-50">
                                            <a class="pure-button btn-tiny btn-expand-tiny btn-green" disabled>EDIT</a>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                    else {
                                    ?>
                                    <div class="rw">
                                            <div class="b-50">
                                                <a class="edit-dayoff pure-button btn-tiny btn-expand-tiny btn-white" onclick="confirmation('<?php echo site_url('coach/day_off/edit/' . @$d->id);?>', 'group', 'Edit Day Off', 'list-dayoff', 'edit-dayoff');">EDIT</a>
                                            </div>    
                                            <div class="b-50">
                                                <a class="delete-dayoff pure-button btn-tiny btn-expand-tiny btn-white" onclick="confirmation('<?php echo site_url('coach/day_off/delete/' . @$d->id); ?>', 'group', 'Delete Day Off', 'list-dayoff', 'delete-dayoff');">DELETE</a>
                                            </div>
                                        </div>       
                                    <?php
                                    }
                                    ?>    
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>  
</div>
        <script type="text/javascript">
            $(document).ready(function () {

                function getDate(dates){
                var now = new Date(dates);
                var day = ("0" + (now.getDate() + 1)).slice(-2);
                var month = ("0" + (now.getMonth() + 1)).slice(-2);
                var resultDate = now.getFullYear() + "-" + (month) + "-" + (day);
                return resultDate;
                }

                function removeDatepicker(){
                    $('.datepicker2').datepicker('remove');
                }

                // datepicker
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    startDate: "now",
                    autoclose:true
                });
                //startDate: getDate(dates),
                $('.datepicker').change(function(){
                    var dates = $(this).val();
                    removeDatepicker();
                    $('.datepicker2').datepicker({
                        format: 'yyyy-mm-dd',
                        startDate: dates,
                        autoclose: true
                    });
                });

                $('#form-add').hide();
                $('#form-edit').hide();
                $('.cancle-manage').hide();

                $('.add-manage').click(function () {
                    $('.no-result').hide();
                    $('.add-manage').hide();
                    $('.cancle-manage').show();
                    $('#form-add').show();
                    $('.data-dayoff').hide();
                });

                $('.cancle-manage').click(function () {
                    $('.add-manage').show();
                    $('.no-result').show();
                    $('.cancle-manage').hide();
                    $('#form-add').hide();
                    $('#form-edit').hide();
                    $('.data-dayoff').show();

                    $('#start_date').val('');
                    $('#end_date').val('');
                    $('#remark').val('');
                    $('#form-add').parley.parsley().reset();

                });

                $('.list-dayoff').each(function () {
                    var dropdown = $(this);
                    $('.delete-day-off', dropdown).click(function (event) {
                        event.preventDefault();
                        var href = this.href;
                        alertify.dialog('confirm').set({
                            'title': 'Delete Day Off',
                        });
                        alertify.confirm("Are you sure?", function (e) {
                            if (e) {
                                window.location.href = href;
                            }
                        });
                    });
                });

                $('a.edit-dayoff').click(function(){
                    return false;
                });

                $('a.delete-dayoff').click(function() {
                    return false;
                })

                $('#start_date').change(function(){
                    $('#start_date').parsley().reset();
                });

                $('#end_date').change(function(){
                    $('#end_date').parsley().reset();
                });

            })
        </script>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".listTop > a").prepend($("<span/>"));
                $(".listBottom > a").prepend($("<span/>"));
                $(".listTop, .listBottom").click(function(event){
                 event.stopPropagation(); 
                 $(this).children("ul").slideToggle();
               });
            });
        </script>
        <script type="text/javascript">
    $(document).ready(function () {

        function getDate(dates){
        var now = new Date(dates);
        var day = ("0" + (now.getDate() + 1)).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var resultDate = now.getFullYear() + "-" + (month) + "-" + (day);
        return resultDate;
        }

        function removeDatepicker(){
            $('.datepicker2').datepicker('remove');
        }

        // datepicker
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            startDate: "now",
            autoclose:true
        });
        //startDate: getDate(dates),
        $('.datepicker').change(function(){
            var dates = $(this).val();
            removeDatepicker();
            $('.datepicker2').datepicker({
                format: 'yyyy-mm-dd',
                startDate: dates,
                autoclose: true
            });
        });

        $('#form-add').hide();
        $('#form-edit').hide();
        $('.cancle-manage').hide();

        $('.add-manage').click(function () {
            $('.no-result').hide();
            $('.add-manage').hide();
            $('.cancle-manage').show();
            $('#form-add').show();
            $('.data-dayoff').hide();
        });

        $('.cancle-manage').click(function () {
            $('.add-manage').show();
            $('.no-result').show();
            $('.cancle-manage').hide();
            $('#form-add').hide();
            $('#form-edit').hide();
            $('.data-dayoff').show();

            $('#start_date').val('');
            $('#end_date').val('');
            $('#remark').val('');
            $('#form-add').parley.parsley().reset();

        });

        $('.list-dayoff').each(function () {
            var dropdown = $(this);
            $('.delete-day-off', dropdown).click(function (event) {
                event.preventDefault();
                var href = this.href;
                alertify.dialog('confirm').set({
                    'title': 'Delete Day Off',
                });
                alertify.confirm("Are you sure?", function (e) {
                    if (e) {
                        window.location.href = href;
                    }
                });
            });
        });

        $('a.edit-dayoff').click(function(){
            return false;
        });

        $('a.delete-dayoff').click(function() {
            return false;
        })

        $('#start_date').change(function(){
            $('#start_date').parsley().reset();
        });

        $('#end_date').change(function(){
            $('#end_date').parsley().reset();
        });

    })
</script>
<!--        <a href="https://idbuild.id.dyned.com/live_v20/index.php/lang_switch/switch_language/english">English</a>
        <a href="https://idbuild.id.dyned.com/live_v20/index.php/lang_switch/switch_language/traditional-chinese">Chinese</a>-->
