USER='root'
PASSWD='pwd'
DBNAME='bioseqdbvrl'
BIOSQL='./biosql/sql/biosqldb-mysql-zhy.sql'
mysqladmin -u ${USER} -p${PASSWD} drop ${DBNAME}
mysqladmin -u ${USER} -p${PASSWD} create ${DBNAME}
mysql -u ${USER} -p${PASSWD} ${DBNAME} < ${BIOSQL}
