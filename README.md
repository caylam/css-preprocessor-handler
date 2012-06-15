# Apache 2 CSS Pre Processor handler for LESS, SASS and STYLUS

## Overview
The purpose is to have Apache handle serving LESS, SASS and STYLUS documents. This allows these files to be served directly by the server without the need to compile manually.
LESS is compiled on the server using node.js and lessc.
SASS is compiled on the server using ruby gem install SASS.
STYLUS is compiled on the server using node.js and stylus.

The compiled files are cached in the temporary directory and then passed through to the browser. Compiled code is returned as text/css.


### Error Handling
When an error occurs while compiling the server will return a 500 status code and the STDERR output from the compiler as text/plain.


### Options
There are several query string flags that can be set to perform some specific tasks.

- **recache**
    * Forces recompile and caching of a given file.
    * example: *http://example.com/styles.less?recache*
    * example: *http://example.com/styles.scss?recache*
    * example: *http://example.com/styles.sass?recache*
    * example: *http://example.com/styles.styl?recache*
- **nocache**
    * Bypasses caching and returns compiled output. This is significantly less scalable than serving cached files. Typically this is used while development is occurring.
    * example: *http://example.com/styles.less?nocache*
- **nocompile**
    * Returns the uncompiled LESS / SASS / STYLUS file.
    * example: *http://example.com/styles.less?nocompile*
- **debug**
    * Bypasses caching and returns compiled output with debug info. Used while development is occurring.
    * This currently only really works for SASS and fireSass. STYLUS compiler has a bug that is prevents this from working at the moment. LESS doesn't really output debug info but the verbose switch is set.
    * example: *http://example.com/styles.less?debug*

##Installation

### Requirements
- Apache 2.2.x
- PHP 5.x
- nodejs >= 6.x
- LESS >= 1.x / SASS / STYLUS

###Install Nodejs

####Fedora / CentOS / RHEL

Execute the following with root permissions i.e. sudo.

    rpm --import http://nodejs.tchol.org/RPM-GPG-KEY-tchol
    yum localinstall --nogpgcheck http://nodejs.tchol.org/repocfg/fedora/nodejs-stable-release.noarch.rpm
    yum install nodejs npm
    ln -svf /usr/bin/nodejs /usr/bin/node

####Debian / Mint / Ubuntu

Execute the following with root permissions i.e. sudo.

    apt-get install python-software-properties
    add-apt-repository ppa:chris-lea/node.js
    apt-get update
    apt-get install nodejs npm

###SASS / SCSS

    gem install sass

###STYLUS

    npm install -g stylus

###LESS

####Fedora / CentOS / RHEL
    npm install -g less
    ln -svf /usr/lib/node_modules/less/bin/lessc /usr/bin/lessc
    
    cp ./less.conf /etc/httpd/conf.d/less.conf
    cp -r ./less-compiler /etc/httpd/conf.d/

####Debian / Mint / Ubuntu
    npm install -g less

    cp less.conf /etc/apache2/conf.d/less.conf
    cp -r ./less-compiler /etc/apache2/conf.d/



