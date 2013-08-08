USER='root'
PASSWD='iplouc'
DBNAME='bioseqdb'
BIOSQL='./biosql/sql/biosqldb-mysql.sql'
mysqladmin -u ${USER} -p${PASSWD} drop ${DBNAME}
mysqladmin -u ${USER} -p${PASSWD} create ${DBNAME}
mysql -u ${USER} -p${PASSWD} ${DBNAME} < ${BIOSQL}
