USER='root'
PASSWD='pwd'
DBNAME='bioseqdbvrl'
BIOSEQDBSQL='./biosql/bioseqdbvrl-Birnaviridae-zhy.sql'
mysql -u ${USER} -p${PASSWD} ${DBNAME} < ${BIOSEQDBSQL}
