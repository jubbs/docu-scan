#!/bin/bash

scanimage -d kds_i2000:i2000 --resolution 300 --batch --duplex $1 --mode Color

i=0
for filename in *.pnm; do
    pnmtopng $filename > /home/hhaadmin/docu-scan/scans/$i.png
    rm $filename
    let i=$i+1
done
