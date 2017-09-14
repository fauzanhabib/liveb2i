<div class="heading text-cl-primary padding15">
    <h1 class="margin0">James</h1>
</div>


<div class="box b-f3-1">
    
    <div class="content">
        
        <div class="pure-g">
            
            <div class="pure-u-8-24 profile-image text-center divider-right">
                <div class="thumb-small">
                    <img src="http://idbuild.id.dyned.com/live/uploads/images/prifile11.jpg" class="pure-img fit-cover preview-image m-b-10">
                    <span class="text-cl-secondary font-14">Coach</span><br>
                    <span class="font-12">Jakarta, Indonesia</span>
                </div>
            </div>

            <div class="pure-u-16-24 profile-detail prelative">
                
                <div class="heading m-b-15">
                    <div class="pure-u-1">
                        <h3 class="h3 font-normal padding-b-15 text-cl-secondary">MY ACTIVITY</h3>
                    </div>
                </div>
                
                        <div class="date pure-form pure-g">
                            <div class="pure-u-2-5" style="width:52%">
                                <div class="frm-date" style="display:inline-block">
                                    <input class="date_available datepicker frm-date" type="text" name="11" readonly="" style="width:125px;">
                                    <span class="icon icon-date active-schedule" style="left: 99px;"></span>
                                </div>
                                <div class="selected-date margin0 text-cl-secondary" style="display:inline-block">Tuesday, August 25, 2015</div>
                            </div>
                            <div class="pure-u-3-5 text-right" style="width:48%">
                                <button class="weekly_schedule padding0">WEEKLY SCHEDULE</button>
                            </div>
                        </div>
                        <form class="pure-form">
                            <div class="list-schedule" style="color:#939393;height: 150px;overflow-y: scroll;margin-top:10px;">
                                
                                <table class="tbl-booking" style="border-top:1px solid #f3f3f3">
                                    <thead>
                                        <tr>
                                            <th class="text-center padding-tb-5">START TIME</th>
                                            <th class="text-center padding-tb-5">END TIME</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        for ($i=0; $i < 10; $i++) { 
                                        ?>
                                        
                                        <tr>
                                            <td class="text-center">10:00</td>
                                            <td class="text-center">10:20</td>
                                            <td>
                                                <a href="#" class="pure-button btn-small btn-white" style="margin-right:30px">Book</a>
                                            </td>
                                        </tr>

                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>

            </div>

        </div>

    </div>
    
</div>

<div class="box">

    <div class="pure-g">
        
        <div class="pure-u-1">
            <div class="heading">
                <div class="pure-u-1">
                    <h3 class="h3 font-normal padding15 text-cl-secondary">MORE INFO</h3>
                </div>
            </div>

            <div class="content">
                <table class="table-no-border2"> 
                    <tbody>
                        <tr>
                            <td class="pad15">Skype ID</td>
                            <td>
                                ponel.panjaitan
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Email</td>
                            <td>
                                jurgen_james@webi.com
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Timezone</td>
                            <td>
                                (GMT +2.00)
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Spoken Language</td>
                            <td>
                                Germany
                            </td>
                        </tr>
                    </tbody>    
                </table>
            </div>
        </div> 
        
        <div class="pure-u-1">
            <div class="heading">
                <div class="pure-u-1">
                    <h3 class="h3 font-normal padding15 text-cl-secondary">EDUCATION</h3>
                </div>
            </div>

            <div class="content">
                <table class="table-no-border2"> 
                    <tbody>
                        <tr>
                            <td class="pad15">Higher Education</td>
                            <td class="pad15">
                                <span class="r-only block-school">Lorem</span>
                                <span>1998 to 1999</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Undergraduate</td>
                            <td class="pad15">
                                <span class="r-only block-school">Lorem</span>
                                <span>1998 to 1999</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Masters</td>
                            <td class="pad15">
                                <span class="r-only block-school">Lorem</span>
                                <span>1998 to 1999</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Phd</td>
                            <td class="pad15">
                                <span class="r-only block-school">Lorem</span>
                                <span>1998 to 1999</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Teaching Credintial</td>
                            <td>
                                Lorem
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Dyned English Certification</td>
                            <td>
                                lorem
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Year of Experience</td>
                            <td>
                                Lorem
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Specialization</td>
                            <td>
                                Lorem
                            </td>
                        </tr>
                    </tbody>    
                </table>
            </div>
        </div>    

    </div>

</div>

<script type="text/javascript">
    $(function(){
        var now = new Date();
        var day = ("0" + (now.getDate() + 2)).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var resultDate = now.getFullYear() + "-" + (month) + "-" + (day);

        $('.datepicker').datepicker({
            startDate: resultDate,
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
    })
</script>