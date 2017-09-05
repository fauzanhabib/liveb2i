<style type="text/css">
/*
.star-rating{
  font-size:0;
  white-space:nowrap;
  display:inline-block;
  width:250px;
  height:50px;
  overflow:hidden;
  position:relative;
  background:
      url('data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB3aWR0aD0iMjBweCIgaGVpZ2h0PSIyMHB4IiB2aWV3Qm94PSIwIDAgMjAgMjAiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDIwIDIwIiB4bWw6c3BhY2U9InByZXNlcnZlIj48cG9seWdvbiBmaWxsPSIjREREREREIiBwb2ludHM9IjEwLDAgMTMuMDksNi41ODMgMjAsNy42MzkgMTUsMTIuNzY0IDE2LjE4LDIwIDEwLDE2LjU4MyAzLjgyLDIwIDUsMTIuNzY0IDAsNy42MzkgNi45MSw2LjU4MyAiLz48L3N2Zz4=');
  background-size: contain;
  i{
    opacity: 0;
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 20%;
    z-index: 1;
    background: 
        url('data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB3aWR0aD0iMjBweCIgaGVpZ2h0PSIyMHB4IiB2aWV3Qm94PSIwIDAgMjAgMjAiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDIwIDIwIiB4bWw6c3BhY2U9InByZXNlcnZlIj48cG9seWdvbiBmaWxsPSIjRkZERjg4IiBwb2ludHM9IjEwLDAgMTMuMDksNi41ODMgMjAsNy42MzkgMTUsMTIuNzY0IDE2LjE4LDIwIDEwLDE2LjU4MyAzLjgyLDIwIDUsMTIuNzY0IDAsNy42MzkgNi45MSw2LjU4MyAiLz48L3N2Zz4=');  
    background-size: contain;
  }
  input{ 
    -moz-appearance:none;
    -webkit-appearance:none;
    opacity: 0;
    display:inline-block;
    width: 20%;
    height: 100%; 
    margin:0;
    padding:0;
    z-index: 2;
    position: relative;
    &:hover + i,
    &:checked + i{
      opacity:1;    
    }
  }
  i ~ i{
    width: 40%;
  }
  i ~ i ~ i{
    width: 60%;
  }
  i ~ i ~ i ~ i{
    width: 80%;
  }
  i ~ i ~ i ~ i ~ i{
    width: 100%;
  }
}

//JUST COSMETIC STUFF FROM HERE ON. THESE AREN'T THE DROIDS YOU ARE LOOKING FOR: MOVE ALONG. 

//just styling for the number
.choice{
  position: fixed;
  top: 0;
  left:0;
  right:0;
  text-align: center;
  padding: 20px;
  display:block;
}

//reset, center n shiz (don't mind this stuff)
*, ::after, ::before{
  height: 100%;
  padding:0;
  margin:0;
  box-sizing: border-box;
  text-align: center;  
  vertical-align: middle;
}
body{
  font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", 
  Helvetica, Arial, "Lucida Grande", sans-serif;
  &::before{
    height: 100%;
    content:'';
    width:0;
    background:red;
    vertical-align: middle;
    display:inline-block;
  }
}*/
</style>

<!--<span class="star-rating">
  <input type="radio" name="rating" value="1"><i></i>
  <input type="radio" name="rating" value="2"><i></i>
  <input type="radio" name="rating" value="3"><i></i>
  <input type="radio" name="rating" value="4"><i></i>
  <input type="radio" name="rating" value="5"><i></i>
</span>
<strong class="choice">Choose a rating</strong>-->

<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo("<br>Rate coach<br><br>");
?>

<table border="1">
    <tr>
        <th>No</th>
        <th>Coach</th>
        <th>Date Appointment</th>
        <th>Start_time</th>
        <th>End Time</th>
        <th>Rating</th>
        <th>Action</th>
    </tr>
    <?php $i =1; foreach(@$data as $d){ ?>
	<tr>
            <td><?php echo($i++);?></td>
            <td><?php echo @$d->fullname; ?></td>
            <td><?php echo @$d->date; ?></td>
            <td><?php echo @$d->start_time; ?></td>
            <td><?php echo @$d->end_time; ?></td>
            <td><?php echo (@round($rating[@$d->coach_id],2) == 0 ? ('No rating yet') : @round($rating[@$d->coach_id],2)); ?></td>
            <td> <a href="<?php echo site_url('student/rate_coaches/rate/' .@$d->id.'/'.@$d->coach_id ); ?>" onclick=" return confirm('Rate coach for this session?');"> Rate Coach for this session </a> </td>
        </tr>
    <?php } ?>
</table>


<script Language="JavaScript">
//    $(':radio').change(
//  function(){
//    $('.choice').text( this.value + ' stars' );
//  } 
//)
</script>
