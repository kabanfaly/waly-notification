#!/bin/sh
npm run build
ssh nafadjitech "cd; cd waly-notification; git stash; git pull"
scp -r public/build nafadjitech:/home/nafaudww/public_html/notification
scp -r public/css nafadjitech:/home/nafaudww/public_html/notification
scp -r public/images nafadjitech:/home/nafaudww/public_html/notification
scp -r public/js nafadjitech:/home/nafaudww/public_html/notification
