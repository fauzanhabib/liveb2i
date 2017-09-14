<div>
    <form name="newuser" METHOD="POST" action="pre_register">
        <table>
            <tr>
                <td class="regr" width="50%" align="left">
                    Subdomain WebEx :&nbsp;&nbsp;
                </td>
                <td class="regr" width="50%" align="left">
                    <input type="text" name="SDW" size="20" tabindex="0">
                </td>
            </tr>
            <tr>
                <td class="regr" width="50%" align="left">
                    Partner ID (PID):&nbsp;&nbsp;
                </td>
                <td class="regr" width="50%" align="left">
                    <input type="text" name="PID" size="20" tabindex="1">
                </td>
            </tr>
            <tr>
                <td class="regr" width="50%" align="left">
                    First Name (FN):&nbsp;&nbsp;
                </td>
                <td class="regr" width="50%" align="left">
                    <input type="text" name="FN" size="20" tabindex="2">
                </td>
            </tr>
            <tr>
                <td class="regr" width="50%" align="left">
                    Last Name (LN):&nbsp;&nbsp;
                </td>
                <td class="regr" width="50%" align="left">
                    <input type="text" name="LN" size="20" tabindex="3">
                </td>
            </tr>
            <tr>
                <td class="regr" width="50%" align="left">
                    Email Address (EM):&nbsp;&nbsp;
                </td>
                <td class="regr" width="50%" align="left">
                    <input type="text" name="EM" size="20" tabindex="4">
                </td>
            </tr>
            <tr>
                <td class="regr" width="50%" align="left">
                    WebEx ID (WID):&nbsp;&nbsp;
                </td>
                <td class="regr" width="50%" align="left">
                    <input type="text" name="WID" size="20" tabindex="5">
                </td>
            </tr>
            <tr>
                <td class="regr" width="50%" align="left">
                    Password (PW):&nbsp;&nbsp;
                </td>
                <td class="regr" width="50%" align="left">
                    <input type="password" name="PW" size="20" tabindex="6">
                </td>
            </tr>
            <tr>
                <td class="regr" width="50%" align="left">
                    Timezone (TZ):&nbsp;&nbsp;
                </td>
                <td class="regr" width="50%" align="left">
                    <?php echo timezone_menu();?>
                </td>
            </tr>
            <tr>
                <td class="reg" width="50%" align="left">
                    Site ID (SiteID):&nbsp;&nbsp;
                </td>
                <td class="reg" width="50%" align="left">
                    <input type="text" name="SiteID" size="20" tabindex="7">
                </td>
            </tr>
            <tr>
                <td class="reg" width="50%" align="left">
                    Site Name (SiteName):&nbsp;&nbsp;
                </td>
                <td class="reg" width="50%" align="left">
                    <input type="text" name="SiteName" size="20" tabindex="8">
                </td>
            </tr>
            <tr>
                <td class="reg">
                    <input type="submit" value="Submit" name="B1" tabindex="9">
                    <input type="reset" value="Reset" name="B2" tabindex="10">
                </td>
            </tr>
        </table>
    </form>
</div>
