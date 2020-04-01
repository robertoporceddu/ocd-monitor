#!/bin/bash

echo "asterisk stop ... "
service asterisk stop

echo "clean spool ... "
rm /var/spool/asterisk/outgoing/*

echo "reboot ... "
reboot
