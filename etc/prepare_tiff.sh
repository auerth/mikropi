#!/bin/bash
isBiggestPage=true
pageIndex="0"
biggestPage="0"
substring="width:"
biggestHeight="0"   
biggestWidth="0"
tiffPath=""
savePathAndName=""
if [ -z $1  ] 2>/run/shm/error.log
  then
    echo "Fehler: Bitte Source Tiff angeben"
    exit 0
  else
    tiffPath=$1
    echo $1
fi
if [ -z $2  ] 2>/run/shm/error.log
  then
    echo "Fehler: Bitte Target Name angeben"
    exit 0
  else
    savePathAndName=$2
    echo $2
fi

while [ "$isBiggestPage" = true ] 
do
tiffHeader=`vipsheader -a  $tiffPath[page=$pageIndex] 2>&1`
if [[ $tiffHeader =~ "no property named" ]]; then
   isBiggestPage=false
   biggestPage=-1
   echo "page atrribute not found. Trying to convert without page arrtribute ..."
else
echo $tiffHeader
   	string='width:'
	variable2='height:'
	tmp=${tiffHeader#*$string}
	width=${tmp%$variable2*}
	width=$((width+1))
	string='height:'
	variable2='bands:'
	tmp=${tiffHeader#*$string}
	height=${tmp%$variable2*}
	height=$((height+1))
	if [ "$height" -gt "$biggestHeight" ]; then
		biggestHeight=$height
		biggestPage=$pageIndex
	fi
	if [ "$width" -gt "$biggestWidth" ]; then
		biggestWidth=$width
	fi

	if [[ $tiffHeader =~ $substring ]]; then
		pageIndex=$((pageIndex+1))
	else
		isBiggestPage=false
	fi
fi
done
if [ "$biggestPage" -gt "-1" ]; then
	
 echo "Converting tiff page $biggestPage"
 
 tiffHeader=`vips dzsave "$tiffPath[page=$biggestPage]"  "$savePathAndName" --suffix .jpg[Q=90] 2>&1` 
else
 tiffHeader=`vips dzsave "$tiffPath"  "$savePathAndName" --suffix .jpg[Q=90] 2>&1` 
fi

