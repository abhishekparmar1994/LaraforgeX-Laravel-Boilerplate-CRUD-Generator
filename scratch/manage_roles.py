import pymysql
import uuid

conn = pymysql.connect(host='127.0.0.1', user='root', password='', database='laraforgex')
cur = conn.cursor()

# 1. Fetch current roles
cur.execute("SELECT id, name FROM roles")
roles_dict = {row[1]: row[0] for row in cur.fetchall()}
print("Existing roles:", roles_dict)

admin_id = roles_dict.get('administrator')
student_id = roles_dict.get('student')
educator_id = roles_dict.get('educator')

# 2. Create 'executive' role if not exists
executive_id = str(uuid.uuid4())
cur.execute("SELECT id FROM roles WHERE name = 'executive'")
existing_exec = cur.fetchone()
if existing_exec:
    executive_id = existing_exec[0]
    print("Executive role already exists with id:", executive_id)
else:
    cur.execute("""
        INSERT INTO roles (id, name, guard_name, description, parent_id, created_at, updated_at)
        VALUES (%s, 'executive', 'web', 'Solar EPC Executive handling pipeline, site surveys, sizing and customer proposals', %s, NOW(), NOW())
    """, (executive_id, admin_id))
    print("Created executive role with id:", executive_id)

# 3. Assign permissions to executive
# Executive gets: users.view, media.view, media.upload, settings.view, etc.
exec_permissions = ['users.view', 'media.view', 'media.upload', 'settings.view', 'roles.view']
for perm in exec_permissions:
    cur.execute("SELECT id FROM permissions WHERE name = %s", (perm,))
    p = cur.fetchone()
    if p:
        perm_id = p[0]
        cur.execute("SELECT 1 FROM role_has_permissions WHERE permission_id = %s AND role_id = %s", (perm_id, executive_id))
        if not cur.fetchone():
            cur.execute("INSERT INTO role_has_permissions (permission_id, role_id) VALUES (%s, %s)", (perm_id, executive_id))
print("Permissions assigned to executive.")

# 4. Reassign any user who had student/educator role to executive (if they don't already have another role)
if student_id:
    cur.execute("""
        UPDATE model_has_roles 
        SET role_id = %s 
        WHERE role_id = %s
    """, (executive_id, student_id))
    print("Reassigned student users to executive.")

if educator_id:
    cur.execute("""
        DELETE FROM model_has_roles WHERE role_id = %s
    """, (educator_id,))
    print("Removed educator assignments.")

# 5. Delete permissions for student and educator
if student_id:
    cur.execute("DELETE FROM role_has_permissions WHERE role_id = %s", (student_id,))
if educator_id:
    cur.execute("DELETE FROM role_has_permissions WHERE role_id = %s", (educator_id,))

# 6. Delete student and educator roles
if student_id:
    cur.execute("DELETE FROM roles WHERE id = %s", (student_id,))
    print("Deleted student role.")
if educator_id:
    cur.execute("DELETE FROM roles WHERE id = %s", (educator_id,))
    print("Deleted educator role.")

# Ensure parent_id is not pointing to deleted roles
cur.execute("UPDATE roles SET parent_id = %s WHERE parent_id IN (%s, %s)", (admin_id, student_id or '', educator_id or ''))

conn.commit()

cur.execute("SELECT id, name, description, parent_id FROM roles")
print("Remaining roles:", cur.fetchall())

conn.close()
