<?php

defined('ABSPATH') or die('No direct access permitted');

// ajax call load follow buttons
function sfbp_lazy_callback()
{
    // initiate sfbp button class
    $sfbpButtons = new sfbpFollowButtons();

    // the buttons!
    echo $sfbpButtons->get_sfbp_buttons();

    // if the minimum follow count has been met
    if ($sfbpButtons->sfbp_settings['counters_enabled'] == 'Y' && $sfbpButtons->sfbpFollowCountData['total'] >= intval($sfbpButtons->sfbp_settings['min_follows'])) {

        // if not using custom images
        if ($sfbpButtons->sfbp_settings['custom_images'] != 'Y') {
            // add total follows here
            echo '<span class="sfbp-total-follows"><b>' . sfbp_format_number($sfbpButtons->sfbpFollowCountData['total']) . '</b></span>';
        }
    }

    // exit so zero is not returned
    exit;
}

// format the returned number
function sfbp_format_number($intNumber)
{
    // if the number is greater than or equal to 1000
    if ($intNumber >= 1000) {
        // divide by 1000 and add k
        $intNumber = round(($intNumber / 1000), 1) . 'k';
    }

    // return the number
    return $intNumber;
}

// the main follow buttons class
class sfbpFollowButtons
{
    // declare variables
    public $htmlFollowButtons = '';
    public $booShowFollowCount = false;
    public $strPageTitle;
    public $urlCurrentPage;

    // count variables
    public $sfbpFollowCountData = array();
    public $sfbpTotalFollowCount = 0;
    public $sfbpfacebookFollowCount = 0;
    public $sfbpgoogleFollowCount = 0;
    public $sfbplinkedinFollowCount = 0;
    public $sfbppinterestFollowCount = 0;
    public $sfbpredditFollowCount = 0;
    public $sfbpstumbleuponFollowCount = 0;
    public $sfbptwitterFollowCount = 0;
    public $sfbpvkFollowCount = 0;

    // construct buttons function
    public function sfbpFollowButtons()
    {
        // get sfbp settings
        $this->sfbp_settings = get_sfbp_settings();

        // explode saved include list and add to a new array
        $this->arrSelectedSFBP = explode(',', $this->sfbp_settings['selected_buttons']);

        $this->sfbp_settings['counters_enabled'] = false;

        // prepare array of buttons
        $this->buttons = json_decode(get_option('sfbp_buttons'), true);
    }

    // the buttons themselves
    public function get_sfbp_buttons()
    {
        // variables
        $this->strFollowText = stripslashes_deep($this->sfbp_settings['follow_text']);

        // if follow counts are enabled then get them
        if ($this->sfbp_settings['counters_enabled'] == 'Y') {
            $this->get_sfbp_follow_counts();
        }

        // follow text is above add it now
        if (in_array($this->sfbp_settings['text_placement'], array('above', 'left')) && $this->strFollowText != '') {
            // add follow text
            $this->htmlFollowButtons .= '<span class="sfbp-follow-text">' . stripslashes_deep($this->strFollowText) . '</span>';

            // if above add line break
            if ($this->sfbp_settings['text_placement'] == 'above') {
                $this->htmlFollowButtons .= '<br/>';
            }
        }

        // check if array is not empty
        if ($this->sfbp_settings['selected_buttons'] != '') {
            // explode saved include list and add to a new array
            $this->arrSelectedSFBP = explode(',', $this->sfbp_settings['selected_buttons']);

            // start the list
            $this->htmlFollowButtons .= '<ul class="sfbp-list">';

            // for each included button
            foreach ($this->arrSelectedSFBP as $strSelected) {
                // new list item
                $this->htmlFollowButtons .= '<li class="sfbp-li--' . $strSelected . '">';

                // prepare function name
                $strGetButton = 'sfbp_' . $strSelected;

                // add a button for each selected
                $this->htmlFollowButtons .= $this->$strGetButton();

                // new list item
                $this->htmlFollowButtons .= '</li>';
            }

            // if using custom images
//            if ($this->sfbp_settings['custom_images'] == 'Y') {
//                // add total count as a list item
//                $this->htmlFollowButtons .= '<li><span class="sfbp-total-follows">' . $this->sfbpFollowCountData['total'] . '</span></li>';
//            }

            // close the list
            $this->htmlFollowButtons .= '</ul>';
        }

        // follow text is right or below
        if (in_array($this->sfbp_settings['text_placement'], array('right', 'below')) && $this->strFollowText != '') {
            // if above add line break
            if ($this->sfbp_settings['text_placement'] == 'below') {
                $this->htmlFollowButtons .= '<br/>';
            }

            // add follow text
            $this->htmlFollowButtons .= '<span class="sfbp-follow-text">' . stripslashes_deep($this->strFollowText) . '</span>';
        }

        // return follow buttons
        return $this->htmlFollowButtons;
    }

    // get follow count data
    public function get_follow_counts()
    {
        // start a session in case one hasn't been for any reason
        @session_start();

        // check if already performing this
        if (isset($_SESSION['sfbp_' . $hash])) {
            // unset session var
            unset($_SESSION['sfbp_' . $hash]);

            // no need to continue
            return;
        }

        // set a unique session var to overcome race condition
        @$_SESSION['sfbp_' . $hash] == true;

        // global db
        global $wpdb;

        // get sfbp table name
        $table_name = $wpdb->prefix . 'sfbp_follow_counts';

        // query db
        $counts = $wpdb->get_row("SELECT data, expires FROM $table_name WHERE hash = '$hash'");

        // no match found, go get follow counts
        if ($counts === null) {
            return false;
        }

        // date and time now
        $now = date('Y-m-d H:i:s');

        // check if the follow counts have expired
        if ($now > $counts->expires) {
            // delete the record
            $wpdb->delete($table_name, array('hash' => $hash));

            // return false so that fresh counts are retrieved
            return false;
        }

        // match found, return it
        return $counts->data;
    }

    // follow counts function
    public function get_sfbp_follow_counts()
    {
        // hash the url
        $hash = md5($this->urlCurrentPage);

        // check if there's a follow count record for this url
        $counts = $this->get_url_follow_counts($hash);

        // if some follow counts were found
        if ($counts) {
            $this->sfbpFollowCountData = json_decode($counts, true);
        } // no follow counts were found in the db
        else {
            // an array of networks with follow counts
            $hasCounts = array(
                'facebook', 'google', 'linkedin', 'pinterest', 'reddit', 'stumbleupon', 'tumblr', 'twitter', 'vk', 'yummly',
            );

            // create empty total
            $total = 0;

            // loop through each selected button
            foreach ($this->arrSelectedSFBP as $strSelected) {
                // if this button has a follow count
                if (in_array($strSelected, $hasCounts)) {
                    // prepare class function and name
                    $strGetCount = 'sfbp_' . $strSelected . '_count';
                    $strThisCount = 'sfbp' . $strSelected . 'FollowCount';

                    // get the count
                    $this->$strGetCount($this->urlCurrentPage);

                    // add to total follow count
                    $total = $total + $this->$strThisCount;

                    // add to follow count data array
                    $this->sfbpFollowCountData[$strSelected] = $this->$strThisCount;
                }
            }

            // add total follows to the array
            $this->sfbpFollowCountData['total'] = $total;

            // global db
            global $wpdb;

            // get sfbp table name
            $table_name = $wpdb->prefix . 'sfbp_follow_counts';

            // typecast follow count cache
            $count_cache = (int)$this->sfbp_settings['count_cache'];

            // if count cache is less than 10 minutes
            $count_cache = ($count_cache < 600 ? 600 : $count_cache);

            // prepare expiry
            $expiry = time() + $count_cache;

            // insert the follow counts
            $wpdb->insert($table_name, array(
                'hash' => $hash,
                'data' => json_encode($this->sfbpFollowCountData),
                'expires' => date('Y-m-d H:i:s', $expiry),
            ));
        }
    }

    // get diggit button
    public function sfbp_diggit()
    {
        // diggit follow link
        $this->htmlFollowButtons .= '<a href="" class="sfbp-btn sfbp-diggit" ' . ($this->sfbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null). '>';

        // not using custom images
        if ($this->sfbp_settings['custom_images'] != 'Y') {
            $this->htmlFollowButtons .= '<span class="sfbp-text">Diggit</span>';
        } else {// using custom images
            $this->htmlFollowButtons .= '<img src="' . $this->sfbp_settings['custom_diggit'] . '" title="Follow on Digg" class="sfbp" alt="Follow on Digg" />';
        }

        // close link
        $this->htmlFollowButtons .= '</a>';
    }

    // get email button
    public function sfbp_email()
    {
        // prepare email popup class if needed
        if ($this->sfbp_settings['email_popup'] == 'Y') {
            // email follow link
            $this->htmlFollowButtons .= '<a href="javascript:;" class="sfbp-btn sfbp-email sfbp-email-popup">';
        } else {
            // email follow link
            $this->htmlFollowButtons .= '<a href="mailto:?Subject=&amp;Body=" class="sfbp-btn sfbp-email">';
        }

        // not using custom images
        if ($this->sfbp_settings['custom_images'] != 'Y') {
            $this->htmlFollowButtons .= '<span class="sfbp-text">Email</span>';
        } else {// using custom images
            $this->htmlFollowButtons .= '<img src="' . $this->sfbp_settings['custom_email'] . '" title="Email" class="sfbp" alt="Email" />';
        }

        // close link
        $this->htmlFollowButtons .= '</a>';
    }

    // get facebook button
    public function sfbp_facebook()
    {
        // declare variable
        $facebook_follow_count = '';
        $facebook_button2 = '';

        // if follow counts are enabled
        if ($this->sfbp_settings['counters_enabled'] == 'Y') {
            // if the min count has been met or exceeded
            if ((int)$this->sfbpFollowCountData['facebook'] >= intval($this->sfbp_settings['min_follows'])) {
                $facebook_follow_count = '<span class="sfbp-total-facebook-follows sfbp-each-follow">' . sfbp_format_number($this->sfbpFollowCountData['facebook']) . '</span>';
            }
        }

        // facebook follow link
        $facebook_link = '<a href="'.$this->buttons['facebook']['url_prefix'].$this->sfbp_settings['url_facebook'].'" class="sfbp-btn sfbp-facebook sfbp-facebook--standard" ' . ($this->sfbp_settings['rel_nofollow'] == 'Y' ? ' rel="nofollow"' : null).'>';

        // not using custom images
        if ($this->sfbp_settings['custom_images'] != 'Y') {
            $facebook_button2 = '<span class="sfbp-text">deliciousvalencia</span>';
        } else {// using custom images
            $facebook_button = '<img src="' . $this->sfbp_settings['custom_facebook'] . '" title="Follow on Facebook" class="sfbp" alt="Follow on Facebook" />';
        }

        // put button together
        $facebook_buttons = $facebook_link.$facebook_button.'</a>'.$facebook_button2.$facebook_follow_count;

        // add facebook buttons
        $this->htmlFollowButtons .= $facebook_buttons;
    }

    // get facebook follow counts
    public function sfbp_facebook_count()
    {
        // if using sfbp api
        if ($this->sfbp_settings['follow_api'] == 'Y') {
            // retrieve our license key from the DB
            $sfbpLicense_key = trim(get_option('sfbp_license_key'));

            // check license key is there
            if (!$sfbpLicense_key || $sfbpLicense_key == '') {
                // set and return 0
                $this->sfbpfacebookFollowCount = 0;

                return;
            }

            // encryption key
            $sfbpKey = '7649E9A8A8319D47D4499B316BEA3';

            // encrypt license key
            $sfbpLicense_key = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($sfbpKey), $sfbpLicense_key, MCRYPT_MODE_CBC, md5(md5($sfbpKey))));

            // get results from sfbp api
            $htmlSFBPAPI = wp_remote_post('https://api.simplefollowbuttons.com/v1/facebook_follow_count', array(
                'method' => 'POST',
                'timeout' => 4,
                'body' => array(
                    'url' => $this->urlCurrentPage,
                    'license' => $sfbpLicense_key,
                ),
            ));

            // check there was an error
            if (is_wp_error($htmlSFBPAPI)) {
                // set and return 0
                $this->sfbpfacebookFollowCount = 0;

                return;
            }

            // decode
            $arrSFBPAPI = json_decode($htmlSFBPAPI['body'], true);

            // check the response was successful
            if ($arrSFBPAPI['status'] == 'success' && isset($arrSFBPAPI['follow_count'])) {
                // get follow count
                $this->sfbpfacebookFollowCount = $arrSFBPAPI['follow_count'];

                // return
                return;
            }

            // return
            return;
        } else {
            // get results from facebook and return the number of follows
            $htmlFacebookFollowDetails = wp_remote_get('http://graph.facebook.com/' . $this->urlCurrentPage, array('timeout' => $this->sfbp_settings['count_timeout']));

            // if no error
            if (!is_wp_error($htmlFacebookFollowDetails)) {
                $arrFacebookFollowDetails = json_decode($htmlFacebookFollowDetails['body'], true);
                $intFacebookFollowCount = (isset($arrFacebookFollowDetails['follows']) ? $arrFacebookFollowDetails['follows'] : 0);
                $this->sfbpfacebookFollowCount = ($intFacebookFollowCount) ? $intFacebookFollowCount : '0';

                // return the count
                return $this->sfbpfacebookFollowCount;
            }

            // return
            return 0;
        }
    }

    // get google+ button
    public function sfbp_google()
    {
        // google follow link
        $this->htmlFollowButtons .= '<a href="'.$this->buttons['google']['url_prefix'].$this->sfbp_settings['url_google'].'" class="sfbp-btn sfbp-google" ' . ($this->sfbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null).'>';

        // not using custom images
        if ($this->sfbp_settings['custom_images'] != 'Y') {
            $this->htmlFollowButtons .= '<span class="sfbp-text">Google+</span>';
        } else {// using custom images
            $this->htmlFollowButtons .= '<img src="' . $this->sfbp_settings['custom_google'] . '" title="Follow on Google+" class="sfbp" alt="Follow on Google+" />';
        }

        // close link
        $this->htmlFollowButtons .= '</a>';

        // if follow counts are enabled
        if ($this->sfbp_settings['counters_enabled'] == 'Y') {

            // if the min count has been met or exceeded
            if ((int)$this->sfbpFollowCountData['google'] >= intval($this->sfbp_settings['min_follows'])) {
                $this->htmlFollowButtons .= '<span class="sfbp-total-google-follows sfbp-each-follow">' . sfbp_format_number($this->sfbpFollowCountData['google']) . '</span>';
            }
        }
    }

    // get google+ count
    public function sfbp_google_count()
    {
        // prepare data for post
        $args = array(
            'method' => 'POST',
            'headers' => array(
                // setup content type to JSON
                'Content-Type' => 'application/json',
            ),
            // setup POST options to Google API
            'body' => json_encode(array(
                'method' => 'pos.plusones.get',
                'id' => 'p',
                'method' => 'pos.plusones.get',
                'jsonrpc' => '2.0',
                'key' => 'p',
                'apiVersion' => 'v1',
                'params' => array(
                    'nolog' => true,
                    'id' => $this->urlCurrentPage,
                    'source' => 'widget',
                    'userId' => '@viewer',
                    'groupId' => '@self',
                ),
            )),
            // disable checking SSL sertificates
            'sslverify' => false,
        );

        // retrieves JSON with HTTP POST method for current URL
        $json_string = wp_remote_post('https://clients6.google.com/rpc', $args);

        // if there was an error
        if (is_wp_error($json_string)) {
            // zero if response is error
            $this->sfbpgoogleFollowCount = 0;

            // return
            return;
        }

        // decode result
        $json = json_decode($json_string['body'], true);

        // return count of Google +1 for requsted URL
        $this->sfbpgoogleFollowCount = intval($json['result']['metadata']['globalCounts']['count']);

        // return
        return;
    }

    // get instagram button
    public function sfbp_instagram()
    {
        // declare variable
        $follow_count = '';
        $button2 = '';

        // if follow counts are enabled
        if ($this->sfbp_settings['counters_enabled'] == 'Y') {
            // if the min count has been met or exceeded
            if ((int)$this->sfbpFollowCountData['instagram'] >= intval($this->sfbp_settings['min_follows'])) {
                $follow_count = '<span class="sfbp-total-instagram-follows sfbp-each-follow">' . sfbp_format_number($this->sfbpFollowCountData['instagram']) . '</span>';
            }
        }

        // instagram follow link
        $link = '<a href="'.$this->buttons['instagram']['url_prefix'].$this->sfbp_settings['url_instagram'].'" class="sfbp-btn sfbp-instagram" ' . ($this->sfbp_settings['rel_nofollow'] == 'Y' ? ' rel="nofollow"' : null).'>';

        // not using custom images
        if ($this->sfbp_settings['custom_images'] != 'Y') {
            $button2 = '<span class="sfbp-text">deliciousvlc</span>';
        } else {// using custom images
            $button = '<img src="' . $this->sfbp_settings['custom_instagram'] . '" title="Follow on Instagram" class="sfbp" alt="Follow on Instagram" />';
        }

        // put button together
        $button = $link.$button.'</a>'.$button2.$follow_count;

        // add button
        $this->htmlFollowButtons .= $button;
    }

    // get linkedin button
    public function sfbp_linkedin()
    {
        // linkedin follow link
        $this->htmlFollowButtons .= '<a href="'.$this->buttons['linkedin']['url_prefix'].$this->sfbp_settings['url_linkedin'].'" class="sfbp-btn sfbp-linkedin" ' . ($this->sfbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null).'>';

        // not using custom images
        if ($this->sfbp_settings['custom_images'] != 'Y') {
            $this->htmlFollowButtons .= '<span class="sfbp-text">Linkedin</span>';
        } else {// using custom images
            $this->htmlFollowButtons .= '<img src="' . $this->sfbp_settings['custom_linkedin'] . '" title="Follow on Linkedin" class="sfbp" alt="Follow on Linkedin" />';
        }

        // close link
        $this->htmlFollowButtons .= '</a>';

        // if follow counts are enabled
        if ($this->sfbp_settings['counters_enabled'] == 'Y') {

            // if the min count has been met or exceeded
            if ((int)$this->sfbpFollowCountData['linkedin'] >= intval($this->sfbp_settings['min_follows'])) {
                $this->htmlFollowButtons .= '<span class="sfbp-total-linkedin-follows sfbp-each-follow">' . sfbp_format_number($this->sfbpFollowCountData['linkedin']) . '</span>';
            }
        }
    }

    // get linkedin count
    public function sfbp_linkedin_count()
    {
        // get results from linkedin and return the number of follows
        $htmlLinkedinFollowDetails = wp_remote_get('http://www.linkedin.com/countserv/count/follow?url=' . $this->urlCurrentPage, array('timeout' => $this->sfbp_settings['count_timeout']));

        // if there was an error
        if (is_wp_error($htmlLinkedinFollowDetails)) {
            // set follow count
            $this->sfbplinkedinFollowCount = 0;

            // return
            return;
        }

        // extract/decode follow count
        $htmlLinkedinFollowDetails = str_replace('IN.Tags.Follow.handleCount(', '', $htmlLinkedinFollowDetails);
        $htmlLinkedinFollowDetails = str_replace(');', '', $htmlLinkedinFollowDetails);
        $arrLinkedinFollowDetails = json_decode($htmlLinkedinFollowDetails['body'], true);
        $intLinkedinFollowCount = $arrLinkedinFollowDetails['count'];
        $this->sfbplinkedinFollowCount = ($intLinkedinFollowCount) ? $intLinkedinFollowCount : '0';

        // return
        return;
    }

    // get pinterest button
    public function sfbp_pinterest()
    {
        $this->htmlFollowButtons2 = '';
        // pinterest follow link
        $this->htmlFollowButtons .= '<a href="'.$this->buttons['pinterest']['url_prefix'].$this->sfbp_settings['url_pinterest'].'" class="sfbp-btn sfbp-pinterest" ' . ($this->sfbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null).'>';

        // not using custom images
        if ($this->sfbp_settings['custom_images'] != 'Y') {
            $this->htmlFollowButtons2 = '<span class="sfbp-text">deliciousvlc</span>';
        } else {// using custom images
            $this->htmlFollowButtons .= '<img src="' . $this->sfbp_settings['custom_pinterest'] . '" title="Follow on Pinterest" class="sfbp" alt="Follow on Pinterest" />';
        }

        // close link
        $this->htmlFollowButtons .= '</a>'.$this->htmlFollowButtons2;

        // if follow counts are enabled
        if ($this->sfbp_settings['counters_enabled'] == 'Y') {

            // if the min count has been met or exceeded
            if ((int)$this->sfbpFollowCountData['pinterest'] >= intval($this->sfbp_settings['min_follows'])) {
                $this->htmlFollowButtons .= '<span class="sfbp-total-pinterest-follows sfbp-each-follow">' . sfbp_format_number($this->sfbpFollowCountData['pinterest']) . '</span>';
            }
        }
    }

    // get pinterest follow count
    public function sfbp_pinterest_count($urlCurrentPage)
    {
        // get results from pinterest
        $htmlPinterestFollowDetails = wp_remote_get('http://api.pinterest.com/v1/urls/count.json?url=' . $urlCurrentPage, array('timeout' => $this->sfbp_settings['count_timeout']));

        // check there was an error
        if (is_wp_error($htmlPinterestFollowDetails)) {
            // set follow count
            $this->sfbppinterestFollowCount = 0;

            return;
        }

        // decode data
        $htmlPinterestFollowDetails = str_replace('receiveCount(', '', $htmlPinterestFollowDetails);
        $htmlPinterestFollowDetails = str_replace(')', '', $htmlPinterestFollowDetails);
        $arrPinterestFollowDetails = json_decode($htmlPinterestFollowDetails['body'], true);
        $intPinterestFollowCount = $arrPinterestFollowDetails['count'];
        $this->sfbppinterestFollowCount = ($intPinterestFollowCount) ? $intPinterestFollowCount : '0';

        // return
        return;
    }

    // get reddit button
    public function sfbp_reddit()
    {
        // reddit follow link
        $this->htmlFollowButtons .= '<a href="'.$this->buttons['reddit']['url_prefix'].$this->sfbp_settings['url_reddit'].'" class="sfbp-btn sfbp-reddit" ' . ($this->sfbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null).'>';

        // not using custom images
        if ($this->sfbp_settings['custom_images'] != 'Y') {
            $this->htmlFollowButtons .= '<span class="sfbp-text">Reddit</span>';
        } else {// using custom images
            $this->htmlFollowButtons .= '<img src="' . $this->sfbp_settings['custom_reddit'] . '" title="Follow on Reddit" class="sfbp" alt="Follow on Reddit" />';
        }

        // close link
        $this->htmlFollowButtons .= '</a>';

        // if follow counts are enabled
        if ($this->sfbp_settings['counters_enabled'] == 'Y') {

            // if the min count has been met or exceeded
            if ((int)$this->sfbpFollowCountData['reddit'] >= intval($this->sfbp_settings['min_follows'])) {
                $this->htmlFollowButtons .= '<span class="sfbp-total-reddit-follows sfbp-each-follow">' . sfbp_format_number($this->sfbpFollowCountData['reddit']) . '</span>';
            }
        }
    }

    // get reddit count
    public function sfbp_reddit_count()
    {
        // get results from reddit and return the number of follows
        $htmlRedditFollowDetails = wp_remote_get('http://www.reddit.com/api/info.json?url=' . $this->urlCurrentPage, array('timeout' => $this->sfbp_settings['count_timeout']));

        // check there was an error
        if (is_wp_error($htmlRedditFollowDetails)) {
            // set follow count
            $this->sfbpredditFollowCount = 0;

            // return
            return;
        }

        // decode and get follow count
        $arrRedditResult = json_decode($htmlRedditFollowDetails['body'], true);
        $intRedditFollowCount = (isset($arrRedditResult['data']['children']['0']['data']['score']) ? $arrRedditResult['data']['children']['0']['data']['score'] : 0);
        $this->sfbpredditFollowCount = ($intRedditFollowCount) ? $intRedditFollowCount : '0';

        // return
        return;
    }

    // get soundcloud button
    public function sfbp_soundcloud()
    {
        // declare variable
        $follow_count = '';

        // if follow counts are enabled
        if ($this->sfbp_settings['counters_enabled'] == 'Y') {
            // if the min count has been met or exceeded
            if ((int)$this->sfbpFollowCountData['soundcloud'] >= intval($this->sfbp_settings['min_follows'])) {
                $follow_count = '<span class="sfbp-total-soundcloud-follows sfbp-each-follow">' . sfbp_format_number($this->sfbpFollowCountData['soundcloud']) . '</span>';
            }
        }

        // soundcloud follow link
        $link = '<a href="'.$this->buttons['soundcloud']['url_prefix'].$this->sfbp_settings['url_soundcloud'].'" class="sfbp-btn sfbp-soundcloud" ' . ($this->sfbp_settings['rel_nofollow'] == 'Y' ? ' rel="nofollow"' : null).'>';

        // not using custom images
        if ($this->sfbp_settings['custom_images'] != 'Y') {
            $button = '<span class="sfbp-text">SoundCloud</span>';
        } else {// using custom images
            $button = '<img src="' . $this->sfbp_settings['custom_soundcloud'] . '" title="Follow on soundcloud" class="sfbp" alt="Follow on soundcloud" />';
        }

        // put button together
        $button = $link.$button.'</a>'.$follow_count;

        // add button
        $this->htmlFollowButtons .= $button;
    }

    // get tumblr button
    public function sfbp_tumblr()
    {
        // tumblr follow link
        $this->htmlFollowButtons .= '<a href="'.$this->buttons['tumblr']['url_prefix'].$this->sfbp_settings['url_tumblr'].$this->buttons['tumblr']['url_suffix'].'" class="sfbp-btn sfbp-tumblr" ' . ($this->sfbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null).'>';

        // not using custom images
        if ($this->sfbp_settings['custom_images'] != 'Y') {
            $this->htmlFollowButtons .= '<span class="sfbp-text">tumblr</span>';
        } else {// using custom images
            $this->htmlFollowButtons .= '<img src="' . $this->sfbp_settings['custom_tumblr'] . '" title="Follow this on tumblr" class="sfbp" alt="Follow this on tumblr" />';
        }

        // close link
        $this->htmlFollowButtons .= '</a>';

        // if follow counts are enabled
        if ($this->sfbp_settings['counters_enabled'] == 'Y') {

            // if the min count has been met or exceeded
            if ((int)$this->sfbpFollowCountData['tumblr'] >= intval($this->sfbp_settings['min_follows'])) {
                $this->htmlFollowButtons .= '<span class="sfbp-total-tumblr-follows sfbp-each-follow">' . sfbp_format_number($this->sfbpFollowCountData['tumblr']) . '</span>';
            }
        }
    }

    // get tumblr follow count
    public function sfbp_tumblr_count()
    {
        // get results from tumblr and return the number of follows
        $htmlTumblrFollowDetails = wp_remote_get('http://api.tumblr.com/v2/follow/stats?url=' . $this->urlCurrentPage, array('timeout' => $this->sfbp_settings['count_timeout']));

        // check there was an error
        if (is_wp_error($htmlTumblrFollowDetails)) {
            // set follow count
            $this->sfbptumblrFollowCount = 0;

            // return
            return;
        }

        // decode data
        $arrTumblrResult = json_decode($htmlTumblrFollowDetails['body'], true);
        $intTumblrFollowCount = (isset($arrTumblrResult['response']['note_count']) ? $arrTumblrResult['response']['note_count'] : 0);
        $this->sfbptumblrFollowCount = ($intTumblrFollowCount) ? $intTumblrFollowCount : '0';

        // return
        return;
    }

    // get twitter button
    public function sfbp_twitter()
    {
        $twitter_follow_count = '';
        $twitterButton2 = '';

        // if follow counts are enabled
        if ($this->sfbp_settings['counters_enabled'] == 'Y') {
            // if the min count has been met or exceeded
            if ((int)$this->sfbpFollowCountData['twitter'] >= intval($this->sfbp_settings['min_follows'])) {
                $twitter_follow_count = '<span class="sfbp-total-twitter-follows sfbp-each-follow">' . sfbp_format_number($this->sfbpFollowCountData['twitter']) . '</span>';
            }
        }
        // twitter follow link
        $twitterLink = '<a href="'.$this->buttons['twitter']['url_prefix'].$this->sfbp_settings['url_twitter'].'" class="sfbp-btn sfbp-twitter sfbp-twitter--standard"' . ($this->sfbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null).'>';

        // not using custom images
        if ($this->sfbp_settings['custom_images'] != 'Y') {
            $twitterButton2 = '<span class="sfbp-text">@vlcfoodtour</span>';
        } else {// using custom images
            $twitterButton = '<img src="' . $this->sfbp_settings['custom_twitter'] . '" title="Tweet about this" class="sfbp" alt="Tweet about this" />';
        }

        // put button together
        $twitterButtons = $twitterLink.$twitterButton.'</a>'.$twitterButton2.$twitter_follow_count;

        // add one/two twitter follow buttons
        $this->htmlFollowButtons .= $twitterButtons;
    }

    // get twitter follow count
    public function sfbp_twitter_count()
    {
        // get results from twitter and return the number of follows
        $htmlTwitterFollowDetails = wp_remote_get('http://urls.api.twitter.com/1/urls/count.json?url=' . $this->urlCurrentPage, array('timeout' => $this->sfbp_settings['count_timeout']));

        // if error
        if (is_wp_error($htmlTwitterFollowDetails)) {
            // set follow count
            $this->sfbptwitterFollowCount = 0;

            // return
            return;
        }

        // get and decode count
        $arrTwitterFollowDetails = json_decode($htmlTwitterFollowDetails['body'], true);
        $intTwitterFollowCount = $arrTwitterFollowDetails['count'];
        $this->sfbptwitterFollowCount = ($intTwitterFollowCount) ? $intTwitterFollowCount : '0';

        // return
        return;
    }

    // get vk button
    public function sfbp_vk()
    {
        // vk follow link
        $this->htmlFollowButtons .= '<a href="'.$this->buttons['vk']['url_prefix'].$this->sfbp_settings['url_vk'].'" class="sfbp-btn sfbp-vk" ' . ($this->sfbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null).'>';
        $this->htmlFollowButtons2 = '';
        // not using custom images
        if ($this->sfbp_settings['custom_images'] != 'Y') {
            $this->htmlFollowButtons2 = '<span class="sfbp-text">vlcfoodtour</span>';
        } else {// using custom images
            $this->htmlFollowButtons .= '<img src="' . $this->sfbp_settings['custom_vk'] . '" title="Follow this on VK" class="sfbp" alt="Follow this on VK" />';
        }

        // close link
        $this->htmlFollowButtons .= '</a>'.$this->htmlFollowButtons2;

        // if follow counts are enabled
        if ($this->sfbp_settings['counters_enabled'] == 'Y') {

            // if the min count has been met or exceeded
            if ((int)$this->sfbpFollowCountData['vk'] >= intval($this->sfbp_settings['min_follows'])) {
                $this->htmlFollowButtons .= '<span class="sfbp-total-vk-follows sfbp-each-follow">' . sfbp_format_number($this->sfbpFollowCountData['vk']) . '</span>';
            }
        }
    }

    // get vk follow count
    public function sfbp_vk_count()
    {
        // get results from vk and return the number of follows
        $htmlVKFollowDetails = wp_remote_get('http://vk.com/follow.php?act=count&url=' . $this->urlCurrentPage, array('timeout' => $this->sfbp_settings['count_timeout']));

        // check there was an error
        if (is_wp_error($htmlVKFollowDetails)) {
            // set follow count
            $this->sfbpvkFollowCount = 0;

            // return
            return;
        } // follow data retrieved
        else {
            // decode data
            if (!$htmlVKFollowDetails['body']) {
                $this->sfbpvkFollowCount = 0;
            } else {
                preg_match('/^VK.Follow.count\((\d+),\s+(\d+)\);$/i', $htmlVKFollowDetails['body'], $matches);
                $this->sfbpvkFollowCount = $matches[2];
            }
        }

        // return
        return;
    }

    // get youtube button
    public function sfbp_youtube()
    {
        // declare variable
        $follow_count = '';

        // if follow counts are enabled
        if ($this->sfbp_settings['counters_enabled'] == 'Y') {
            // if the min count has been met or exceeded
            if ((int)$this->sfbpFollowCountData['youtube'] >= intval($this->sfbp_settings['min_follows'])) {
                $follow_count = '<span class="sfbp-total-youtube-follows sfbp-each-follow">' . sfbp_format_number($this->sfbpFollowCountData['youtube']) . '</span>';
            }
        }

        // youtube follow link
        $link = '<a href="'.$this->buttons['youtube']['url_prefix'].$this->sfbp_settings['url_youtube'].'" class="sfbp-btn sfbp-youtube" ' . ($this->sfbp_settings['rel_nofollow'] == 'Y' ? ' rel="nofollow"' : null).'>';

        // not using custom images
        if ($this->sfbp_settings['custom_images'] != 'Y') {
            $button = '<span class="sfbp-text">YouTube</span>';
        } else {// using custom images
            $button = '<img src="' . $this->sfbp_settings['custom_youtube'] . '" title="Follow on youtube" class="sfbp" alt="Follow on youtube" />';
        }

        // put button together
        $button = $link.$button.'</a>'.$follow_count;

        // add button
        $this->htmlFollowButtons .= $button;
    }

    // get yummly button
    public function sfbp_yummly()
    {
        // yummly follow link
        $this->htmlFollowButtons .= '<a href="'.$this->buttons['yummly']['url_prefix'].$this->sfbp_settings['url_yummly'].'" class="sfbp-btn sfbp-yummly" ' . ($this->sfbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null).'>';

        // not using custom images
        if ($this->sfbp_settings['custom_images'] != 'Y') {
            $this->htmlFollowButtons .= '<span class="sfbp-text">Yummly</span>';
        } else {// using custom images
            $this->htmlFollowButtons .= '<img src="' . $this->sfbp_settings['custom_yummly'] . '" title="Follow on Yummly" class="sfbp" alt="Yummly" />';
        }

        // close link
        $this->htmlFollowButtons .= '</a>';

        // if follow counts are enabled
        if ($this->sfbp_settings['counters_enabled'] == 'Y') {

            // if the min count has been met or exceeded
            if ((int)$this->sfbpFollowCountData['yummly'] >= intval($this->sfbp_settings['min_follows'])) {
                $this->htmlFollowButtons .= '<span class="sfbp-total-yummly-follows sfbp-each-follow">' . sfbp_format_number($this->sfbpFollowCountData['yummly']) . '</span>';
            }
        }
    }

    // get yummly follow count
    public function sfbp_yummly_count()
    {
        // get results from yummly and return the number of follows
        $result = wp_remote_get('http://www.yummly.com/services/yum-count?url=' . $this->urlCurrentPage, array('timeout' => $this->sfbp_settings['count_timeout']));

        // check there was an error
        if (is_wp_error($result)) {
            // set follow count
            $this->sfbpyummlyFollowCount = 0;

            // return
            return;
        }

        // decode data
        $array = json_decode($result['body'], true);
        $count = (isset($array['count']) ? $array['count'] : 0);
        $this->sfbpyummlyFollowCount = ($count) ? $count : '0';

        // return
        return;
    }
}
