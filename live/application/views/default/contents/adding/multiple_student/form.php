<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Add Multiple Student</h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <div class="pure-u-1 edit no-left">
            <a href="#" class="add"><i class="icon icon-add"></i> Add New Student</a>
            <a href="#" class="add"><i class="icon icon-add"></i> Add Multiple Student</a>
        </div>
        <!-- end block edit -->
    </div>

    <div class="content">
        <div class="box">
            <form action="" role="form" class="pure-form pure-form-aligned" method="post" accept-charset="utf-8">            
                <fieldset>
                    <div class="pure-control-group">
                        <div class="label" style="vertical-align: top;">
                            <label for="email">File</label>
                        </div>
                        <div class="input">
                            <input type="file" class="pure-input-1-2" /> 
                             <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit, autem ut magnam assumenda beatae praesentium</p>
                        </div>                       
                        </div>
                    </div>
                    <div class="pure-control-group" style="border-top:1px solid #f3f3f3;padding: 15px 0px;">
                        <div class="label">
                            <input type="submit" name="__submit" value="SUBMIT" class="pure-button btn-small btn-primary" /> 
                            <a href="#" class="pure-button btn-red btn-small approve-user delete_match">CANCEL</a>

                    </div>
                </fieldset>
            </form> 
        </div>
    </div>
</div>				

<script>
    $(function (){
        var now = new Date();
        var day = ("0" + (now.getDate())).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var resultDate = now.getFullYear() + "-" + (month) + "-" + (day);
        
        $('.datepicker').datepicker({
            endDate: resultDate,
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
        $(".datepicker").datepicker("setDate", '1990-01-01');
    });
</script>