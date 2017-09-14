<script src='//static.opentok.com/v2/js/opentok.min.js'></script> 
<script charset="utf-8">
    var apiKey = '45552832';
    var sessionId = '<?php echo $sessionId ?>';
    var token = '<?php echo $token ?>';
    var session = OT.initSession(apiKey, sessionId);
    //Self
        session.connect(token, function(error) {
            var publisher = OT.initPublisher('myPublisherElementId',
                                 {insertMode: 'append',
                                  width: '100%',
                                  height: 200, name: "<?php echo $this->auth_manager->get_name();?>"});
            session.publish(publisher);
		});
    //Other
        session.on('streamCreated', function(event) {
            var subscriberProperties = {insertMode: 'append',
                                        width: '100%',
                                        height: 500, name: "<?php echo $this->auth_manager->get_name();?>"};
            var subscriber = session.subscribe(event.stream,
            'subscriberContainer',
            subscriberProperties,
            function (error) {
              if (error) {
                console.log(error);
              } else {
                console.log('Subscriber added.');
              }
              });
        });
</script>
<style>
  .publisher {
      position: absolute;
    }
  .subscriber {
  top: 0;
  left: 0;
  width: 100%;
  height: 40em;
  z-index: 100;
}
.publisher {
  bottom: 15em;
  right: 1.6em;
  width: 20%;
  z-index: 200;
  border: 3px solid white;
  border-radius: 3px;
}
    
@media only screen and (max-width: 768px) {
    .publisher {
        width: 70%;
        right: 3.4em;
        bottom: 14.5em;
    }
}
</style>
<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Opentok - Coach</h1>
</div>

    <div class="content padding0">
        <div class="b-pad">
        <!--<table class="table-session tbl-manage-class">
            <thead>
                <tr>
                    <th class="padding15">Session ID</th>
                </tr>
            </thead>
            <tbody class="manage-class">
                <tr>
					<td class="padding15"><?php /* echo $sessionId */ ?></td>
				</tr>
            </tbody>
        </table>
		<table class="table-session tbl-manage-class">
            <thead>
                <tr>
                    <th class="padding15">Tokens ID</th>
                </tr>
            </thead>
            <tbody class="manage-class">
                <tr>
					<td class="padding15"><?php echo $token ?></td>
				</tr>
            </tbody>
        </table>-->
		
		</div>
	</div>
<div class="box b-f3-1">
	<div class="content padding15">
		<h2>Live Session</h2>
		<h4>Student: <?php echo $this->auth_manager->get_name();?></h4>
		<hr>
		<div class="subscriber" id="subscriberContainer"></div>
		<div class="publisher" id="myPublisherElementId"></div>
		<!-- <div id="subscribers"><div id="publisher"></div></div> -->
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/js/skype-uri.js'); ?>"></script>