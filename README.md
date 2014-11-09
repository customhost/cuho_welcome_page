<h3>Installation</h3>
Extract directory "cuho_welcome_page" from archive and copy to the system/expressionengine/third_party/ catalog of your site install.
<br/>
Go to <b>"Add-ons > Extensions"</b> in top menu and seek for <b>"CUHO Welcome Page"</b> module.
Click "Install".

<br/><br/>

<h3>Welcome page setup</h3>
<ol>
	<li>After addon installation got to <b>"Add-ons > Extension"</b> and find <b>"Settins"</b> link for the "<b>"CUHO Categories Groups</b>".</li>

	<li>Select entry to consider as a "Welcome page" from drop-down menu list and enter enrty URL.<br/>
Enter page URL to show or redirect user into field "Page URL".</li>

	<li>
		To force show page display/redirect for all user, regardless previous show/redirect &mdash; check <b>"Force page display/redirect next time" before save settings</b>
	</li>
</ol>
<br/>
<i>
	<b>Please note:</b> Due to flexible URL and template structure within ExpressionEngine we cannot determine real front-end URL for any entry you have.  You can simply copy URL from the site and paste into the field
</i>
<br/>

<h4>Controlling welcome page</h4>
Extension will control selected page status and show will show/redirect welcome page <b>only</b> if page status are set to <b>"Open"</b>.<br/>
If you want to disable "Welcome page" simply turn-off entry ( set status "Close" )

<h3>Redirect users back from the welcome page</h3>
To redirect users back and do not show page next time — add GET-parameter <code><b>_ch_wpp</b></code> to the redirection URL.<br/>
GET-parameter will be stripped and user will see clean URL without any addional query-parts.<br/>
<br/>
For e.g.: redirection URL <code>http://domain.com/home/about?_ch_wpp=1</code> will become <code>http://domain.com/home/about</code> right after user click this URL and will see no "Welcome page" next time.
<br/>

<h4>Form redirection</h4>
If you are able to build FORM with POST-request method, you may use no additional URL part — use hidden field for this purpose:
<code>
	&lt;input type=&quot;hidden&quot; name=&quot;_ch_wpp&quot; value=&quot;1&quot; /&gt;
</code>
<br/>
<h3>Support, requests and wishes</h3>
We are always open to help and willing to make our products better.
If you have any request you can contact authors by mail: <a href="mailto:info@cuho.eu">info@cuho.eu</a>