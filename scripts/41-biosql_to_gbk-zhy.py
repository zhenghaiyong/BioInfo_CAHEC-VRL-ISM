#!/usr/bin/env python
"""Extract BioSQL entries to GenBank file.

Haiyong Zheng <zhenghaiyong@gmail.com>

References:
    http://www.biostars.org/p/46849/
    http://www.biostars.org/p/65644/

Usage:
    biosql_to_gbk.py <bioseqdb name> <biodatabase name>
"""

import sys
from BioSQL import BioSeqDatabase
from Bio import SeqIO

def main(bioseqdb,biodatabase):
    driver = "MySQLdb"
    user   = "root"
    passwd = "pwd"
    host   = "localhost"

    print "Extracting BioSQL entries from database: %s-%s" % (bioseqdb,biodatabase)
    server = BioSeqDatabase.open_database(driver=driver, user=user,
            passwd=passwd, host=host, db=bioseqdb)
    db = server[biodatabase]
    records = []
    for key in db:
        records.append(db[key])
    gbfile = bioseqdb + "-" + biodatabase + ".gbk"
    print "Writing to GenBank file: %s" % (gbfile)
    num = SeqIO.write(records, gbfile, "genbank")
    print "%d records done." % (num)

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print __doc__
        sys.exit()
    main(sys.argv[1],sys.argv[2])
