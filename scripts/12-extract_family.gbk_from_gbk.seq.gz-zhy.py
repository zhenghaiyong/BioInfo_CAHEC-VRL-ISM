#!/usr/bin/env python
"""Extract family entries from GenBank .gz files to GenBank file.

Haiyong Zheng <zhenghaiyong@gmail.com>

Usage:
    extract_family.gbk_from_gbk.seq.gz <GenBank .gz directory path> <family name>
"""

from __future__ import with_statement

import re
def sort_nicely( l ): 
  """ Sort the given list in the way that humans expect. 
  Natural/Human Sorting:
  http://www.codinghorror.com/blog/2007/12/sorting-for-humans-natural-sort-order.html
  http://nedbatchelder.com/blog/200712/human_sorting.html
  http://stackoverflow.com/questions/5967500/how-to-correctly-sort-string-with-number-inside
  http://stackoverflow.com/questions/2545532/python-analog-of-natsort-function-sort-a-list-using-a-natural-order-algorithm
  http://stackoverflow.com/questions/4836710/does-python-have-a-built-in-function-for-string-natural-sort
  """ 
  convert = lambda text: int(text) if text.isdigit() else text 
  alphanum_key = lambda key: [ convert(c) for c in re.split('([0-9]+)', key) ] 
  l.sort( key=alphanum_key ) 


import os, sys
import gzip
from Bio import SeqIO

def main(gbdirpath,familyname):
    gbdir = os.listdir(gbdirpath)
    gbfiles = [file for file in gbdir if (".seq.gz" in file)]
    sort_nicely(gbfiles)
    print "Starting Batch Extracting: %s" % (gbfiles)
    familyrecords = []
    for gbfile in gbfiles:
	gbdirpathfile = gbdirpath + "/" + gbfile
        print "Parsing GenBank file sequence file: %s" % (gbdirpathfile)
	with gzip.open(gbdirpathfile, "r") as gb_handle:
	    records = list(SeqIO.parse(gb_handle, "genbank"))
	for record in records:
	    if "taxonomy" in record.annotations and isinstance(record.annotations['taxonomy'], list):
	        if ((len(record.annotations['taxonomy']) >= 1) and (record.annotations['taxonomy'][0] == familyname))\
	        or ((len(record.annotations['taxonomy']) >= 2) and (record.annotations['taxonomy'][1] == familyname))\
	        or ((len(record.annotations['taxonomy']) >= 3) and (record.annotations['taxonomy'][2] == familyname))\
	        or ((len(record.annotations['taxonomy']) >= 4) and (record.annotations['taxonomy'][3] == familyname))\
	        or ((len(record.annotations['taxonomy']) >= 5) and (record.annotations['taxonomy'][4] == familyname)):
	                familyrecords.append(record) 
	print "%s: %d\t%s: %d" % (gbdirpathfile, len(records), familyname, len(familyrecords))
    print "\nTOTAL %s: %d" % (familyname, len(familyrecords))
    familyfile = familyname + ".gbk"
    print "Writing to GenBank file: %s" % (familyfile)
    SeqIO.write(familyrecords, familyfile, "genbank")
    print "%d records done." % (len(familyrecords))

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print __doc__
        sys.exit()
    elif sys.argv[2][-3:] != 'dae':
        print "%s is not a family name that should be ended with 'dae'." % (sys.argv[2])
        sys.exit()
    main(sys.argv[1],sys.argv[2])
