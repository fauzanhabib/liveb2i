<div class="heading text-cl-primary padding15">
    <h1 class="margin0"><small>Booking Summary for <?php echo($data_student[0]->fullname); ?></small></h1>
</div>

<div class="box">
    <div class="heading pure-g" style="border-top: 2px solid #f3f3f3;">
        <div class="pure-u-1">
            <h3 class="h3 font-normal padding15 text-cl-secondary">BOOKING 1</h3>
        </div>
    </div>

    <div class="content">
        <div class="box pure-g">
            <table class="table-no-border2" style="border-collapse: separate;border-spacing: 0px 10px;padding:0">
                <tr>
                    <td>Coach Name</td>
                    <td><?php echo($data_coach[0]->fullname); ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?php echo($data_coach[0]->email); ?></td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td><?php echo(date('l jS \of F Y', @$date)); ?></td>
                </tr>
                <tr>
                    <td>Start Time</td>
                    <td><?php echo($start_time); ?></td>
                </tr>
                <tr>
                    <td>End Time</td>
                    <td><?php echo($end_time); ?></td>
                </tr>
                <tr>
                    <td>Token Cost</td>
                    <td><?php echo($data_coach[0]->token_for_student); ?></td>
                </tr>
                <tr>
                    <td style="border-top:1px solid #f3f3f3">
                        <button type="submit" id="submit_summary" class="pure-button btn-normal btn-primary" style="margin-right:20px;border:1px solid transpert">SUBMIT</button>
                    </td>
                    <td style="border-top:1px solid #f3f3f3;border-bottom:0px">
                        <button type="submit" id="cancel_summary" class="pure-button btn-normal btn-white" style="margin:0">BACK</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script>
    document.getElementById("submit_summary").onclick = function () {
        location.href = "<?php echo site_url('student_partner/schedule/book_single_coach/' . $data_student[0]->id .'/'. $data_coach[0]->id . '/' . $date . '/' . $start_time . '/' . $end_time); ?>";
    };

    document.getElementById("cancel_summary").onclick = function () {
        location.href = "<?php echo site_url('student_partner/schedule/create/' . $data_student[0]->id) ; ?>";
    };
</script>