#!/usr/bin/env sh

composer install

rr serve -d -c .rr.yaml
