#!/bin/bash

ssh arkania "cd /home/grace/DPL/dpl22-23/UT4/TE2/src/Spring/travelroad; git pull; systemctl --user restart travelroad.service"
