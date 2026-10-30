import pymysql

conn = pymysql.connect(host='127.0.0.1', user='root', password='', database='laraforgex', port=3306)
cursor = conn.cursor()
cursor.execute("SHOW TABLES")
tables = [row[0] for row in cursor.fetchall()]
print("Tables in laraforgex:", len(tables))
for t in sorted(tables):
    if 'group' in t or 'solar' in t or 'lead' in t or 'crm' in t:
        print(" -", t)
