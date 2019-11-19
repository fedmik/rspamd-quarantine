Quarantine service for Rspamd
-----------------------------

Stores blocked by rspamd messages in mysql database and provides a simple GUI to find and get from quarantine the message you are looking for.

Requirements
------------

Web server with PHP support, PHP, Mysql

Setting up
----------

Create database using the database.sql file.

Put all the other files in the virtual host's root directory.

Change the settings.php file: specify database settings and rspamd hosts wich will be allowed to store messages in the quarantine service.

Since messages can be large, I recommend you to increase the maximum post size of your web server (in apache it's the post_max_size parameter).

I didn’t do any authorization to access web GUI so if you need it you can configure it using htpasswd for example - https://httpd.apache.org/docs/2.4/howto/auth.html

Configure the Metadata Exporter module on each rspamd host by adding the rule like this:

```rules {
   QUARANTINE_1 {
      backend = "http";
      url = "http://quarantine_site/metadata.php";
      selector = "is_spam";
      formatter = "default";
      meta_headers = true;
   }
```}

The "selector" parameter can be "is_spam" or "is_reject". In the first case it will quarantine all messages with "reject" or "add header" action, in the second case it will quarantine only messages with "reject" action.

You can get more detailed information on configuring the Metadata Exporter module here - https://rspamd.com/doc/modules/metadata_exporter.html
