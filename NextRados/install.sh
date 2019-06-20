#!/bin/sh
# setup.sh
# setup script for NextRados

if [ ! -w /etc/init.d ]; then
  echo "Run this script in more privileged mode please"
else
  if [ ! -d /etc/nextrados ]; then
    echo "NextRados - Ceph Service Administration Tool"
    echo "Installing..."
    cp bin/NextRados /usr/bin
    cp -r bin/config/ /etc/
    mv /etc/config /etc/nextrados

    echo "[Unit]" >> nextrados.service
    echo "Description=NextRados - Ceph Service Administration Tool" >> nextrados.service
    echo "After=network.target" >> nextrados.service
    echo "StartLimitIntervalSec=0" >> nextrados.service
    echo "[Service]" >> nextrados.service
    echo "Type=simple" >> nextrados.service
    echo "Restart=always" >> nextrados.service
    echo "RestartSec=1" >> nextrados.service
    echo "User=$USER" >> nextrados.service
    echo "ExecStart=/usr/bin/nextrados" >> nextrados.service
    echo "" >> nextrados.service
    echo "[Install]" >> nextrados.service
    echo "WantedBy=multi-user.target" >> nextrados.service

    mv nextrados.service /lib/systemd/system/
    
    echo "NEXTRADOS SUCCESSFULLY INSTALLED"

    systemctl start nextrados
    systemctl enable nextrados
    echo "Success!"
    echo "Don't forget to config on /etc/nextrados/config.toml"
  else
    echo "Nextrados already installed"
  fi
fi
