import pymysql

conn = pymysql.connect(host='127.0.0.1', user='root', password='', database='laraforgex')
cur = conn.cursor()
cur.execute("""
    SELECT u.id, u.name, u.email, r.name as role_name
    FROM users u
    JOIN model_has_roles mhr ON u.id = mhr.model_id
    JOIN roles r ON mhr.role_id = r.id
""")
print('Users and their roles:')
for row in cur.fetchall():
    print(row)

cur.execute("SELECT id, name FROM permissions")
print('Available permissions count:', len(cur.fetchall()))
conn.close()
