#!/bin/bash

set -e

usermod -u 1000 nginx
groupmod -g 1000 nginx
