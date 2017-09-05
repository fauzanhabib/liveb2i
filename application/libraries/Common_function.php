<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

/**
 * Class Common_function
 * Class library for common functions like generate random string, gender, etc
 * @author      Ponel Panjaitan <ponel@pistarlabs.co.id>
 */
class Common_function {

    /**
     * var $ci
     * CodeIgniter Instance
     */
    private $CI;
    // country code
    var $country_code = array(
        array("name" => "Afghanistan", "dial_code" => "+93"),
        array("name" => "Albania", "dial_code" => "+355"),
        array("name" => "Algeria", "dial_code" => "+213"),
        array("name" => "American Samoa", "dial_code" => "+1"),
        array("name" => "Andorra", "dial_code" => "+376"),
        array("name" => "Angola", "dial_code" => "+244"),
        array("name" => "Anguilla", "dial_code" => "+1"),
        array("name" => "Antigua", "dial_code" => "+1"),
        array("name" => "Argentina", "dial_code" => "+54"),
        array("name" => "Armenia", "dial_code" => "+374"),
        array("name" => "Aruba", "dial_code" => "+297"),
        array("name" => "Australia", "dial_code" => "+61"),
        array("name" => "Austria", "dial_code" => "+43"),
        array("name" => "Azerbaijan", "dial_code" => "+994"),
        array("name" => "Bahrain", "dial_code" => "+973"),
        array("name" => "Bangladesh", "dial_code" => "+880"),
        array("name" => "Barbados", "dial_code" => "+1"),
        array("name" => "Belarus", "dial_code" => "+375"),
        array("name" => "Belgium", "dial_code" => "+32"),
        array("name" => "Belize", "dial_code" => "+501"),
        array("name" => "Benin", "dial_code" => "+229"),
        array("name" => "Bermuda", "dial_code" => "+1"),
        array("name" => "Bhutan", "dial_code" => "+975"),
        array("name" => "Bolivia", "dial_code" => "+591"),
        array("name" => "Bosnia and Herzegovina", "dial_code" => "+387"),
        array("name" => "Botswana", "dial_code" => "+267"),
        array("name" => "Brazil", "dial_code" => "+55"),
        array("name" => "British Indian Ocean Territory", "dial_code" => "+246"),
        array("name" => "British Virgin Islands", "dial_code" => "+1"),
        array("name" => "Brunei", "dial_code" => "+673"),
        array("name" => "Bulgaria", "dial_code" => "+359"),
        array("name" => "Burkina Faso", "dial_code" => "+226"),
        array("name" => "Burma Myanmar", "dial_code" => "+95"),
        array("name" => "Burundi", "dial_code" => "+257"),
        array("name" => "Cambodia", "dial_code" => "+855"),
        array("name" => "Cameroon", "dial_code" => "+237"),
        array("name" => "Canada", "dial_code" => "+1"),
        array("name" => "Cape Verde", "dial_code" => "+238"),
        array("name" => "Cayman Islands", "dial_code" => "+1"),
        array("name" => "Central African Republic", "dial_code" => "+236"),
        array("name" => "Chad", "dial_code" => "+235"),
        array("name" => "Chile", "dial_code" => "+56"),
        array("name" => "China", "dial_code" => "+86"),
        array("name" => "Colombia", "dial_code" => "+57"),
        array("name" => "Comoros", "dial_code" => "+269"),
        array("name" => "Cook Islands", "dial_code" => "+682"),
        array("name" => "Costa Rica", "dial_code" => "+506"),
        array("name" => "Côte d'Ivoire", "dial_code" => "+225"),
        array("name" => "Croatia", "dial_code" => "+385"),
        array("name" => "Cuba", "dial_code" => "+53"),
        array("name" => "Cyprus", "dial_code" => "+357"),
        array("name" => "Czech Republic", "dial_code" => "+420"),
        array("name" => "Democratic Republic of Congo", "dial_code" => "+243"),
        array("name" => "Denmark", "dial_code" => "+45"),
        array("name" => "Djibouti", "dial_code" => "+253"),
        array("name" => "Dominica", "dial_code" => "+1"),
        array("name" => "Dominican Republic", "dial_code" => "+1"),
        array("name" => "Ecuador", "dial_code" => "+593"),
        array("name" => "Egypt", "dial_code" => "+20"),
        array("name" => "El Salvador", "dial_code" => "+503"),
        array("name" => "Equatorial Guinea", "dial_code" => "+240"),
        array("name" => "Eritrea", "dial_code" => "+291"),
        array("name" => "Estonia", "dial_code" => "+372"),
        array("name" => "Ethiopia", "dial_code" => "+251"),
        array("name" => "Falkland Islands", "dial_code" => "+500"),
        array("name" => "Faroe Islands", "dial_code" => "+298"),
        array("name" => "Federated States of Micronesia", "dial_code" => "+691"),
        array("name" => "Fiji", "dial_code" => "+679"),
        array("name" => "Finland", "dial_code" => "+358"),
        array("name" => "France", "dial_code" => "+33"),
        array("name" => "French Guiana", "dial_code" => "+594"),
        array("name" => "French Polynesia", "dial_code" => "+689"),
        array("name" => "Gabon", "dial_code" => "+241"),
        array("name" => "Georgia", "dial_code" => "+995"),
        array("name" => "Germany", "dial_code" => "+49"),
        array("name" => "Ghana", "dial_code" => "+233"),
        array("name" => "Gibraltar", "dial_code" => "+350"),
        array("name" => "Greece", "dial_code" => "+30"),
        array("name" => "Greenland", "dial_code" => "+299"),
        array("name" => "Grenada", "dial_code" => "+1"),
        array("name" => "Guadeloupe", "dial_code" => "+590"),
        array("name" => "Guam", "dial_code" => "+1"),
        array("name" => "Guatemala", "dial_code" => "+502"),
        array("name" => "Guinea", "dial_code" => "+224"),
        array("name" => "Guinea-Bissau", "dial_code" => "+245"),
        array("name" => "Guyana", "dial_code" => "+592"),
        array("name" => "Haiti", "dial_code" => "+509"),
        array("name" => "Honduras", "dial_code" => "+504"),
        array("name" => "Hong Kong", "dial_code" => "+852"),
        array("name" => "Hungary", "dial_code" => "+36"),
        array("name" => "Iceland", "dial_code" => "+354"),
        array("name" => "India", "dial_code" => "+91"),
        array("name" => "Indonesia", "dial_code" => "+62"),
        array("name" => "Iran", "dial_code" => "+98"),
        array("name" => "Iraq", "dial_code" => "+964"),
        array("name" => "Ireland", "dial_code" => "+353"),
        array("name" => "Israel", "dial_code" => "+972"),
        array("name" => "Italy", "dial_code" => "+39"),
        array("name" => "Jamaica", "dial_code" => "+1"),
        array("name" => "Japan", "dial_code" => "+81"),
        array("name" => "Jordan", "dial_code" => "+962"),
        array("name" => "Kazakhstan", "dial_code" => "+7"),
        array("name" => "Kenya", "dial_code" => "+254"),
        array("name" => "Kiribati", "dial_code" => "+686"),
        array("name" => "Kosovo", "dial_code" => "+381"),
        array("name" => "Kuwait", "dial_code" => "+965"),
        array("name" => "Kyrgyzstan", "dial_code" => "+996"),
        array("name" => "Laos", "dial_code" => "+856"),
        array("name" => "Latvia", "dial_code" => "+371"),
        array("name" => "Lebanon", "dial_code" => "+961"),
        array("name" => "Lesotho", "dial_code" => "+266"),
        array("name" => "Liberia", "dial_code" => "+231"),
        array("name" => "Libya", "dial_code" => "+218"),
        array("name" => "Liechtenstein", "dial_code" => "+423"),
        array("name" => "Lithuania", "dial_code" => "+370"),
        array("name" => "Luxembourg", "dial_code" => "+352"),
        array("name" => "Macau", "dial_code" => "+853"),
        array("name" => "Macedonia", "dial_code" => "+389"),
        array("name" => "Madagascar", "dial_code" => "+261"),
        array("name" => "Malawi", "dial_code" => "+265"),
        array("name" => "Malaysia", "dial_code" => "+60"),
        array("name" => "Maldives", "dial_code" => "+960"),
        array("name" => "Mali", "dial_code" => "+223"),
        array("name" => "Malta", "dial_code" => "+356"),
        array("name" => "Marshall Islands", "dial_code" => "+692"),
        array("name" => "Martinique", "dial_code" => "+596"),
        array("name" => "Mauritania", "dial_code" => "+222"),
        array("name" => "Mauritius", "dial_code" => "+230"),
        array("name" => "Mayotte", "dial_code" => "+262"),
        array("name" => "Mexico", "dial_code" => "+52"),
        array("name" => "Moldova", "dial_code" => "+373"),
        array("name" => "Monaco", "dial_code" => "+377"),
        array("name" => "Mongolia", "dial_code" => "+976"),
        array("name" => "Montenegro", "dial_code" => "+382"),
        array("name" => "Montserrat", "dial_code" => "+1"),
        array("name" => "Morocco", "dial_code" => "+212"),
        array("name" => "Mozambique", "dial_code" => "+258"),
        array("name" => "Namibia", "dial_code" => "+264"),
        array("name" => "Nauru", "dial_code" => "+674"),
        array("name" => "Nepal", "dial_code" => "+977"),
        array("name" => "Netherlands", "dial_code" => "+31"),
        array("name" => "Netherlands Antilles", "dial_code" => "+599"),
        array("name" => "New Caledonia", "dial_code" => "+687"),
        array("name" => "New Zealand", "dial_code" => "+64"),
        array("name" => "Nicaragua", "dial_code" => "+505"),
        array("name" => "Niger", "dial_code" => "+227"),
        array("name" => "Nigeria", "dial_code" => "+234"),
        array("name" => "Niue", "dial_code" => "+683"),
        array("name" => "Norfolk Island", "dial_code" => "+672"),
        array("name" => "North Korea", "dial_code" => "+850"),
        array("name" => "Northern Mariana Islands", "dial_code" => "+1"),
        array("name" => "Norway", "dial_code" => "+47"),
        array("name" => "Oman", "dial_code" => "+968"),
        array("name" => "Pakistan", "dial_code" => "+92"),
        array("name" => "Palau", "dial_code" => "+680"),
        array("name" => "Palestine", "dial_code" => "+970"),
        array("name" => "Panama", "dial_code" => "+507"),
        array("name" => "Papua New Guinea", "dial_code" => "+675"),
        array("name" => "Paraguay", "dial_code" => "+595"),
        array("name" => "Peru", "dial_code" => "+51"),
        array("name" => "Philippines", "dial_code" => "+63"),
        array("name" => "Poland", "dial_code" => "+48"),
        array("name" => "Portugal", "dial_code" => "+351"),
        array("name" => "Puerto Rico", "dial_code" => "+1"),
        array("name" => "Qatar", "dial_code" => "+974"),
        array("name" => "Republic of the Congo", "dial_code" => "+242"),
        array("name" => "Réunion", "dial_code" => "+262"),
        array("name" => "Romania", "dial_code" => "+40"),
        array("name" => "Russia", "dial_code" => "+7"),
        array("name" => "Rwanda", "dial_code" => "+250"),
        array("name" => "Saint Barthélemy", "dial_code" => "+590"),
        array("name" => "Saint Helena", "dial_code" => "+290"),
        array("name" => "Saint Kitts and Nevis", "dial_code" => "+1"),
        array("name" => "Saint Martin", "dial_code" => "+590"),
        array("name" => "Saint Pierre and Miquelon", "dial_code" => "+508"),
        array("name" => "Saint Vincent and the Grenadines", "dial_code" => "+1"),
        array("name" => "Samoa", "dial_code" => "+685"),
        array("name" => "San Marino", "dial_code" => "+378"),
        array("name" => "São Tomé and Príncipe", "dial_code" => "+239"),
        array("name" => "Saudi Arabia", "dial_code" => "+966"),
        array("name" => "Senegal", "dial_code" => "+221"),
        array("name" => "Serbia", "dial_code" => "+381"),
        array("name" => "Seychelles", "dial_code" => "+248"),
        array("name" => "Sierra Leone", "dial_code" => "+232"),
        array("name" => "Singapore", "dial_code" => "+65"),
        array("name" => "Slovakia", "dial_code" => "+421"),
        array("name" => "Slovenia", "dial_code" => "+386"),
        array("name" => "Solomon Islands", "dial_code" => "+677"),
        array("name" => "Somalia", "dial_code" => "+252"),
        array("name" => "South Africa", "dial_code" => "+27"),
        array("name" => "South Korea", "dial_code" => "+82"),
        array("name" => "Spain", "dial_code" => "+34"),
        array("name" => "Sri Lanka", "dial_code" => "+94"),
        array("name" => "St. Lucia", "dial_code" => "+1"),
        array("name" => "Sudan", "dial_code" => "+249"),
        array("name" => "Suriname", "dial_code" => "+597"),
        array("name" => "Swaziland", "dial_code" => "+268"),
        array("name" => "Sweden", "dial_code" => "+46"),
        array("name" => "Switzerland", "dial_code" => "+41"),
        array("name" => "Syria", "dial_code" => "+963"),
        array("name" => "Taiwan", "dial_code" => "+886"),
        array("name" => "Tajikistan", "dial_code" => "+992"),
        array("name" => "Tanzania", "dial_code" => "+255"),
        array("name" => "Thailand", "dial_code" => "+66"),
        array("name" => "The Bahamas", "dial_code" => "+1"),
        array("name" => "The Gambia", "dial_code" => "+220"),
        array("name" => "Timor-Leste", "dial_code" => "+670"),
        array("name" => "Togo", "dial_code" => "+228"),
        array("name" => "Tokelau", "dial_code" => "+690"),
        array("name" => "Tonga", "dial_code" => "+676"),
        array("name" => "Trinidad and Tobago", "dial_code" => "+1"),
        array("name" => "Tunisia", "dial_code" => "+216"),
        array("name" => "Turkey", "dial_code" => "+90"),
        array("name" => "Turkmenistan", "dial_code" => "+993"),
        array("name" => "Turks and Caicos Islands", "dial_code" => "+1"),
        array("name" => "Tuvalu", "dial_code" => "+688"),
        array("name" => "Uganda", "dial_code" => "+256"),
        array("name" => "Ukraine", "dial_code" => "+380"),
        array("name" => "United Arab Emirates", "dial_code" => "+971"),
        array("name" => "United Kingdom", "dial_code" => "+44"),
        array("name" => "United States", "dial_code" => "+1"),
        array("name" => "Uruguay", "dial_code" => "+598"),
        array("name" => "US Virgin Islands", "dial_code" => "+1"),
        array("name" => "Uzbekistan", "dial_code" => "+998"),
        array("name" => "Vanuatu", "dial_code" => "+678"),
        array("name" => "Vatican City", "dial_code" => "+39"),
        array("name" => "Venezuela", "dial_code" => "+58"),
        array("name" => "Vietnam", "dial_code" => "+84"),
        array("name" => "Wallis and Futuna", "dial_code" => "+681"),
        array("name" => "Yemen", "dial_code" => "+967"),
        array("name" => "Zambia", "dial_code" => "+260"),
        array("name" => "Zimbabwe", "dial_code" => "+263"),
    );

    public function __construct() {

        $this->CI = &get_instance();
        $this->CI->load->model('user_profile_model');
    }

    /**
     * Get User Notification
     * @return mixed
     */
    public function new_notification() {
        $this->CI->db->where('user_id', $this->CI->auth->userid());
        // status 2 means that the notification never been opened before
        $this->CI->db->where('status', 2);
        $this->CI->db->from('user_notifications');
        $notification = $this->CI->db->count_all_results();
        //$notification = $this->CI->user_notification_model->where('user_id', $this->CI->auth->userid())->count_all_result();
        return(@$notification);
    }

    /**
     * Get coach ongoing session
     * @return mixed
     */
    public function ongoing_session_coach() {
        $this->CI->db->where('status', 'active');
        $this->CI->db->where('coach_id', $this->CI->auth->userid());
        $this->CI->db->where('date =', date('Y-m-d'));
        $this->CI->db->where('start_time <=', date('H:i:s'));
        $this->CI->db->where('end_time >=', date('H:i:s'));
        $this->CI->db->from('appointments');

        $ongoing = $this->CI->db->count_all_results();

        $this->CI->db->where('coach_id', $this->CI->auth->userid());
        $this->CI->db->where('date =', date('Y-m-d'));
        $this->CI->db->where('start_time <=', date('H:i:s'));
        $this->CI->db->where('end_time >=', date('H:i:s'));
        $this->CI->db->from('class_meeting_days');

        $ongoing_class = $this->CI->db->count_all_results();
        return(@$ongoing + @$ongoing_class);
    }

    /**
     * Get coach ongoing session
     * @return mixed
     */
    public function ongoing_session_student() {
        $this->CI->db->where('status', 'active');
        $this->CI->db->where('student_id', $this->CI->auth->userid());
        $this->CI->db->where('date =', date('Y-m-d'));
        $this->CI->db->where('start_time <=', date('H:i:s'));
        $this->CI->db->where('end_time >=', date('H:i:s'));
        $this->CI->db->from('appointments');

        $ongoing = $this->CI->db->count_all_results();

        $this->CI->db->from('class_members cm');
        $this->CI->db->join('class_meeting_days cmd', 'cm.class_id = cmd.class_id');
        $this->CI->db->where('cm.student_id', $this->CI->auth->userid());
        $this->CI->db->where('cmd.date =', date('Y-m-d'));
        $this->CI->db->where('cmd.start_time <=', date('H:i:s'));
        $this->CI->db->where('cmd.end_time >=', date('H:i:s'));
        $ongoing_class = $this->CI->db->count_all_results();
        return(@$ongoing + @$ongoing_class);
    }

    /**
     * @function gender
     * @return array gender
     */
    public function gender() {
        return Array(
            '' => 'Select Gender',
            'Female' => 'Female',
            'Male' => 'Male',
            'Others' => 'Others'
        );
    }

    /**
     * @function gender
     * @return array gender
     */
    public function timezones() {
        $timezones = Array('0' => 'UM12', '1' => 'UM11', '2' => 'UM10', '3' => 'UM9', '4' => 'UM8', '5' => 'UM7', '7' => 'UM6', '10' => 'UM5', '13' => 'UM4', '15' => 'UM25', '16' => 'UM3', '18' => 'UM2', '19' => 'UM1', '20' => 'UTC', '22' => 'UP1', '26' => 'UP2', '32' => 'UP3', '35' => 'UP25', '36' => 'UP4', '38' => 'UP35', '39' => 'UP5', '41' => 'UP45', '42' => 'UP6', '44' => 'UP7', '45' => 'UP8', '49' => 'UP9', '51' => 'UP85', '54' => 'UP10', '59' => 'UP11', '61' => 'UP12');
        return $timezones;
    }

    /**
     * @function generate random password
     * @param (int) lenght
     */
    public function generate_random_string($length = 5) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    /**
     * Function is_date_available
     * @param (string)(date) date that will be checked wheather available ('Y-m-d')
     * @param (int)(day) sum of day()
     * @return return TRUE if date available
     */
    public function is_date_available($date, $day) {
        if ((DateTime::createFromFormat('Y-m-d', trim($date)) != FALSE) && (strtotime($date) >= strtotime(date('Y-m-d', strtotime("+" . $day . "days"))))) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * @function time_reminder_before_session
     * @param (string)(session_time) session time ('Y-m-d H:i:s')
     * @param (int)(delay_time) delay time before session time (s)
     *
     * @return if the function not return positive int, return FALSE
     */
    public function time_reminder_before_session($session_time, $delay_time) {
        if (DateTime::createFromFormat('Y-m-d H:i:s', trim($session_time)) != FALSE) {
            $now = (date('Y-m-d H:i:s', time() + $delay_time));
            return (((strtotime($session_time) - strtotime($now))) < 0 ? FALSE : (strtotime($session_time) - strtotime($now)));
        } else {
            return FALSE;
        }
    }

    public function language() {
        return array(
            'Afrikaans' => 'Afrikaans', 'Albanian' => 'Albanian', 'Armenian' => 'Armenian', 'Azerbaijan' => 'Azerbaijan',
            'Basque' => 'Basque', 'Belarusian' => 'Belarusian', 'Bengali' => 'Bengali', 'Bosnian' => 'Bosnian', 'Bulgarian' => 'Bulgarian',
            'Catalan' => 'Catalan', 'Cebuano' => 'Cebuano', 'Chichewa' => 'Chichewa', 'Chinese' => 'Chinese (Simplified)',
            'ChineseT' => 'Chinese (Traditional)', 'Croatian' => 'Croatian', 'Czech' => 'Czech', 'Danish' => 'Danish',
            'Dutch' => 'Dutch', 'English' => 'English', 'Esperanto' => 'Esperanto', 'Estonian' => 'Estonian', 'Filipino' => 'Filipino',
            'Finnish' => 'Finnish', 'French' => 'French', 'Galician' => 'Galician', 'Georgian' => 'Georgian', 'German' => 'German',
            'Greek' => 'Greek', 'Gujarati' => 'Gujarati', 'Haitian Creole' => 'Haitian Creole', 'Hausa' => 'Hausa', 'Hebrew' => 'Hebrew',
            'Hindi' => 'Hindi', 'Hmong' => 'Hmong', 'Hungarian' => 'Hungarian', 'Icelandic' => 'Icelandic', 'Igbo' => 'Igbo',
            'Indonesian' => 'Indonesian', 'Irish' => 'Irish', 'Italian' => 'Italian', 'Japanese' => 'Japanese', 'Javanese' => 'Javanese',
            'Kannada' => 'Kannada', 'Kazakh' => 'Kazakh', 'Khmer' => 'Khmer', 'Korean' => 'Korean', 'Lao' => 'Lao', 'Latin' => 'Latin',
            'LAV' => 'Latvian', 'LIT' => 'Lithuanian', 'MAC' => 'Macedonian', 'MLG' => 'Malagasy', 'MAY' => 'Malay',
            'Malayalam' => 'Malayalam', 'Maltese' => 'Maltese', 'Maori' => 'Maori', 'Marathi' => 'Marathi', 'Mongolian' => 'Mongolian',
            'Myanmar' => 'Myanmar (Burmese)', 'Nepali' => 'Nepali', 'Norwegian' => 'Norwegian', 'Persian' => 'Persian', 'Polish' => 'Polish',
            'Portugese' => 'Portugese', 'Punjabi' => 'Punjabi', 'Romanian' => 'Romanian', 'Russian' => 'Russian', 'Serbian' => 'Serbian',
            'Sesotho' => 'Sesotho', 'Sinhala' => 'Sinhala', 'Slovak' => 'Slovak', 'Slovenian' => 'Slovenian', 'Somali' => 'Somali',
            'Spanish' => 'Spanish', 'Sundanese' => 'Sundanese', 'Swahili' => 'Swahili', 'Swedish' => 'Swedish', 'Tajik' => 'Tajik',
            'Thai' => 'Thai', 'Tamil' => 'Tamil', 'Telugu' => 'Telugu', 'Turkish' => 'Turkish', 'Ukrainian' => 'Ukrainian',
            'Urdu' => 'Urdu', 'Uzbek' => 'Uzbek', 'Vietnamese' => 'Vietnamese', 'Welsh' => 'Welsh', 'Yiddish' => 'Yiddish',
            'Yoruba' => 'Yoruba', 'Zulu' => 'Zulu');
    }

    public function create_date_range_array($from_date_ = '', $to_date_ = '') {
        $from_date = \DateTime::createFromFormat('Y-m-d', $from_date_);
        $to_date = \DateTime::createFromFormat('Y-m-d', $to_date_);

        $date_period = new \DatePeriod(
                $from_date, new \DateInterval('P1D'), $to_date->modify('+1 day')
        );
        $dates = '';
        foreach ($date_period as $date) {
            $dates[] = $date->format('Y-m-d');
        }
        return $dates;
    }

    /**
     * @function create_link_pagination
     * @param (int)$page
     * @param (int)$offset
     * @param (string)$base_url
     * @param (int)$total_rows
     * @param (int)$per_page
     * @param (int)$uri_segment
     * @return pagination link  
     */
    public function create_link_pagination($page = '', &$offset = '', $base_url = '', $total_rows = '', $per_page = '', $uri_segment = '') {
        $this->CI->load->library('pagination');
        $config['base_url'] = $base_url;
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['use_page_numbers'] = TRUE;
        $config['uri_segment'] = $uri_segment;

        $config['first_link'] = '<i class="icon icon-previous"></i>';
        $config['last_link'] = '<i class="icon icon-next"></i>';
        $config['full_tag_open'] = '<ul class="pagging">';
        $config['full_tag_close'] = '</ul>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="selected">';
        $config['cur_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '<i class="icon icon-arrowright"></i>';
        $config['prev_link'] = '<i class="icon icon-arrowleft"></i>';

        if (($page == 1 && ($total_rows > $per_page)) || (!$page && ($total_rows > $per_page))) {
            $offset = "first_page";
        } elseif ($total_rows > $per_page) {
            $offset = ($page - 1) * $per_page;
        } else {
            return FALSE;
        }
        $this->CI->pagination->initialize($config);
        $pagination = $this->CI->pagination->create_links();
        return $pagination;
    }

    public function run_validation($rules=''){
        $this->CI->load->library('form_validation');
        if (!empty($rules)) {
            if (is_array($rules)) {
                $this->CI->form_validation->set_rules($rules);
                return $this->CI->form_validation->run();
            } else {
                return $this->CI->form_validation->run($rules);
            }
        } else {
            return TRUE;
        }
    }
    
    public function get_partner_type($partner_id = ''){
        if($this->CI->user_profile_model->get_students($partner_id, 3, 'first_page') && @$this->CI->user_profile_model->get_coaches($partner_id, 3, 'first_page')){
            return 'Student and Coach Supplier';
        }
        else if($this->CI->user_profile_model->get_students($partner_id, 3, 'first_page')){
            return 'Student Supplier';
        }
        else if(@$this->CI->user_profile_model->get_coaches($partner_id, 3, 'first_page')){
            return 'Coach Supplier';
        }
    }
    
    public function server_code(){
        return(
            array(
                'am1'=>'Americas',
                'us1'=>'Asia',
                'cn1'=>'China-1',
                'cn2'=>'China-2',
                'eu1'=>'Europe',
                'id1'=>'Indonesia-1',
                'id2'=>'Indonesia-2',
                'kr1'=>'Korea',
                'my1'=>'Malaysia',
                'mx1'=>'Mexico',
                'mn1'=>'Mongolia',
                'mone'=>'Turkey (Sadece Resmi Okullar)',
                'vn1'=>'Vietnam',
                'site11' => 'site11'
             ));
    }

    public function get_info_region($region_id = ''){
        return $this->CI->db->select('*')
                     ->from('user_profiles')
                     ->where('user_id',$region_id)
                     ->get()->result();

    }

    public function region_from_partner($partner_id = ''){
        return $this->CI->db->select('*')
                            ->from('partners')
                            ->where('id',$partner_id)
                            ->get()->result();

    }

    public function get_usertimezone($id = ''){
        $tz = $this->CI->db->select('*')
                ->from('user_timezones')
                ->where('user_id', $id)
                ->get()->result();

        
        $minutes = $tz[0]->minutes_val;
        return $minutes;
    }
}
