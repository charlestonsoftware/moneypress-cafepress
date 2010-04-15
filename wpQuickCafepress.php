<?
  /*
  Plugin Name: CSL Quick Cafepress
  Plugin URI: http://www.cybersprocket.com/products/wpquickcafepress/
  Description: Cafepress store plugin for Wordpress.
  Author: Cyber Sprocket Labs
  Version: 1.1
  Author URI: http://www.cybersprocket.com/

Based on the Wishads Cafepress store plugin.  We picked it up
and tweaked it after they shut down the project.  Improvements include:

Missing API key - dumps out faster (saves processing / CPU)
On error, don't write cache file.
On API error, show it.
Don't kill entire page on caching error.
Eliminate custom REST/cURL calls, use built-in Wordpress page fetch.
PHP4 compatability

*/

/*	Copyright 2010  Cyber Sprocket Labs (info@cybersprocket.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



/*--------------------------------------------------------------------------
*
* Variable Initialization
*
*/
$cpstore_my_error_handler = set_error_handler("cpstore_myErrorHandler");


add_shortcode('QuickCafe', 'wpQuickCafe');
add_action('admin_menu', 'wpQC_plugin_menu');
add_action('admin_menu', 'wpQC_Handle_AdminMenu');
add_action('wp_head', 'wpQC_add_css');
add_filter('admin_print_scripts', 'wpQC_AdminHead');


/*--------------------------------------------------------------------------
*
* wpQuickCafe
*
* Main processing function.  This is what parses and generates the output
* for the QuickCafe tag.
*
*/
function wpQuickCafe ($attr, $content) {
global $thisprod;

// Get the CafePress API Key - return if blank.
$cpApiKey = trim(get_option('qcp_cpapikey'));
if ($cpApiKey == '') {
return '<div>QuickCafepress: Missing API key, get it from developer.cafepress.com and put in you Wordpress settings.</div>';
}

// Process theincoming attributes
$attr = shortcode_atts(array('return'   => get_option('qcp_numtoshow'),
'preview'   => get_option('qcp_numtopreview')), $attr);
$cpstore_url = $content;

// Set the display attributes
$cpstore_preview = $attr['preview'];
$cpstore_return = $attr['return'];
$cpstore_permalink = get_permalink($post->ID);
$cpstore_startPage = (isset($_GET['startpage']) && $_GET['startpage']) ? $_GET['startpage'] : "1";

// build the file name
$cpstoreArray1 = explode("cafepress.com/",$cpstore_url);
list($cpstore_storeid,$cpstore_sectionid) = explode("/",$cpstoreArray1[1]);
if ($cpstore_sectionid == '') { $cpstore_sectionid='0'; }	                    # Default store section 0 = top if not set
$cpstore_dir = dirname(__FILE__) ;
$cpstore_cache_dir = $cpstore_dir . "/cache" ;
cpstore_cleancache($cpstore_cache_dir);
$cpstore_FileName = $cpstore_cache_dir . "/" . $cpstore_storeid . "_" . $cpstore_sectionid . ".xml";
$depth = array();
$qcpCacheOK = true;

// No cache?  Build one...
if (!file_exists ($cpstore_FileName)) {
$file_content = wp_remote_fopen("http://open-api.cafepress.com/product.listByStoreSection.cp?appKey=$cpApiKey&storeId=$cpstore_storeid&sectionId=$cpstore_sectionid&v=3");

// Write Cache File if the response does not contain an error message.
if (preg_match('/<help>\s+<exception-message>(.*?)<\/exception-message>/',$file_content,$error) > 0) {
return 'No products found: ' . $error[1] . '<br>';
} else {
if ($fh = fopen($cpstore_FileName, 'w')) {
fwrite($fh, $file_content);
fclose($fh);
} else {
$qcpCacheOK = false;
}
}

// Read Cache
} else {
if (!$file_content = file_get_contents($cpstore_FileName)) { return 'could not open cache file '.$cpstore_FileName; }
}

// Setup for XML Parsing
$cpstore_xml_parser = xml_parser_create();
xml_set_element_handler($cpstore_xml_parser, "startElement", "endElement");
if (!xml_parse($cpstore_xml_parser, $file_content, feof($fp))) {
return sprintf("XML error: %s at line %d",
xml_error_string(xml_get_error_code($cpstore_xml_parser)),
xml_get_current_line_number($cpstore_xml_parser));
}
xml_parser_free($cpstore_xml_parser);

// Display Settings...

// Default category ordering
$cpstore_category[] = "Shirts (short)";
$cpstore_category[] = "Shirts (long)";
$cpstore_category[] = "Kids Clothing";
$cpstore_category[] = "Outerwear";
$cpstore_category[] = "Intimate Apparel";
$cpstore_category[] = "Home & Office";
$cpstore_category[] = "Fun Stuff";
$cpstore_category[] = "Cards, Prints & Calendars";
$cpstore_category[] = "Hats & Caps";
$cpstore_category[] = "Bags";
$cpstore_category[] = "Stickers";
$cpstore_category[] = "Mugs";
$cpstore_category[] = "Pets";
$cpstore_category[] = "Buttons & Magnets";
$cpstore_category[] = "Books & CDs";

// Get CSS Settings
$cpstore_css_container = get_option('qcp_css_container');
$cpstore_css_category = get_option('qcp_css_category');
$cpstore_css_float = get_option('qcp_css_float');
$cpstore_css_float_img = get_option('qcp_css_float_img');
$cpstore_css_float_p = get_option('qcp_css_float_p');
$cpstore_css_price_a = get_option('qcp_css_price_a');
$cpstore_css_float_hover = get_option('qcp_css_float_hover');
$cpstore_css_float_hover_img = get_option('qcp_css_float_hover_img');
$cpstore_css_float_hover_p = get_option('qcp_css_float_hover_p');
$cpstore_css_price_hover_a = get_option('qcp_css_price_hover_a');
$cpstore_css_viewall = get_option('qcp_css_viewall');
$cpstore_css_catmenu = get_option('qcp_css_catmenu');


// Build Style Sheet
$cpstore_content .= '<style>
<!--
div.cpstore_css_category {
' . $cpstore_css_category . '
}
div.cpstore_css_container {
' . $cpstore_css_container . '
}
div.cpstore_css_spacer {
clear: both;
}
div.cpstore_css_float {
' . $cpstore_css_float . '
}
div.cpstore_css_float img{
' . $cpstore_css_float_img . '
}
div.cpstore_css_float p {
' . $cpstore_css_float_p . '
}
div.cpstore_css_float_hover {
' . $cpstore_css_float_hover . '
}
div.cpstore_css_float_hover img{
' . $cpstore_css_float_hover_img . '
}

div.cpstore_css_float_hover p {
' . $cpstore_css_float_hover_p . '
}
div.cpstore_css_price a {
' . $cpstore_css_price_a . '
}

div.cpstore_css_price a:hover {
' . $cpstore_css_price_hover_a . '
}
div.cpstore_css_viewall {
' . $cpstore_css_viewall . '
}
div.cpstore_css_viewall a {
' . $cpstore_css_viewall . '
}
div.cpstore_css_catmenu {
' . $cpstore_css_catmenu . '
}

-->
</style>
<div class="cpstore_css_container">'
;

// create the category menu if this is a single post or page
if (is_single() || is_page())  {
foreach ($cpstore_category as $key => $cpstore_catname) {
$cpstore_productlist = $thisprod["$cpstore_catname"];
if (!empty($cpstore_productlist)){
$cpstore_catlist .= "<span style=\"white-space:nowrap;\"><a href=\"#$key\">$cpstore_catname</a></span> | ";
}
}
$cpstore_catlist = substr($cpstore_catlist,0,strlen($cpstore_catlist)-3);
$cpstore_catlist = '<div class="cpstore_css_catmenu"><a name="cpstore_menu"></a>' . $cpstore_catlist . '</div>';
$cpstore_content .= $cpstore_catlist;
}
$cpstore_content .= '<div class="cpstore_css_spacer"></div>';

// now run through each category and show the thumbs
foreach ($cpstore_category as $key => $cpstore_catname) {
$cpstore_productlist = $thisprod["$cpstore_catname"];
if (!empty($cpstore_productlist)){
$cpstore_content .= '<div class="cpstore_css_spacer"></div>';
$cpstore_content .= "<div class=\"cpstore_css_category\"><a name=\"$key\"></a>$cpstore_catname</div>";
foreach ($cpstore_productlist as $cpstore_id => $cpstore_attr) {
$this_link = $cpstore_attr["link"];
$cpstore_content .= '
<div class="cpstore_css_float" onmouseover="this.className=\'cpstore_css_float_hover\'" onmouseout="this.className=\'cpstore_css_float\'">
<a href="' . $this_link . '"><img title="' . $cpstore_attr["description"] . '" src="' . $cpstore_attr["image"] . '" alt="' . $cpstore_attr["description"] . '" width="150" height="150" /></a>
<div><a class="thickbox" href="' . str_replace("150x150","350x350",$cpstore_attr["image"]) . '">
+zoom</a></div><p>' . $cpstore_attr["name"] . '</p><div class="cpstore_css_price"><a href="' . $this_link . '">Buy Now! - $' . $cpstore_attr["price"] . '</a></div></div>
';
$cpstore_loopcounter++;
if (!is_single() && ($cpstore_loopcounter == $cpstore_preview)) { // exit both loops
$cpstore_content .= '<div class="cpstore_css_spacer"></div>';
$cpstore_content .= "<div class=\"cpstore_css_viewall\"><a href=\"" . get_permalink($post->ID) . "\">View all</a></div>";
break 2;

}
if (is_single() && ($cpstore_loopcounter == $cpstore_return)) { // exit both loops
break 2;
}
}

// end of individual category loop
// if this is a single post or page, show the "back to top" link
if (is_single() || is_page())  {
$cpstore_content .= '<div class="cpstore_css_spacer"></div>';
$cpstore_content .= "<div class=\"cpstore_toplink\"><a href=\"#cpstore_menu\">back to menu</a></div>";
}
}
}
$cpstore_content .= '<div class="cpstore_css_spacer"></div><div style="margin-bottom:2em;"></div></div>';

# Info messages
if (!$qcpCacheOK) { $cpstore_content .= '<br />wpQuickCafepress could not create the cache file '.$cpstore_FileName.'<br />'; }

# Return
return $cpstore_content;
}


//--------------------------------------------------------------------------
function wpQC_add_css() {
wp_enqueue_script('jquery');
wp_enqueue_script('thickbox');
echo '<script type="text/javascript" src="/wp-includes/js/jquery/jquery.js"></script>'."\n";
echo '<script type="text/javascript" src="/wp-includes/js/thickbox/thickbox.js"></script>'."\n";
echo '<link rel="stylesheet" href="/wp-includes/js/thickbox/thickbox.css" type="text/css" media="screen" />'."\n";
}


//--------------------------------------------------------------------------
function wpQC_Handle_AdminMenu() {
add_meta_box('cpStoreMB', 'CSL Quick CafePress Entry', 'cpStoreInsertForm', 'post', 'normal');
add_meta_box('cpStoreMB', 'CSL Quick CafePress Entry', 'cpStoreInsertForm', 'page', 'normal');
}


//--------------------------------------------------------------------------
function cpstorewarning() {
echo "<div id='wpCPStore_warning' class='updated fade-ff0000'><p><strong>"
.__('Quick CafePress is almost ready.')."</strong> "
.sprintf(__('You must <a href="options-general.php?page=wpQuickCafepress/cafepress_grid.php">enter your CafePress API key</a> for it to work.'), "options-general.php?page=wpQuickCafepress/cafepress_grid.php")
."</p></div>";
}


//--------------------------------------------------------------------------
function cpStoreInsertForm() {
?>
<table class="form-table">
  <tr valign="top">
    <th align="right" scope="row"><label for="wpCPStore_url"><?php _e('Section Url:')?></label></th>
    <td>
      <input type="text" size="40" style="width:95%;" name="wpCPStore_url" id="wpCPStore_url" />
    </td>
  </tr>
  <tr valign="top">
    <th align="right" scope="row"><label for="wpCPStore_preview"><?php _e('Preview how many?:')?></label></th>
    <td>
      <input type="text" size="40" style="width:95%;" name="wpCPStore_preview" id="wpCPStore_preview" />
    </td>
  </tr>
  <tr valign="top">
    <th align="right" scope="row"><label for="wpCPStore_return"><?php _e('Show how many?:')?></label></th>
    <td>
      <input type="text" size="40" style="width:95%;" name="wpCPStore_return" id="wpCPStore_return" />
    </td>
  </tr>
</table>
<p class="submit">
            <input type="button" onclick="return this_wpCPStoreAdmin.sendToEditor(this.form);" value="<?php _e('Create QuickCafepress Shortcode &raquo;'); ?>" />
        </p>
<?php
}


//--------------------------------------------------------------------------
function wpQC_AdminHead () {
    if ($GLOBALS['editing']) {
        wp_enqueue_script('wpCPStoreAdmin', WP_PLUGIN_URL .'/wpQuickCafepress/js/cpstore.js', array('jquery'), '1.0.0');
    }
}


//--------------------------------------------------------------------------
function wpQC_plugin_menu() {
  	add_options_page('wpQuickCafepress Settings', 'wpQuickCafepress', 8, __FILE__, 'qcp_plugin_options');
}


//--------------------------------------------------------------------------
function qcp_plugin_options() {
	echo "<h2>CSL Quick CafePress Store</h2>";

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( $_POST['get_cpstore_submit'] == 'Y' ) {

		// let the browser know it's been updated
		echo '<div id="message" class="updated fade"><p><strong>Settings saved.</strong></p></div>';

        // Read their posted value
        $cpstore_cpapikey = $_POST['cpstore_cpapikey'];
        $cpstore_numtopreview = $_POST['cpstore_numtopreview'];
        $cpstore_numtoshow = $_POST['cpstore_numtoshow'];

		// css
        $cpstore_css_viewall = $_POST['cpstore_css_viewall'];
        $cpstore_css_category = $_POST['cpstore_css_category'];
        $cpstore_css_container = $_POST['cpstore_css_container'];
        $cpstore_css_float = $_POST['cpstore_css_float'];
        $cpstore_css_float_img = $_POST['cpstore_css_float_img'];
        $cpstore_css_float_p = $_POST['cpstore_css_float_p'];
        $cpstore_css_price_a = $_POST['cpstore_css_price_a'];
        $cpstore_css_float_hover = $_POST['cpstore_css_float_hover'];
        $cpstore_css_float_hover_img = $_POST['cpstore_css_float_hover_img'];
        $cpstore_css_float_hover_p = $_POST['cpstore_css_float_hover_p'];
        $cpstore_css_price_hover_a = $_POST['cpstore_css_price_hover_a'];
        $cpstore_css_catmenu = $_POST['cpstore_css_catmenu'];

        // Save the posted value in the database
        update_option( 'qcp_cpapikey', $cpstore_cpapikey );
        update_option( 'qcp_numtoshow', $cpstore_numtoshow );
        update_option( 'qcp_numtopreview', $cpstore_numtopreview );

        update_option( 'qcp_css_viewall', $cpstore_css_viewall );
        update_option( 'qcp_css_category', $cpstore_css_category );
        update_option( 'qcp_css_container', $cpstore_css_container);
        update_option( 'qcp_css_float', $cpstore_css_float);
        update_option( 'qcp_css_float_img', $cpstore_css_float_img);
        update_option( 'qcp_css_float_p', $cpstore_css_float_p);
        update_option( 'qcp_css_price_a', $cpstore_css_price_a);
        update_option( 'qcp_css_float_hover', $cpstore_css_float_hover);
        update_option( 'qcp_css_float_hover_img', $cpstore_css_float_hover_img);
        update_option( 'qcp_css_float_hover_p', $cpstore_css_float_hover_p);
        update_option( 'qcp_css_price_hover_a', $cpstore_css_price_hover_a);
        update_option( 'qcp_css_catmenu', $cpstore_css_catmenu);

	}

	$cpstore_cpapikey = get_option('qcp_cpapikey');
	$cpstore_numtoshow = get_option('qcp_numtoshow');
	$cpstore_numtopreview = get_option('qcp_numtopreview');

	$cpstore_css_viewall = get_option('qcp_css_viewall');
	$cpstore_css_category = get_option('qcp_css_category');
	$cpstore_css_container = get_option('qcp_css_container');
	$cpstore_css_float = get_option('qcp_css_float');
	$cpstore_css_float_img = get_option('qcp_css_float_img');
	$cpstore_css_float_p = get_option('qcp_css_float_p');
	$cpstore_css_price_a = get_option('qcp_css_price_a');
	$cpstore_css_float_hover = get_option('qcp_css_float_hover');
	$cpstore_css_float_hover_img = get_option('qcp_css_float_hover_img');
	$cpstore_css_float_hover_p = get_option('qcp_css_float_hover_p');
	$cpstore_css_price_hover_a = get_option('qcp_css_price_hover_a');
	$cpstore_css_catmenu = get_option('qcp_css_catmenu');

	?>

	<div class="wrap">
    <h3>For a complete explanation of the setup and use, see the <a href="<? echo WP_PLUGIN_URL; ?>/wpQuickCafepress/wpQuickCafepress-help.php" target="_blank">help file</a>.	</h3>

	<form name="myform" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <input type="hidden" name="get_cpstore_submit" value="Y" />
    <table style="border 2px solid black;" border=0 cellspacing=5>
    <tr valign="top"><td align="left" colspan="2"><h3>CSL Quick CafePress Store Configuration: </h3>
    <p>Complete the settings below to configure your Quick store.</p></td></tr>
    <tr valign="top"><td width="400px" align="right" ><div style="color:red;">Required - Enter your CafePress API Key:</div></td>
    	<td><input type=text name="cpstore_cpapikey" value="<?php echo $cpstore_cpapikey ?>" /> </td></tr>
      <tr valign="top">
        <td></td><td>Until you acquire your own api key, you can use our demo key &quot;ut3dcs8rr3svqt5r4r2u8677&quot; (without the quotes). This is a shared demo key and should not be used to run your plugin. Check the help file above for details about acquiring an api key.</td></tr>
    <tr valign="top"><td width="400px" align="right" >Optional - <b>CafePress</b> Account Number</a>:</td>
    	<td><input type=text name="cpstore_cjxid" value="<?php echo $cpstore_cjxid ?>" /> </td></tr>
    <p>These settings can be changed per individual post</p></td></tr>
    <tr valign="top">
        <td width="400px" align="right" >Default # of products to preview on the main page/archive pages:</td>
        <td><input name="cpstore_numtopreview" type=text value="<?php echo $cpstore_numtopreview ?>" size="4"><br />(Leave blank to show all products)</td></tr>
      <tr valign="top">
        <td width="400px" align="right" >Limit the number of products on single post pages to:</td>
        <td><input name="cpstore_numtoshow" type=text value="<?php echo $cpstore_numtoshow ?>" size="4"><br />(Leave blank to show all products)</td></tr>
      <tr valign="top">
        <td colspan="2" align="center" ><p style="color:red;font-weight:bold;">Edit the styles for the store grid. Copy and paste the sample styles the first time you set up the plugin, then make changes as necessary to match your site's style.</p></td></tr>
      <tr valign="top">
        <td width="400px" align="right" >Style for entire container:</td>
        <td>.cpstore_css_container<br />{<br /><textarea cols="40" rows="2" name="cpstore_css_container"><?php echo $cpstore_css_container ?></textarea><br />}</td></tr>
      <tr valign="top">
        <td></td><td>Sample:
<pre><i>margin-left:0px;
</i></pre>
</td></tr>
      <tr valign="top">
        <td width="400px" align="right" >Style for category header:</td>
        <td>.cpstore_css_category<br />{<br /><textarea cols="40" rows="2" name="cpstore_css_category"><?php echo $cpstore_css_category ?></textarea><br />}</td></tr>
      <tr valign="top">
        <td></td><td>Sample:
<pre><i>font-size:125%;
font-weight:bold;
</i></pre>
</td></tr>
      <tr valign="top">
        <td width="400px" align="right" >Style for category menu:</td>
        <td>.cpstore_css_catmenu<br />{<br /><textarea cols="40" rows="2" name="cpstore_css_catmenu"><?php echo $cpstore_css_catmenu ?></textarea><br />}</td></tr>
      <tr valign="top">
        <td></td><td>Sample:
<pre><i>text-align:center;
</i></pre>
</td></tr>
      <tr valign="top">
        <td colspan="2" align="center"><h3>Normal Style (when the mouse is not over the cell):</h3></td></tr>

      <tr valign="top">
        <td width="400px" align="right" >Style for each cell:</td>
        <td>.cpstore_css_float<br />{<br /><textarea cols="40" rows="7" name="cpstore_css_float"><?php echo $cpstore_css_float?></textarea><br />}</td></tr>
      <tr valign="top">
        <td></td><td>Sample:
<pre><i>float: left;
width: 158px;
height: 250px;
padding: 2px;
background:#F5F5F5;
border: #999999 1px solid;
text-align: center;
margin-right: 4px;
margin-bottom: 6px;</i></pre>
</td></tr>

      <tr valign="top">
        <td width="400px" align="right" >Style for the thumbnail image:</td>
        <td>.cpstore_css_float img<br />{<br /><textarea cols="40" rows="7" name="cpstore_css_float_img"><?php echo $cpstore_css_float_img ?></textarea><br />}</td></tr>
      <tr valign="top">
        <td></td><td>Sample:
<pre><i>padding: 2px;
background:#999999;
margin-top:2px;
border: 0px;
margin-bottom: 0;
</i></pre>
</td></tr>

      <tr valign="top">
        <td width="400px" align="right" >Style for the text:</td>
        <td>.cpstore_css_float p<br />{<br /><textarea cols="40" rows="7" name="cpstore_css_float_p"><?php echo $cpstore_css_float_p ?></textarea><br />}</td></tr>
      <tr valign="top">
        <td></td><td>Sample:
<pre><i>margin: 0;
text-align: center;
font-weight:bold;
line-height:normal;
</i></pre>
</td></tr>

      <tr valign="top">
        <td width="400px" align="right" >Style for the buy now/price link:</td>
        <td>.cpstore_css_price a<br />{<br /><textarea cols="40" rows="7" name="cpstore_css_price_a"><?php echo $cpstore_css_price_a ?></textarea><br />}</td></tr>
      <tr valign="top">
        <td></td><td>Sample:
<pre><i>font-size:100%;
font-weight:bold;
text-decoration:none;
color:#000;
font-weight:bold;
border:2px solid;
padding:1px 1px 3px 1px;
border-color: #eee #999 #666 #e3e3e3;
background:#fff;
</i></pre>
</td></tr>

      <tr valign="top">
        <td colspan="2" align="center"><h3>Style when the mouse is over the cell:</h3></td></tr>

      <tr valign="top">
        <td width="400px" align="right" >Style for each cell:</td>
        <td>.cpstore_css_float_hover<br />{<br /><textarea cols="40" rows="7" name="cpstore_css_float_hover"><?php echo $cpstore_css_float_hover ?></textarea><br />}</td></tr>
      <tr valign="top">
        <td></td><td>Sample:
<pre><i>float: left;
width: 158px;
height: 250px;
padding: 2px;
background:#E8E8E8;
border: #9F9F9F 1px solid;
text-align: center;
margin-right: 4px;
margin-bottom: 6px;</i></pre>
</td></tr>

      <tr valign="top">
        <td width="400px" align="right" >Style for the thumbnail image:</td>
        <td>.cpstore_css_float_hover img<br />{<br /><textarea cols="40" rows="7" name="cpstore_css_float_hover_img"><?php echo $cpstore_css_float_hover_img ?></textarea><br />}</td></tr>
      <tr valign="top">
        <td></td><td>Sample:
<pre><i>padding: 2px;
background:#999999;
margin-top:2px;
border: 0px;
margin-bottom: 0;
</i></pre>
</td></tr>

      <tr valign="top">
        <td width="400px" align="right" >Style for the text:</td>
        <td>.cpstore_css_float_hover p<br />{<br /><textarea cols="40" rows="7" name="cpstore_css_float_hover_p"><?php echo $cpstore_css_float_hover_p ?></textarea><br />}</td></tr>
      <tr valign="top">
        <td></td><td>Sample:
<pre><i>margin: 0;
text-align: center;
font-weight:bold;
line-height:normal;
</i></pre>
</td></tr>

      <tr valign="top">
        <td width="400px" align="right" >Style for the price link:</td>
        <td>.cpstore_css_price a:hover<br />{<br /><textarea cols="40" rows="7" name="cpstore_css_price_hover_a"><?php echo $cpstore_css_price_hover_a ?></textarea><br />}</td></tr>
      <tr valign="top">
        <td></td><td>Sample:
<pre><i>border-color: #666 #e3e3e3 #eee #999;
</i></pre>
</td></tr>
      <tr valign="top">
        <td width="400px" align="right" >Style for the &quot;View All&quot; link:</td>
        <td>.cpstore_css_viewall a<br />{<br /><textarea cols="40" rows="7" name="cpstore_css_viewall"><?php echo $cpstore_css_viewall ?></textarea><br />}</td></tr>
      <tr valign="top">
        <td></td><td>Sample:
<pre><i>text-align:center;
font-size:125%;
</i></pre>
</td></tr>
    </table>
    <input type="submit" value="Update" />
    </form></p>
	</div>
  <?
}


//--------------------------------------------------------------------------
function cpstore_cleancache($directory)
{
	$seconds_old = 84600;
	if( !$dirhandle = @opendir($directory) )
			return;

	while( false !== ($filename = readdir($dirhandle)) ) {
			if( $filename != "." && $filename != ".." ) {
					$filename = $directory. "/". $filename;

					if( @filemtime($filename) < (time()-$seconds_old) )
							@unlink($filename);
			}
	}

}



//--------------------------------------------------------------------------
function startElement($parser, $name, $attrs)
{
    global $depth,$thisprod;
	if ($depth[$parser] == 1) {
		$temp_cat = $attrs['CATEGORYCAPTION'];
		$temp_id = $attrs['ID'];
		$temp_link = "http://www.cafepress.com/" . $attrs['STOREID'] . "." . $temp_id;
		$temp_description = $attrs['DESCRIPTION'];
		$temp_name = $attrs['NAME'];
		$temp_price = $attrs['SELLPRICE'];
		$temp_image = str_replace("240x240","150x150",$attrs['DEFAULTPRODUCTURI']);

		$thisprod[$temp_cat][$temp_id]["name"] = $temp_name;
		$thisprod[$temp_cat][$temp_id]["link"] = $temp_link;
		$thisprod[$temp_cat][$temp_id]["description"] = $temp_description;
		$thisprod[$temp_cat][$temp_id]["price"] = $temp_price;
		$thisprod[$temp_cat][$temp_id]["image"] = $temp_image;

	}
	$depth[$parser]++;
}



//--------------------------------------------------------------------------
function endElement($parser, $name)
{
    global $depth;
    $depth[$parser]--;
}



//--------------------------------------------------------------------------
function cpstore_myErrorHandler($errno, $errstr, $errfile, $errline)
{
    switch ($errno) {
    case E_USER_ERROR:
        echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
        echo "  Fatal error on line $errline in file $errfile";
        echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
        echo "Aborting...<br />\n";
        exit(1);
        break;

    case E_USER_WARNING:
        echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
        break;

    case E_USER_NOTICE:
        echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
        break;

    default:

       //echo "Unknown error type: [$errno] $errstr<br />\n";
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}

?>