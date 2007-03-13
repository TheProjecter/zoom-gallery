<?php
//zOOm Media Gallery//
/** 
-----------------------------------------------------------------------
|  zOOm Media Gallery! by Mike de Boer - a multi-gallery component    |
-----------------------------------------------------------------------

-----------------------------------------------------------------------
|                                                                     |
| Author: Mike de Boer, <http://www.mikedeboer.nl>                    |
| Copyright: copyright (C) 2007 by Mike de Boer                       |
| Description: zOOm Media Gallery, a multi-gallery component for      |
|              Joomla!. It's the most feature-rich gallery component  |
|              for Joomla!! For documentation and a detailed list     |
|              of features, check the zOOm homepage:                  |
|              http://www.zoomfactory.org                             |
| License: GPL                                                        |
| Filename: credits.php                                               |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:credits.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
$Itemid = mosGetParam($_REQUEST,'Itemid');
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
?>
<center>
  <?php
  if (!$zoom->_isBackend) {
  ?>
  <table border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
      <td align="center" width="100%"><a href="<?php echo ($zoom->_isBackend) ? "index2.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=admin" : sefReltoAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=admin");?>"> <img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/home.gif" alt="<?php echo _ZOOM_MAINSCREEN;?>" border="0" /> &nbsp;&nbsp;<?php echo _ZOOM_MAINSCREEN;?> </a> </td>
    </tr>
  </table>
  <?php
  }
  ?>
  <br />
  <br />
  <table cellspacing="0" cellpadding="0" border="0">
    <tr>
      <td style="padding-right: 10px;" valign="top"><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/zoom_logo.gif" border="0" alt="" /><br />ver. <?php echo $zoom->_CONFIG['version'];?></td>
      <td valign="top" style="padding-right: 10px;"><p><strong>Created by:</strong><br />
          Mike de Boer <br />
          Amsterdam - The Netherlands<br />
        </p>
        <p><strong>Contact Information</strong><br />
          Web: <a href="http://www.zoomfactory.org" target="_blank">http://www.zoomfactory.org</a><br />
          Personal: <a href="http://www.mikedeboer.nl"  target="_blank">http://www.mikedeboer.nl</a> </p></td>
    </tr>
  </table>
  <br />
  <br />
  <table width="85%" border="0" class="adminform">
	<tr>
		<th colspan="4">
		zOOm Media Gallery Developers
		</th>
	</tr>
      <tr>
        <td width="100%">
			<div align="left">
				<ul>
              	<li><a href="http://www.corephp.com/" target="_blank" title="corePHP Web Development Services">'corePHP' Web Development Services</a> + <a href="http://www.themirrorimages.com/" target="_blank" title="Steven and Michael - The Mirror Images">Steven Pignataro</a> - Developer</li>
          		<li><a href="http://www.kah-lab.com" target="_blank" title="Kevin Hayne">Kevin Hayne</a> - Developer </li>
				</ul>
          	</div>
		</td>
      </tr>
  </table>
  <br />
  <br />
  <table width="85%" border="0" class="adminform">
  	<tr>
		<th colspan="4">
		zOOm Media Gallery Contributions
		</th>
	</tr>
      <tr>
        <td width="100%"><div align="left">
            <ul>
              <li><strong>Zoom logo</strong> by Varina Kammeraat <a href="http://www.kammeraat.nl" target="_blank">http://www.kammeraat.nl</a>.</li>
              <li><strong>Icons used by zOOm Media Gallery </strong>are made by FOOOD at <a href="http://www.foood.net" target="_blank">FOOOD.net</a><br />
                All icons are commercial software and NOT available for public use or (re)distribution!</li>
              <li><strong>Andrea Melzi</strong> for the Italian translation and being my first fan!</li>
              <li><strong>Chris Marquette </strong>for his help on the gallery-sorting and imagesize problem(s).</li>
              <li><strong>D. Gentile </strong>for his useful addition to zOOm 2.1, see his work on <a href="http://www.ronin.to" target="_blank">www.ronin.to</a></li>
              <li><strong>Max Faber</strong> for his input on the development of the user-system</li>
              <li><strong>Chrixo ITA </strong>for fixing the navigation issue!</li>
              <li><strong>Per Lasse Baasch (a.k.a. freindler) </strong>for the german translation, his work on the zOOm Module, the new website and many other things! His positive support and feedback helps me a lot. Thanx!</li>
              <li><strong>Rob aka. Xirtam</strong> for making the first zOOm website possible!</li>
              <li><strong>David Bates</strong> for his offer to co-develop zOOm Media Gallery! He already made some helpful suggestions. Thanx!</li>
              <li><strong>Mic</strong> Thank you so much and I hope we keep in touch.</li>
              <li><strong>The Translation Team</strong> Consisting of: <strong>Jimmy Halldin</strong>, <strong>Helv&eacute;cio Mafra</strong>, setup, <strong>Tom HAN</strong>, <strong>Lars H&oslash;rmann</strong>, <strong>Per Lasse Baasch</strong>,
                <strong>Andrea Melzi</strong>, <strong>Phillippe Lenzi and Federico</strong> <strong>Almada</strong>!</li>
              <li><strong>Presdough</strong> for his fix of the eCards function.</li>
              <li><strong>Steven Pignataro</strong> for his continuing efforts to make ZMG the best gallery product on the web.</li>
              <li><strong>Zachary Hopkins</strong> for all his hard work in helping to get all the international characters working! <a href="http://hopkinsprogramming.net/" target="_blank">Hopkins Programming</a></li>
              <li><strong>Matthias Buchwald</strong> Thanks for the update german language files</li>
	          <li><strong>Kevin Hayne</strong> for being the biggest contributor to zOOm to this date! Remember this name ;)</li>
              <li><strong>And many, many more...</strong> I'm just as grateful to whom I forgot to mention here and contributed to zOOm!</li>
            </ul>
          </div></td>
      </tr>
  </table>
  <br />
  <br />
  <table width="85%" border="0" class="adminform">
  	<tr>
		<th colspan="4">
		Acknowledgements (credits to authors who made various libraries the ZMG project uses)
		</th>
	</tr>
      <tr>
        <td width="100%"><div align="left">            
            <ul>
              <li><strong>getID3()</strong> by James Heinrich &lt;<a href="http://www.getid3.org" target="_blank">http://www.getid3.org</a>&gt;</li>
              <li><strong>PHP JPEG Metadata Toolkit</strong> by Evan Hunter &lt;<a href="http://electronics.ozhiker.com" target="_blank">http://electronics.ozhiker.com</a>&gt;</li>
              <li><strong>MiniXML</strong> by Patrick Deegan &lt;<a href="http://minixml.psychogenic.com" target="_blank">http://minixml.psychogenic.com</a>&gt;</li>
              <li><strong>Scriptaculous</strong> by Thomas Fuchs &lt;<a href="http://script.aculo.us" target="_blank">http://script.aculo.us</a>&gt;</li>
              <li><strong>tjpzoom2</strong> by Valid &lt;<a href="http://valid.tjp.hu/zoom2/index_en.html" target="_blank">http://valid.tjp.hu/zoom2/index_en.html</a>&gt;</li>
              <li><strong>dhtmlgoodies Tree</strong> by Alf Magne Kalleland &lt;<a href="http://www.dhtmlgoodies.com" target="_blank">http://www.dhtmlgoodies.com</a>&gt;</li>
              <li><strong>Lightbox</strong> by Lokesh Dhakar &lt;<a href="http://huddletogether.com/projects/lightbox2/" target="_blank">http://huddletogether.com/projects/lightbox2/</a>&gt;</li>
              <li><strong>Flash mp3 Player</strong> by Jeroen Wijering &lt;<a href="http://www.jeroenwijering.com/?item=Flash+MP3+Player" target="_blank">http://www.jeroenwijering.com/?item=Flash+MP3+Player</a>&gt;</li>
              <li><strong>Tab Pane</strong> by Erik Arvidsson &lt;<a href="http://webfx.eae.net" target="_blank">http://webfx.eae.net</a>&gt;</li>
              <li><strong>JUpload</strong> by Mike Haller &amp; Dominik Seifert &lt;<a href="http://www.jupload.biz" target="_blank">http://www.jupload.biz</a>&gt;</li>
            </ul>
          </div></td>
      </tr>
  </table>
</center>