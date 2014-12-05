### Installation

Extract directory "cuho_welcome_page" from archive and copy to the system/expressionengine/third_party/ catalog of your site install.

Go to **"Add-ons > Extensions"** in top menu and seek for **"CUHO Welcome Page"** module.
Click "Install".

### Welcome page setup

1.  After addon installation got to **"Add-ons > Extension"** and find **"Settins"** link for the "**CUHO Welcome Pages**".
2.  Select entry to consider as a "Welcome page" from drop-down menu list and enter enrty URL.

    Enter page URL to show or redirect user into field "Page URL".
3.  To force show page display/redirect for all user, regardless previous show/redirect &mdash; check **"Force page display/redirect next time" before save settings**

_
	**Please note:** Due to flexible URL and template structure within ExpressionEngine we cannot determine real front-end URL for any entry you have.  You can simply copy URL from the site and paste into the field
_

#### Controlling welcome page

Extension will control selected page status and show will show/redirect welcome page **only** if page status are set to **"Open"**.

If you want to disable "Welcome page" simply turn-off entry ( set status "Close" )

### Redirect users back from the welcome page

To redirect users back and do not show page next time — add GET-parameter `**_ch_wpp**` to the redirection URL.

GET-parameter will be stripped and user will see clean URL without any addional query-parts.

For e.g.: redirection URL `http://domain.com/home/about?_ch_wpp=1` will become `http://domain.com/home/about` right after user click this URL and will see no "Welcome page" next time.

#### Form redirection

If you are able to build FORM with POST-request method, you may use no additional URL part — use hidden field for this purpose:
`
	<input type="hidden" name="_ch_wpp" value="1" />
`

### Support, requests and wishes

We are always open to help and willing to make our products better.
If you have any request you can contact authors by mail: [info@cuho.eu](mailto:info@cuho.eu)
