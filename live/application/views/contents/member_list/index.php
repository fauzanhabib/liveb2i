<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    /**
     * Link
     * @var array
    */

echo("<br>Member List<br><br>");
?>
<table>
    <tr>
        <td>
            <?php echo anchor(base_url().'index.php/partner/member_list/student',"Student Member");?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo anchor(base_url().'index.php/partner/member_list/coach',"Coach Member");?>
        </td>
    </tr>
</table>