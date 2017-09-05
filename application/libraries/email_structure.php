<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

/**
 * Class email_structure
 * Class library for common functions like generate random string, gender, etc
 * @author      Ponel Panjaitan <ponel@pistarlabs.co.id>
 */
class email_structure {

    /**
     * var $ci
     * CodeIgniter Instance
     */
    private $CI;
    
    public function __construct() {
        $this->CI = &get_instance();
    }

    /**
     * Get User Notification
     * @return mixed
     */
    public function header1() {
        return('
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width" />
</head>
<body style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; background: #e9ecf1; color: #222222; font-family: "Open Sans", sans-serif; font-size: 14px; font-weight: normal; line-height: 19px; margin: 0; min-width: 100%; padding: 0; text-align: left; width: 100% !important" bgcolor="#e9ecf1">
<style type="text/css">
@font-face {
font-family: "Open Sans"; font-style: normal; font-weight: 300; src: local("Open Sans Light"), local("OpenSans-Light"), url("https://fonts.gstatic.com/s/opensans/v13/DXI1ORHCpsQm3Vp6mXoaTYnF5uFdDttMLvmWuJdhhgs.ttf") format("truetype");
}
@font-face {
font-family: "Open Sans"; font-style: normal; font-weight: 400; src: local("Open Sans"), local("OpenSans"), url("https://fonts.gstatic.com/s/opensans/v13/cJZKeOuBrn4kERxqtaUH3aCWcynf_cDxXwCLxiixG1c.ttf") format("truetype");
}
@font-face {
font-family: "Open Sans"; font-style: normal; font-weight: 600; src: local("Open Sans Semibold"), local("OpenSans-Semibold"), url("https://fonts.gstatic.com/s/opensans/v13/MTP_ySUJH_bn48VBG8sNSonF5uFdDttMLvmWuJdhhgs.ttf") format("truetype");
}
body {
width: 100% !important; min-width: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; margin: 0; padding: 0;
}
.ExternalClass {
width: 100%;
}
.ExternalClass {
line-height: 100%;
}
#backgroundTable {
margin: 0; padding: 0; width: 100% !important; line-height: 100% !important;
}
img {
outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; width: auto; max-width: 100%; float: left; clear: both; display: block;
}
body {
color: #222222; font-weight: normal; padding: 0; margin: 0; text-align: left; line-height: 1.3;
}
body {
font-size: 14px; line-height: 19px;
}
a:hover {
color: #2795b6 !important;
}
a:active {
color: #2795b6 !important;
}
a:visited {
color: #2ba6cb !important;
}
h1 a:active {
color: #2ba6cb !important;
}
h2 a:active {
color: #2ba6cb !important;
}
h3 a:active {
color: #2ba6cb !important;
}
h4 a:active {
color: #2ba6cb !important;
}
h5 a:active {
color: #2ba6cb !important;
}
h6 a:active {
color: #2ba6cb !important;
}
h1 a:visited {
color: #2ba6cb !important;
}
h2 a:visited {
color: #2ba6cb !important;
}
h3 a:visited {
color: #2ba6cb !important;
}
h4 a:visited {
color: #2ba6cb !important;
}
h5 a:visited {
color: #2ba6cb !important;
}
h6 a:visited {
color: #2ba6cb !important;
}
table.button:hover td {
background: #2795b6 !important;
}
table.button:visited td {
background: #2795b6 !important;
}
table.button:active td {
background: #2795b6 !important;
}
table.button:hover td a {
color: #fff !important;
}
table.button:visited td a {
color: #fff !important;
}
table.button:active td a {
color: #fff !important;
}
table.button:hover td {
background: #2795b6 !important;
}
table.tiny-button:hover td {
background: #2795b6 !important;
}
table.small-button:hover td {
background: #2795b6 !important;
}
table.medium-button:hover td {
background: #2795b6 !important;
}
table.large-button:hover td {
background: #2795b6 !important;
}
table.button:hover td a {
color: #ffffff !important;
}
table.button:active td a {
color: #ffffff !important;
}
table.button td a:visited {
color: #ffffff !important;
}
table.tiny-button:hover td a {
color: #ffffff !important;
}
table.tiny-button:active td a {
color: #ffffff !important;
}
table.tiny-button td a:visited {
color: #ffffff !important;
}
table.small-button:hover td a {
color: #ffffff !important;
}
table.small-button:active td a {
color: #ffffff !important;
}
table.small-button td a:visited {
color: #ffffff !important;
}
table.medium-button:hover td a {
color: #ffffff !important;
}
table.medium-button:active td a {
color: #ffffff !important;
}
table.medium-button td a:visited {
color: #ffffff !important;
}
table.large-button:hover td a {
color: #ffffff !important;
}
table.large-button:active td a {
color: #ffffff !important;
}
table.large-button td a:visited {
color: #ffffff !important;
}
table.secondary:hover td {
background: #d0d0d0 !important; color: #555;
}
table.secondary:hover td a {
color: #555 !important;
}
table.secondary td a:visited {
color: #555 !important;
}
table.secondary:active td a {
color: #555 !important;
}
table.success:hover td {
background: #457a1a !important;
}
table.alert:hover td {
background: #970b0e !important;
}
body.outlook p {
display: inline !important;
}
body {
font-family: "Open Sans", sans-serif; background: #e9ecf1;
}
@media only screen and (max-width: 600px) {
  table[class="body"] img {
    width: auto !important; height: auto !important;
  }
  table[class="body"] center {
    min-width: 0 !important;
  }
  table[class="body"] .container {
    width: 95% !important;
  }
  table[class="body"] .row {
    width: 100% !important; display: block !important;
  }
  table[class="body"] .wrapper {
    display: block !important; padding-right: 0 !important;
  }
  table[class="body"] .columns {
    table-layout: fixed !important; float: none !important; width: 100% !important; padding-right: 0px !important; padding-left: 0px !important; display: block !important;
  }
  table[class="body"] .column {
    table-layout: fixed !important; float: none !important; width: 100% !important; padding-right: 0px !important; padding-left: 0px !important; display: block !important;
  }
  table[class="body"] .wrapper.first .columns {
    display: table !important;
  }
  table[class="body"] .wrapper.first .column {
    display: table !important;
  }
  table[class="body"] table.columns td {
    width: 100% !important;
  }
  table[class="body"] table.column td {
    width: 100% !important;
  }
  table[class="body"] .columns td.one {
    width: 8.333333% !important;
  }
  table[class="body"] .column td.one {
    width: 8.333333% !important;
  }
  table[class="body"] .columns td.two {
    width: 16.666666% !important;
  }
  table[class="body"] .column td.two {
    width: 16.666666% !important;
  }
  table[class="body"] .columns td.three {
    width: 25% !important;
  }
  table[class="body"] .column td.three {
    width: 25% !important;
  }
  table[class="body"] .columns td.four {
    width: 33.333333% !important;
  }
  table[class="body"] .column td.four {
    width: 33.333333% !important;
  }
  table[class="body"] .columns td.five {
    width: 41.666666% !important;
  }
  table[class="body"] .column td.five {
    width: 41.666666% !important;
  }
  table[class="body"] .columns td.six {
    width: 50% !important;
  }
  table[class="body"] .column td.six {
    width: 50% !important;
  }
  table[class="body"] .columns td.seven {
    width: 58.333333% !important;
  }
  table[class="body"] .column td.seven {
    width: 58.333333% !important;
  }
  table[class="body"] .columns td.eight {
    width: 66.666666% !important;
  }
  table[class="body"] .column td.eight {
    width: 66.666666% !important;
  }
  table[class="body"] .columns td.nine {
    width: 75% !important;
  }
  table[class="body"] .column td.nine {
    width: 75% !important;
  }
  table[class="body"] .columns td.ten {
    width: 83.333333% !important;
  }
  table[class="body"] .column td.ten {
    width: 83.333333% !important;
  }
  table[class="body"] .columns td.eleven {
    width: 91.666666% !important;
  }
  table[class="body"] .column td.eleven {
    width: 91.666666% !important;
  }
  table[class="body"] .columns td.twelve {
    width: 100% !important;
  }
  table[class="body"] .column td.twelve {
    width: 100% !important;
  }
  table[class="body"] td.offset-by-one {
    padding-left: 0 !important;
  }
  table[class="body"] td.offset-by-two {
    padding-left: 0 !important;
  }
  table[class="body"] td.offset-by-three {
    padding-left: 0 !important;
  }
  table[class="body"] td.offset-by-four {
    padding-left: 0 !important;
  }
  table[class="body"] td.offset-by-five {
    padding-left: 0 !important;
  }
  table[class="body"] td.offset-by-six {
    padding-left: 0 !important;
  }
  table[class="body"] td.offset-by-seven {
    padding-left: 0 !important;
  }
  table[class="body"] td.offset-by-eight {
    padding-left: 0 !important;
  }
  table[class="body"] td.offset-by-nine {
    padding-left: 0 !important;
  }
  table[class="body"] td.offset-by-ten {
    padding-left: 0 !important;
  }
  table[class="body"] td.offset-by-eleven {
    padding-left: 0 !important;
  }
  table[class="body"] table.columns td.expander {
    width: 1px !important;
  }
  table[class="body"] .right-text-pad {
    padding-left: 10px !important;
  }
  table[class="body"] .text-pad-right {
    padding-left: 10px !important;
  }
  table[class="body"] .left-text-pad {
    padding-right: 10px !important;
  }
  table[class="body"] .text-pad-left {
    padding-right: 10px !important;
  }
  table[class="body"] .hide-for-small {
    display: none !important;
  }
  table[class="body"] .show-for-desktop {
    display: none !important;
  }
  table[class="body"] .show-for-small {
    display: inherit !important;
  }
  table[class="body"] .hide-for-desktop {
    display: inherit !important;
  }
  .p {
    font-size: 13px; line-height: 2em;
  }
  h1 {
    font-size: 28px;
  }
}
</style>
    <table class="body" style="border-collapse: collapse; border-spacing: 0; color: #222222; font-size: 14px; font-weight: normal; height: 100%; line-height: 19px; margin: 0; padding: 0; text-align: left; vertical-align: top; width: 100%">
      <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
        <td class="center" align="center" valign="top" style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0; text-align: center; vertical-align: top; word-break: break-word">
          <center style="min-width: 580px; width: 100%">

          <table class="container" style="border-collapse: collapse; border-spacing: 0; margin: 0 auto; padding: 0; text-align: inherit; vertical-align: top; width: 580px">
            <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
              <td style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0; text-align: left; vertical-align: top; word-break: break-word" align="left" valign="top">

                <table class="row" style="border-collapse: collapse; border-spacing: 0; display: block; padding: 0px; position: relative; text-align: left; vertical-align: top; width: 100%">
                  <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                    <td class="wrapper last" style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 10px 0px 0px; position: relative; text-align: left; vertical-align: top; word-break: break-word" align="left" valign="top">

                      <table class="twelve columns" style="border-collapse: collapse; border-spacing: 0; margin: 0 auto; padding: 0; text-align: left; vertical-align: top; width: 580px">
                        <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                          <td style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0px 0px 10px; text-align: left; vertical-align: top; word-break: break-word" align="left" valign="top">
                            <img class="logo center" src="' .base_url("assets/images/logo.png"). '" style="-ms-interpolation-mode: bicubic; clear: both; display: block; float: none; margin: 15px auto 0; max-width: 100%; outline: none; text-decoration: none; width: 124px" align="none" />
                          </td>
                          <td class="expander" style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0; text-align: left; vertical-align: top; visibility: hidden; width: 0px; word-break: break-word" align="left" valign="top"></td>
                        </tr>
                      </table>

                    </td>
                  </tr>
                </table>

                <table class="row" style="border-collapse: collapse; border-spacing: 0; display: block; padding: 0px; position: relative; text-align: left; vertical-align: top; width: 100%">
                  <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                    <td class="wrapper last" style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 10px 0px 0px; position: relative; text-align: left; vertical-align: top; word-break: break-word" align="left" valign="top">

                      <table style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top">
                        <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                          <td class="panel" style="-moz-hyphens: auto; -webkit-hyphens: auto; background: #fff; border-collapse: collapse !important; border: 1px solid #d9d9d9; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0 auto; padding: 0px; text-align: left; vertical-align: top; word-break: break-word" align="left" bgcolor="#fff" valign="top">
                            <table class="banner" style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top">
                              <tbody>
                                <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                                  <td style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0; text-align: left; vertical-align: top; word-break: break-word" align="left" valign="top">
                                    <img src="' .base_url("assets/images/banner.jpg"). '" style="-ms-interpolation-mode: bicubic; clear: both; display: block; float: left; max-width: 100%; outline: none; text-decoration: none; width: auto" align="left" />
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="descripttion" style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top">
                              <tbody>
                                <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                                  <td class="center" style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0; text-align: center; vertical-align: top; word-break: break-word" align="center" valign="top">
                                    <!-- Content -->
                                    <h1 class="center" style="color: #222222; font-size: 32px; font-weight: 600; line-height: 1.3; margin: 20px 0 0; padding: 0; text-align: center; word-break: normal" align="center">TITLE</h1>
                                    <p class="center p" style="color: #222222; font-size: 14px; font-weight: normal; line-height: 2.5em !important; margin: 20px auto 0; padding: 0; text-align: center; width: 85%" align="center">
                                      You have an appointment with student Panji Arafat Sirait, Please prepare yourself 5 minutes before start the session at Friday 31st of July 2015 <span class="green" style="color: #59ba81">15:00</span> until <span class="green" style="color: #59ba81">15:30</span>.
                                    </p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="content-button" style="border-collapse: collapse; border-spacing: 0; border-top-color: #D9D9D9; border-top-style: solid; border-top-width: 1px; padding: 0; text-align: left; vertical-align: top; width: 100%">
                              <tbody>
                                <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                                  <td style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0; text-align: left; vertical-align: top; word-break: break-word" align="left" valign="top">
                                    <center style="min-width: 580px; width: 100%">
                                    <!-- button -->
                                    <a href="#" class="button" style="background: #39AAE2; border-radius: 20px; border: 1px solid #39aae2; color: #fff; display: inline-block; font-size: 18px; font-weight: 600; margin: 15px 0; padding: 5px 20px; text-decoration: none">BUTTON1</a>
                                    </center>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </td>
                          <td class="expander" style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0; text-align: left; vertical-align: top; visibility: hidden; width: 0px; word-break: break-word" align="left" valign="top"></td>
                        </tr>
                      </table>

                    </td>
                  </tr>
                </table>
                <table class="row" style="border-collapse: collapse; border-spacing: 0; display: block; padding: 0px; position: relative; text-align: left; vertical-align: top; width: 100%">
                  <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                    <td class="wrapper last" style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 10px 0px 0px; position: relative; text-align: left; vertical-align: top; word-break: break-word" align="left" valign="top">

                      <table class="twelve columns" style="border-collapse: collapse; border-spacing: 0; margin: 0 auto; padding: 0; text-align: left; vertical-align: top; width: 580px">
                        <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                          <td class="p10" style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 10px; text-align: left; vertical-align: top; word-break: break-word" align="left" valign="top">
                            <table class="twelve columns" style="border-collapse: collapse; border-spacing: 0; margin: 0 auto; padding: 0; text-align: left; vertical-align: top; width: 580px">
                              <tbody>
                                <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                                  <td style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0px 0px 10px; text-align: left; vertical-align: top; word-break: break-word" align="left" valign="top">
                                    <!-- paragraph footer -->
                                    <p class="center p" style="color: #222222; font-size: 14px; font-weight: normal; line-height: 2.5em !important; margin: 20px auto 0; padding: 0; text-align: center; width: 85%" align="center">FOOTER</p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="twelve columns" style="border-collapse: collapse; border-spacing: 0; margin: 0 auto; padding: 0; text-align: left; vertical-align: top; width: 580px">
                              <tbody>
                                <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                                  <td class="center" style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0px 0px 10px; text-align: center; vertical-align: top; word-break: break-word" align="center" valign="top">
                                    <!-- link facebook -->
                                    <a href="#" style="color: #2ba6cb; text-decoration: none"><img class="mt20 mr20 center inline" src="' .base_url("assets/images/facebook.png"). '" style="-ms-interpolation-mode: bicubic; border: none; clear: both; display: inline-block; float: none; margin: 20px 20px 0 auto; max-width: 100%; outline: none; text-decoration: none; width: auto" align="none" /></a>
                                    <!-- link twitter -->
                                    <a href="#" style="color: #2ba6cb; text-decoration: none"><img class="mt20 center inline" src="' .base_url("assets/images/twitter.png"). '" style="-ms-interpolation-mode: bicubic; border: none; clear: both; display: inline-block; float: none; margin: 20px auto 0; max-width: 100%; outline: none; text-decoration: none; width: auto" align="none" /></a>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </td>
                          <td class="expander" style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0; text-align: left; vertical-align: top; visibility: hidden; width: 0px; word-break: break-word" align="left" valign="top"></td>
                        </tr>
                      </table>

                    </td>
                  </tr>
                </table>


              </td>
            </tr>
          </table>

          </center>
        </td>
      </tr>
    </table>
  </body>
</html>');
    }
    
    
    public function header(){
        return('
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width" />
  </head>
  <body style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; background: #e9ecf1; color: #222222; font-family: "Open Sans", sans-serif; font-size: 14px; font-weight: normal; line-height: 19px; margin: 0; min-width: 100%; padding: 0; text-align: left; width: 100% !important" bgcolor="#e9ecf1">
  <style type="text/css">
  @font-face {
  font-family: "Open Sans"; font-style: normal; font-weight: 300; src: local("Open Sans Light"), local("OpenSans-Light"), url("https://fonts.gstatic.com/s/opensans/v13/DXI1ORHCpsQm3Vp6mXoaTYnF5uFdDttMLvmWuJdhhgs.ttf") format("truetype");
  }
  @font-face {
  font-family: "Open Sans"; font-style: normal; font-weight: 400; src: local("Open Sans"), local("OpenSans"), url("https://fonts.gstatic.com/s/opensans/v13/cJZKeOuBrn4kERxqtaUH3aCWcynf_cDxXwCLxiixG1c.ttf") format("truetype");
  }
  @font-face {
  font-family: "Open Sans"; font-style: normal; font-weight: 600; src: local("Open Sans Semibold"), local("OpenSans-Semibold"), url("https://fonts.gstatic.com/s/opensans/v13/MTP_ySUJH_bn48VBG8sNSonF5uFdDttMLvmWuJdhhgs.ttf") format("truetype");
  }
  body {
  width: 100% !important; min-width: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; margin: 0; padding: 0;
  }
  .ExternalClass {
  width: 100%;
  }
  .ExternalClass {
  line-height: 100%;
  }
  #backgroundTable {
  margin: 0; padding: 0; width: 100% !important; line-height: 100% !important;
  }
  img {
  outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; width: auto; max-width: 100%; float: left; clear: both; display: block;
  }
  body {
  color: #222222; font-weight: normal; padding: 0; margin: 0; text-align: left; line-height: 1.3;
  }
  body {
  font-size: 14px; line-height: 19px;
  }
  a:hover {
  color: #2795b6 !important;
  }
  a:active {
  color: #2795b6 !important;
  }
  a:visited {
  color: #2ba6cb !important;
  }
  h1 a:active {
  color: #2ba6cb !important;
  }
  h2 a:active {
  color: #2ba6cb !important;
  }
  h3 a:active {
  color: #2ba6cb !important;
  }
  h4 a:active {
  color: #2ba6cb !important;
  }
  h5 a:active {
  color: #2ba6cb !important;
  }
  h6 a:active {
  color: #2ba6cb !important;
  }
  h1 a:visited {
  color: #2ba6cb !important;
  }
  h2 a:visited {
  color: #2ba6cb !important;
  }
  h3 a:visited {
  color: #2ba6cb !important;
  }
  h4 a:visited {
  color: #2ba6cb !important;
  }
  h5 a:visited {
  color: #2ba6cb !important;
  }
  h6 a:visited {
  color: #2ba6cb !important;
  }
  table.button:hover td {
  background: #2795b6 !important;
  }
  table.button:visited td {
  background: #2795b6 !important;
  }
  table.button:active td {
  background: #2795b6 !important;
  }
  table.button:hover td a {
  color: #fff !important;
  }
  table.button:visited td a {
  color: #fff !important;
  }
  table.button:active td a {
  color: #fff !important;
  }
  table.button:hover td {
  background: #2795b6 !important;
  }
  table.tiny-button:hover td {
  background: #2795b6 !important;
  }
  table.small-button:hover td {
  background: #2795b6 !important;
  }
  table.medium-button:hover td {
  background: #2795b6 !important;
  }
  table.large-button:hover td {
  background: #2795b6 !important;
  }
  table.button:hover td a {
  color: #ffffff !important;
  }
  table.button:active td a {
  color: #ffffff !important;
  }
  table.button td a:visited {
  color: #ffffff !important;
  }
  table.tiny-button:hover td a {
  color: #ffffff !important;
  }
  table.tiny-button:active td a {
  color: #ffffff !important;
  }
  table.tiny-button td a:visited {
  color: #ffffff !important;
  }
  table.small-button:hover td a {
  color: #ffffff !important;
  }
  table.small-button:active td a {
  color: #ffffff !important;
  }
  table.small-button td a:visited {
  color: #ffffff !important;
  }
  table.medium-button:hover td a {
  color: #ffffff !important;
  }
  table.medium-button:active td a {
  color: #ffffff !important;
  }
  table.medium-button td a:visited {
  color: #ffffff !important;
  }
  table.large-button:hover td a {
  color: #ffffff !important;
  }
  table.large-button:active td a {
  color: #ffffff !important;
  }
  table.large-button td a:visited {
  color: #ffffff !important;
  }
  table.secondary:hover td {
  background: #d0d0d0 !important; color: #555;
  }
  table.secondary:hover td a {
  color: #555 !important;
  }
  table.secondary td a:visited {
  color: #555 !important;
  }
  table.secondary:active td a {
  color: #555 !important;
  }
  table.success:hover td {
  background: #457a1a !important;
  }
  table.alert:hover td {
  background: #970b0e !important;
  }
  body.outlook p {
  display: inline !important;
  }
  body {
  font-family: "Open Sans", sans-serif; background: #e9ecf1;
  }
  @media only screen and (max-width: 600px) {
    table[class="body"] img {
      width: auto !important; height: auto !important;
    }
    table[class="body"] center {
      min-width: 0 !important;
    }
    table[class="body"] .container {
      width: 95% !important;
    }
    table[class="body"] .row {
      width: 100% !important; display: block !important;
    }
    table[class="body"] .wrapper {
      display: block !important; padding-right: 0 !important;
    }
    table[class="body"] .columns {
      table-layout: fixed !important; float: none !important; width: 100% !important; padding-right: 0px !important; padding-left: 0px !important; display: block !important;
    }
    table[class="body"] .column {
      table-layout: fixed !important; float: none !important; width: 100% !important; padding-right: 0px !important; padding-left: 0px !important; display: block !important;
    }
    table[class="body"] .wrapper.first .columns {
      display: table !important;
    }
    table[class="body"] .wrapper.first .column {
      display: table !important;
    }
    table[class="body"] table.columns td {
      width: 100% !important;
    }
    table[class="body"] table.column td {
      width: 100% !important;
    }
    table[class="body"] .columns td.one {
      width: 8.333333% !important;
    }
    table[class="body"] .column td.one {
      width: 8.333333% !important;
    }
    table[class="body"] .columns td.two {
      width: 16.666666% !important;
    }
    table[class="body"] .column td.two {
      width: 16.666666% !important;
    }
    table[class="body"] .columns td.three {
      width: 25% !important;
    }
    table[class="body"] .column td.three {
      width: 25% !important;
    }
    table[class="body"] .columns td.four {
      width: 33.333333% !important;
    }
    table[class="body"] .column td.four {
      width: 33.333333% !important;
    }
    table[class="body"] .columns td.five {
      width: 41.666666% !important;
    }
    table[class="body"] .column td.five {
      width: 41.666666% !important;
    }
    table[class="body"] .columns td.six {
      width: 50% !important;
    }
    table[class="body"] .column td.six {
      width: 50% !important;
    }
    table[class="body"] .columns td.seven {
      width: 58.333333% !important;
    }
    table[class="body"] .column td.seven {
      width: 58.333333% !important;
    }
    table[class="body"] .columns td.eight {
      width: 66.666666% !important;
    }
    table[class="body"] .column td.eight {
      width: 66.666666% !important;
    }
    table[class="body"] .columns td.nine {
      width: 75% !important;
    }
    table[class="body"] .column td.nine {
      width: 75% !important;
    }
    table[class="body"] .columns td.ten {
      width: 83.333333% !important;
    }
    table[class="body"] .column td.ten {
      width: 83.333333% !important;
    }
    table[class="body"] .columns td.eleven {
      width: 91.666666% !important;
    }
    table[class="body"] .column td.eleven {
      width: 91.666666% !important;
    }
    table[class="body"] .columns td.twelve {
      width: 100% !important;
    }
    table[class="body"] .column td.twelve {
      width: 100% !important;
    }
    table[class="body"] td.offset-by-one {
      padding-left: 0 !important;
    }
    table[class="body"] td.offset-by-two {
      padding-left: 0 !important;
    }
    table[class="body"] td.offset-by-three {
      padding-left: 0 !important;
    }
    table[class="body"] td.offset-by-four {
      padding-left: 0 !important;
    }
    table[class="body"] td.offset-by-five {
      padding-left: 0 !important;
    }
    table[class="body"] td.offset-by-six {
      padding-left: 0 !important;
    }
    table[class="body"] td.offset-by-seven {
      padding-left: 0 !important;
    }
    table[class="body"] td.offset-by-eight {
      padding-left: 0 !important;
    }
    table[class="body"] td.offset-by-nine {
      padding-left: 0 !important;
    }
    table[class="body"] td.offset-by-ten {
      padding-left: 0 !important;
    }
    table[class="body"] td.offset-by-eleven {
      padding-left: 0 !important;
    }
    table[class="body"] table.columns td.expander {
      width: 1px !important;
    }
    table[class="body"] .right-text-pad {
      padding-left: 10px !important;
    }
    table[class="body"] .text-pad-right {
      padding-left: 10px !important;
    }
    table[class="body"] .left-text-pad {
      padding-right: 10px !important;
    }
    table[class="body"] .text-pad-left {
      padding-right: 10px !important;
    }
    table[class="body"] .hide-for-small {
      display: none !important;
    }
    table[class="body"] .show-for-desktop {
      display: none !important;
    }
    table[class="body"] .show-for-small {
      display: inherit !important;
    }
    table[class="body"] .hide-for-desktop {
      display: inherit !important;
    }
    .p {
      font-size: 13px; line-height: 2em;
    }
    h1 {
      font-size: 28px;
    }
  }
</style>
    <table class="body" style="border-collapse: collapse; border-spacing: 0; color: #222222; font-size: 14px; font-weight: normal; height: 100%; line-height: 19px; margin: 0; padding: 0; text-align: left; vertical-align: top; width: 100%">
      <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
        <td class="center" align="center" valign="top" style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0; text-align: center; vertical-align: top; word-break: break-word">
          <center style="min-width: 580px; width: 100%">

          <table class="container" style="border-collapse: collapse; border-spacing: 0; margin: 0 auto; padding: 0; text-align: inherit; vertical-align: top; width: 580px">
            <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
              <td style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0; text-align: left; vertical-align: top; word-break: break-word" align="left" valign="top">

                <table class="row" style="border-collapse: collapse; border-spacing: 0; display: block; padding: 0px; position: relative; text-align: left; vertical-align: top; width: 100%">
                  <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                    <td class="wrapper last" style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 10px 0px 0px; position: relative; text-align: left; vertical-align: top; word-break: break-word" align="left" valign="top">

                      <table class="twelve columns" style="border-collapse: collapse; border-spacing: 0; margin: 0 auto; padding: 0; text-align: left; vertical-align: top; width: 580px">
                        <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                          <td style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0px 0px 10px; text-align: left; vertical-align: top; word-break: break-word" align="left" valign="top">
                            <!-- LOGO DYNED LIVE-->
                            <img class="logo center" src="'.base_url('assets/images/logo.png').'" style="-ms-interpolation-mode: bicubic; clear: both; display: block; float: none; margin: 15px auto 0; max-width: 100%; outline: none; text-decoration: none; width: 124px" align="none" />
                            <!-- END LOGO -->
                          </td>
                          <td class="expander" style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0; text-align: left; vertical-align: top; visibility: hidden; width: 0px; word-break: break-word" align="left" valign="top"></td>
                        </tr>
                      </table>

                    </td>
                  </tr>
                </table>

                <table class="row" style="border-collapse: collapse; border-spacing: 0; display: block; padding: 0px; position: relative; text-align: left; vertical-align: top; width: 100%">
                  <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                    <td class="wrapper last" style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 10px 0px 0px; position: relative; text-align: left; vertical-align: top; word-break: break-word" align="left" valign="top">

                      <table style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top">
                        <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                          <td class="panel" style="-moz-hyphens: auto; -webkit-hyphens: auto; background: #fff; border-collapse: collapse !important; border: 1px solid #d9d9d9; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0 auto; padding: 0px; text-align: left; vertical-align: top; word-break: break-word" align="left" bgcolor="#fff" valign="top">
                            <table class="banner" style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top">
                              <tbody>
                                <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                                  <td style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0; text-align: left; vertical-align: top; word-break: break-word" align="left" valign="top">
                                    <!-- BANNER-->
                                    <img src="'.base_url('assets/images/banner.jpg').'" style="-ms-interpolation-mode: bicubic; clear: both; display: block; float: left; max-width: 100%; outline: none; text-decoration: none; width: auto" align="left" />
                                    <!-- END BANNER-->
                                  </td>
                                </tr>
                              </tbody>
                            </table>

                            <!-- CONTENT -->
                            <table class="description" style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%">
                              <tbody>
                                <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                                  <td class="center" style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0; text-align: center; vertical-align: top; word-break: break-word" align="center" valign="top">
                                    <!-- TITLE-->');
    }
    public function title($index = ''){
        return('<h1 class="center" style="color: #222222; font-size: 32px; font-weight: 600; line-height: 1.3; margin: 20px 0 0; padding: 0; text-align: center; word-break: normal" align="center">'.@$index.'</h1>

                                    <!-- CONTENT -->');
    }
    
    public function content($index = ''){
//        return('<p class="center p" style="color: #222222; font-size: 14px; font-weight: normal; line-height: 2.5em !important; margin: 20px auto 0; padding: 0; text-align: center; width: 85%" align="center">
//                                      You have an appointment with student Panji Arafat Sirait, Please prepare yourself 5 minutes before start the session at Friday 31st of July 2015 <span class="green" style="color: #59ba81">15:00</span> until <span class="green" style="color: #59ba81">15:30</span>.
//                                    </p>');
        return('<p class="center p" style="color: #222222; font-size: 14px; font-weight: normal; line-height: 2.5em !important; margin: 20px auto 0; padding: 0; text-align: center; width: 85%" align="center">
                                      '.@$index.'
                                    </p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <!-- END CONTENT-->

                            <table class="content-button" style="border-collapse: collapse; border-spacing: 0; border-top-color: #D9D9D9; border-top-style: solid; border-top-width: 1px; padding: 0; text-align: left; vertical-align: top; width: 100%">
                              <tbody>
                                <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                                  <td style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0; text-align: left; vertical-align: top; word-break: break-word" align="left" valign="top">
                                    <center style="min-width: 580px; width: 100%">
                                    <!-- BUTTON -->');
    }
    
    public function button($index = ''){
        return('<a href="#" class="button" style="background: #39AAE2; border-radius: 20px; border: 1px solid #39aae2; color: #fff; display: inline-block; font-size: 18px; font-weight: 600; margin: 15px 0; padding: 5px 20px; text-decoration: none">'.@$index.'</a>
                                    <!-- END BUTTON -->
                                    </center>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </td>
                          <td class="expander" style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0; text-align: left; vertical-align: top; visibility: hidden; width: 0px; word-break: break-word" align="left" valign="top"></td>
                        </tr>
                      </table>

                    </td>
                  </tr>
                </table>
                <table class="row" style="border-collapse: collapse; border-spacing: 0; display: block; padding: 0px; position: relative; text-align: left; vertical-align: top; width: 100%">
                  <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                    <td class="wrapper last" style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 10px 0px 0px; position: relative; text-align: left; vertical-align: top; word-break: break-word" align="left" valign="top">

                      <table class="twelve columns" style="border-collapse: collapse; border-spacing: 0; margin: 0 auto; padding: 0; text-align: left; vertical-align: top; width: 580px">
                        <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                          <td class="p10" style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 10px; text-align: left; vertical-align: top; word-break: break-word" align="left" valign="top">
                            <table class="twelve columns" style="border-collapse: collapse; border-spacing: 0; margin: 0 auto; padding: 0; text-align: left; vertical-align: top; width: 580px">
                              <tbody>
                                <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                                  <td style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0px 0px 10px; text-align: left; vertical-align: top; word-break: break-word" align="left" valign="top">
                                    <!-- FOOTER -->');
    }
    public function footer($index = ''){
        return('<p class="center p" style="color: #222222; font-size: 14px; font-weight: normal; line-height: 2.5em !important; margin: 20px auto 0; padding: 0; text-align: center; width: 85%" align="center">'.@$index.'</p>
                                    <!-- END FOOTER -->
                                  </td>
                                </tr>
                              </tbody>
                            </table>

                            <!-- SOCIAL MEDIA ICON -->
                            <table class="twelve columns" style="border-collapse: collapse; border-spacing: 0; margin: 0 auto; padding: 0; text-align: left; vertical-align: top; width: 580px">
                              <tbody>
                                <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                                  <td class="center" style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0px 0px 10px; text-align: center; vertical-align: top; word-break: break-word" align="center" valign="top">
                                    <!-- FACEBOOK -->
                                    <a href="https://www.facebook.com/DynEdInternational" style="color: #2ba6cb; text-decoration: none"><img class="mt20 mr20 center inline" src="'.base_url('assets/images/facebook.png').'" style="-ms-interpolation-mode: bicubic; border: none; clear: both; display: inline-block; float: none; margin: 20px 20px 0 auto; max-width: 100%; outline: none; text-decoration: none; width: auto" align="none" /></a>
                                    <!-- END FACEBOOK -->

                                    <!-- TWITTER -->
                                    <a href="https://twitter.com/dyned_intl" style="color: #2ba6cb; text-decoration: none"><img class="mt20 center inline" src="'.base_url('assets/images/twitter.png').'" style="-ms-interpolation-mode: bicubic; border: none; clear: both; display: inline-block; float: none; margin: 20px auto 0; max-width: 100%; outline: none; text-decoration: none; width: auto" align="none" /></a>
                                    <!-- END TWITTER -->
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <!-- END SOCIAL MEDIA ICON -->
                          </td>
                          <td class="expander" style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #222222; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 19px; margin: 0; padding: 0; text-align: left; vertical-align: top; visibility: hidden; width: 0px; word-break: break-word" align="left" valign="top"></td>
                        </tr>
                      </table>

                    </td>
                  </tr>
                </table>


              </td>
            </tr>
          </table>

          </center>
        </td>
      </tr>
    </table>
  </body>
</html>');
    }
}
