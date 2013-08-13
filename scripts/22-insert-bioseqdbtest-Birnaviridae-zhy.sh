USER='root'
PASSWD='pwd'
DBNAME='bioseqdbtest'
BIOSEQDBSQL='./biosql/bioseqdbvrl-Birnaviridae-zhy.sql'
mysql -u ${USER} -p${PASSWD} ${DBNAME} < ${BIOSEQDBSQL}
