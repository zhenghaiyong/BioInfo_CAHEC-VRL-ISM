USER='root'
PASSWD='pwd'
DBNAME='bioseqdbvrl'
BIODATABASESQL='./biosql/bioseqdbvrl-biodatabase-Birnaviridae-zhy.sql'
GENESQL='./biosql/bioseqdbvrl-gene-Birnaviridae-zhy.sql'
mysql -u ${USER} -p${PASSWD} ${DBNAME} < ${BIODATABASESQL}
mysql -u ${USER} -p${PASSWD} ${DBNAME} < ${GENESQL}
