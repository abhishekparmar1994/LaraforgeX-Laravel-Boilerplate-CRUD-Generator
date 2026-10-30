import pymysql

conn = pymysql.connect(host='127.0.0.1', user='root', password='', database='laraforgex')
cur = conn.cursor()
cur.execute("SELECT id, name FROM permissions")
for p in cur.fetchall():
    print(p)
conn.close()
