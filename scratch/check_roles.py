import pymysql

conn = pymysql.connect(host='127.0.0.1', user='root', password='', database='laraforgex')
cur = conn.cursor()
cur.execute("SHOW TABLES LIKE '%role%'")
print('Role tables:', cur.fetchall())
try:
    cur.execute("SELECT id, name, guard_name, created_at FROM roles")
    print('Roles in roles table:', cur.fetchall())
except Exception as e:
    print('Error on roles table:', e)

try:
    cur.execute("DESCRIBE roles")
    print('Roles columns:', cur.fetchall())
except Exception as e:
    print('Error describing roles:', e)

try:
    cur.execute("SELECT * FROM model_has_roles")
    print('model_has_roles:', cur.fetchall())
except Exception as e:
    print('Error on model_has_roles:', e)

conn.close()
