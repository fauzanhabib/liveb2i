<div>
    <form name="login" METHOD="POST" action="https://apidemoeu.webex.com/apidemoeu/p.php">
        <table>
            <tr>
                <td class="reg" width="50%">
                    Email Address (EM):&nbsp;&nbsp;</td>
                <td class="reg" width="50%">
                    <input type="text" name="EM" size="20" tabindex="3">
                </td>
            </tr>
            <tr>
                <td class="regr" width="50%">
                    WebEx ID (WID):&nbsp;&nbsp;
                </td>
                <td class="reg" width="50%"> <input type="text" name="WID" size="20" tabindex="4">
                </td>
            </tr>
            <tr>
                <td class="regr" width="50%">
                    Password (PW):&nbsp;&nbsp;
                </td>
                <td class="reg" width="50%"> <input type="password" name="PW" size="20" tabindex="5">
                </td>
            </tr>
            <tr>
                <td class="reg" width="50%">&nbsp;</td>
                <td class="reg" width="50%">&nbsp;</td>
            </tr>
            <tr>
                <td class="reg" width="100%" colspan="2">
                    <input type="submit" value="Submit" name="B1" tabindex="8">
                    <input type="reset" value="Reset" name="B2" tabindex="9">
                </td>
            </tr>
            

        </table>
        <input type="hidden" name="MU" value="GoBack">
        <input type="hidden" name="AT" value="LI">
        <input type="hidden" name="BU" value="http://idbuild.id.dyned.com/live/index.php/webex/validate/<?php echo @$appointment_id?>">
    </form>
</div>
