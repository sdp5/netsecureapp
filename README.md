# netsecureapp
basic network tools

### Requirements

* PHP 5.3.0, MySQL 5.1, Apache Server or XAMPP Server 1.7.2


### Deploy

* Download and install [Fedora 12](https://archives.fedoraproject.org/pub/archive/fedora/linux/releases/12/Fedora/x86_64/iso/Fedora-12-x86_64-DVD.iso). Make sure to select development tools, webserver and mysql.

Once fedora installation is complete. 

- check versions and start servers.
- look if php module is loaded in web server.
- initialize mysql database server.

```
rpm -q php
rpm -q mysql

service httpd start
service mysqld start

service httpd status
service mysqld status

httpd -M
/usr/bin/mysql_secure_installation
```

Download application and bootstrap.

```
cd /var/www/html
wget https://github.com/sundeep-anand/netsecureapp/archive/refs/heads/master.zip
unzip netsecureapp-master.zip
```

then, hit localhost on browser. 
You should see "Netsecureapp Installation" (Database Configuration) page.

1. Create a database in MySQL and edit db.xml with repective values. (follow onscreen instructions)
2. Second step is to create user. After that, one can login into the system. 
