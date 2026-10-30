import pymysql

conn = pymysql.connect(host='127.0.0.1', user='root', password='', database='laraforgex', port=3306)
cursor = conn.cursor()
cursor.execute("DESCRIBE crm_leads")
columns = [row[0] for row in cursor.fetchall()]
for c in columns:
    if 'group' in c or 'product' in c or 'customer' in c:
        print("Column:", c)
