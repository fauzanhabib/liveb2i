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

//------------------------------------------------student reminder B2C---------------------------------------------

function student_reminder_b2c($studentmail = '', $coachname = '', $studentname = '', $date = '', $start = '', $end = '', $gmt = ''){

        $tz = '';
        if ($gmt > 0) {
            $tz = '(UTC +'.$gmt.')';
        }else{
            $tz = '(UTC '.$gmt.')';
        }


        $isi = 'hi '.$coachname.' you have been booked by a student : '.$studentname.' on '.$date.' start from '.$start.' to '.$end.' '.$tz.' please remember';

        $year = date("Y");
        $b2c_rem = '<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en" style="background-color:#232547;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;min-height:100%;">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width">
</head>

<body style="-moz-box-sizing:border-box;-ms-text-size-adjust:100%;-webkit-box-sizing:border-box;-webkit-text-size-adjust:100%;Margin:0;background-color:#232547;background-image:url(http://i6.cmail20.com/ei/y/33/128/611/030239/footer-background.jpg);background-repeat:no-repeat;background-position:bottom
center;background-attachment:scroll;box-sizing:border-box;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:19px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;min-width:100%;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;width:100%!important;">
    <style>
        @media only screen and (max-width: 596px) {
            .small-float-center {
                margin: 0 auto!important;
                float: none!important;
                text-align: center!important
            }
            .small-text-center {
                text-align: center!important
            }
            .small-text-left {
                text-align: left!important
            }
            .small-text-right {
                text-align: right!important
            }
        }
        @media only screen and (max-width: 596px) {
            table.body table.container .hide-for-large {
                display: block!important;
                width: auto!important;
                overflow: visible!important
            }
        }
        @media only screen and (max-width: 596px) {
            table.body table.container .row.hide-for-large {
                display: table!important;
                width: 100%!important
            }
        }
        @media only screen and (max-width: 596px) {
            table.body table.container .show-for-large {
                display: none!important;
                width: 0;
                mso-hide: all;
                overflow: hidden
            }
        }
        @media only screen and (max-width: 596px) {
            table.body img {
                width: auto!important;
                height: auto!important
            }
            table.body center {
                min-width: 0!important
            }
            table.body .container {
                width: 95%!important
            }
            table.body .column,
            table.body .columns {
                height: auto!important;
                -moz-box-sizing: border-box;
                -webkit-box-sizing: border-box;
                box-sizing: border-box;
                padding-left: 16px!important;
                padding-right: 16px!important
            }
            table.body .column .column,
            table.body .column .columns,
            table.body .columns .column,
            table.body .columns .columns {
                padding-left: 0!important;
                padding-right: 0!important
            }
            table.body .collapse .column,
            table.body .collapse .columns {
                padding-left: 0!important;
                padding-right: 0!important
            }
            td.small-1,
            th.small-1 {
                display: inline-block!important;
                width: 8.33333%!important
            }
            td.small-2,
            th.small-2 {
                display: inline-block!important;
                width: 16.66667%!important
            }
            td.small-3,
            th.small-3 {
                display: inline-block!important;
                width: 25%!important
            }
            td.small-4,
            th.small-4 {
                display: inline-block!important;
                width: 33.33333%!important
            }
            td.small-5,
            th.small-5 {
                display: inline-block!important;
                width: 41.66667%!important
            }
            td.small-6,
            th.small-6 {
                display: inline-block!important;
                width: 50%!important
            }
            td.small-7,
            th.small-7 {
                display: inline-block!important;
                width: 58.33333%!important
            }
            td.small-8,
            th.small-8 {
                display: inline-block!important;
                width: 66.66667%!important
            }
            td.small-9,
            th.small-9 {
                display: inline-block!important;
                width: 75%!important
            }
            td.small-10,
            th.small-10 {
                display: inline-block!important;
                width: 83.33333%!important
            }
            td.small-11,
            th.small-11 {
                display: inline-block!important;
                width: 91.6666 7%!important
            }
            td.small-12,
            th.small-12 {
                display: inline-block!important;
                width: 100%!important
            }
            .column td.small-12,
            .column th.small-12,
            .columns td.small-12,
            .columns th.small-12 {
                display: block!important;
                width: 100%!important
            }
            .body .column td.small-1,
            .body .column th.small-1,
            .body .columns td.small-1,
            .body .columns th.small-1,
            td.small-1 center,
            th.small-1 center {
                display: inline-block!important;
                width: 8.33333%!important
            }
            .body .column td.small-2,
            .body .column th.small-2,
            .body .columns td.small-2,
            .body .columns th.small-2,
            td.small-2 center,
            th.small-2 center {
                display: inline-block!important;
                width: 16.66667%!important
            }
            .body .column td.small-3,
            .body .column th.small-3,
            .body .columns td.small-3,
            .body .columns th.small-3,
            td.small-3 center,
            th.small-3 center {
                display: inline-block!important;
                width: 25%!important
            }
            .body .column td.small-4,
            .body .column th.small-4,
            .body .columns td.small-4,
            .body .columns th.small-4,
            td.small-4 center,
            th.small-4 center {
                display: inline-block!important;
                width: 33.33333%!important
            }
            .body .column td.small-5,
            .body .column th.small-5,
            .body .columns td.small-5,
            .body .columns th.small-5,
            td.small-5 center,
            th.small-5 center {
                display: inline-block!important;
                width: 41.66667%!important
            }
            .body .column td.small-6,
            .body .column th.small-6,
            .body .columns td.small-6,
            .body .columns th.small-6,
            td.small-6 center,
            th.small-6 center {
                display: inline-block!important;
                width: 50%!important
            }
            .body .column td.small-7,
            .body .column th.small-7,
            .body .columns td.small-7,
            .body .columns th.small-7,
            td.small-7 center,
            th.small-7 center {
                display: inline-block!important;
                width: 58.33333%!important
            }
            .body .column td.small-8,
            .body .column th.small-8,
            .body .columns td.small-8,
            .body .columns th.small-8,
            td.small-8 center,
            th.small-8 center {
                display: inline-block!important;
                width: 66.66667%!important
            }
            .body .column td.small-9,
            .body .column th.small-9,
            .body .columns td.small-9,
            .body .columns th.small-9,
            td.small-9 center,
            th.small-9 center {
                display: inline-block!important;
                width: 75%!important
            }
            .body .column td.small-10,
            .body .column th.small-10,
            .body .columns td.small-10,
            .body .columns th.small-10,
            td.small-10 center,
            th.small-10 center {
                display: inline-block!important;
                width: 83.33333%!important
            }
            .body .column td.small-11,
            .body .column th.small-11,
            .body .columns td.small-11,
            .body .columns th.small-11,
            td.small-11 center,
            th.small-11 center {
                display: inline-block!important;
                width: 91.66667%!important
            }
            table.body td.small-offset-1,
            table.body th.small-offset-1 {
                margin-left: 8.33333%!important;
                Margin-left: 8.33333%!important
            }
            table.body td.small-offset-2,
            table.body th.small-offset-2 {
                margin-left: 16.66667%!important;
                Margin-left: 16.66667%!important
            }
            table.body td.small-offset-3,
            table.body th.small-offset-3 {
                margin-left: 25%!important;
                Margin-left: 25%!important
            }
            table.body td.small-offset-4,
            table.body th.small-offset-4 {
                margin-left: 33.33333%!important;
                Margin-left: 33.33333%!important
            }
            table.body td.small-offset-5,
            table.body th.small-offset-5 {
                margin-left: 41.66667%!important;
                Margin-left: 41.66667%!important
            }
            table.body td.small-offset-6,
            table.body th.small-offset-6 {
                margin-left: 50%!important;
                Margin-left: 50%!important
            }
            table.body td.small-offset-7,
            table.body th.small-offset-7 {
                margin-left: 58.33333%!important;
                Margin-left: 58.33333%!important
            }
            table.body td.small-offset-8,
            table.body th.small-offset-8 {
                margin-left: 66.66667%!important;
                Margin-left: 66.66667%!important
            }
            table.body td.small-offset-9,
            table.body th.small-offset-9 {
                margin-left: 75%!important;
                Margin-left: 75%!important
            }
            table.body td.small-offset-10,
            table.body th.small-offset-10 {
                margin-left: 83.33333%!important;
                Margin-left: 83.33333%!important
            }
            table.body td.small-offset-11,
            table.body th.small-offset-11 {
                margin-left: 91.66667%!important;
                Margin-left: 91.66667%!important
            }
            table.body table.columns td.expander,
            table.body table.columns th.expander {
                display: none!important
            }
            table.body .right-text-pad,
            table.body .text-pad-right {
                padding-left: 10px!important
            }
            table.body .left-text-pad,
            table.body .text-pad-left {
                padding-right: 10px!important
            }
            table.menu {
                width: 100%!important
            }
            table.menu td,
            table.menu th {
                width: auto!important;
                display: inline-block!important
            }
            table.menu.small-vertical td,
            table.menu.small-vertical th,
            table.menu.vertical td,
            table.menu.vertical th {
                display: block!important
            }
            table.button.expand {
                width: 100%!important
            }
        }


         .neobutton:hover{
          background-color: #49c5fe;
          color: #fff;
         }
    </style>
    <table class="body" style="Margin:0;background-color:#37456e!important;background: radial-gradient(ellipse at bottom,#3f4e7b 30%,#273454 100%);background-repeat:no-repeat;background-position:bottom center;background-attachment:scroll;border-collapse:collapse;border-spacing:0;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;height:100%;line-height:19px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;vertical-align:top;width:100%;">
        <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;vertical-align:top;">
            <td class="center" align="center" valign="top" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;hyphens:auto;line-height:19px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;vertical-align:top;word-wrap:break-word;">
                <center data-parsed="" style="min-width:580px;width:100%;">
                    <table class="container text-center" style="Margin:0 auto;background-color:transparent;background-image:none;background-repeat:repeat;background-position:0 0;background-attachment:scroll;border-collapse:collapse;border-spacing:0;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:center;vertical-align:top;width:580px;">
                        <tbody>
                            <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;vertical-align:top;">
                                <td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;hyphens:auto;line-height:19px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;vertical-align:top;word-wrap:break-word;">
                                    <table class="row header" style="border-collapse:collapse;border-spacing:0;display:table;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;position:relative;text-align:left;vertical-align:top;width:100%;">
                                        <tbody>
                                            <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;vertical-align:top;">
                                                <th class="small-12 large-12 columns first last" style="Margin:0
auto;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:19px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto;padding-top:0;padding-bottom:16px;padding-right:16px;padding-left:16px;text-align:left;width:564px;">
                                                    <table style="border-collapse:collapse;border-spacing:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;vertical-align:top;width:100%;">
                                                        <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;vertical-align:top;">
                                                            <th style="Margin:0;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:19px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;">
                                                                <center data-parsed="" style="min-width:564px;width:100%;">
                                                                    <img src="http://i.imgur.com/EDSl9y0.png" align="center" class="text-center" style="-ms-interpolation-mode:bicubic;Margin:0 auto;clear:both;display:block;float:none;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto;max-width:100%;outline-color:0;text-align:center;text-decoration:none;width:auto;">
                                                                </center>
                                                            </th>
                                                            <th class="expander" style="Margin:0;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:19px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0 !important;padding-bottom:0 !important;padding-right:0 !important;padding-left:0 !important;text-align:left;visibility:hidden;width:0;"></th>
                                                        </tr>
                                                    </table>
                                                </th>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="row title" style="border-collapse:collapse;border-spacing:0;display:table;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;position:relative;text-align:left;vertical-align:top;width:100%;">
                                        <tbody>
                                            <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;vertical-align:top;">
                                                <th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:19px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto;padding-top:0;padding-bottom:16px;padding-right:16px;padding-left:16px;text-align:left;width:564px;">
                                                    <table style="border-collapse:collapse;border-spacing:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;vertical-align:top;width:100%;">
                                                        <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;vertical-align:top;">
                                                            <th style="Margin:0;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:19px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;">
                                                                <h1 class="title-heading text-center" style="Margin:0;Margin-bottom:10px;color:#49c5fe;font-family:Helvetica,Arial,sans-serif;font-size:32px;font-weight:700;line-height:1.3;margin-top:0;margin-bottom:10px;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:center;word-wrap:normal;">DynEd Live Session Reminder</h1>
                                                               <!--  <p class="title-subheading text-center" style="Margin:0;Margin-bottom:32px;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:24px;font-weight:400;line-height:19px;margin-top:0;margin-bottom:10px;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:center;">is here.</p> -->
                                                                <br>
                                                                <center data-parsed="" style="min-width:564px;width:100%;">

                                                                </center>
                                                            </th>
                                                            <th class="expander" style="Margin:0;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:19px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0 !important;padding-bottom:0 !important;padding-right:0 !important;padding-left:0 !important;text-align:left;visibility:hidden;width:0;"></th>
                                                        </tr>
                                                    </table>
                                                </th>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="row main" style="border-collapse:collapse;border-spacing:0;display:table;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;position:relative;text-align:left;vertical-align:top;width:100%;">
                                        <tbody>
                                            <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;vertical-align:top;">
                                                <th class="small-12 large-12 columns first last" style=" background-color: rgba(44, 54, 84, 0.51); Margin:0 auto;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:19px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto;padding-top:0;padding-bottom:16px;padding-right:16px;padding-left:16px;text-align:left;width:564px;">
                                                    <table style="border-collapse:collapse;border-spacing:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;vertical-align:top;width:100%;">
                                                        <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;vertical-align:top;">

                                                            <th style="Margin:0;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:19px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;">
                                                                    <br>
                                                                    <br>
                                                                <p style="Margin:0;Margin-bottom:10px;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:15px;font-weight:700;line-height:19px;margin-top:0;margin-bottom:10px;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;">Hi '.$studentname.',</p>
                                                                <p style="Margin:0;Margin-bottom:10px;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:15px;font-weight:400;line-height:19px;margin-top:0;margin-bottom:10px;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;letter-spacing:1px;">You Have A live session booked!</p><br><br>
                                                                <p style="Margin:0;Margin-bottom:10px;color:#d6ca2e;font-family:Helvetica,Arial,sans-serif;font-size:18px;font-weight:700;line-height:19px;margin-top:0;margin-bottom:10px;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:center;">Detail Session Information</p>
                                                                <p style="Margin:0;Margin-bottom:10px;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:15px;font-weight:700;line-height:27px;margin-top:0;margin-bottom:10px;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;">Coach Name <span style="margin-left:31px;">: '.$coachname.'</span><br/> Date<span style="margin-left:94px">: '.$date.'</span><br/>Time <span style="margin-left:87px;">: From '.$start.' To '.$end.' '.$tz.'</span></p>
                                                                <p style="Margin:0;Margin-bottom:10px;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:15px;font-weight:400;line-height:19px;margin-top:0;margin-bottom:10px;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;"></br></br>For more Details, go to the DynEd Live page. Thank You!</p>

                                                                <br>
                                                            </th>
                                                            <th class="expander" style="Margin:0;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:19px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0 !important;padding-bottom:0 !important;padding-right:0 !important;padding-left:0 !important;text-align:left;visibility:hidden;width:0;"></th>
                                                        </tr>
                                                    </table>
                                                </th>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <table class="row footer" style="border-collapse:collapse;border-spacing:0;display:table;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;position:relative;text-align:left;vertical-align:top;width:100%;">
                                        <tbody>
                                            <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;vertical-align:top;">
                                                <th class="small-12 large-12 columns first last" style="Margin:0
auto;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:19px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto;padding-top:0;padding-bottom:16px;padding-right:16px;padding-left:16px;text-align:left;width:564px;">
                                                    <table style="border-collapse:collapse;border-spacing:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;vertical-align:top;width:100%;">
                                                        <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;vertical-align:top;">
                                                            <th style="Margin:0;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:19px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:left;">
                                                                <center data-parsed="" style="min-width:564px;width:100%;">
                                                                     <br>
                                                                    <p class="text-center" align="center" style="Margin:0;Margin-bottom:10px;color:#afa8c9;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:19px;margin-top:0;margin-bottom:10px;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:center;"><a href="https://live.myneo.space" class="neobutton" style="text-decoration: none; background:#3f4e7b;border:1px solid #49c5fe;border-radius:100px;margin:0 auto;padding:.75em 1.5em;-webkit-transition:all ease .5s;transition:all ease .5s;cursor:pointer;margin-top:15px;outline:0;min-width:160px;background-color:#3f4e7b;color:#49c5fe;">Login To DynEd Live</a></p>
                                                                    <br>

                                                                    <p class="text-center" align="center" style="Margin:0;Margin-bottom:10px;color:#afa8c9;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:19px;margin-top:0;margin-bottom:10px;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;text-align:center;">DynEd Live © '.$year.' DynEd International, Inc. All rights reserved.</p>

                                                                    <br align="center" class="text-center">
                                                                    <br align="center" class="text-center">

                                                                </center>
                                                            </th>
                                                            <th class="expander" style="Margin:0;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:19px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0 !important;padding-bottom:0 !important;padding-right:0 !important;padding-left:0 !important;text-align:left;visibility:hidden;width:0;"></th>
                                                        </tr>
                                                    </table>
                                                </th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </center>
            </td>
        </tr>
    </table>
    <img src="https://foundation.cmail20.com/t/y-o-ddiiijy-jkjdltldtt/o.gif" width="1" height="1" border="0" alt="" style="visibility:hidden !important;display:block !important;height:1px !important;width:1px !important;border-width:0 !important;margin-top:0 !important;margin-bottom:0 !important;margin-right:0 !important;margin-left:0 !important;padding-top:0 !important;padding-bottom:0 !important;padding-right:0
!important;padding-left:0 !important;" />
</body>

</html>';

        $this->CI->queue->push($this->tube, $b2c_rem, 'email.send_email');

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