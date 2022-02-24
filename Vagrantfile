# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|
    # The most common configuration options are documented and commented below.
    # For a complete reference, please see the online documentation at
    # https://docs.vagrantup.com.
  
    # Every Vagrant development environment requires a box. You can search for
    # boxes at https://vagrantcloud.com/search.
    config.vm.box = "ubuntu/bionic64"
  
    # Disable automatic box update checking. If you disable this, then
    # boxes will only be checked for updates when the user runs
    # `vagrant box outdated`. This is not recommended.
    # config.vm.box_check_update = false
  
    # Create a forwarded port mapping which allows access to a specific port
    # within the machine from a port on the host machine. In the example below,
    # accessing "localhost:8080" will access port 80 on the guest machine.
    # NOTE: This will enable public access to the opened port
    # Apache2 Server
    # Port 8080 in host machine forwards to port 80 in guest machine
    config.vm.network "forwarded_port", guest: 80, host: 8080
  
    # MySQL Server
    # Port 33060 in host machine forwards to port 3306 in guest machine
    config.vm.network "forwarded_port", guest: 3306, host: 33060
  
    # Create a forwarded port mapping which allows access to a specific port
    # within the machine from a port on the host machine and only allow access
    # via 127.0.0.1 to disable public access
    # config.vm.network "forwarded_port", guest: 80, host: 8080, host_ip: "127.0.0.1"
  
    # Create a private network, which allows host-only access to the machine
    # using a specific IP.
    # config.vm.network "private_network", ip: "192.168.33.10"
  
    # Create a public network, which generally matched to bridged network.
    # Bridged networks make the machine appear as another physical device on
    # your network.
    # config.vm.network "public_network"
  
    # Share an additional folder to the guest VM. The first argument is
    # the path on the host to the actual folder. The second argument is
    # the path on the guest to mount the folder. And the optional third
    # argument is a set of non-required options.
    config.vm.synced_folder ".", "/vagrant", :mount_options => ["dmode=777","fmode=766"]
  
  
    # Provider-specific configuration so you can fine-tune various
    # backing providers for Vagrant. These expose provider-specific options.
    # Example for VirtualBox:
    #
     config.vm.provider "virtualbox" do |vb|
    #   # Display the VirtualBox GUI when booting the machine
    #   vb.gui = true
    #
    #   # Customize the amount of memory on the VM:
       vb.memory = "2048"
     end
    #
    # View the documentation for the provider you are using for more
    # information on available options.
  
    # Enable provisioning with a shell script. Additional provisioners such as
    # Puppet, Chef, Ansible, Salt, and Docker are also available. Please see the
    # documentation for more information about their specific syntax and use.
    config.vm.provision "shell", inline: <<-SHELL
      cd /tmp
      echo "Updating repositories"
      apt-get -qq update
      apt-get -qq install -y software-properties-common
      echo "Adding php repository"
      add-apt-repository -y ppa:ondrej/php
      echo "Updating repositories"
      apt-get -qq update -y
      echo "Configuring mysql server pre-install"
      debconf-set-selections <<< 'mysql-server mysql-server/root_password password vagrant'
      debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password vagrant'
      echo "Installing Apache2, PHP8.0 and  MariaDB Server"
      apt-get -qq -y install php8.0 php8.0-bcmath php8.0-bz2 php8.0-gd php8.0-xml php8.0-mysql php8.0-curl php8.0-gmp php8.0-intl php8.0-mcrypt php8.0-mbstring php8.0-zip mariadb-server zip unzip apache2
      cd /etc/apache2/sites-available
      a2dissite 000-default.conf
      echo -e "<VirtualHost *:80>\n\tServerAdmin webmaster@localhost\n\tDocumentRoot /vagrant/public\n\n\tErrorLog \${APACHE_LOG_DIR}/error.log\n\tCustomLog \${APACHE_LOG_DIR}/access.log combined\n\t<Directory \"/vagrant/public\">\n\t\tAllowOverride All\n\t\tRequire all granted\n\t</Directory>\n</VirtualHost>" > 001-laravel.conf
      a2ensite 001-laravel.conf
      a2enmod rewrite
      service apache2 reload
      cd /tmp
      echo "NodeJS pre-install"
      curl -fsSL https://deb.nodesource.com/setup_16.x | sudo -E bash -
      echo "Installing NodeJS"
      apt-get install -y nodejs

      echo "Creating test database"
      echo "CREATE DATABASE vagrant;" | mariadb
      echo "Creating users for database connection"
      echo "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'vagrant'" | mariadb
      echo "GRANT ALL PRIVILEGES ON *.* TO 'laravel'@'%' IDENTIFIED BY 'vagrant'" | mariadb
      
      echo "Allow connection to MariaDB-server"
      echo "" >> /etc/mysql/my.cnf
      echo "[mysqld]" >> /etc/mysql/my.cnf
      echo "bind-address = ::" >> /etc/mysql/my.cnf
      echo "" >> /etc/mysql/my.cnf

      echo "Restarting MariaDB"
      service mariadb restart

      echo "Fetching composer installer"
      EXPECTED_SIGNATURE="$(wget -q -O - https://composer.github.io/installer.sig)"
      php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
      ACTUAL_SIGNATURE="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"
      if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ]
      then
          >&2 echo 'ERROR: Invalid installer signature'
          rm composer-setup.php
      fi
      echo "Installing composer"
      php composer-setup.php
      rm composer-setup.php
      mv composer.phar /usr/local/bin/composer
      echo "Running composer install/update"
      cd /vagrant

      if [ ! -f .env ]; then
          su vagrant -c "\
          sed -e 's/^APP_URL=.*$/APP_URL=http:\\/\\/localhost:8080/' \
          -e 's/^LOG_CHANNEL=.*$/LOG_CHANNEL=single/' \
          -e 's/^DB_DATABASE=.*$/DB_DATABASE=vagrant/' \
          -e 's/^DB_USERNAME=.*$/DB_USERNAME=laravel/' \
          -e 's/^DB_PASSWORD=.*$/DB_PASSWORD=vagrant/' \
          .env.example > .env"
      fi
      su vagrant -c "composer install -vvv"
      su vagrant -c "php artisan key:generate"
      su vagrant -c "php artisan migrate:fresh --seed"
    SHELL
  end