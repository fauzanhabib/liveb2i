<?php
    /**
     * Link
     * @var array
    */
$link = array(
            'education' => 'Education',
            'geography' => 'Home Town',
            'profile' => 'Profile',
            'social_media' => 'Social Media',
            'token' => 'Token',
        );

if($this->auth_manager->role()=='STD'){
    echo anchor(base_url().'index.php/student/find_coaches', 'Find Coaches | ');
    echo anchor(base_url().'index.php/student/manage_appointments', 'Manage Appointment | ');
    echo anchor(base_url().'index.php/student/histories/token', 'History Token | ');
    echo anchor(base_url().'index.php/student/rate_coaches', 'Rate Coach');
    echo anchor(base_url().'index.php/student/ongoing_session', (@$this->auth_manager->ongoing_session_student() > 0 ? (' | Ongoing Session ('.@$this->auth_manager->ongoing_session_student().')') : ' '));
    echo('<br><br>');
}
else if($this->auth_manager->role()=='CCH'){
    echo anchor(base_url().'index.php/coach/schedule', 'Schedule | ');
    echo anchor(base_url().'index.php/coach/histories/session', 'History Session | ');
    echo anchor(base_url().'index.php/coach/day_off', 'Day Off | ');
    echo anchor(base_url().'index.php/coach/upcoming_session', 'Upcoming Session');
    echo anchor(base_url().'index.php/coach/ongoing_session', (@$this->auth_manager->ongoing_session_coach() > 0 ? (' | Ongoing Session ('.@$this->auth_manager->ongoing_session_coach().')') : ' '));
    echo('<br><br>');
}
else if($this->auth_manager->role()=='PRT'){
    echo anchor(base_url().'index.php/partner/adding', 'Add Member | ');
    echo anchor(base_url().'index.php/partner/manage_coach_token_cost', 'Manage Coach Token Cost | ');
    echo anchor(base_url().'index.php/partner/member_list', 'Member List | ');
    echo anchor(base_url().'index.php/partner/approve_coach_day_off', 'Aprove Coach Day Off');
    echo('<br><br>');
}
else if($this->auth_manager->role()=='SPR'){
    echo anchor(base_url().'index.php/student_partner/adding', 'Add Class | ');
    echo anchor(base_url().'index.php/student_partner/managing', 'Manage Class');
    echo('<br><br>');
}
else if($this->auth_manager->role()=='ADM'){
    echo anchor(base_url().'index.php/admin/approve_user', 'Approve User | ');
    echo anchor(base_url().'index.php/admin/histories/partner', 'History Session of Coach | ');
    echo anchor(base_url().'index.php/admin/manage_partner', 'Manage Partner');
    echo('<br><br>');
}

echo("<br>IDENTITY<br><br>");
?>
<table>
<?php foreach($identity as $i ){ ?>
    <tr>
        <td>
            <?php echo anchor(base_url().'index.php/account/identity/detail/'.$i, $link[$i]);?>
        </td>
    </tr>
<?php } ?>
</table>