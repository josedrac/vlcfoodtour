<?php

defined('ABSPATH') or die('No direct access permitted');

function sfbp_admin_header()
{
    // get license status
    $status = get_option('sfbp_license_status');

    // open wrap
    $htmlHeader = '<div class="sfbp-admin-wrap">';

    // if left to right
    if (is_rtl()) {
        // move save button
        $htmlHeader .= '<style>.sfbp-btn-save{left: 0!important;
                                        right: auto !important;
                                        border-radius: 0 5px 5px 0;}
                                </style>';
    }

    // navbar/header
    $htmlHeader .= '<nav class="navbar navbar-default">
					  <div class="container-fluid">
					    <div class="navbar-header">
					      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					        <span class="sr-only">Toggle navigation</span>
					        <span class="icon-bar"></span>
					        <span class="icon-bar"></span>
					        <span class="icon-bar"></span>
					      </button>
					      <a class="navbar-brand" href="https://simplefollowbuttons.com"><img src="' . plugins_url() . '/simple-follow-buttons-plus/images/simple_follow_buttons_logo.png" alt="Simple Follow Buttons Plus" class="sfbp-logo-img" /></a>
					    </div>

					    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					      <ul class="nav navbar-nav navbar-right">
					        <li><a href="https://simplefollowbuttons.com/forums/forum/simple-follow-buttons-plus/" target="_blank">Support</a></li>';
    // the license is not valid
    if ($status === false || $status != 'valid') {
        // add license notification
        $htmlHeader .= '<li><button type="button" class="sfbp-btn-inactive btn-danger sfbp-float-right-btn btn" data-toggle="modal" data-target="#sfbpLicenseModal">License inactive</button></li>';
    }
    $htmlHeader .= '</ul>
					    </div>
					  </div>
					</nav>';

    $htmlHeader .= '<div class="modal fade" id="sfbpLicenseModal" tabindex="-1" role="dialog" aria-labelledby="sfbpFooterModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			        <h4 class="modal-title">Simple Follow Buttons Licensing</h4>
			      </div>
			      <div class="modal-body">
			        <p>You do not have a <strong>valid and activated</strong> license for this installation of <b>Simple Follow Buttons Plus</b>.</p>
					<p>If you have a license, please save and activate it on the <a href="admin.php?page=simple-follow-buttons-license">license page</a>. A valid license must be in place to take advantage of all of the plugin\'s features, including update notifications and support.</p>
				<p class="text-center">Licenses can be <a href="https://simplefollowbuttons.com/plus/" target="_blank"><b>purchased here</b></a> and <a href="https://simplesharebuttons.com/purchases/" target="_blank"><b>managed here</b></a></p>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			      </div>
			    </div>
			  </div>
			</div>';

    // open container - closed in footer
    $htmlHeader .= '<div class="container">';

    // return
    return $htmlHeader;
}

function sfbp_admin_footer()
{
    // row
    $htmlFooter = '<footer class="row">';

    // col
    $htmlFooter .= '<div class="col-sm-12">';

    // link to show footer content
    $htmlFooter .= '<a href="https://simplefollowbuttons.com/plus/" target="_blank">Simple Follow Buttons Plus</a> <span class="badge">' . SFBP_VERSION . '</span>';

    // show more/less links
    $htmlFooter .= '<button type="button" class="sfbp-btn-thank-you pull-right btn btn-primary" data-toggle="modal" data-target="#sfbpFooterModal"><i class="fa fa-info"></i></button>';

    $htmlFooter .= '<div class="modal fade" id="sfbpFooterModal" tabindex="-1" role="dialog" aria-labelledby="sfbpFooterModalLabel" aria-hidden="true">
        						  <div class="modal-dialog">
        						    <div class="modal-content">
        						      <div class="modal-header">
        						        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        						        <h4 class="modal-title">Simple Follow Buttons Plus</h4>
        						      </div>
        						      <div class="modal-body">
        						        <p>Many thanks for choosing <a href="https://simplefollowbuttons.com/plus/" target="_blank">Simple Follow Buttons Plus</a> for your follow buttons plugin, we\'re confident you won\'t be disappointed in your decision. If you require any support, please visit the <a href="https://simplesharebuttons.com/forums/forum/simple-follow-buttons-plus" target="_blank">support forum</a>.</p>
        						       </div>
        						      <div class="modal-footer">
        						        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        						      </div>
        						    </div>
        						  </div>
        						</div>';

    // close col
    $htmlFooter .= '</div>';

    // close row
    $htmlFooter .= '</footer>';

    // close container - opened in header
    $htmlFooter .= '</div>';

    // close sfbp-admin-wrap - opened in header
    $htmlFooter .= '</div>';

    // return
    return $htmlFooter;
}

function sfbp_admin_dashboard()
{

    // make sure we have settings ready
    $sfbp_settings = get_sfbp_settings();

    // sfbp header
    $htmlFollowButtonsDash = sfbp_admin_header();

    // row
    $htmlFollowButtonsDash .= '<div class="row">';

    // support div
    $htmlFollowButtonsDash .= '<div class="col-sm-6">';
    $htmlFollowButtonsDash .= '<h3 class="sfbp-support">Support</h3>';
    $htmlFollowButtonsDash .= '<p>Please ensure that you include as many details as possible when posting, ideally including a link to an example or screenshots wherever you can.</p>';
    $htmlFollowButtonsDash .= '<p>It is strongly recommended that you copy and paste the support details below to ensure that assistance can be provided and a solution found as swiftly as possible. The more information provided the better. It is also recommended that you export your SFBP settings and provide the .txt file too.</p>';

    // hidden support details
    $htmlFollowButtonsDash .= '<fieldset>';
    $htmlFollowButtonsDash .= '<div class="form-group">';
    $htmlFollowButtonsDash .= sfbp_support_details($sfbp_settings);
    $htmlFollowButtonsDash .= '</div>';
    $htmlFollowButtonsDash .= '</fieldset>';

    // visit support button
    $htmlFollowButtonsDash .= '<p><a href="https://simplesharebuttons.com/forums/forum/simple-follow-buttons-plus" target="_blank"><button class="btn btn-primary btn-block">Visit the Support Forum</button></a></p>';

    // close col
    $htmlFollowButtonsDash .= '</div>';

    // close row
    $htmlFollowButtonsDash .= '</div>';

    // new row
    $htmlFollowButtonsDash .= '<div class="row">';

    // new col
    $htmlFollowButtonsDash .= '<div class="col-sm-6">';

    // import/export
    $htmlFollowButtonsDash .= '<h3 class="margin-top--md">Export/Import</h3>';
    $htmlFollowButtonsDash .= '<p>When moving Simple Follow Buttons Plus from one site to another, save yourself the hassle of going through all your settings again by simply exporting/importing them using the buttons below. <b>Note that licenses are outside the scope of this functionality</b>.</p>';

    // new row
    $htmlFollowButtonsDash .= '<div class="row">';

    // if we've just imported sfbp settings
    if (isset($_SESSION['sfbp_import']) && $_SESSION['sfbp_import'] === true) {
        // new col
        $htmlFollowButtonsDash .= '<div class="col-sm-12">';

        // confirmation/disabled button
        $htmlFollowButtonsDash .= '<button type="button" class="btn btn-success btn-block">Settings imported successfully!</button>';

        // close col
        $htmlFollowButtonsDash .= '</div>';

        // unset import
        unset($_SESSION['sfbp_import']);
    } else {
        // new col
        $htmlFollowButtonsDash .= '<div class="col-sm-4">';

        // export csv form
        $htmlFollowButtonsDash .= '<form method="post" target="_blank">';
        $htmlFollowButtonsDash .= wp_nonce_field('export_sfbp_settings_nonce');
        $htmlFollowButtonsDash .= '<input type="hidden" name="export_sfbp_settings" />';
        $htmlFollowButtonsDash .= '<button type="submit" class="btn btn-default btn-block">Export</button>';
        $htmlFollowButtonsDash .= '</form>';

        // close col
        $htmlFollowButtonsDash .= '</div>';

        // new col
        $htmlFollowButtonsDash .= '<div class="col-sm-4">';

        // text file validation
        $htmlFollowButtonsDash .= '<script language="javascript">
    												function Checkfiles()
    												{
    												var fup = document.getElementById("sfbp_settings_txt");
    												var fileName = fup.value;
    												var ext = (/[.]/.exec(fileName)) ? /[^.]+$/.exec(fileName) : undefined;
    												if(ext == "txt")
    												{
    												document.getElementById("sfbp-import-button").disabled = false;
    												return true;
    												}
    												else
    												{
    												alert("Upload .txt files only");
    												fup.focus();
    												return false;
    												}
    												}
    												</script>';

        // import csv form
        $htmlFollowButtonsDash .= '<form method="post" id="sfbp_import_form" enctype="multipart/form-data" action="?page=simple-follow-buttons-plus">';

        // file input
        $htmlFollowButtonsDash .= '<input class="filestyle" id="sfbp_settings_txt" onchange="Checkfiles()" type="file" size="30" name="sfbp_settings_txt"  data-icon="false" data-input="false" data-buttonName="btn-default btn-block" />';

        // close col
        $htmlFollowButtonsDash .= '</div>';

        // new col
        $htmlFollowButtonsDash .= '<div class="col-sm-4">';

        $htmlFollowButtonsDash .= '<button disabled id="sfbp-import-button" class="btn btn-primary btn-block">Import</button>';
        $htmlFollowButtonsDash .= wp_nonce_field('import_sfbp_settings_nonce');
        $htmlFollowButtonsDash .= '<input type="hidden" name="import_sfbp_settings" />';
        $htmlFollowButtonsDash .= '</form>';

        // close col
        $htmlFollowButtonsDash .= '</div>';
    }

    // close row
    $htmlFollowButtonsDash .= '</div>';

    // close col
    $htmlFollowButtonsDash .= '</div>';

//    // new col
//    $htmlFollowButtonsDash .= '<div class="col-sm-6">';
//
//    // heading
//    $htmlFollowButtonsDash .= '<h3 class="margin-top--md">Default Settings</h3>';
//    $htmlFollowButtonsDash .= '<p>Whether you\'ve decided you\'d like the same follow buttons as us, or you just want some help getting started, click the button below to import all of the follow button settings from <a href="https://simplefollowbuttons.com">simplefollowbuttons.com</a>. <strong>Note that all your current settings will be overwritten.</strong></p>';
//
//    // sfbp settings import
//    $htmlFollowButtonsDash .= '<form method="post">';
//    $htmlFollowButtonsDash .= wp_nonce_field('import_official_sfbp_settings_nonce');
//    $htmlFollowButtonsDash .= '<input type="hidden" name="import_official_sfbp_settings" />';
//
//    // if we've just imported official sfbp settings
//    if (isset($_SESSION['sfbp_official_import']) && $_SESSION['sfbp_official_import'] === true) {
//        // confirmation/disabled button
//        $htmlFollowButtonsDash .= '<button type="button" class="btn btn-success btn-block">Settings imported successfully!</button>';
//
//        // unset import
//        unset($_SESSION['sfbp_official_import']);
//    } else {
//        // import button
//        $htmlFollowButtonsDash .= '<button id="ssb-official-import" type="submit" class="btn btn-warning btn-block">Use simplefollowbuttons.com\'s Settings</button>';
//    }
//
//    $htmlFollowButtonsDash .= '</form>';
//
//    // close col
//    $htmlFollowButtonsDash .= '</div>';

    // close row
    $htmlFollowButtonsDash .= '</div>';

    // sfbp footer
    $htmlFollowButtonsDash .= sfbp_admin_footer();

    echo $htmlFollowButtonsDash;
}

function sfbp_support_details()
{
    // open textarea
    $htmlSupportDetails = '<textarea class="form-control" id="sfbp-support-textarea" rows="7">';

    // get wordpress version
    $wp_version = get_bloginfo('version');
    $htmlSupportDetails .= 'WordPress Version: ' . $wp_version . '|';

    // get theme details
    $my_theme = wp_get_theme();
    $htmlSupportDetails .= 'Theme: ' . $my_theme->get('Name') . '| Theme Version: ' . $my_theme->get('Version') . '|';

    // other plugins installed
    $all_plugins = get_plugins();

    // loop through and output
    foreach ($all_plugins as $arrPlugin) {

        // add to textarea
        $htmlSupportDetails .= $arrPlugin['Name'] . ': ' . $arrPlugin['Version'] . '|';
    }

    // close text area
    $htmlSupportDetails .= '</textarea>';

    // echo details
    return $htmlSupportDetails;
}
