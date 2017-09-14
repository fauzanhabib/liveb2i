<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Manage Partners</h1>
</div>

<div class="box">
    <div class="heading pure-g"></div>

    <div class="content padding0">
        <div class="box list-admins">
        	<div class="b-pad">
            <table id="tab2" class="table-session">
                <thead>
                    <tr>
                        <th class="padding15">USERNAME</th>
                        <th class="padding15">PARTNER</th>
                        <th class="padding15">STATUS</th>
                        <th class="padding15">ACTOION</th>
                    </tr>
                </thead>
                <tbody>
                	<tr>
                        <td class="padding15" data-label="USERNAME">
                            <span class="text-cl-secondary">darmian@dyned.com</span>
                        </td>
                        <td class="padding15" data-label="PARTNER">
                            WEBI
                        </td>
                        <td class="padding15" data-label="STATUS">
                            <span class="labels pending" style="width:75px">Deactivated</span>
                        </td>
                        <td class="padding15">
                        	<div class="rw">
								<a onclick="confirmation('', 'group', 'Activate Admin', 'list-admins', 'admin-active');" class="pure-button btn-small btn-white b-100 admin-active" style="width:85px">Activate</a>
                        	</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="padding15" data-label="USERNAME">
                            <span class="text-cl-secondary">darmian@dyned.com</span>
                        </td>
                        <td class="padding15" data-label="PARTNER">
                            WEBI
                        </td>
                        <td class="padding15" data-label="STATUS">
                            <span class="labels active" style="width:75px">Activate</span>
                        </td>
                        <td class="padding15">
                        	<div class="rw">
								<a onclick="confirmation('', 'group', 'Deactivate Admin', 'list-admins', 'admin-deactivated');" class="pure-button btn-small btn-white b-100 admin-deactivated" style="width:85px">Deactivated</a>
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
		$('.admin-active').click(function(){
			return false
		})
		$('.admin-deactivated').click(function(){
			return false
		})
	});
</script>