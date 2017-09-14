<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Regional Settings</h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <div class="pure-u-1 tab-list tab-link">
            <ul>
                <li class="current"><a href="" class="active">Spesific Setting</a></li>
                <li><a href="">Default Setting</a></li>
            </ul>
        </div>    
    </div>
    <div class="content padding0">
        <div class="box list-regions">
        	<div class="b-pad">
            <table id="tab2" class="table-session">
                <thead>
                    <tr>
                        <th class="padding15">REGIONAL</th>
                        <th class="padding15">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                	<tr>
                        <td class="padding15" data-label="REGIONAL">
                            <span class="text-cl-secondary">Regional A</span>
                        </td>
                        <td class="padding15">
                        	<div class="rw">
                        		<div class="b-50">
									<a onclick="confirmation('', 'group', 'Edit Region Setting', 'list-regions', 'region-setting');" class="pure-button btn-small btn-white region-setting">EDIT</a>
                        		</div>
                        		<div class="b-50">
									<a disabled class="pure-button btn-small btn-white">RESET SETTING</a>
                        		</div>
                        	</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="padding15" data-label="REGIONAL">
                            <span class="text-cl-secondary">Regional B</span>
                        </td>
                        <td class="padding15">
                        	<div class="rw">
                        		<div class="b-50">
									<a onclick="confirmation('', 'group', 'Edit Region Setting', 'list-regions', 'region-setting');" class="pure-button btn-small btn-white region-setting">EDIT</a>
                        		</div>
                        		<div class="b-50">
									<a onclick="confirmation('', 'group', 'Edit Region Setting', 'list-regions', 'reset-setting');" class="pure-button btn-small btn-white reset-setting">RESET SETTING</a>
                        		</div>
                        	</div>
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
		$('.region-setting').click(function(){
			return false
		})
		$('.reset-setting').click(function(){
			return false
		})
	});
</script>