#!/bin/bash
#
# Project: BasePHP Core
# File: provision.sh created by Ariel Bogdziewicz on 29/07/2018
# Author: Ariel Bogdziewicz
# Copyright: Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
# License: MIT
#
export DEBIAN_FRONTEND=noninteractive
export PROJECT_DIR=/home/vagrant/www/basephp-core

lib_configuration() {
    echo "Executing composer"
    su - vagrant -s /bin/bash -c "cd ${PROJECT_DIR} && php composer.phar install"
}

echo "BasePHP Core - Provisioning virtual machine..."
lib_configuration
