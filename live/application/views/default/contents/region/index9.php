<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Token Histories</h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <div class="pure-u-1 tab-list tab-link">
            <ul>
                <li class="current"><a href="" class="active">Token Histories</a></li>
                <li><a href="">Request Token</a></li>
            </ul>
        </div>
    </div>
    <div class="content tab-content padding0">
        <div id="tab1" class="tab active">
            
            <div class="tab-edited m-b-0 padding15">
                <a href="#" class="link-filter">Please select date to filter <i class="icon icon-arrow-down"></i></a>
                <?php 
                echo form_open('', 'class="pure-form filter-form" style="border:none"'); 
                ?>
                <div class="pure-g">
                    <div class="pure-u-1">
                        <div class="frm-date" style="display:inline-block">
                            <input name="date_from" class="datepicker frm-date margin0" type="text" readonly="" placeholder="Start Date">  
                            <span class="icon icon-date"></span>
                        </div>
                        <span class="to" style="font-size: 16px;margin:0px 10px;">to</span>  
                        <div class="frm-date" style="display:inline-block">
                            <input name="date_to" class="datepicker2 frm-date margin0" type="text" readonly="" placeholder="End Date">  
                            <span class="icon icon-date"></span>
                        </div>
                        <?php echo form_submit('__submit', 'Go','class="pure-button btn-small btn-primary" style="margin:0px 10px;"'); ?>
                    </div>
                </div>
                <?php echo form_close(); ?>

            </div>
            
            <div class="b-pad">
            <table id="tab2" class="table-session">
                <thead>
                    <tr>
                        <th class="padding15">TRANSACTION</th>
                        <th class="padding15">DESCRIPTION</th>
                        <th class="padding15">TOKEN</th>
                        <th class="padding15">STATUS</th>
                        <th class="padding15">BALANCE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="padding15" data-label="TRANSACTION">
                        	<span>November, 19 2015 17:03:29</span>
                        </td>
                        <td class="padding15" data-label="DESCRIPTION">
                            <span>Token for WEBI at 2015-09-30 06:00 until 06:20.</span>
                        </td>
                        <td class="padding15" data-label="TOKEN">
                            <span>5</span>
                        </td>
                        <td class="padding15" data-label="STATUS">
                            <span class="labels Book tooltip-bottom" style="width:75px">Booked</span>
                        </td>
                        <td class="padding15" data-label="BALANCE">
                            655
                        </td>    
                    </tr>
                    <tr>
                        <td class="padding15" data-label="TRANSACTION">
                        	<span>November, 19 2015 17:03:29</span>
                        </td>
                        <td class="padding15" data-label="DESCRIPTION">
                            <span>Token for WEBI at 2015-09-30 06:00 until 06:20.</span>
                        </td>
                        <td class="padding15" data-label="TOKEN">
                            <span>5</span>
                        </td>
                        <td class="padding15" data-label="STATUS">
                            <span class="labels Book tooltip-bottom" style="width:75px">Booked</span>
                        </td>
                        <td class="padding15" data-label="BALANCE">
                            655
                        </td>    
                    </tr>
                </tbody>
            </table>
            </div>
            <div class="height-plus"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {

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
	});    

</script>