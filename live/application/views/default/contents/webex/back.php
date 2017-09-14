<html>
    <head>
        <title>Return Status</title>
        <script language=JavaScript>
            var strAction = "";
            var strSearch = location.search;
            if (strSearch == "" || strSearch == "?")
                strSearch = "";
            if (strSearch.indexOf("?") == 0)
                strSearch = strSearch.substring(1, strSearch.length);
            var arrSearch = strSearch.split("&");
            for (i = 0; i < arrSearch.length; i++) {
                arrPair = arrSearch[i].split("=");
                if (arrPair.length == 2 && arrPair[0] == "AT")
                    strAction = arrPair[1];
                if (arrPair.length == 2 && arrPair[0] == "WID")
                    strWID = arrPair[1];
                if (arrPair.length == 2 && arrPair[0] == "ST")
                    strStatus = arrPair[1];
                if (arrPair.length == 2 && arrPair[0] == "RS")
                    strReason = arrPair[1];
                if (arrPair.length == 2 && arrPair[0] == "MK")
                    strMeetingKey = arrPair[1];
                if (arrPair.length == 2 && arrPair[0] == "TN")
                    strTeleNumber = arrPair[1];
                if (arrPair.length == 2 && arrPair[0] == "OID")
                    strOfficeID = arrPair[1];
                if (arrPair.length == 2 && arrPair[0] == "OURL")
                    strOfficeURL = arrPair[1];
            }
            if (strAction == "PL" || strAction == "LI") {// Login
                if (strStatus == "SUCCESS" || (strStatus == "FAIL" && strReason == "AlreadyLogon")) {
                    document.write("Welcome, " + strWID + "!");
                } else {
                    document.write("Sorry, " + strWID + ", login failed. Reason is " + strReason);
                }
            }
            else if (strAction == "LO") {// Login
                if (strStatus == "SUCCESS") {
                    document.write("You've successfully logged out!");
                }
                else {
                    document.write("Failed to log out!");
                }
            }
            else if (strAction == "IM" || strAction == "HM") {// Instant Meeting or Start Scheduled Meeting
                if (strStatus == "SUCCESS") {
                    document.write("Meeting is closed");
                }
                else {
                    document.write("Sorry, meeting can not be started. Reason is " + strReason);
                }
            }
            else if (strAction == "JM") {// Join Meeting
                if (strStatus == "SUCCESS") {
                    document.write("Meeting is closed by the host");
                }
                else {
                    if (strReason == "InvalidMeetingKeyOrPassword")
                        document.write("Sorry, join meeting failed due to invalid meeting key or password.");
                    else if (strReason == "MeetingNotInProgress")
                        document.write("Sorry, join meeting failed because meeting is not open or already closed.");
                    if (strReason == "MeetingLocked")
                        document.write("Sorry, join meeting failed because the meeting is locked.");
                }
            }
            else if (strAction == "SM") {// Schedule Meeting
                if (strStatus == "SUCCESS") {
                    document.write("Meeting is scheduled. Meeting Key is " + strMeetingKey);
                    document.write("<form name=\"HostMeetingForm\" ACTION = \"https://apidemoeu.webex.com/apidemoeu/m.php\" METHOD = \"POST\">"
                            + "<INPUT TYPE=\"HIDDEN\" NAME=\"AT\" VALUE=\"HM\">"
                            + "<INPUT TYPE=\"HIDDEN\" NAME=\"MK\" VALUE=" + strMeetingKey + ">"
                            + "<INPUT TYPE=\"HIDDEN\" NAME=\"BU\" VALUE = \"http://localhost:8080/javascript/back.htm\">"
                            + "<INPUT TYPE=\"submit\" name=\"btnHostMeeting\" value = \"Start\"></form>");
                }
                else {
                    if (strReason == "AccessDenied")
                        document.write("Sorry, schedule meeting failed because you haven't logged in or timeout.");
                    else if (strReason == "TeleConferenceLineReserveFail")
                        document.write("Sorry, schedule meeting failed due to insufficient telephone lines available.");
                    if (strReason == "MeetingScheduleFail")
                        document.write("Sorry, schedule meeting failed due to insufficient system resource.");
                }
            }
            else if (strAction == "MO" || strAction == "CO") {// Create or Update WebEx Office
                if (strStatus == "SUCCESS") {
                    document.write("Office is created successfully. OfficeID=" + strOfficeID + ",OfficeURL=" + strOfficeURL);
                }
                else {
                    document.write("Sorry, office can not be created, you might haven't logged in");
                }
            }
            else if (strAction == "SS") {// Start Support Session
                if (strStatus == "SUCCESS") {
                    document.write("Support session ended normally.");
                }
                else {
                    document.write("Failed to start support session, reason is " + strReason);
                }
            }
        </script>
        <STYLE>
            .hdr {font-family:Verdana; font-size: 10pt; font-weight: bold; color: #000000;
                  background-color: #99CCFF }
            .reg {font-family:Verdana; font-size: 8pt; font-weight: bold; color: #000000;
                  background-color: #EEEEEE }
            .regr {font-family:Verdana; font-size: 8pt; font-weight: bold; color: #FF0000;
                   background-color: #EEEEEE }
            .regb {font-family:Verdana; font-size: 8pt; font-weight: bold; color: #0066ff;
                   background-color: #EEEEEE }
            .red {font-family:Verdana; font-size: 8pt; font-weight: bold; color: #FF0000;
                  background-color: #ffffff }
        </STYLE>
    </head>
</html>