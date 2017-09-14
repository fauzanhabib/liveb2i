<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<br>
<div>
    <table border="1">
        <tr>
            <th>Affiliate</th>
            <th>Address</th>
        </tr>
        <?php foreach (@$partners as $partner) { ?>
            <tr>
                <td><a href="coach/<?php echo $partner->id?>" ><?php echo($partner->name); ?></a></td>
                <td><?php echo($partner->address); ?></td>
            </tr>
        <?php } ?>        
    </table>
</div>

<script>
    $(function () {
        var now = new Date();
        past = new Date(now.getFullYear(), now.getMonth() - 1, 1);
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd', 
            maxDate: new Date, 
            minDate: past
        });
    });
</script>