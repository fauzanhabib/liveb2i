<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

/**
 * Class email_structure
 * Class library for common functions like generate random string, gender, etc
 * @author      Ponel Panjaitan <ponel@pistarlabs.co.id>
 */
class send_email {


var $tube = 'com.live.email';
    /**
     * var $ci
     * CodeIgniter Instance
     */
    private $CI;

    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->library('email');
        $this->CI->load->library('queue');
        $this->CI->load->library('email_structure');
    }

    function testinga(){

        // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
            // $this->CI->email->to($email);
            // $this->CI->email->bcc('test.soerbakti@gmail.com');
            // $this->CI->email->set_mailtype('html');

        // $content['content'] = $this->CI->email_structure->header()
        //         .$this->CI->email_structure->title('TES')
        //         .$this->CI->email_structure->content('TES CONTENT')
        //         .$this->CI->email_structure->footer('');

                    $content[] = array(
                        'subject' => 'test worker',
                        'email' => 'test.soerbakti@gmail.com'
                        );

                    $this->CI->queue->push($this->tube, $content, 'email.send_email');

                // if ($this->CI->email->send($id ='', $content = '')) {

                //     } else {
                //     echo $this->CI->email->print_debugger();
                //     }
            // }
    }

    function testing(){
        $year = date("Y");
                $email_body ='<html lang="en">

            <head>
                <meta http-equiv="content-type" content="text/html" ; charset="utf-8">
                <title>DynEd Live Email</title>


                <style type="text/css" media="screen">
                    @media screen {
                        h1,
                        h2,
                        h3 {}
                    }
                </style>

                <style type="text/css" media="screen">

                    /* Medium Screen */

                    @media only screen and (max-width: 660px) {
                        table[class="container"] {
                            width: 480px !important;
                        }
                        td[class="logo"] img {
                            display: block;
                            margin-left: auto!important;
                            margin-right: auto!important;
                        }
                    }
                    /* Small Screen*/

                    @media only screen and (max-width: 500px) {
                        table[class="container"] {
                            width: 320px !important;
                        }
                        td[class="logo"] img {
                            display: block;
                            margin-left: auto!important;
                            margin-right: auto!important;
                        }
                    }
                </style>
            </head>

            <body bgcolor="#fafafa">

                <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#fafafa">
                    <tr>
                        <td>
                            <table class="container" width="640px" align="center" border="0" cellpadding="0" cellspacing="0" style="margin-top: 20px ">
                                <tr>
                                    <td valign="top" class="logo" bgcolor="#ffffff" style="padding: 10px 20px 10px 20px; border: 0;">
                                        <a href="http://live.dyned.com"><img src="http://i1300.photobucket.com/albums/ag89/Devananda_Onta/logo__zpslfnzndsv.png" alt="logo" width="auto" height="30">
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" class="headline" bgcolor="#3199DD" style="padding: 30px 20px 10px 20px; border-left: 0; border-right: 0; font-family: Calibri, sans-serif; font-size: 24px; line-height: 22px; color: #ffffff;">
                                        Coach Created
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" class="headline" bgcolor="#ffffff" style="padding: 40px 20px 40px 20px; border-left: 0; border-right: 0; font-family: Calibri, sans-serif; font-size: 15px; line-height: 22px; color: #666666;">
                                        Hi Dude
                                        <br>
                                        Welcome to DynEd Live!
                                        <br>Your email has been added to DynEd Live, as Coach Once your Role is approved, you can sign in with below information.
                                        <br>
                                        <br>
                                        Email = alo@dyned.com
                                        <br>
                                        Password = abs
                                        <br><br><br>
                                        You can change your password under your profile page on DynEd Live.
                                        For more information, please ask the administrator. Thank you!
                                        <br><br> Best,
                                        <br> DynEd Live Teams
                                        <p style="font-family: Calibri, sans-serif; font-size: 13px; line-height: 12px; color: #CCCCCC;">
                                            This email can`t receive replies. For more information, visit the
                                            <a href="mailto:livesupport@dyned.com" style="text-decoration: none; color:#3EA6DD;">DynEd Live Help Center.</a>
                                        </p>
                                        <p style="font-family: Calibri, sans-serif; font-size: 13px; line-height: 12px; color: #CCCCCC;">
                                            You received this mandatory email service announcement to update you about important changes to your DynEd product or account.
                                        </p>

                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" class="footer" bgcolor="#3199DD" style="text-align:center; padding: 10px; font-family: Calibri, sans-serif; font-size: 12px; line-height: 12px; color: #ffffff;">
                                        DynEd Live - &copy; '.$year.' All Rights Reserved - DynEd International, Inc. 1350 Bayshore Highway, Suite 850. Burlingame, CA 94010
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>

            </body>

        </html>';
        // ======

        $tesworker = array(
            'subject' => 'Test Workera',
            'email' => 'test.soerbakti@gmail.com',
        );

        $tesworker['content'] = $email_body;

        $this->CI->queue->push($this->tube, $tesworker, 'email.send_email');
    }


    function add_user($email = '', $password='', $content='') {
        $year = date("Y");
        // Loads the email library
        // if ($content == 'add') {
        //         $data['subject'] = 'Add Region';
        //         $data['content'] = 'Your region has been created but still inactive.
        //                             Your password is '.$password;

        //         $this->CI->email->subject($data['subject']);
        //         $this->CI->email->message($data['content']);
        //         if ($this->CI->email->send($email = '', $password = '')) {
        //             echo "All OK";
        //             } else {
        //             echo $this->CI->email->print_debugger();

        //             }

        //     } else if ($content == 'cancelled') {

        //         $data['subject'] = 'Token Request ';
        //         $data['content'] = 'Your token request has been cancelled.
        //                             Amount : ';

        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($data['content']);
        //          if ($this->CI->email->send($id = '', $content = '')) {
        //             echo "All OK";
        //             } else {
        //             echo $this->CI->email->print_debugger();
        //             }
        //     }

        $email_add_reg = 'Your region has been created but still inactive.  Your password is '.$password;

        $addregion = array(
            'subject' => 'Add Region',
            'email' => $email,
        );

        $addregion['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Add Region
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Your region hass been created but still inactive. Your password is : '.$password.'
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>


                                                                <td>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $addregion, 'email.send_email');

    }

    function forgot_password($email='', $password=''){

        $email_new_pass = 'Your new password is '.$password;

        $forgotpass = array(
            'subject' => 'New Password',
            'email' => $email,
        );
        $year = date("Y");
        $forgotpass['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Forgot Password
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Your new password is : '.$password.'
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>


                                                                <td>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $forgotpass, 'email.send_email');

        // $this->CI->email->from('no-reply@dyned.com','no-reply');
        // $this->CI->email->to($email);
        // $this->CI->email->bcc('test.soerbakti@gmail.com');

        // $data['subject'] = 'New Password';
        // $data['content'] = 'Your new password is '.$password;

        // $this->CI->email->subject($data['subject']);
        // $this->CI->email->message($data['content']);
        // if ($this->CI->email->send()) {
        //     echo "All OK";

        // } else {
        //     echo $this->CI->email->print_debugger();

        // }

    }

    //----------------------------------------------------Create User ----------------------------------------------------------
    function create_user($email = '', $realpassword = '', $content = '', $fullname = '', $type='', $partner = '') {

        $isi = '';
        if($type == 'student') {
            $isi = 'Your email has been added to DynEd Live, under '.$partner.' as a '.ucfirst($type).' Role. You now can sign in with below information.';
        } else if ($type == 'coach') {
            $isi = 'Your email has been added to DynEd Live, under '.$partner.' as a '.ucfirst($type).' Role. Once your Role is approved, you can sign in with below information.';
        } else if ($type == 'Student Affiliate Monitor') {
            $isi = 'Your email has been added to DynEd Live, under '.$partner.' as a '.ucfirst($type).' Role. Once your Role is approved, you can sign in with below information.';
        }
        $year = date("Y");


            $create_user = array(
            'subject' => ucfirst($type).' Login Created',
            'email' => $email,
        );

        $create_user['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    '.ucfirst($type).' Login Created
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Hi '.$fullname.', <br><br>'.$isi.'

                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #ea5656; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Detail Account Information
                                                                </td>
                                                            </tr>
                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">


                                                                   Email : '.$email.'
                                                                   <br>
                                                                    Password : '.$realpassword.'
                                                                   <br>
                                                                   <br>
                                                                   You can change your password under your profile page on DynEd.<br><br>Best, <br>DynEd Live Teams.<br><br>
                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $create_user, 'email.send_email');

        //     $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($email);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');

        // if ($content == 'created') {
        //         $data['subject'] = ucfirst($type).' Created';
        //         //$data['content'] = 'Welcome to DynEd Live, account information: Email = ' . $email . ' Password = ' . $realpassword . ' If Super Admin Approve, you can login to DynEd Live as Partner Admin. For more information, please ask the administrator. Thank you';
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($email_body);
        //         if ($this->CI->email->send($id ='', $content = '')) {

        //             } else {
        //             echo $this->CI->email->print_debugger();
        //             }
        //     }

    }

    //----------------------------------------------------Notif Admin-----------------------------------------------------------
    function notif_admin($email = '', $realpassword = '', $content = '', $fullname = '', $type = '', $adminname = '') {
        $email_body ='New '.$type.' has been added by '.$type.' partner';

        $notifadmin = array(
            'subject' => 'New Coach Created',
            'email' => $email,
        );
        $year = date("Y");
        $notifadmin['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    New Coach Created
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Hi '.$adminname.'`s Admin,
                                                                    <br><br>
                                                                    A new coach '.$fullname.' has been created.
                                                                    <br><br>
                                                                    Please decide if you want to Approve/Decline it.
                                                                    <br><br>
                                                                    Best,
                                                                    <br>DynEd Live Teams.
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>


                                                                <td>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Approve/Reject</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $notifadmin, 'email.send_email');

        //     $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($email);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');

        // if ($content == 'created') {
        //         $data['subject'] = ucfirst($type).' Created';
        //         //$data['content'] = 'Welcome to DynEd Live, account information: Email = ' . $email . ' Password = ' . $realpassword . ' If Super Admin Approve, you can login to DynEd Live as Partner Admin. For more information, please ask the administrator. Thank you';
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($email_body);
        //         if ($this->CI->email->send($id ='', $content = '')) {

        //             } else {
        //             echo $this->CI->email->print_debugger();
        //             }
        //     }

    }

    //----------------------------------------------------Student Supplier Request Token ----------------------------------------------------------
    function send_student_supplier_request_token($email = '', $amount = '', $content = '', $fullname = '', $name_admin = ''){

        $isi = '';
        if($content == 'requested') {
            $isi = 'Hi '.$name_admin.'`s Admin, <br><br>'.$fullname.' has requested additional '.$amount.' tokens.';
        } else if ($content == 'cancelled') {
            $isi = 'Hi '.$name_admin.'`s Admin, <br><br>'.$fullname.' has cancelled '.$amount.' tokens.';
        }
        $token_request_data = $this->CI->db->select('user_id, token_amount')->from('token_requests')->where('id', $id)->get();
        $year = date("Y");


             $sup_req_token = array(
            'subject' => 'Token Request from Student Partner',
            'email' => $email,
        );

        $sup_req_token['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Token Request from Student Partner
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    '.$isi.'<br><br>Please decide if you want to Approve/Decline it
                                                                    <br><br>
                                                                    Best,
                                                                    <br>DynEd Live Teams
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>


                                                                <td>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Approve/Reject</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $sup_req_token, 'email.send_email');


            // $this->CI->email->from('no-reply@dyned.com','no-reply');
            // $this->CI->email->to($email);
            // $this->CI->email->bcc('test.soerbakti@gmail.com');
            // $this->CI->email->set_mailtype('html');

            //     // Loads the email library
            //     $data['subject'] = 'Token Request (Reminder)';


            //     $this->CI->email->subject($data['subject']);
            //     $this->CI->email->message($email_request);
            //     if ($this->CI->email->send($id, $content)) {
            //         echo "All OK";
            //     } else {
            //         echo $this->CI->email->print_debugger();
            //     }


    }

      //----------------------------------------------------Admin Region Approve Token ----------------------------------------------------------
    function send_region_approve_token($email = '', $content = '', $fullname = '', $token = ''){

        $isi = '';
        if ($content == 'approved') {
            $isi = 'Your request for '.$token.' tokens, has been approved.';
        }else if ($content == 'declined') {
            $isi = 'Your request for '.$token.' tokens, has been declined.';
        }
        $year = date("Y");



            $adreg_app_token = array(
            'subject' => 'Token Request' .ucfirst($content),
            'email' => $email,
        );

        $adreg_app_token['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Token Request '.ucfirst($content).'
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Hi '.$fullname.'`s Admin, <br><br>'.$isi.'
                                                                    <br><br>
                                                                    Best,
                                                                    <br>DynEd Live Teams.
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>


                                                                <td>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Go to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $adreg_app_token, 'email.send_email');

            // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
            // $this->CI->email->to($email);
            // $this->CI->email->bcc('test.soerbakti@gmail.com');
            // $this->CI->email->set_mailtype('html');

            //     $data['subject'] = 'Token Request '.ucfirst($content);
            //     $data['content'] = 'Your token request ' . $token . ' has been '.$content.' by Admin';
            //         $this->CI->email->subject($data['subject']);
            //         $this->CI->email->message($email_approve);
            //         if ($this->CI->email->send($token_request_id = '', $content = '')) {

            //         } else {
            //             echo $this->CI->email->print_debugger();
            //         }

    }

        //----------------------------------------------------Admin Region Request Token ----------------------------------------------------------
    function send_admin_request_token($email = '', $fullname = '', $token = '', $content = ''){

        $isi = '';
        if ($content == 'requested') {
            $isi = 'Hi '.$fullname.'`s Admin, <br><br>You have made a token request for '.$token.' tokens.';
        } else if ($content == 'cancelled') {
            $isi = 'Hi '.$fullname.'`s Admin, <br><br>You have cancelled a token request for '.$token.' tokens.';
        }

            $adreg_req_token = array(
            'subject' => 'Token Request',
            'email' => $email,
        );
        $year = date("Y");
        $adreg_req_token['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Token Request
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    '.$isi.'
                                                                    <br><br>Best,
                                                                    <br>DynEd Live Teams.
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>


                                                                <td>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Go to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $adreg_req_token, 'email.send_email');

        //     $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($email);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');

        // // Loads the email library

        //         $data['subject'] = 'Token Request (Reminder)';


        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($email_request);
        //             if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //                 echo $this->CI->email->print_debugger();
        //             }

    }

    //----------------------------------------------------Admin Region Approve Coach/Student ----------------------------------------------------------
    function admin_approval_user($email = '', $password = '', $content = '', $fullname = '', $role = ''){
        $isi = '';
        if ($content == 'approved') {
            $isi = 'Welcome to DynEd Live! "<br />"
            Account information: Email = '.$email.' has been activated. You may now login to DynEd Live as '.$role;
        } else if ($content == 'declined') {
            $isi = 'Sorry, account information: Email = '.$email.' Has been declined.';
        }

            $adreg_app_member = array(
            'subject' => $role.' Status '.ucfirst($content),
            'email' => $email,
        );
        $year = date("Y");
        $adreg_app_member['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    '.$role.' Status '.ucfirst($content).'
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Dear '.$fullname.', '.$isi.'
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>


                                                                <td>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $adreg_app_member, 'email.send_email');


            // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
            // $this->CI->email->to($email);
            // $this->CI->email->bcc('test.soerbakti@gmail.com');
            // $this->CI->email->set_mailtype('html');


            //     $data['subject'] = ''.$role.' Status '.ucfirst($content);
            //     //$data['content'] = 'Welcome to DynEd Live, account information: Email = ' . $email . ' Has been activated by Admin, you can login to DynEd Live as member. For more information, please ask the administrator. Thank you';
            //         $this->CI->email->subject($data['subject']);
            //         $this->CI->email->message($email_app_member);
            //         if ($this->CI->email->send($id ='', $content = '')) {

            //         } else {
            //             echo $this->CI->email->print_debugger();
            //         }


    }

    //----------------------------------------------------Notif Partner-------------------------------------------------------------------------
    function notif_partner($email = '', $password = '', $content = '', $fullname = '', $role = ''){
        $isi = '';
        if ($content == 'approved' && $role == 'coach') {
            $isi = 'New '.$role.' has been approved by admin';
        } else if ($content == 'declined' && $role == 'coach') {
            $isi = 'Sorry, account information: Email = '.$email.' Has been declined by admin.';
        } else if ($content == 'approved' && $role == 'student') {
            $isi = 'New '.$role.' has been added';
        }

        $emailnotif = $isi;

            $notifpartner = array(
            'subject' => $role.' Status '.ucfirst($content),
            'email' => $email,
        );
        $year = date("Y");
        $notifpartner['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    '.$role.' Status '.ucfirst($content).'
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    '.$isi.'
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>


                                                                <td>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $notifpartner, 'email.send_email');


            // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
            // $this->CI->email->to($email);
            // $this->CI->email->bcc('test.soerbakti@gmail.com');
            // $this->CI->email->set_mailtype('html');


            //     $data['subject'] = ''.$role.' Status '.ucfirst($content);
            //     //$data['content'] = 'Welcome to DynEd Live, account information: Email = ' . $email . ' Has been activated by Admin, you can login to DynEd Live as member. For more information, please ask the administrator. Thank you';
            //         $this->CI->email->subject($data['subject']);
            //         $this->CI->email->message($isi);
            //         if ($this->CI->email->send($id ='', $content = '')) {

            //         } else {
            //             echo $this->CI->email->print_debugger();
            //         }


    }

    //--------------------------------------------------------Notif Superadmin------------------------------------------------------------------
    function notif_superadmin($email = '', $realpassword = '', $content = '', $fullname = '', $partner = '', $partnermail = '', $tor = '', $type = ''){
        $isi = 'New supplier has been added by admin under '.$partner.'';


        $notifsuperadmin = array(
            'subject' => 'New '.$type.' registered',
            'email' => $email,
        );
        $year = date("Y");
        $notifsuperadmin['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    New '.$type.' registered
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Hi Superadmin,

                                                                    <br><br>A new '.ucfirst($tor).' login ('.$fullname.') has been created.

                                                                    <br><br>Please decide if you want to Approve/Decline it.

                                                                    <br><br>

                                                                    <br><br>Best,
                                                                    <br>DynEd Live Teams.
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Approve/Reject</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->
    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $notifsuperadmin, 'email.send_email');


            // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
            // $this->CI->email->to($this->email_superadmin);
            // $this->CI->email->bcc('test.soerbakti@gmail.com');
            // $this->CI->email->set_mailtype('html');


            //     $data['subject'] = ''.$role.' Status '.ucfirst($content);
            //     //$data['content'] = 'Welcome to DynEd Live, account information: Email = ' . $email . ' Has been activated by Admin, you can login to DynEd Live as member. For more information, please ask the administrator. Thank you';
            //         $this->CI->email->subject($data['subject']);
            //         $this->CI->email->message($isi);
            //         if ($this->CI->email->send($id ='', $content = '')) {

            //         } else {
            //             echo $this->CI->email->print_debugger();
            //         }


    }

        //----------------------------------------------------Admin Region Create Supplier ----------------------------------------------------------
    function admin_create_supplier($email = '', $realpassword = '', $content = '', $fullname = '', $partner = '', $tor = ''){



        $adreg_cre_sup = array(
            'subject' => ucfirst($tor).' login created',
            'email' => $email,
        );
        $year = date("Y");
        $adreg_cre_sup['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Supplier Created
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Hi '.$fullname.', <br><br>
                                                                    Your email has been added to DynEd Live, Under '.$partner.' as '.$tor.'`s Admin Role. Once your Role is approved, you can sign in with below information.
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #ea5656; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Detail Supplier Information
                                                                </td>
                                                            </tr>
                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">


                                                                   Email : '.$email.'
                                                                   <br>
                                                                    Password : '.$realpassword.'
                                                                   <br>
                                                                   <br>
                                                                   You can change your password under your profile page on DynEd Live.<br><br>Best, <br>DynEd Live Teams.<br><br>
                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $adreg_cre_sup, 'email.send_email');

        //     $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($email);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');

        // if ($content == 'created') {
        //         $data['subject'] = 'Partner Created';
        //         //$data['content'] = 'Welcome to DynEd Live, account information: Email = ' . $email . ' Password = ' . $realpassword . ' If Super Admin Approve, you can login to DynEd Live as Partner Admin. For more information, please ask the administrator. Thank you';
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($email_body);
        //         if ($this->CI->email->send($id ='', $content = '')) {
        //             } else {
        //             echo $this->CI->email->print_debugger();
        //             }
        //     }

    }

        //----------------------------------------------------Super Admin Region Create Region ----------------------------------------------------------
    function superadmin_edit_email_region($fullname='', $email = '', $pass_default = ''){
        //
        $super_cre_reg = array(
            'subject' => 'New Email Region',
            'email' => $email,
        );
        $year = date("Y");
        $super_cre_reg['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Region Created
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Hi '.$fullname.'`s Admin, <br>Welcome to DynEd Live!
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #ea5656; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    New email for '.$fullname.' region has been Changed. Login Information :
                                                                </td>
                                                            </tr>
                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">


                                                                   Email : '.$email.'
                                                                   <br>
                                                                    Password : '.$pass_default.'
                                                                   <br>
                                                                   Please change your password after login
                                                                   <br>
                                                                   <br>
                                                                   Best,
                                                                   <br>DynEd Live Teams.
                                                                   <br><br>
                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $super_cre_reg, 'email.send_email');

        //     $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($email);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');

        // if ($content == 'add') {
        //         $data['subject'] = 'Region Created';
        //         $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($email_body);
        //         if ($this->CI->email->send($content)) {

        //             } else {
        //             echo $this->CI->email->print_debugger();
        //             }
        //     } else if ($content == 'activate') {

        //         $data['subject'] = 'Region Activated';
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($email_activate);
        //          if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //             echo $this->CI->email->print_debugger();
        //             }
        //     }
    }

        //----------------------------------------------------Super Admin Region Create Region ----------------------------------------------------------
    function superadmin_create_region($fullname='', $email = '', $realpassword = '', $content = ''){

        $super_cre_reg = array(
            'subject' => 'Region Created',
            'email' => $email,
        );
        $year = date("Y");
        $super_cre_reg['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Region Created
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Hi '.$fullname.'`s Admin, <br>Welcome to DynEd Live!
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #ea5656; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    '.$fullname.' region has been created. Login Information :
                                                                </td>
                                                            </tr>
                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">


                                                                   Email : '.$email.'
                                                                   <br>
                                                                    Password : '.$realpassword.'
                                                                   <br>
                                                                   <br>
                                                                   Best,
                                                                   <br>DynEd Live Teams.
                                                                   <br><br>
                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $super_cre_reg, 'email.send_email');

        //     $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($email);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');

        // if ($content == 'add') {
        //         $data['subject'] = 'Region Created';
        //         $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($email_body);
        //         if ($this->CI->email->send($content)) {

        //             } else {
        //             echo $this->CI->email->print_debugger();
        //             }
        //     } else if ($content == 'activate') {

        //         $data['subject'] = 'Region Activated';
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($email_activate);
        //          if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //             echo $this->CI->email->print_debugger();
        //             }
        //     }
    }

    //----------------------------------------------------Region Admin Create Affiliate ----------------------------------------------------------
    function admin_create_affiliate($affiliate = '', $admin = '', $region = '', $email = ''){

        $adm_cre_aff = array(
            'subject' => 'Affiliate Created',
            'email' => $email,
        );
        $year = date("Y");
        $adm_cre_aff['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Affiliate Created
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Hi Superadmin!
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #ea5656; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    New affiliate has been created by '.$admin.'. Affiliate Information :
                                                                </td>
                                                            </tr>
                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">


                                                                   Name : '.$affiliate.'
                                                                   <br>
                                                                    Region : '.$region.'
                                                                   <br>
                                                                   <br>
                                                                   Best,
                                                                   <br>DynEd Live Teams.
                                                                   <br><br>
                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $adm_cre_aff, 'email.send_email');

        //     $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($email);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');

        // if ($content == 'add') {
        //         $data['subject'] = 'Region Created';
        //         $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($email_body);
        //         if ($this->CI->email->send($content)) {

        //             } else {
        //             echo $this->CI->email->print_debugger();
        //             }
        //     } else if ($content == 'activate') {

        //         $data['subject'] = 'Region Activated';
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($email_activate);
        //          if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //             echo $this->CI->email->print_debugger();
        //             }
        //     }
    }

        //----------------------------------------------------Super Admin Approve Supplier ----------------------------------------------------------
    function superadmin_approval_supplier($email = '', $content = '', $fullname = '', $type = ''){
        $isi = '';
        if($content == 'approved'){
            $isi = 'Welcome to DynEd Live!<br><br>Your login has been approved. Please sign in using the information from previous email';
        }  else if($content == 'declined'){
            $isi = 'Your login has been declined.';
        }

           $super_app_sup = array(
            'subject' => 'DynEd Live Login '.ucfirst($content),
            'email' => $email,
        );
        $year = date("Y");
        $super_app_sup['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    DynEd Live Login '.ucfirst($content).'
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Hi '.$fullname.', <br><br>'.$isi.'
                                                                    <br><br>Best,
                                                                    <br>DynEd Live Teams.
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>


                                                                <td>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Go to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $super_app_sup, 'email.send_email');

            // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
            // $this->CI->email->to($email);
            // $this->CI->email->bcc('test.soerbakti@gmail.com');
            // $this->CI->email->set_mailtype('html');


            //     $data['subject'] = 'Supplier '.ucfirst($content);
            //     //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
            //         $this->CI->email->subject($data['subject']);
            //         $this->CI->email->message($email_app_supplier);
            //         if ($this->CI->email->send($id = '', $content = '')) {

            //         } else {
            //             echo $this->CI->email->print_debugger();
            //         }



    }

    //----------------------------------------------------Notif Creator ----------------------------------------------------------
    function notif_creator($email = '', $content = '', $fullname = '', $emailcreator = '', $namecreator = ''){
        $isi = '';
        if($content == 'approved'){
            $isi = 'Hi '.$namecreator.'`s Admin, <br><br>'.$fullname.' has been approved.';
        }  else if($content == 'declined'){
            $isi = 'Hi '.$namecreator.'`s Admin, <br><br>'.$fullname.' has been declined.';
        }

        $emailnotif = $isi;

        $notifcreator = array(
            'subject' => 'Partner`s Login '.ucfirst($content),
            'email' => $emailcreator,
        );
        $year = date("Y");
        $notifcreator['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Partner`s Login '.ucfirst($content).'
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    '.$isi.'<br><br>
                                                                    Best,
                                                                    <br>DynEd Live Teams.
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>


                                                                <td>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $notifcreator, 'email.send_email');

            // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
            // $this->CI->email->to($emailcreator);
            // $this->CI->email->bcc('test.soerbakti@gmail.com');
            // $this->CI->email->set_mailtype('html');


            //     $data['subject'] = ;
            //     //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
            //         $this->CI->email->subject($data['subject']);
            //         $this->CI->email->message($isi);
            //         if ($this->CI->email->send($id = '', $content = '')) {

            //         } else {
            //             echo $this->CI->email->print_debugger();
            //         }



    }

    //----------------------------------------------------Super Admin Approve Token ----------------------------------------------------------
    function superadmin_approve_token($token_request_id = '', $email = '', $content = '', $fullname = ''){
        $isi = '';
        if($content == 'approved'){
            $isi = 'Your request for ' . $token_request->token_amount . ' tokens has been approved.';
        }  else if($content == 'declined'){
            $isi = 'Your request for ' . $token_request->token_amount . ' tokens has been declined.';
        }

            $super_app_tok = array(
            'subject' => 'Token Request' .ucfirst($content),
            'email' => $email,
        );
        $year = date("Y");
        $super_app_tok['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Token Request '.ucfirst($content).'
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    '.$isi.'
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>


                                                                <td>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $super_app_tok, 'email.send_email');

            // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
            // $this->CI->email->to($email);
            // $this->CI->email->bcc('test.soerbakti@gmail.com');
            // $this->CI->email->set_mailtype('html');

            //     $data['subject'] = 'Token Request '.ucfirst($content);
            //     //$data['content'] = 'Your token request ' . $token_request_data->token_amount . ' has been approved by Super Admin';
            //         $this->CI->email->subject($data['subject']);
            //         $this->CI->email->message($email_app_token);
            //         if ($this->CI->email->send($token_request_id = '', $content = '')) {

            //         } else {
            //         echo $this->CI->email->print_debugger();
            //         }


    }


//----------------------------------------------------Student Request Token ----------------------------------------------------------
    function student_request($email = '', $token = '', $fullname = '', $content = '', $partnername = ''){
        $isi = '';
        if ($content == 'requested') {
            $isi = 'Hi '.$partnername.', <br><br> '.$fullname.' requested '.$token.' tokens.';
        }else if ($content == 'cancelled') {
            $isi = 'Hi '.$partnername.', <br><br> '.$fullname.' cancelled '.$token.' tokens.';
        }

        $stu_req_tok = array(
            'subject' => 'Token Request from student',
            'email' => $email,
        );
        $year = date("Y");
        $stu_req_tok['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Token Request from student
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    '.$isi.'<br><br>Please Approve/Decline his request.
                                                                    <br><br>Best,
                                                                    <br>DynEd Live Teams.
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>


                                                                <td>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Go to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $stu_req_tok, 'email.send_email');

            // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
            // $this->CI->email->to($email);
            // $this->CI->email->bcc('test.soerbakti@gmail.com');
            // $this->CI->email->set_mailtype('html');


            //     $data['subject'] = 'Supplier '.ucfirst($content);
            //     //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
            //         $this->CI->email->subject($data['subject']);
            //         $this->CI->email->message($isi);
            //         if ($this->CI->email->send($id = '', $content = '')) {

            //         } else {
            //             echo $this->CI->email->print_debugger();
            //         }



    }

//-----------------------------------------------------Student Partner Approve Token Student-------------------------------------------------
   function student_supplier_approve_token($email = '', $content = '', $fullname = '', $token = ''){

        $isi = '';
        if ($content == 'approved') {
           $isi = 'Your request for '.$token.' tokens has been approved by your student partner.';
        }else if ($content == 'declined') {
            $isi = 'Your request for '.$token.' tokens has been declined by your student partner.';
        }
        $year = date("Y");

        // $email_approve ='<html lang="en">

        //     <head>
        //         <meta http-equiv="content-type" content="text/html" ; charset="utf-8">
        //         <title>DynEd Live Email</title>


        //         <style type="text/css" media="screen">
        //             @media screen {
        //                 h1,
        //                 h2,
        //                 h3 {}
        //             }
        //         </style>

        //         <style type="text/css" media="screen">

        //             /* Medium Screen */

        //             @media only screen and (max-width: 660px) {
        //                 table[class="container"] {
        //                     width: 480px !important;
        //                 }
        //                 td[class="logo"] img {
        //                     display: block;
        //                     margin-left: auto!important;
        //                     margin-right: auto!important;
        //                 }
        //             }
        //             /* Small Screen*/

        //             @media only screen and (max-width: 500px) {
        //                 table[class="container"] {
        //                     width: 320px !important;
        //                 }
        //                 td[class="logo"] img {
        //                     display: block;
        //                     margin-left: auto!important;
        //                     margin-right: auto!important;
        //                 }
        //             }
        //         </style>
        //     </head>

        //     <body bgcolor="#fafafa">

        //         <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#fafafa">
        //             <tr>
        //                 <td>
        //                     <table class="container" width="640px" align="center" border="0" cellpadding="0" cellspacing="0" style="margin-top: 20px ">
        //                         <tr>
        //                             <td valign="top" class="logo" bgcolor="#ffffff" style="padding: 10px 20px 10px 20px; border: 0;">
        //                                 <a href="http://live.dyned.com"><img src="http://i1300.photobucket.com/albums/ag89/Devananda_Onta/logo__zpslfnzndsv.png" alt="logo" width="auto" height="30">
        //                                 </a>
        //                             </td>
        //                         </tr>
        //                         <tr>
        //                             <td valign="top" class="headline" bgcolor="#3199DD" style="padding: 30px 20px 10px 20px; border-left: 0; border-right: 0; font-family: Calibri, sans-serif; font-size: 24px; line-height: 22px; color: #ffffff;">
        //                                 Token Request '.ucfirst($content).'
        //                             </td>
        //                         </tr>
        //                         <tr>
        //                             <td valign="top" class="headline" bgcolor="#ffffff" style="padding: 40px 20px 40px 20px; border-left: 0; border-right: 0; font-family: Calibri, sans-serif; font-size: 15px; line-height: 22px; color: #666666;">
        //                                 Hi '.$fullname.',
        //                                 <br>
        //                                 <br>'.
        //                                     $isi.
        //                                 '<br>
        //                                 <br>
        //                                 <table align="center" width="150" height="60">
        //                                     <tr>
        //                                         <td class="button-live">
        //                                             <a href="http://live.dyned.com" style="border: 2px solid #3199DD; border-radius: 5px; text-align:center; padding: 20px; text-decoration:none; color:#3199DD; font-family: Calibri, sans-serif; font-size: 14px;">Go to DynEd Live</a>
        //                                         </td>
        //                                     </tr>
        //                                 </table>
        //                                 <br> Best,
        //                                 <br> DynEd Live Teams
        //                                 <p style="font-family: Calibri, sans-serif; font-size: 13px; line-height: 12px; color: #CCCCCC;">
        //                                     This email can`t receive replies. For more information, visit the
        //                                     <a href="mailto:livesupport@dyned.com" style="text-decoration: none; color:#3EA6DD;">DynEd Live Help Center.</a>
        //                                 </p>
        //                                 <p style="font-family: Calibri, sans-serif; font-size: 13px; line-height: 12px; color: #CCCCCC;">
        //                                     You received this mandatory email service announcement to update you about important changes to your DynEd product or account.
        //                                 </p>

        //                             </td>
        //                         </tr>
        //                         <tr>
        //                             <td valign="top" class="footer" bgcolor="#3199DD" style="text-align:center; padding: 10px; font-family: Calibri, sans-serif; font-size: 12px; line-height: 12px; color: #ffffff;">
        //                                 DynEd Live - &copy;'.$year.' All Rights Reserved - DynEd International, Inc. 1350 Bayshore Highway, Suite 850. Burlingame, CA 94010
        //                             </td>
        //                         </tr>
        //                     </table>
        //                 </td>
        //             </tr>

        //         </table>

        //     </body>

        // </html>';

        $sup_app_tok = array(
            'subject' => 'Token Request' .ucfirst($content),
            'email' => $email,
        );

        $sup_app_tok['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Token Request '.ucfirst($content).'
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Hi '.$fullname.', <br><br>'.$isi.'
                                                                    <br><br>
                                                                    Best,
                                                                    <br>DynEd Live Teams.
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>


                                                                <td>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">Please contact your <a href="mailto:livesupport@dyned.com">administrator</a> for more information.</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $sup_app_tok, 'email.send_email');

            // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
            // $this->CI->email->to($email);
            // $this->CI->email->bcc('test.soerbakti@gmail.com');
            // $this->CI->email->set_mailtype('html');

            //     $data['subject'] = 'Token Request '.ucfirst($content);
            //     $data['content'] = 'Your token request ' . $token . ' has been '.$content.' by Admin';
            //         $this->CI->email->subject($data['subject']);
            //         $this->CI->email->message($email_approve);
            //         if ($this->CI->email->send($token_request_id = '', $content = '')) {

            //         } else {
            //             echo $this->CI->email->print_debugger();
            //         }

    }

//------------------------------------------------------Day Off-----------------------------------------------------------------------------
function coach_request_dayoff($email = '', $content = '', $fullname = '', $start = '', $end = '', $remark = '', $partnername = ''){
        $isi = $fullname.' has requested day off from '.$start.' to '.$end.' for '.$remark.' reason.';

            $req_dayoff = array(
            'subject' => 'Day Off request from coach',
            'email' => $email,
        );
        $year = date("Y");
        $req_dayoff['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Day Off request from coach
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Hi '.$partnername.', <br><br>'.$isi.'
                                                                    <br><br> Please Approve/Decline his request.
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>


                                                                <td>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Go to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $req_dayoff, 'email.send_email');

            // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
            // $this->CI->email->to($email);
            // $this->CI->email->bcc('test.soerbakti@gmail.com');
            // $this->CI->email->set_mailtype('html');


            //     $data['subject'] = 'Coach Requested Day Off';
            //     //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
            //         $this->CI->email->subject($data['subject']);
            //         $this->CI->email->message($isi);
            //         if ($this->CI->email->send($id = '', $content = '')) {

            //         } else {
            //             echo $this->CI->email->print_debugger();
            //         }



    }


//------------------------------------------------------Approve Day Off-----------------------------------------------------------------------------
function coach_partner_approve_dayoff($email = '', $content = '', $fullname = '', $start = '', $end = '', $remark = ''){
        $isi = '';
        if ($content == 'approved') {
           $isi = 'Hi '.$fullname.', <br><br>Your day-off request has been approved.';
        }else if ($content == 'declined') {
            $isi = 'Hi '.$fullname.', <br><br>Your day-off request has been declined.';
        }


        $sup_app_dayoff = array(
            'subject' => 'Day-Off '.ucfirst($content),
            'email' => $email,
        );
        $year = date("Y");
        $sup_app_dayoff['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Day-Off '.ucfirst($content).'
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    '.$isi.'
                                                                    <br><br>Best,
                                                                    <br>DynEd Live Teams.
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>


                                                                <td>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $sup_app_dayoff, 'email.send_email');

            // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
            // $this->CI->email->to($email);
            // $this->CI->email->bcc('test.soerbakti@gmail.com');
            // $this->CI->email->set_mailtype('html');


            //     $data['subject'] = 'Day Off '.ucfirst($content).'';
            //     //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
            //         $this->CI->email->subject($data['subject']);
            //         $this->CI->email->message($isi);
            //         if ($this->CI->email->send($id = '', $content = '')) {

            //         } else {
            //             echo $this->CI->email->print_debugger();
            //         }



    }


//---------------------------------------------------------Student Booking---------------------------------------------------------------
    function student_book_coach($studentmail = '', $coachmail = '', $studentname = '', $coachname = '', $start = '', $end = '', $date = '', $content = '', $gmt = ''){

        $tz = '';
        if ($gmt > 0) {
            $tz = '(UTC +'.$gmt.')';
        }else{
            $tz = '(UTC '.$gmt.')';
        }


        $isi = 'hi '.$coachname.' you have been booked by a student : '.$studentname.' on '.$date.' start from '.$start.' to '.$end.' '.$tz.' please remember';

        $stu_book = array(
            'subject' => 'DynEd Live Session ' .ucfirst($content),
            'email' => $studentmail,
        );
        $year = date("Y");
        $stu_book['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    DynEd Live Session '.ucfirst($content).'
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Hi '.$studentname.', You have a live session booked!
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #ea5656; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Detail Session Information
                                                                </td>
                                                            </tr>
                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">


                                                                   Coach Name : '.$coachname.'
                                                                   <br>
                                                                    Date : '.$date.'
                                                                   <br>
                                                                   Time : From '.$start.' to '.$end.' '.$tz.'
                                                                   <br>
                                                                   <br>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;">For more details, go to the DynEd Live page.</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $stu_book, 'email.send_email');

        // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($coachmail);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');


        //         $data['subject'] = 'Session '.ucfirst($content).'';
        //         //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($isi);
        //             if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //                 echo $this->CI->email->print_debugger();
        //             }
    }

//---------------------------------------------------------notif_coach---------------------------------------------------------------
    function notif_coach($studentmail = '', $coachmail = '', $studentname = '', $coachname = '', $start = '', $end = '', $date = '', $content = '', $gmt = ''){

        $tz = '';
        if ($gmt > 0) {
            $tz = '(UTC +'.$gmt.')';
        }else{
            $tz = '(UTC '.$gmt.')';
        }


        $isi = 'hi '.$coachname.' you have been booked by a student : '.$studentname.' on '.$date.' start from '.$start.' to '.$end.' '.$tz.' please remember';

        $notifcoach = array(
            'subject' => 'DynEd Live Session ' .ucfirst($content),
            'email' => $coachmail,
        );
        $year = date("Y");
        $notifcoach['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    DynEd Live Session '.ucfirst($content).'
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Dear '.$coachname.', You have been booked by a student!
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #ea5656; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Detail Session Information
                                                                </td>
                                                            </tr>
                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">


                                                                   Student Name : '.$studentname.'
                                                                   <br>
                                                                    Date : '.$date.'
                                                                   <br>
                                                                   Time : From '.$start.' to '.$end.' '.$tz.'
                                                                   <br>
                                                                   <br>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;">For more details, go to the DynEd Live page.</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $notifcoach, 'email.send_email');

        // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($coachmail);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');


        //         $data['subject'] = 'Session '.ucfirst($content).'';
        //         //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($isi);
        //             if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //                 echo $this->CI->email->print_debugger();
        //             }
    }

    //---------------------------------------------------------notif_student_partner---------------------------------------------------------------
    function notif_student_partner($studentmail = '', $studentname = '', $classname = '', $partnermail = '', $partnername = '', $content = ''){

        $isi = 'hi '.$partnername.' you have been added member to a class : '.$classname.'. Your new member name is : '.$studentname.'';

        $notifstupar = array(
            'subject' => 'New Class Member' .ucfirst($content),
            'email' => $partnermail,
        );
        $year = date("Y");
        $notifstupar['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    New Class Member'.ucfirst($content).'
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Dear '.$partnername.', You have added member to a class.
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #ea5656; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Detail Class Information
                                                                </td>
                                                            </tr>
                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">


                                                                   Class Name : '.$classname.'
                                                                   <br>
                                                                    Student Name : '.$studentname.'
                                                                   <br>
                                                                   <br>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $notifstupar, 'email.send_email');

        // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($coachmail);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');


        //         $data['subject'] = 'Session '.ucfirst($content).'';
        //         //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($isi);
        //             if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //                 echo $this->CI->email->print_debugger();
        //             }
    }

    //---------------------------------------------------------create class member---------------------------------------------------------------
    function create_class_member($studentmail = '', $studentname = '', $classname = '', $partnermail = '', $partnername = '', $content = ''){

        $isi = 'hi '.$studentname.' you have been added to a class : '.$classname.' by your student partner : '.$partnername.'';

        $createclassmember = array(
            'subject' => 'New Class Member' .ucfirst($content),
            'email' => $studentmail,
        );
        $year = date("Y");
        $createclassmember['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    New Class Member '.ucfirst($content).'
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Dear '.$studentname.', You have been added to a class.
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #ea5656; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Detail Class Information
                                                                </td>
                                                            </tr>
                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">


                                                                   Class Name : '.$classname.'
                                                                   <br>
                                                                    Student Partner : '.$partnername.'
                                                                   <br>
                                                                   <br>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $createclassmember, 'email.send_email');

        // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($coachmail);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');


        //         $data['subject'] = 'Session '.ucfirst($content).'';
        //         //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($isi);
        //             if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //                 echo $this->CI->email->print_debugger();
        //             }
    }

    //---------------------------------------------------------create class---------------------------------------------------------------
    function create_class($email = '', $fullname = '', $classname = '', $subgroup = '', $start = '', $end = '', $cost = '', $content = ''){

        $isi = 'hi '.$fullname.' you have created a class : '.$classname.' for subgroup : '.$subgroup.' start from : '.$start.' to : '.$end.'. Cost for this class is : '.$cost.' please remember this';

        $createclass = array(
            'subject' => 'Class' .ucfirst($content),
            'email' => $email,
        );
        $year = date("Y");
        $createclass['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Class '.ucfirst($content).'
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Dear '.$fullname.', You have created a class.
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #ea5656; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Detail Class Information
                                                                </td>
                                                            </tr>
                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">


                                                                   Class Name : '.$classname.'
                                                                   <br>
                                                                    Subgroup : '.$subgroup.'
                                                                   <br>
                                                                   Time : From '.$start.' to '.$end.'
                                                                   <br>
                                                                   Cost : '.$cost.' Token
                                                                   <br>
                                                                   <br>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $createclass, 'email.send_email');

        // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($coachmail);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');


        //         $data['subject'] = 'Session '.ucfirst($content).'';
        //         //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($isi);
        //             if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //                 echo $this->CI->email->print_debugger();
        //             }
    }

    //---------------------------------------------------------add token---------------------------------------------------------------
    function add_token($email = '', $sender = '', $token = '', $role = '', $receiver = ''){

        $addtoken = array(
            'subject' => 'Token Added',
            'email' => $email,
        );
        $year = date("Y");
        $addtoken['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Add Token
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    Hi '.$receiver.'`s Admin, <br><br>
                                                                    Your token has been added '.$token.' tokens.
                                                                    <br><br>
                                                                    Best,
                                                                    <br>DynEd Live Teams
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">


                                                                   <br>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Go to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $addtoken, 'email.send_email');

        // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($coachmail);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');


        //         $data['subject'] = 'Session '.ucfirst($content).'';
        //         //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($isi);
        //             if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //                 echo $this->CI->email->print_debugger();
        //             }
    }

    //---------------------------------------------------------add token region to superadmin---------------------------------------------------------------
    function add_token_region($email = '', $name = '', $token = '', $content = ''){

        $isi = '';
        if ($content == 'requested') {
            $isi = 'Hi Superadmin, <br><br>'.$name.' requested '.$token.' tokens.';
        } else if ($content == 'cancelled') {
            $isi = 'Hi Superadmin, <br><br>'.$name.' cancelled '.$token.' tokens.';
        }


        $addtokenregion = array(
            'subject' => 'New token request from '.$name.'',
            'email' => $email,
        );
        $year = date("Y");
        $addtokenregion['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    New token request from '.$name.'
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    '.$isi.'<br><br> Please Decide if you want to Approve/Decline it.

                                                                    <br><br>Best,
                                                                    <br>DynEd Live Teams.
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>


                                                                <td>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Approve/Reject</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $addtokenregion, 'email.send_email');

        // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($coachmail);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');


        //         $data['subject'] = 'Session '.ucfirst($content).'';
        //         //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($isi);
        //             if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //                 echo $this->CI->email->print_debugger();
        //             }
    }

    //---------------------------------------------------------add token student---------------------------------------------------------------
    function add_token_student($email = '', $token = '', $content = ''){

        $isi = '';
        if ($content == 'requested') {
            $isi =  'You have made a token request. Amount : <font style="color:#2b89b9;">'.$token.'.</font>';
        } else if ($content == 'cancelled') {
            $isi = 'You have cancelled token request. Amount : <font style="color:#2b89b9;">'.$token.'.</font>';
        }


        $addtokenstudent = array(
            'subject' => 'Token Request',
            'email' => $email,
        );
        $year = date("Y");
        $addtokenstudent['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Token Request
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    '.$isi.'
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>


                                                                <td>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $addtokenstudent, 'email.send_email');

        // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($coachmail);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');


        //         $data['subject'] = 'Session '.ucfirst($content).'';
        //         //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($isi);
        //             if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //                 echo $this->CI->email->print_debugger();
        //             }
    }

    //---------------------------------------------------------add token---------------------------------------------------------------
    function add_token_partner($email = '', $sender = '', $token = '', $role = '', $receiver = ''){

        $addtokenpartner = array(
            'subject' => 'Token addition by Regional Admin',
            'email' => $email,
        );
        $year = date("Y");
        $addtokenpartner['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Add Token
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    Hi '.$receiver.', <br><br>
                                                                    You just received '.$token.' tokens from your regional admin.
                                                                    <br><br>
                                                                    Best,
                                                                    <br>DynEd Live Teams
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">


                                                                   <br>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">Please contact your <a href="mailto:livesupport@dyned.com">administrator</a> for more information.</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Go to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $addtokenpartner, 'email.send_email');

        // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($coachmail);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');


        //         $data['subject'] = 'Session '.ucfirst($content).'';
        //         //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($isi);
        //             if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //                 echo $this->CI->email->print_debugger();
        //             }
    }

    //---------------------------------------------------------add token for student---------------------------------------------------------------
    function add_token_for_student($email = '', $sender = '', $token = '', $role = '', $receiver = ''){

        $addtokenforstudent = array(
            'subject' => 'Token Added',
            'email' => $email,
        );
        $year = date("Y");
        $addtokenforstudent['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Add Token
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    Hi '.$receiver.', <br><br>
                                                                    You just received '.$token.' tokens from your administrator.
                                                                    <br><br>
                                                                    Best,
                                                                    <br>DynEd Live Teams
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">


                                                                   <br>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">Please contact your <a href="mailto:livesupport@dyned.com">administrator</a> for more information.</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Go to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $addtokenforstudent, 'email.send_email');

        // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($coachmail);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');


        //         $data['subject'] = 'Session '.ucfirst($content).'';
        //         //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($isi);
        //             if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //                 echo $this->CI->email->print_debugger();
        //             }
    }

    //----------------------------------------------------Admin Region Request Token ----------------------------------------------------------
    function send_partner_request_token($email = '', $fullname = '', $token = '', $content = ''){

        $isi = '';
        if ($content == 'requested') {
            $isi = 'Hi '.$fullname.', <br><br>You just requested '.$token.' tokens to your regional admin';
        } else if ($content == 'cancelled') {
            $isi = 'Hi '.$fullname.', <br><br>You just cancelled '.$token.' tokens to your regional admin';
        }

            $partner_req_token = array(
            'subject' => 'Token Request',
            'email' => $email,
        );
        $year = date("Y");
        $partner_req_token['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Token Request
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    '.$isi.'
                                                                    <br><br>Best,
                                                                    <br>DynEd Live Teams.
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>


                                                                <td>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">Please contact your <a href="mailto:livesupport@dyned.com">administrator</a> for more information.</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Go to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $partner_req_token, 'email.send_email');

        //     $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($email);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');

        // // Loads the email library

        //         $data['subject'] = 'Token Request (Reminder)';


        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($email_request);
        //             if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //                 echo $this->CI->email->print_debugger();
        //             }

    }

    //----------------------------------------------------Admin Region Approve Token ----------------------------------------------------------
    function send_partner_approve_token($email = '', $content = '', $fullname = '', $token = ''){

        $isi = '';
        if ($content == 'approved') {
            $isi = 'Your request for '.$token.' tokens has been approved by your regional admin.';
        }else if ($content == 'declined') {
            $isi = 'Your request for '.$token.' tokens has been declined by your regional admin.';
        }




            $adreg_app_token = array(
            'subject' => 'Token Request' .ucfirst($content),
            'email' => $email,
        );
        $year = date("Y");
        $adreg_app_token['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Token Request '.ucfirst($content).'
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Hi '.$fullname.', <br><br>'.$isi.'
                                                                    <br><br>
                                                                    Best,
                                                                    <br>DynEd Live Teams.
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>


                                                                <td>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;text-align:center;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Go to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $adreg_app_token, 'email.send_email');

            // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
            // $this->CI->email->to($email);
            // $this->CI->email->bcc('test.soerbakti@gmail.com');
            // $this->CI->email->set_mailtype('html');

            //     $data['subject'] = 'Token Request '.ucfirst($content);
            //     $data['content'] = 'Your token request ' . $token . ' has been '.$content.' by Admin';
            //         $this->CI->email->subject($data['subject']);
            //         $this->CI->email->message($email_approve);
            //         if ($this->CI->email->send($token_request_id = '', $content = '')) {

            //         } else {
            //             echo $this->CI->email->print_debugger();
            //         }

    }

//---------------------------------------------------------Coach Partner Reschedule---------------------------------------------------------------
    function partner_reschedule($studentmail = '', $studentname = '', $oldcoachname = '', $olddate ='', $oldstart = '', $oldend = '', $newcoachname = '', $newdate = '', $newstart = '', $newend = '', $gmt = ''){

        $tz = '';
        if ($gmt > 0) {
            $tz = '(UTC +'.$gmt.')';
        }else{
            $tz = '(UTC '.$gmt.')';
        }

        $isi = 'Hi '.$studentname.', <br><br> Your session with '.$oldcoachname.' on '.$olddate.' from '.$oldstart.' to '.$oldend.' has been rescheduled. Your new session is with '.$newcoachname.'  on '.$newdate.' from '.$newstart.' to '.$newend.'';

        $part_reschedule = array(
            'subject' => 'Rescheduled Session',
            'email' => $studentmail,
        );
        $year = date("Y");
        $part_reschedule['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Rescheduled Session
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Hi '.$studentname.', Your session has been rescheduled!
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #ea5656; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Rescheduled Session Information
                                                                </td>
                                                            </tr>
                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">


                                                                    Coach Name : '.$oldcoachname.'
                                                                   <br>
                                                                    Date : '.$olddate.'
                                                                   <br>
                                                                    Time : From '.$oldstart.' to '.$oldend.' '.$tz.'
                                                                   <br>
                                                                   <br>
                                                                   <strong>TO</strong>
                                                                   <br>
                                                                   <br>
                                                                    Coach Name : '.$newcoachname.'
                                                                   <br>
                                                                    Date : '.$newdate.'
                                                                   <br>
                                                                    Time : From '.$newstart.' to '.$newend.' '.$tz.'

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $part_reschedule, 'email.send_email');

        // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($coachmail);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');


        //         $data['subject'] = 'Session '.ucfirst($content).'';
        //         //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($isi);
        //             if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //                 echo $this->CI->email->print_debugger();
        //             }
    }

    //---------------------------------------------------------Notif Coach Partner Reschedule---------------------------------------------------------------
    function notif_partner_reschedule($coachmail = '', $studentname = '', $oldcoachname = '', $olddate ='', $oldstart = '', $oldend = '', $newcoachname = '', $newdate = '', $newstart = '', $newend = '', $gmt = ''){

        $tz = '';
        if ($gmt > 0) {
            $tz = '(UTC +'.$gmt.')';
        }else{
            $tz = '(UTC '.$gmt.')';
        }

        $isi = 'Hi '.$newcoachname.', <br><br> Your Administrator have assign you for a rescheduled session with '.$studentname.' on '.$newdate.' from '.$newstart.' to '.$newend.'';

        $notif_part_reschedule = array(
            'subject' => 'Rescheduled Session',
            'email' => $coachmail,
        );
        $year = date("Y");
        $notif_part_reschedule['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Rescheduled Session
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Dear '.$newcoachname.', Your Administrator have assign you for a rescheduled session!
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #ea5656; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Rescheduled Session Information
                                                                </td>
                                                            </tr>
                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">


                                                                    Student Name : '.$studentname.'
                                                                   <br>
                                                                    Date : '.$newdate.'
                                                                   <br>
                                                                    Time : From '.$newstart.' to '.$newend.' '.$tz.'
                                                                   <br>
                                                                   <br>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $notif_part_reschedule, 'email.send_email');

        // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($coachmail);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');


        //         $data['subject'] = 'Session '.ucfirst($content).'';
        //         //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($isi);
        //             if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //                 echo $this->CI->email->print_debugger();
        //             }
    }

    //---------------------------------------------------------Student Reschedule---------------------------------------------------------------
    function student_reschedule($coachmail = '', $studentname = '', $coachname = '', $olddate ='', $oldstart = '', $oldend = '', $newdate = '', $newstart = '', $newend = '', $gmt = ''){

        $isi = 'Hi '.$coachname.', <br><br> Your student '.$studentname.' have rescheduled your session into '.$newdate.' from '.$newstart.' to '.$newend.'';

        $tz = '';
        if ($gmt > 0) {
            $tz = '(UTC +'.$gmt.')';
        }else{
            $tz = '(UTC '.$gmt.')';
        }

        $stu_reschedule = array(
            'subject' => 'Rescheduled Session',
            'email' => $coachmail,
        );
        $year = date("Y");
        $stu_reschedule['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Rescheduled Session
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Dear '.$coachname.', Your student have rescheduled your session!
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #ea5656; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Rescheduled Session Information
                                                                </td>
                                                            </tr>
                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">


                                                                    Student Name : '.$studentname.'
                                                                   <br>
                                                                    Date : '.$newdate.'
                                                                   <br>
                                                                    Time : From '.$newstart.' to '.$newend.' '.$tz.'
                                                                   <br>
                                                                   <br>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $stu_reschedule, 'email.send_email');

        // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($coachmail);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');


        //         $data['subject'] = 'Session '.ucfirst($content).'';
        //         //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($isi);
        //             if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //                 echo $this->CI->email->print_debugger();
        //             }
    }

    //---------------------------------------------------------Notif Student Reschedule---------------------------------------------------------------
    function notif_student_reschedule($studentmail = '', $studentname = '', $coachname = '', $olddate ='', $oldstart = '', $oldend = '', $newdate = '', $newstart = '', $newend = '', $gmt = ''){

        $tz = '';
        if ($gmt > 0) {
            $tz = '(UTC +'.$gmt.')';
        }else{
            $tz = '(UTC '.$gmt.')';
        }

        $isi = 'Hi '.$studentname.', <br><br> You have rescheduled your session with '.$coachname.' on '.$newdate.' from '.$newstart.' to '.$newend.'';

        $notif_stu_reschedule = array(
            'subject' => 'Rescheduled Session',
            'email' => $studentmail,
        );
        $year = date("Y");
        $notif_stu_reschedule['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Rescheduled Session
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Hi '.$studentname.', You have rescheduled your session!
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #ea5656; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Rescheduled Session Information
                                                                </td>
                                                            </tr>
                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">


                                                                    Coach Name : '.$coachname.'
                                                                   <br>
                                                                    Date : '.$newdate.'
                                                                   <br>
                                                                    Time : From '.$newstart.' to '.$newend.' '.$tz.'
                                                                   <br>
                                                                   <br>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $notif_stu_reschedule, 'email.send_email');

        // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($coachmail);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');


        //         $data['subject'] = 'Session '.ucfirst($content).'';
        //         //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($isi);
        //             if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //                 echo $this->CI->email->print_debugger();
        //             }
    }

    //---------------------------------------------------------Notif Coach Reschedule---------------------------------------------------------------
    function notif_coach_reschedule($coachmail = '', $studentname = '', $oldcoachname = '', $olddate ='', $oldstart = '', $oldend = '', $newdate = '', $newstart = '', $newend = '', $gmt = ''){

        $tz = '';
        if ($gmt > 0) {
            $tz = '(UTC +'.$gmt.')';
        }else{
            $tz = '(UTC '.$gmt.')';
        }

        $isi = 'Hi '.$oldcoachname.', <br><br> Your Administrator has cancelled your session with '.$studentname.' on '.$olddate.' from '.$oldstart.' to '.$oldend.'.';

        $notif_coach_reschedule = array(
            'subject' => 'Rescheduled Session',
            'email' => $coachmail,
        );
        $year = date("Y");
        $notif_coach_reschedule['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Rescheduled Session
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Hi '.$oldcoachname.',
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #ea5656; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Cancelled Session Information
                                                                </td>
                                                            </tr>
                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">
                                                                    Your student '.$studentname.' has cancelled your session on '.$olddate.' from '.$oldstart.' to '.$oldend.' '.$tz.'
                                                                   <br>
                                                                   <br>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;">For more information, please contact <a href="mailto:livesupport@dyned.com">administrator</a>. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $notif_coach_reschedule, 'email.send_email');

        // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($coachmail);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');


        //         $data['subject'] = 'Session '.ucfirst($content).'';
        //         //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($isi);
        //             if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //                 echo $this->CI->email->print_debugger();
        //             }
    }

//----------------------------------------------------Notif Admin Region Create Student Partner ----------------------------------------------------------
    function notif_ad_reg_stu($email = '', $fullname = '', $name_admin = '', $token_amount = ''){



        $adreg_cre_stu = array(
            'subject' => 'New Student Partner created',
            'email' => $email,
        );
        $year = date("Y");
        $adreg_cre_stu['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Supplier Created
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Hi '.$name_admin.' `s Admin, <br><br>
                                                                    A new Student Partner has been created by Superadmin with:
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #ea5656; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">

                                                                </td>
                                                            </tr>
                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">


                                                                    Partner`s name : '.$fullname.'.
                                                                   <br>
                                                                    Token amount : '.$token_amount.' tokens.
                                                                   <br>
                                                                   <br>
                                                                   Consequently, we have deducted '.$token_amount.' tokens from your administrator`s account and transferred them to the '.$fullname.'.<br><br>
                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;">Please contact Super Admin for more information.</p>

                                                                    <br><br>Best,
                                                                    <br>DynEd Live Teams.
                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $adreg_cre_stu, 'email.send_email');

        //     $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($email);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');

        // if ($content == 'created') {
        //         $data['subject'] = 'Partner Created';
        //         //$data['content'] = 'Welcome to DynEd Live, account information: Email = ' . $email . ' Password = ' . $realpassword . ' If Super Admin Approve, you can login to DynEd Live as Partner Admin. For more information, please ask the administrator. Thank you';
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($email_body);
        //         if ($this->CI->email->send($id ='', $content = '')) {
        //             } else {
        //             echo $this->CI->email->print_debugger();
        //             }
        //     }

    }

//----------------------------------------------------Notif Admin Region Create Student Partner ----------------------------------------------------------
    function notif_ad_reg_coa($email = '', $fullname = '', $name_admin = ''){



        $adreg_cre_coa = array(
            'subject' => 'New Coach Partner created',
            'email' => $email,
        );
        $year = date("Y");
        $adreg_cre_coa['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Supplier Created
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Hi '.$name_admin.' `s Admin, <br><br>
                                                                    A new Coach Partner has been created by Superadmin with:
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #ea5656; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">

                                                                </td>
                                                            </tr>
                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">


                                                                    Partner`s name : '.$fullname.'.

                                                                   <br>
                                                                   <br>
                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;">Please contact Super Admin for more information. Thank you!</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $adreg_cre_coa, 'email.send_email');

        //     $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($email);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');

        // if ($content == 'created') {
        //         $data['subject'] = 'Partner Created';
        //         //$data['content'] = 'Welcome to DynEd Live, account information: Email = ' . $email . ' Password = ' . $realpassword . ' If Super Admin Approve, you can login to DynEd Live as Partner Admin. For more information, please ask the administrator. Thank you';
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($email_body);
        //         if ($this->CI->email->send($id ='', $content = '')) {
        //             } else {
        //             echo $this->CI->email->print_debugger();
        //             }
        //     }

    }

    //---------------------------------------------------------Student Reminder---------------------------------------------------------------
    function student_reminder($studentmail = '', $coachname = '', $studentname = '', $date = '', $start = '', $end = '', $gmt = ''){

        $tz = '';
        if ($gmt > 0) {
            $tz = '(UTC +'.$gmt.')';
        }else{
            $tz = '(UTC '.$gmt.')';
        }


        $isi = 'hi '.$coachname.' you have been booked by a student : '.$studentname.' on '.$date.' start from '.$start.' to '.$end.' '.$tz.' please remember';

        $stu_rem = array(
            'subject' => 'DynEd Live Session Reminder',
            'email' => $studentmail,
        );
        $year = date("Y");
        $stu_rem['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    DynEd Live Session Reminder
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Hi '.$studentname.', You have a live session booked!
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #ea5656; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Detail Session Information
                                                                </td>
                                                            </tr>
                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">


                                                                   Coach Name : '.$coachname.'
                                                                   <br>
                                                                    Date : '.$date.'
                                                                   <br>
                                                                   Time : From '.$start.' to '.$end.' '.$tz.'
                                                                   <br>
                                                                   <br>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;">For more details, go to DynEd Live page.</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $stu_rem, 'email.send_email');

        // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($coachmail);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');


        //         $data['subject'] = 'Session '.ucfirst($content).'';
        //         //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($isi);
        //             if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //                 echo $this->CI->email->print_debugger();
        //             }
    }

    //---------------------------------------------------------Student Reminder---------------------------------------------------------------
    function coach_reminder($coachmail = '', $coachname = '', $studentname = '', $date = '', $start = '', $end = '', $gmt = ''){

        $tz = '';
        if ($gmt > 0) {
            $tz = '(UTC +'.$gmt.')';
        }else{
            $tz = '(UTC '.$gmt.')';
        }


        $isi = 'hi '.$coachname.' you have been booked by a student : '.$studentname.' on '.$date.' start from '.$start.' to '.$end.' '.$tz.' please remember';

        $coa_rem = array(
            'subject' => 'DynEd Live Session Reminder',
            'email' => $coachmail,
        );
        $year = date("Y");
        $coa_rem['content'] = '<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>DynEd-Live Email Template</title>

    <style type="text/css">


        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }
        /* Konfigurasi untuk Hotmail */

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        /* Konfigurasi Hotmail*/

        #backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .image_fix {
            display: block;
        }

        p {
            margin: 0px 0px !important;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        a {
            color: #0a8cce;
            text-decoration: none;
            text-decoration: none!important;
        }
        /*STYLES*/

        table[class=full] {
            width: 100%;
            clear: both;
        }
        /*IPAD STYLES*/

        @media only screen and (max-width: 640px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 440px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 420px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 440px!important;
                height: 220px!important;
            }
            img[class=colimg2] {
                width: 440px!important;
                height: 220px!important;
            }
        }
        /*IPHONE STYLES*/

        @media only screen and (max-width: 480px) {
            a[href^="tel"],
            a[href^="sms"] {
                text-decoration: none;
                color: #0a8cce;
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"],
            .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #0a8cce !important;
                pointer-events: auto;
                cursor: default;
            }
            table[class=devicewidth] {
                width: 280px!important;
                text-align: center!important;
            }
            table[class=devicewidthinner] {
                width: 260px!important;
                text-align: center!important;
            }
            img[class=banner] {
                width: 280px!important;
                height: 140px!important;
            }
            img[class=colimg2] {
                width: 280px!important;
                height: 140px!important;
            }
            td[class=mobile-hide] {
                display: none!important;
            }
            td[class="padding-bottom25"] {
                padding-bottom: 25px!important;
            }
        }
    </style>
</head>

<body>

    <!-- Start of header -->
    <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="border-top: 3px solid #144d80;">
                                        <tbody>
                                            <!-- Spacing -->
                                            <tr>
                                                <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td>
                                                    <!-- logo -->
                                                    <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <td width="169" height="45" align="center">
                                                                    <div class="imgpop">
                                                                        <a target="_blank" href="http://live.dyned.com">
                                                                            <img src="https://live.dyned.com/uploads/images/dyned-logo.png" alt="" border="0" width="100%" height="auto" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of logo -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Header -->

    <table>

    </table>


    <table width="600" align="center" cellpadding="0" border="0" class="devicewidthinner">
        <tbody>
            <tr style="background-color:#2b89b9;">
                                                                <td style="color: #fff;font-family: Helvetica, arial, sans-serif; font-size: 30px; padding:15px; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    DynEd Live Session Reminder
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 28px; color: #919191; text-align:center; line-height: auto;" st-title="fulltext-heading" >
                                                                    <br>
                                                                    Dear '.$coachname.', You have a live session booked!
                                                                </td>
                                                            </tr>
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="35" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
        </tbody>
    </table>

    <!-- Detail Account Start -->

    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                                        <tbody>
                                                            <!-- Title -->

                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #ea5656; text-align:center; line-height: 35px; font-weight:600;" st-title="fulltext-heading">
                                                                    Detail Session Information
                                                                </td>
                                                            </tr>
                                                            <!-- End of Title -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <!-- content -->
                                                            <tr>
                                                                <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #919191; text-align:center; line-height: 30px;" st-content="fulltext-content">


                                                                   Student Name : '.$studentname.'
                                                                   <br>
                                                                    Date : '.$date.'
                                                                   <br>
                                                                   Time : From '.$start.' to '.$end.' '.$tz.'
                                                                   <br>
                                                                   <br>

                                                                   <p style="font-weight:400;font-size:14px;line-height:30px;">For more details, go to the DynEd Live page.</p>

                                                                </td>

                                                            </tr>
                                                            <!-- End of content -->
                                                            <!-- spacing -->
                                                            <tr>
                                                                <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- End of spacing -->
                                                            <tr>

                                                                    <td width="auto" align="center" valign="middle" height="36" style="background-color:#2b89b9; border-top-left-radius:4px; border-bottom-left-radius:4px;border-top-right-radius:4px; border-bottom-right-radius:4px; background-clip: padding-box;font-size:1.5em; font-family:Helvetica, arial, sans-serif; text-align:center;  color:#ffffff; font-weight: 300; padding-left:25px; padding-right:25px;padding-top:25px;padding-bottom:25px;">

                                                                        <span style="color: #ffffff; font-weight: 300;">
                                                                              <a style="color: #ffffff; text-align:center;text-decoration: none;" href="http://live.dyned.com">Login to DynEd Live</a>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

    <!-- Detail Account End -->


    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                        <tbody>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of seperator -->
    <!-- 2columns -->



    <!-- Start of Postfooter -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
        <tbody>
            <tr>
                <td>
                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #919191" st-content="postfooter">
                                                    DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.
                                                </td>
                                            </tr>
                                            <!-- Spacing -->
                                            <tr>
                                                <td width="100%" height="20"></td>
                                            </tr>
                                            <!-- Spacing -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of postfooter -->

</body>

</html>';

        $this->CI->queue->push($this->tube, $coa_rem, 'email.send_email');

        // $this->CI->email->from('no-reply@dyned.com', 'no-reply');
        //     $this->CI->email->to($coachmail);
        //     $this->CI->email->bcc('test.soerbakti@gmail.com');
        //     $this->CI->email->set_mailtype('html');


        //         $data['subject'] = 'Session '.ucfirst($content).'';
        //         //$data['content'] = 'Your partner request has been approved by Super Admin. Email : ' . $email;
        //             $this->CI->email->subject($data['subject']);
        //             $this->CI->email->message($isi);
        //             if ($this->CI->email->send($id = '', $content = '')) {

        //             } else {
        //                 echo $this->CI->email->print_debugger();
        //             }
    }
}
