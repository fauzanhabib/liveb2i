<style type="text/css">
    .coachlate{
        padding: 2px;
        background: #e74c3c;
        border-radius: 5px;
        color: white;
    }
    .success{
        padding: 4px;
        background: #59ba82;
        border-radius: 5px;
        color: white;
    }
    .bordered-l{
        border-left: 1px solid #fff !important;
        border-top: 1px solid #fff !important;
    }
    .bordered-m{
        border-left: 1px solid #fff !important;
        border-right: 1px solid #fff !important;
        border-top: 1px solid #fff !important;
    }
    .bordered-r{
        border-top: 1px solid #fff !important;
    }
</style>

<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Token History</h1>
</div>
<div class="box">
    <div class="heading pure-g">
        <div class="pure-u-1 tab-list tab-link">
            <ul>
                <li class="current"><a href="<?php echo site_url('coach/token'); ?>" class="active">Token History</a></li>
                <li><a href="<?php echo site_url('coach/token_withdrawals'); ?>">Token Withdrawal</a></li>
            </ul>
        </div>
    </div>
    <div class="content tab-content padding0">
        <div id="tab1" class="tab active">
            <?php
            if(!@$token_hist){
                echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
            }
            else {
            ?>
            <div class="b-pad">
            <br>
            <script>
                $(document).ready(function() {
                    $('#tab2').DataTable( {
                      "bLengthChange": false,
                      "searching": false,
                      "userTable": false,
                      "bInfo" : false,
                      "bPaginate": true,
                      "ordering": false
                    });
                } );
            </script>
            <table id="tab2" class="table-session">
                <thead style="background-color: #2b89b9;color: white;">
                    <tr>
                        <th rowspan="2" class="padding15">TRANSACTION</th>
                        <th rowspan="2" class="padding15">TIME</th>
                        <th rowspan="2" class="padding15">STUDENT</th>
                        <th rowspan="2" class="padding15">STATUS</th>
                        <th colspan="3" class="padding15">TOKEN</th>
                        <!-- <th class="padding15">BALANCE</th> -->
                    </tr>
                    <tr>
                        <td class="font-16 bordered-l text-center">DEBIT</td>
                        <td class="font-16 bordered-m text-center">CREDIT</td>
                        <td class="font-16 bordered-r text-center">BALANCE</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (@$token_hist as $history) { 
                        $gmt_user = $this->identity_model->new_get_gmt($this->auth_manager->userid());
                        $new_gmt = 0;
                                if($gmt_user[0]->gmt > 0){
                                    $new_gmt = '+'.$gmt_user[0]->gmt;
                                }else{
                                    $new_gmt = $gmt_user[0]->gmt;    
                                }
                        ?>
                        <tr>
                            <td class="padding15" data-label="TRANSACTION">
                            	<span><?php echo($history->date); ?></span>
                            </td>
                            <td class="padding15" data-label="DESCRIPTION">
                                <span><?php echo($history->time);?> (UTC <?php echo $new_gmt;?>)</span>
                            </td>
                            <td class="padding15" data-label="TOKEN">
                                <span><?php echo($history->student_name); ?></span>
                            </td>
                            <td>
                            <?php if($history->token_amount == 0){ ?>
                                <font class="coachlate">Coach is late</font>
                            <?php } else { ?>
                                <font class="success">Successful</font>
                            <?php } ?>
                            </td>
                            <td class="padding15" data-label="DEBIT">
                                <!-- <span><?php echo($history->token_amount); ?></span> -->
                            </td>
                            <td class="padding15" data-label="CREDIT">
                                <span><?php echo($history->token_amount); ?></span>
                            </td>  
                            <td class="padding15" data-label="BALANCE">
                                <?php echo($history->upd_token); ?>
                            </td>   
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
            <?php } ?>
            <?php echo @$pagination;?>
            <div class="height-plus"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {

        /**

        $('.btn-next').hover(function () {
            $('.icon-next').css({"background": "url(assets/icon/arrow-right-blue.png)"});
        }, function () {
            $('.icon-next').css({"background": "url(assets/icon/arrow-right.png)"});
        });

        $('.btn-prev').hover(function () {
            $('.icon-prev').css({"background": "url(assets/icon/arrow-left-blue.png)"});
        }, function () {
            $('.icon-prev').css({"background": "url(assets/icon/arrow-left.png)"});
        });

        $('.box .tab-link a').click(function (e) {
            var currentValue = $(this).attr('href');

            $('.box .tab-link a').removeClass('active');
            $('.tab').removeClass('active');

            $(this).addClass('active');
            $(currentValue).addClass('active');

            e.preventDefault();

        });

        **/

    });

    // ajax
    // don't cache ajax or content won't be fresh
    $.ajaxSetup({
        cache: false
    });

    // load() functions
    var loadUrl = "<?php echo site_url('student/histories/token'); ?>";
    $(".load_session_histories").click(function () {
        //var index = document.getElementById("loadbasic").value;
        //alert(this.value);
        $("#tab2").load(loadUrl, function () {
            $("#schedule-loading").hide();
        });
    });

    // load() functions
    // var from = document.getElementById("#date_from").value;

    $(".load_searched_session_histories").click(function () {
        var load_url_search = "<?php echo site_url('student/histories/search'); ?>" + "/" + document.getElementById('date_from').value + "/" + document.getElementById('date_to').value;
        $("#tab2").load(load_url_search, function () {
            $("#schedule-loading").hide();
        });
    });

    function date_from(val) {
        $("#date_from").val = val;
    }

    function date_to(val) {
        $("#date_to").val = val;
    }

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
        endDate: "now",
        autoclose:true
    });

    $('.datepicker').change(function(){
        var dates = $(this).val();
        removeDatepicker();
        $('.datepicker2').datepicker({
            format: 'yyyy-mm-dd',
            startDate: getDate(dates),
            endDate: "now",
            autoclose: true
        });
    });

    $('.height-plus').css({'height':'40px'});

</script>