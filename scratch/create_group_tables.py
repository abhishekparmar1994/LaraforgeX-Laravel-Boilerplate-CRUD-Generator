import pymysql

conn = pymysql.connect(host='127.0.0.1', user='root', password='', database='laraforgex', port=3306)
cursor = conn.cursor()

# 1. Create crm_solar_product_groups table
cursor.execute("""
CREATE TABLE IF NOT EXISTS `crm_solar_product_groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `crm_solar_product_groups_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
""")

# 2. Create crm_solar_customer_groups table
cursor.execute("""
CREATE TABLE IF NOT EXISTS `crm_solar_customer_groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_group_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `crm_solar_customer_groups_product_group_id_foreign` (`product_group_id`),
  CONSTRAINT `crm_solar_customer_groups_product_group_id_foreign` FOREIGN KEY (`product_group_id`) REFERENCES `crm_solar_product_groups` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
""")

# 3. Add product_group_id and customer_group_id columns to crm_leads if not exist
cursor.execute("DESCRIBE crm_leads")
cols = [r[0] for r in cursor.fetchall()]

if 'product_group_id' not in cols:
    cursor.execute("""
    ALTER TABLE `crm_leads`
    ADD COLUMN `product_group_id` bigint(20) unsigned DEFAULT NULL AFTER `product_group`,
    ADD CONSTRAINT `crm_leads_product_group_id_foreign` FOREIGN KEY (`product_group_id`) REFERENCES `crm_solar_product_groups` (`id`) ON DELETE SET NULL;
    """)
    print("Added product_group_id to crm_leads")

if 'customer_group_id' not in cols:
    cursor.execute("""
    ALTER TABLE `crm_leads`
    ADD COLUMN `customer_group_id` bigint(20) unsigned DEFAULT NULL AFTER `customer_group`,
    ADD CONSTRAINT `crm_leads_customer_group_id_foreign` FOREIGN KEY (`customer_group_id`) REFERENCES `crm_solar_customer_groups` (`id`) ON DELETE SET NULL;
    """)
    print("Added customer_group_id to crm_leads")

# 4. Seed Product Groups and Customer Groups
product_groups_data = [
    {
        'name': 'Residential Rooftop (1-10 kW)',
        'code': 'RES-ROOFTOP',
        'desc': 'Grid-connected rooftop solar plants under PM Surya Ghar Muft Bijli Yojana',
        'customers': ['Individual Homeowner', 'Bungalow / Villa Owner', 'Row House Resident']
    },
    {
        'name': 'Commercial & Industrial (C&I)',
        'code': 'CI-SOLAR',
        'desc': 'High-capacity solar systems for commercial buildings, factories, and HT connections',
        'customers': ['Commercial Enterprise', 'Manufacturing / Factory', 'Cold Storage & Agro Processing', 'Hospital & Healthcare', 'Educational Institution']
    },
    {
        'name': 'Housing Society Common Meter',
        'code': 'GHS-COMMON',
        'desc': 'Group housing society common amenities load (lifts, water pumps, lighting)',
        'customers': ['Apartment Society / RWA', 'Co-operative Housing Society', 'Township Welfare Association']
    },
    {
        'name': 'Solar Water Pump (PM KUSUM)',
        'code': 'KUSUM-PUMP',
        'desc': 'Off-grid and grid-connected DC/AC solar pumps for agricultural irrigation',
        'customers': ['Agricultural Farmer', 'FPO / Farmer Producer Org', 'Gram Panchayat']
    },
    {
        'name': 'Ground Mounted Solar Farm',
        'code': 'GROUND-MOUNT',
        'desc': 'Megawatt-scale ground installations for open access and captive consumption',
        'customers': ['IPP / Power Producer', 'Industrial Captive Consumer', 'Government / PSU']
    }
]

from datetime import datetime
now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')

for pg in product_groups_data:
    cursor.execute("SELECT id FROM crm_solar_product_groups WHERE name = %s", (pg['name'],))
    row = cursor.fetchone()
    if not row:
        cursor.execute("""
        INSERT INTO crm_solar_product_groups (name, code, description, status, created_at, updated_at)
        VALUES (%s, %s, %s, 'active', %s, %s)
        """, (pg['name'], pg['code'], pg['desc'], now, now))
        pg_id = cursor.lastrowid
        print(f"Inserted Product Group: {pg['name']} (ID: {pg_id})")
    else:
        pg_id = row[0]

    for cg_name in pg['customers']:
        cursor.execute("SELECT id FROM crm_solar_customer_groups WHERE name = %s AND product_group_id = %s", (cg_name, pg_id))
        cg_row = cursor.fetchone()
        if not cg_row:
            cursor.execute("""
            INSERT INTO crm_solar_customer_groups (product_group_id, name, status, created_at, updated_at)
            VALUES (%s, %s, 'active', %s, %s)
            """, (pg_id, cg_name, now, now))
            print(f"  - Inserted Customer Group: {cg_name} for PG {pg_id}")

conn.commit()
print("Database schema and seed completed successfully!")
