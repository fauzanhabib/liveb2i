<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    /**
     * Link
     * @var array
    */

echo("<br>Add Member<br><br>");
?>
<table>
    <tr>
        <td>
            <?php echo anchor(base_url().'index.php/partner/adding/student',"Add Student");?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo anchor(base_url().'index.php/partner/adding/coach',"Add Coach");?>
        </td>
    </tr>
</table>