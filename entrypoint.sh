#!/bin/bash

set -e

ffmpeg -i "$1" /tmp/original/thumb%04d.jpg -hide_banner
php wheel.php
