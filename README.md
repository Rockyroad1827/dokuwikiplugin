# dokuwikiplugin
Plugins for Dokuwiki:
+ quickcreate
+ +discordwebhook

directory:
Dokuwii -> lib -> Plugins
## === quickcreate ===

QuickCreate is a plugin used to make pages fast in dokuwiki - by adding this plugin, a button will appear in the bottom right of your screen. click it to add a new page with a custom namespace and pagename.

+ If you wish for the page to be created in the root directory then leave the namespace blank, however if you would like the page to be under a custom namespace then you may enter one.
+ The pagename must be entered, this is so the form can work properly. you can not make a namespace and leave it empty as dokuwiki does not support it. a page must always be in a namespace.

- Once a page has been created it will be empty. if you leave the page once it has been created - the page will auto delete and you will have to make it again.

## === discordwebhook ===

Discordwebhook is a plugin that allows the user to connect a webhook to the dokuwiki server. it will send an embed into the specified chat (changed in the discord ui). it will show if a page is edited or created, and will also show its location.

+ To setup this plugin, go to the configuration settings in your dokuwiki page and go to plugins -> discordwebhook. Copy your discord webhook and paste it into the url box.
+ If you want the plugin to not send the webhook to discord either delete the webhook url or uncheck the enable tickbox in the configuration settings.

  ![image](https://github.com/user-attachments/assets/3bb470b3-61c7-452f-b428-4ee466773f70)

