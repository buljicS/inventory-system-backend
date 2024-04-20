[< Go Back](../README.md)
## Setup custom domain name for local project

> If we want to open project on our local machine we need to put project folder in server root
> `htdocs`, for example. Then we need to open that up in browser like `localhost/myproject`

> We can set custom domain name for these using VirutalHost option in apache web server

### Custom domain name, step by step

- Open up `httpd.conf` file (apache)
- Look for `Include conf/extra/httpd-vhosts.conf` line
  - Uncomment it if its commented
- Open `httpd-vhosts.conf` file and setup your custom domain

```apacheconf
    <VirtualHost *:80>
        DocumentRoot "C:/xampp/htdocs/myproject"
        ServerName myproject.localhost
        <Directory "C:/xampp/htdocs/myproject">
            Require all granted
            AllowOverride All
    </Directory>
    </VirtualHost>
```

- Now restart apache server and visit `myproject.localhost` on your browser
- This would be the usual way to setup project on localhost, but let's say that we want to setup cusom domain like .com, .org. .local, etc
- This would require one more step
- We need to add this domain in our hosts file
- `127.0.0.1     myproject.com`
- Now in `httpd-vhosts.conf` file we have something like this

```apacheconf
    <VirtualHost *:80>
        DocumentRoot "C:/xampp/htdocs/myproject"
        ServerName myproject.com
        <Directory "C:/xampp/htdocs/myproject">
            Require all granted
            AllowOverride All
    </Directory>
    </VirtualHost>
```

- That's it, restart apache and visit `myproject.com`