<?php
/**
 * Master Solar EPC Dataset Seeder.
 * Cleans all dummy/scraped data from CRM tables and seeds realistic Solar EPC records.
 * Leaves users, roles, permissions, settings, migrations completely untouched.
 */
require 'C:/laragon/www/LaraforgeX/apps/api/vendor/autoload.php';
$app = require_once 'C:/laragon/www/LaraforgeX/apps/api/bootstrap/app.php';

use Illuminate\Support\Str;

$pdo = new PDO('mysql:host=127.0.0.1;dbname=laraforgex;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

echo "=== 1. TRUNCATING FAKE/SCRAPED CRM DATA ===\n";
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
$tablesToTruncate = [
    'crm_payments',
    'crm_solar_net_metering',
    'crm_solar_quotations',
    'crm_notes',
    'crm_followups',
    'crm_tasks',
    'crm_project_milestones',
    'crm_project_members',
    'crm_website_credentials',
    'crm_websites',
    'crm_projects',
    'crm_clients',
    'crm_contacts',
    'crm_companies',
    'crm_activities',
    'crm_leads',
    'audit_logs',
];
foreach ($tablesToTruncate as $tbl) {
    $pdo->exec("TRUNCATE TABLE `$tbl`");
    echo "  Cleaned: $tbl\n";
}
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
echo "All dummy CRM data wiped.\n\n";

$rohan = '1a8fd9a7-e1ba-4f2e-89a1-cb9e8c4e47aa';
$omkar = '2b8fd9a7-e1ba-4f2e-89a1-cb9e8c4e47ab';
$bhavik = '3142b8c2-ad6b-4532-9aa5-6198b37bfe72';
$admin = '98fd9a76-e1ba-4f2e-89a1-cb9e8c4e47a9';

// -------------------------------------------------------------
// 2. REAL SOLAR EPC LEADS
// -------------------------------------------------------------
echo "=== 2. SEEDING AUTHENTIC SOLAR EPC LEADS ===\n";

$leadDefs = [
    // 1. Won Leads (Will convert to Clients & Projects)
    [
        'company_name' => 'Baroda Polyplast Pvt Ltd',
        'contact_person' => 'Rajeshbhai Somani',
        'email' => 'rajesh.somani@barodapolyplast.in',
        'phone' => '098250 14820',
        'whatsapp' => '9825014820',
        'consumer_number' => '08402/19482/3',
        'discom' => 'MGVCL',
        'sanctioned_load' => 125.0,
        'monthly_bill' => 145000.0,
        'solar_capacity_kw' => 100.0,
        'subsidy_amount' => 0.0,
        'product_group' => 'C&I Industrial Solar',
        'customer_group' => 'Industrial',
        'lead_potential' => 'Enterprise',
        'lead_type' => 'Commercial CAPEX',
        'society_name' => null,
        'approval_status' => 'Commissioned',
        'franchise' => 'Vadodara Central',
        'lead_source' => 'Direct Referral',
        'location' => 'Makarpura GIDC, Vadodara',
        'area_locality' => 'Makarpura GIDC',
        'full_address' => 'Plot 412/A, Road No. 8, GIDC Industrial Estate, Makarpura, Vadodara, Gujarat 390010',
        'pincode' => '390010',
        'city' => 'Vadodara',
        'state' => 'Gujarat',
        'estimated_budget' => 3950000.0,
        'priority' => 'high',
        'status' => 'won',
        'assigned' => $bhavik,
        'industry' => 'Industrial Manufacturing & Plastics',
        'rating' => '4.8',
        'reviews' => '62',
    ],
    [
        'company_name' => 'Gokul Residency Co-op Hsg Soc Ltd',
        'contact_person' => 'Pravinbhai K. Patel (Chairman)',
        'email' => 'secretary@gokulresidency.org',
        'phone' => '098791 23450',
        'whatsapp' => '9879123450',
        'consumer_number' => '07119/40291/8',
        'discom' => 'MGVCL',
        'sanctioned_load' => 30.0,
        'monthly_bill' => 28500.0,
        'solar_capacity_kw' => 20.0,
        'subsidy_amount' => 78000.0,
        'product_group' => 'Housing Society (GHS)',
        'customer_group' => 'Housing Society',
        'lead_potential' => 'High',
        'lead_type' => 'Rooftop On-Grid',
        'society_name' => 'Gokul Residency CHS',
        'approval_status' => 'Commissioned',
        'franchise' => 'Vadodara Central',
        'lead_source' => 'Society AGM Referral',
        'location' => 'Gotri Road, Vadodara',
        'area_locality' => 'Gotri',
        'full_address' => 'Opposite Yash Complex, Gotri-Sevasi Main Road, Gotri, Vadodara 390021',
        'pincode' => '390021',
        'city' => 'Vadodara',
        'state' => 'Gujarat',
        'estimated_budget' => 980000.0,
        'priority' => 'high',
        'status' => 'won',
        'assigned' => $rohan,
        'industry' => 'Group Housing Society (GHS)',
        'rating' => '4.9',
        'reviews' => '48',
    ],
    [
        'company_name' => 'Dr. Ashok Kothari Multispeciality Hospital',
        'contact_person' => 'Dr. Ashok Kothari',
        'email' => 'drkothari@kotharihospital.in',
        'phone' => '094263 87110',
        'whatsapp' => '9426387110',
        'consumer_number' => '15402/77102/9',
        'discom' => 'MGVCL',
        'sanctioned_load' => 75.0,
        'monthly_bill' => 88000.0,
        'solar_capacity_kw' => 50.0,
        'subsidy_amount' => 0.0,
        'product_group' => 'C&I Commercial Solar',
        'customer_group' => 'Commercial',
        'lead_potential' => 'High',
        'lead_type' => 'Commercial CAPEX',
        'society_name' => null,
        'approval_status' => 'Commissioned',
        'franchise' => 'Vadodara Central',
        'lead_source' => 'Direct Inquiry',
        'location' => 'Raopura, Vadodara',
        'area_locality' => 'Raopura',
        'full_address' => 'Kothari Medical Centre, Near Tower Chokdi, Raopura, Vadodara 390001',
        'pincode' => '390001',
        'city' => 'Vadodara',
        'state' => 'Gujarat',
        'estimated_budget' => 2150000.0,
        'priority' => 'urgent',
        'status' => 'won',
        'assigned' => $bhavik,
        'industry' => 'Healthcare & Hospital',
        'rating' => '4.7',
        'reviews' => '120',
    ],
    [
        'company_name' => 'Patel Residence (Surya Ghar)',
        'contact_person' => 'Dharmeshbhai Patel',
        'email' => 'dharmesh.patel@gmail.com',
        'phone' => '098240 56210',
        'whatsapp' => '9824056210',
        'consumer_number' => '18204/99120/1',
        'discom' => 'MGVCL',
        'sanctioned_load' => 6.0,
        'monthly_bill' => 6200.0,
        'solar_capacity_kw' => 5.5,
        'subsidy_amount' => 78000.0,
        'product_group' => 'PM Surya Ghar Yojana',
        'customer_group' => 'Residential',
        'lead_potential' => 'High',
        'lead_type' => 'Rooftop On-Grid',
        'society_name' => null,
        'approval_status' => 'Subsidy Disbursed',
        'franchise' => 'Vadodara Central',
        'lead_source' => 'PM Surya Ghar Portal',
        'location' => 'Alkapuri, Vadodara',
        'area_locality' => 'Alkapuri',
        'full_address' => 'Bungalow 14, Sampatrao Colony, Alkapuri, Vadodara 390007',
        'pincode' => '390007',
        'city' => 'Vadodara',
        'state' => 'Gujarat',
        'estimated_budget' => 260000.0,
        'priority' => 'high',
        'status' => 'won',
        'assigned' => $omkar,
        'industry' => 'Residential Rooftop',
        'rating' => '5.0',
        'reviews' => '14',
    ],
    [
        'company_name' => 'Apex Engineering Works',
        'contact_person' => 'Nilesh Agarwal',
        'email' => 'nilesh@apexengineering.co.in',
        'phone' => '099099 43210',
        'whatsapp' => '9909943210',
        'consumer_number' => '09102/33819/2',
        'discom' => 'MGVCL',
        'sanctioned_load' => 90.0,
        'monthly_bill' => 115000.0,
        'solar_capacity_kw' => 75.0,
        'subsidy_amount' => 0.0,
        'product_group' => 'C&I Industrial Solar',
        'customer_group' => 'Industrial',
        'lead_potential' => 'High',
        'lead_type' => 'Commercial CAPEX',
        'society_name' => null,
        'approval_status' => 'Commissioned',
        'franchise' => 'Vadodara Central',
        'lead_source' => 'Industrial Expo',
        'location' => 'Makarpura GIDC, Vadodara',
        'area_locality' => 'Makarpura GIDC',
        'full_address' => 'Shed 88/B, GIDC Heavy Industrial Area, Makarpura, Vadodara 390010',
        'pincode' => '390010',
        'city' => 'Vadodara',
        'state' => 'Gujarat',
        'estimated_budget' => 3100000.0,
        'priority' => 'urgent',
        'status' => 'won',
        'assigned' => $bhavik,
        'industry' => 'Heavy Engineering & Fabrication',
        'rating' => '4.6',
        'reviews' => '34',
    ],
    [
        'company_name' => 'Shree Ram Cold Storage & Agro Foods',
        'contact_person' => 'Harishchandra Mehta',
        'email' => 'mehta@shreeramcold.com',
        'phone' => '098251 77490',
        'whatsapp' => '9825177490',
        'consumer_number' => '21904/00291/4',
        'discom' => 'MGVCL',
        'sanctioned_load' => 180.0,
        'monthly_bill' => 220000.0,
        'solar_capacity_kw' => 150.0,
        'subsidy_amount' => 0.0,
        'product_group' => 'C&I Industrial Solar',
        'customer_group' => 'Industrial',
        'lead_potential' => 'Enterprise',
        'lead_type' => 'Commercial CAPEX',
        'society_name' => null,
        'approval_status' => 'Net Meter Installed',
        'franchise' => 'Vadodara Central',
        'lead_source' => 'Agro Association',
        'location' => 'Savli GIDC, Vadodara',
        'area_locality' => 'Savli GIDC',
        'full_address' => 'NH-8 Bypass, Manjusar GIDC, Savli, Vadodara 391775',
        'pincode' => '391775',
        'city' => 'Vadodara',
        'state' => 'Gujarat',
        'estimated_budget' => 5800000.0,
        'priority' => 'urgent',
        'status' => 'won',
        'assigned' => $admin,
        'industry' => 'Cold Storage & Agro Processing',
        'rating' => '4.8',
        'reviews' => '55',
    ],

    // 2. Negotiation / Net Metering Stage Leads
    [
        'company_name' => 'Vrundavan Greens Co-op Housing Society',
        'contact_person' => 'Sanjay Jhaveri',
        'email' => 'contact@vrundavangreens.org',
        'phone' => '098252 66100',
        'whatsapp' => '9825266100',
        'consumer_number' => '08301/44910/2',
        'discom' => 'MGVCL',
        'sanctioned_load' => 25.0,
        'monthly_bill' => 22000.0,
        'solar_capacity_kw' => 15.0,
        'subsidy_amount' => 78000.0,
        'product_group' => 'Housing Society (GHS)',
        'customer_group' => 'Housing Society',
        'lead_potential' => 'High',
        'lead_type' => 'Rooftop On-Grid',
        'society_name' => 'Vrundavan Greens CHS',
        'approval_status' => 'DISCOM Feasibility Approved',
        'franchise' => 'Vadodara Central',
        'lead_source' => 'Field Survey',
        'location' => 'Vasna Road, Vadodara',
        'area_locality' => 'Vasna Road',
        'full_address' => 'Vasna-Bhayli Main Road, Near D-Mart, Vadodara 390015',
        'pincode' => '390015',
        'city' => 'Vadodara',
        'state' => 'Gujarat',
        'estimated_budget' => 740000.0,
        'priority' => 'high',
        'status' => 'negotiation',
        'assigned' => $rohan,
        'industry' => 'Group Housing Society (GHS)',
        'rating' => '4.9',
        'reviews' => '42',
    ],
    [
        'company_name' => 'Hotel Surya Palace & Banquet',
        'contact_person' => 'Vipul Desai',
        'email' => 'gm@hotelsuryapalace.com',
        'phone' => '098242 88123',
        'whatsapp' => '9824288123',
        'consumer_number' => '08109/22391/6',
        'discom' => 'MGVCL',
        'sanctioned_load' => 85.0,
        'monthly_bill' => 105000.0,
        'solar_capacity_kw' => 60.0,
        'subsidy_amount' => 0.0,
        'product_group' => 'C&I Commercial Solar',
        'customer_group' => 'Commercial',
        'lead_potential' => 'High',
        'lead_type' => 'Commercial CAPEX',
        'society_name' => null,
        'approval_status' => 'Draft Agreement Review',
        'franchise' => 'Vadodara Central',
        'lead_source' => 'Direct Inquiry',
        'location' => 'Sayajigunj, Vadodara',
        'area_locality' => 'Sayajigunj',
        'full_address' => 'Sayajigunj Station Road, Sayajigunj, Vadodara 390020',
        'pincode' => '390020',
        'city' => 'Vadodara',
        'state' => 'Gujarat',
        'estimated_budget' => 2550000.0,
        'priority' => 'urgent',
        'status' => 'negotiation',
        'assigned' => $bhavik,
        'industry' => 'Hospitality & Hotels',
        'rating' => '4.5',
        'reviews' => '210',
    ],
    [
        'company_name' => 'Mehta Villa (Residential Solar)',
        'contact_person' => 'Harishchandra Mehta',
        'email' => 'harish.mehta62@gmail.com',
        'phone' => '098795 44210',
        'whatsapp' => '9879544210',
        'consumer_number' => '08502/77129/0',
        'discom' => 'MGVCL',
        'sanctioned_load' => 4.0,
        'monthly_bill' => 3800.0,
        'solar_capacity_kw' => 3.3,
        'subsidy_amount' => 78000.0,
        'product_group' => 'PM Surya Ghar Yojana',
        'customer_group' => 'Residential',
        'lead_potential' => 'Medium',
        'lead_type' => 'Rooftop On-Grid',
        'society_name' => null,
        'approval_status' => 'Net Metering Submitted',
        'franchise' => 'Vadodara Central',
        'lead_source' => 'PM Surya Ghar Portal',
        'location' => 'Gotri Road, Vadodara',
        'area_locality' => 'Gotri',
        'full_address' => 'Plot 28, Harinagar Society, Gotri Road, Vadodara 390021',
        'pincode' => '390021',
        'city' => 'Vadodara',
        'state' => 'Gujarat',
        'estimated_budget' => 165000.0,
        'priority' => 'medium',
        'status' => 'negotiation',
        'assigned' => $omkar,
        'industry' => 'Residential Rooftop',
        'rating' => '4.7',
        'reviews' => '9',
    ],
    [
        'company_name' => 'Gujarat Polyfab Textiles Ltd',
        'contact_person' => 'Bhupendra Dave',
        'email' => 'bdave@gujaratpolyfab.com',
        'phone' => '098254 99182',
        'whatsapp' => '9825499182',
        'consumer_number' => '19204/88129/1',
        'discom' => 'DGVCL',
        'sanctioned_load' => 300.0,
        'monthly_bill' => 390000.0,
        'solar_capacity_kw' => 250.0,
        'subsidy_amount' => 0.0,
        'product_group' => 'C&I Industrial Solar',
        'customer_group' => 'Industrial',
        'lead_potential' => 'Enterprise',
        'lead_type' => 'Commercial CAPEX',
        'society_name' => null,
        'approval_status' => 'CEI Feasibility In Review',
        'franchise' => 'Surat Hub',
        'lead_source' => 'Textile Association',
        'location' => 'Ankleshwar GIDC',
        'area_locality' => 'GIDC Ankleshwar',
        'full_address' => 'Plot 550, Phase 2, GIDC Industrial Area, Ankleshwar 393002',
        'pincode' => '393002',
        'city' => 'Bharuch',
        'state' => 'Gujarat',
        'estimated_budget' => 9500000.0,
        'priority' => 'urgent',
        'status' => 'negotiation',
        'assigned' => $bhavik,
        'industry' => 'Textiles & Technical Fabrics',
        'rating' => '4.6',
        'reviews' => '78',
    ],

    // 3. Proposal Sent Stage Leads
    [
        'company_name' => 'Sterling Heights CHS Ltd',
        'contact_person' => 'Kiritbhai Solanki',
        'email' => 'admin@sterlingheights.org',
        'phone' => '098253 11840',
        'whatsapp' => '9825311840',
        'consumer_number' => '17409/33910/5',
        'discom' => 'DGVCL',
        'sanctioned_load' => 35.0,
        'monthly_bill' => 34000.0,
        'solar_capacity_kw' => 25.0,
        'subsidy_amount' => 78000.0,
        'product_group' => 'Housing Society (GHS)',
        'customer_group' => 'Housing Society',
        'lead_potential' => 'High',
        'lead_type' => 'Rooftop On-Grid',
        'society_name' => 'Sterling Heights',
        'approval_status' => 'Proposal Sent',
        'franchise' => 'Surat Hub',
        'lead_source' => 'Referral',
        'location' => 'Pal, Surat',
        'area_locality' => 'Pal',
        'full_address' => 'TP 10, Gaurav Path Road, Pal, Surat 395009',
        'pincode' => '395009',
        'city' => 'Surat',
        'state' => 'Gujarat',
        'estimated_budget' => 1180000.0,
        'priority' => 'high',
        'status' => 'proposal_sent',
        'assigned' => $rohan,
        'industry' => 'Group Housing Society (GHS)',
        'rating' => '4.8',
        'reviews' => '36',
    ],
    [
        'company_name' => 'Navkar Diamonds & Gems Export',
        'contact_person' => 'Chetan Marathe',
        'email' => 'chetan@navkargems.com',
        'phone' => '098259 88310',
        'whatsapp' => '9825988310',
        'consumer_number' => '18391/00291/7',
        'discom' => 'DGVCL',
        'sanctioned_load' => 45.0,
        'monthly_bill' => 52000.0,
        'solar_capacity_kw' => 30.0,
        'subsidy_amount' => 0.0,
        'product_group' => 'C&I Commercial Solar',
        'customer_group' => 'Commercial',
        'lead_potential' => 'High',
        'lead_type' => 'Commercial CAPEX',
        'society_name' => null,
        'approval_status' => 'Proposal Sent',
        'franchise' => 'Surat Hub',
        'lead_source' => 'Direct Inquiry',
        'location' => 'Katargam, Surat',
        'area_locality' => 'Katargam',
        'full_address' => 'Diamond World Complex, Gotalawadi, Katargam, Surat 395004',
        'pincode' => '395004',
        'city' => 'Surat',
        'state' => 'Gujarat',
        'estimated_budget' => 1350000.0,
        'priority' => 'high',
        'status' => 'proposal_sent',
        'assigned' => $bhavik,
        'industry' => 'Gems & Jewelry Processing',
        'rating' => '4.9',
        'reviews' => '85',
    ],
    [
        'company_name' => 'Joshi Villa (Rooftop Solar)',
        'contact_person' => 'Sunil Joshi',
        'email' => 'sunil.joshi77@yahoo.com',
        'phone' => '094270 33810',
        'whatsapp' => '9427033810',
        'consumer_number' => '08291/66391/3',
        'discom' => 'MGVCL',
        'sanctioned_load' => 7.0,
        'monthly_bill' => 7400.0,
        'solar_capacity_kw' => 6.0,
        'subsidy_amount' => 78000.0,
        'product_group' => 'PM Surya Ghar Yojana',
        'customer_group' => 'Residential',
        'lead_potential' => 'High',
        'lead_type' => 'Rooftop On-Grid',
        'society_name' => null,
        'approval_status' => 'Proposal Sent',
        'franchise' => 'Vadodara Central',
        'lead_source' => 'PM Surya Ghar Portal',
        'location' => 'Manjalpur, Vadodara',
        'area_locality' => 'Manjalpur',
        'full_address' => 'B-12, Suryanagar Society, Near Saraswati Complex, Manjalpur, Vadodara 390011',
        'pincode' => '390011',
        'city' => 'Vadodara',
        'state' => 'Gujarat',
        'estimated_budget' => 285000.0,
        'priority' => 'medium',
        'status' => 'proposal_sent',
        'assigned' => $omkar,
        'industry' => 'Residential Rooftop',
        'rating' => '4.8',
        'reviews' => '11',
    ],
    [
        'company_name' => 'Saraswati International School',
        'contact_person' => 'Principal Meenakshi Dave',
        'email' => 'trustee@saraswatischool.edu.in',
        'phone' => '098255 12940',
        'whatsapp' => '9825512940',
        'consumer_number' => '08601/55291/0',
        'discom' => 'MGVCL',
        'sanctioned_load' => 60.0,
        'monthly_bill' => 68000.0,
        'solar_capacity_kw' => 40.0,
        'subsidy_amount' => 0.0,
        'product_group' => 'C&I Commercial Solar',
        'customer_group' => 'Institutional',
        'lead_potential' => 'High',
        'lead_type' => 'Commercial CAPEX',
        'society_name' => null,
        'approval_status' => 'Proposal Sent',
        'franchise' => 'Vadodara Central',
        'lead_source' => 'Trustee Referral',
        'location' => 'Tarsali, Vadodara',
        'area_locality' => 'Tarsali',
        'full_address' => 'Saraswati Campus, NH-8 Bypass Road, Tarsali, Vadodara 390009',
        'pincode' => '390009',
        'city' => 'Vadodara',
        'state' => 'Gujarat',
        'estimated_budget' => 1780000.0,
        'priority' => 'high',
        'status' => 'proposal_sent',
        'assigned' => $rohan,
        'industry' => 'Education & Institutional',
        'rating' => '4.7',
        'reviews' => '94',
    ],
    [
        'company_name' => 'Western India Casting Ltd',
        'contact_person' => 'Mahendra Patel',
        'email' => 'mpatel@westerncasting.com',
        'phone' => '098250 88291',
        'whatsapp' => '9825088291',
        'consumer_number' => '08772/19402/8',
        'discom' => 'MGVCL',
        'sanctioned_load' => 250.0,
        'monthly_bill' => 310000.0,
        'solar_capacity_kw' => 200.0,
        'subsidy_amount' => 0.0,
        'product_group' => 'C&I Industrial Solar',
        'customer_group' => 'Industrial',
        'lead_potential' => 'Enterprise',
        'lead_type' => 'Commercial CAPEX',
        'society_name' => null,
        'approval_status' => 'Proposal Sent',
        'franchise' => 'Vadodara Central',
        'lead_source' => 'Engineering Expo',
        'location' => 'Por GIDC, Vadodara',
        'area_locality' => 'Por',
        'full_address' => 'Plot 104, Por Industrial Estate, NH-8, Vadodara 391243',
        'pincode' => '391243',
        'city' => 'Vadodara',
        'state' => 'Gujarat',
        'estimated_budget' => 7800000.0,
        'priority' => 'urgent',
        'status' => 'proposal_sent',
        'assigned' => $bhavik,
        'industry' => 'Foundry & Heavy Metallurgy',
        'rating' => '4.5',
        'reviews' => '41',
    ],

    // 4. Feasibility OK / Qualified Stage Leads
    [
        'company_name' => 'Shah Penthouse (Bifacial Solar)',
        'contact_person' => 'Ketankumar Shah',
        'email' => 'ketan.shah@gmail.com',
        'phone' => '098258 77201',
        'whatsapp' => '9825877201',
        'consumer_number' => '18491/22901/4',
        'discom' => 'DGVCL',
        'sanctioned_load' => 12.0,
        'monthly_bill' => 13500.0,
        'solar_capacity_kw' => 10.0,
        'subsidy_amount' => 78000.0,
        'product_group' => 'PM Surya Ghar Yojana',
        'customer_group' => 'Residential',
        'lead_potential' => 'High',
        'lead_type' => 'Rooftop On-Grid',
        'society_name' => 'Royal Palms',
        'approval_status' => 'Feasibility Approved',
        'franchise' => 'Surat Hub',
        'lead_source' => 'Architect Referral',
        'location' => 'Vesu, Surat',
        'area_locality' => 'Vesu',
        'full_address' => 'Penthouse 1102, Royal Palms, VIP Road, Vesu, Surat 395007',
        'pincode' => '395007',
        'city' => 'Surat',
        'state' => 'Gujarat',
        'estimated_budget' => 480000.0,
        'priority' => 'high',
        'status' => 'qualified',
        'assigned' => $omkar,
        'industry' => 'Residential Rooftop',
        'rating' => '5.0',
        'reviews' => '16',
    ],
    [
        'company_name' => 'Shanti Niketan Co-op Housing',
        'contact_person' => 'Rameshbhai Vora',
        'email' => 'shantiniketan.ahs@gmail.com',
        'phone' => '098792 55810',
        'whatsapp' => '9879255810',
        'consumer_number' => '02910/44819/2',
        'discom' => 'Torrent Power',
        'sanctioned_load' => 18.0,
        'monthly_bill' => 17500.0,
        'solar_capacity_kw' => 12.0,
        'subsidy_amount' => 78000.0,
        'product_group' => 'Housing Society (GHS)',
        'customer_group' => 'Housing Society',
        'lead_potential' => 'High',
        'lead_type' => 'Rooftop On-Grid',
        'society_name' => 'Shanti Niketan',
        'approval_status' => 'Feasibility Passed',
        'franchise' => 'Ahmedabad West',
        'lead_source' => 'PM Surya Ghar Portal',
        'location' => 'Bodakdev, Ahmedabad',
        'area_locality' => 'Bodakdev',
        'full_address' => 'Near Judges Bungalow, Bodakdev, Ahmedabad 380054',
        'pincode' => '380054',
        'city' => 'Ahmedabad',
        'state' => 'Gujarat',
        'estimated_budget' => 590000.0,
        'priority' => 'medium',
        'status' => 'qualified',
        'assigned' => $rohan,
        'industry' => 'Group Housing Society (GHS)',
        'rating' => '4.9',
        'reviews' => '29',
    ],
    [
        'company_name' => 'Sunshine Diagnostic & MRI Centre',
        'contact_person' => 'Dr. Hiren Parikh',
        'email' => 'contact@sunshinediagnostics.in',
        'phone' => '098251 33940',
        'whatsapp' => '9825133940',
        'consumer_number' => '02891/33910/1',
        'discom' => 'Torrent Power',
        'sanctioned_load' => 35.0,
        'monthly_bill' => 44000.0,
        'solar_capacity_kw' => 25.0,
        'subsidy_amount' => 0.0,
        'product_group' => 'C&I Commercial Solar',
        'customer_group' => 'Commercial',
        'lead_potential' => 'High',
        'lead_type' => 'Commercial CAPEX',
        'society_name' => null,
        'approval_status' => 'Site Approved',
        'franchise' => 'Ahmedabad West',
        'lead_source' => 'Direct Inquiry',
        'location' => 'Satellite, Ahmedabad',
        'area_locality' => 'Satellite',
        'full_address' => 'Ground Floor, Shapath II, Opp Rajpath Club, Satellite, Ahmedabad 380015',
        'pincode' => '380015',
        'city' => 'Ahmedabad',
        'state' => 'Gujarat',
        'estimated_budget' => 1150000.0,
        'priority' => 'urgent',
        'status' => 'qualified',
        'assigned' => $bhavik,
        'industry' => 'Diagnostic Center & Healthcare',
        'rating' => '4.8',
        'reviews' => '150',
    ],
    [
        'company_name' => 'Krishna Phyto Pharma Ltd',
        'contact_person' => 'Anil Agarwal',
        'email' => 'anil@krishnapharma.com',
        'phone' => '098256 77120',
        'whatsapp' => '9825677120',
        'consumer_number' => '04891/99201/7',
        'discom' => 'UGVCL',
        'sanctioned_load' => 150.0,
        'monthly_bill' => 185000.0,
        'solar_capacity_kw' => 120.0,
        'subsidy_amount' => 0.0,
        'product_group' => 'C&I Industrial Solar',
        'customer_group' => 'Industrial',
        'lead_potential' => 'Enterprise',
        'lead_type' => 'Commercial CAPEX',
        'society_name' => null,
        'approval_status' => 'Feasibility Approved',
        'franchise' => 'Ahmedabad West',
        'lead_source' => 'Pharma Expo',
        'location' => 'Sanand GIDC, Ahmedabad',
        'area_locality' => 'Sanand GIDC',
        'full_address' => 'Plot 214, Sector 2, Sanand Industrial Estate, Ahmedabad 382110',
        'pincode' => '382110',
        'city' => 'Ahmedabad',
        'state' => 'Gujarat',
        'estimated_budget' => 4750000.0,
        'priority' => 'high',
        'status' => 'qualified',
        'assigned' => $bhavik,
        'industry' => 'Pharmaceutical Formulations',
        'rating' => '4.7',
        'reviews' => '65',
    ],

    // 5. Site Survey & Contacted Stage Leads
    [
        'company_name' => 'Solanki Farmhouse (Rooftop & Pump)',
        'contact_person' => 'Dalsukhbhai Solanki',
        'email' => 'dalsukh.solanki@gmail.com',
        'phone' => '098243 44910',
        'whatsapp' => '9824344910',
        'consumer_number' => '04192/00291/9',
        'discom' => 'UGVCL',
        'sanctioned_load' => 10.0,
        'monthly_bill' => 9200.0,
        'solar_capacity_kw' => 8.0,
        'subsidy_amount' => 78000.0,
        'product_group' => 'PM Surya Ghar Yojana',
        'customer_group' => 'Residential',
        'lead_potential' => 'High',
        'lead_type' => 'Rooftop On-Grid',
        'society_name' => null,
        'approval_status' => 'Survey Scheduled',
        'franchise' => 'Ahmedabad West',
        'lead_source' => 'Field Inquiry',
        'location' => 'Gandhinagar',
        'area_locality' => 'Sector 7',
        'full_address' => 'Plot 418/1, Sector 7-B, Gandhinagar 382007',
        'pincode' => '382007',
        'city' => 'Gandhinagar',
        'state' => 'Gujarat',
        'estimated_budget' => 380000.0,
        'priority' => 'medium',
        'status' => 'contacted',
        'assigned' => $rohan,
        'industry' => 'Residential Rooftop',
        'rating' => '4.8',
        'reviews' => '12',
    ],
    [
        'company_name' => 'Samrudhi Enclave CHS Ltd',
        'contact_person' => 'Hemant Trivedi',
        'email' => 'samrudhi.enclave@gmail.com',
        'phone' => '098254 33100',
        'whatsapp' => '9825433100',
        'consumer_number' => '08319/88201/3',
        'discom' => 'MGVCL',
        'sanctioned_load' => 22.0,
        'monthly_bill' => 25000.0,
        'solar_capacity_kw' => 18.0,
        'subsidy_amount' => 78000.0,
        'product_group' => 'Housing Society (GHS)',
        'customer_group' => 'Housing Society',
        'lead_potential' => 'High',
        'lead_type' => 'Rooftop On-Grid',
        'society_name' => 'Samrudhi Enclave',
        'approval_status' => 'AGM Resolution Pending',
        'franchise' => 'Vadodara Central',
        'lead_source' => 'Flyer Campaign',
        'location' => 'Atladara, Vadodara',
        'area_locality' => 'Atladara',
        'full_address' => 'Near Sun Pharma R&D Centre, Atladara, Vadodara 390012',
        'pincode' => '390012',
        'city' => 'Vadodara',
        'state' => 'Gujarat',
        'estimated_budget' => 890000.0,
        'priority' => 'high',
        'status' => 'contacted',
        'assigned' => $rohan,
        'industry' => 'Group Housing Society (GHS)',
        'rating' => '4.9',
        'reviews' => '38',
    ],
    [
        'company_name' => 'Royal Orchid Club & Banquet',
        'contact_person' => 'Gautam Singhania',
        'email' => 'events@royalorchidclub.in',
        'phone' => '098250 44920',
        'whatsapp' => '9825044920',
        'consumer_number' => '08210/11829/4',
        'discom' => 'MGVCL',
        'sanctioned_load' => 70.0,
        'monthly_bill' => 92000.0,
        'solar_capacity_kw' => 50.0,
        'subsidy_amount' => 0.0,
        'product_group' => 'C&I Commercial Solar',
        'customer_group' => 'Commercial',
        'lead_potential' => 'High',
        'lead_type' => 'Commercial CAPEX',
        'society_name' => null,
        'approval_status' => 'Terrace Survey Done',
        'franchise' => 'Vadodara Central',
        'lead_source' => 'Management Referral',
        'location' => 'Sevasi, Vadodara',
        'area_locality' => 'Sevasi',
        'full_address' => 'Sevasi Canal Road, Sevasi, Vadodara 391101',
        'pincode' => '391101',
        'city' => 'Vadodara',
        'state' => 'Gujarat',
        'estimated_budget' => 2200000.0,
        'priority' => 'urgent',
        'status' => 'contacted',
        'assigned' => $bhavik,
        'industry' => 'Hospitality & Leisure',
        'rating' => '4.6',
        'reviews' => '180',
    ],
    [
        'company_name' => 'Marathe Villa (Residential 4kW)',
        'contact_person' => 'Chetan Marathe',
        'email' => 'marathe.c@gmail.com',
        'phone' => '094260 77190',
        'whatsapp' => '9426077190',
        'consumer_number' => '08102/44129/6',
        'discom' => 'MGVCL',
        'sanctioned_load' => 5.0,
        'monthly_bill' => 5100.0,
        'solar_capacity_kw' => 4.0,
        'subsidy_amount' => 78000.0,
        'product_group' => 'PM Surya Ghar Yojana',
        'customer_group' => 'Residential',
        'lead_potential' => 'Medium',
        'lead_type' => 'Rooftop On-Grid',
        'society_name' => null,
        'approval_status' => 'Survey Done',
        'franchise' => 'Vadodara Central',
        'lead_source' => 'PM Surya Ghar Portal',
        'location' => 'Karelibaug, Vadodara',
        'area_locality' => 'Karelibaug',
        'full_address' => 'A-14, Anand Baug Society, Water Tank Road, Karelibaug, Vadodara 390018',
        'pincode' => '390018',
        'city' => 'Vadodara',
        'state' => 'Gujarat',
        'estimated_budget' => 205000.0,
        'priority' => 'medium',
        'status' => 'contacted',
        'assigned' => $omkar,
        'industry' => 'Residential Rooftop',
        'rating' => '4.8',
        'reviews' => '10',
    ],

    // 6. Inbound / New Solar Leads Stage
    [
        'company_name' => 'Radhe Krishna Weaving Mills',
        'contact_person' => 'Govindbhai Patel',
        'email' => 'govind@radhekrishnaweave.com',
        'phone' => '098251 11092',
        'whatsapp' => '9825111092',
        'consumer_number' => '19401/22901/5',
        'discom' => 'DGVCL',
        'sanctioned_load' => 220.0,
        'monthly_bill' => 275000.0,
        'solar_capacity_kw' => 180.0,
        'subsidy_amount' => 0.0,
        'product_group' => 'C&I Industrial Solar',
        'customer_group' => 'Industrial',
        'lead_potential' => 'Enterprise',
        'lead_type' => 'Commercial CAPEX',
        'society_name' => null,
        'approval_status' => 'New Inbound',
        'franchise' => 'Surat Hub',
        'lead_source' => 'Google Search / Website',
        'location' => 'Sachin GIDC, Surat',
        'area_locality' => 'Sachin GIDC',
        'full_address' => 'Plot 880, Road No. 12, Sachin Industrial Area, Surat 394230',
        'pincode' => '394230',
        'city' => 'Surat',
        'state' => 'Gujarat',
        'estimated_budget' => 6950000.0,
        'priority' => 'urgent',
        'status' => 'new',
        'assigned' => $bhavik,
        'industry' => 'Textile Weaving & Processing',
        'rating' => '4.7',
        'reviews' => '52',
    ],
    [
        'company_name' => 'Dave Residence (Surya Ghar 3kW)',
        'contact_person' => 'Dhiren Dave',
        'email' => 'dhiren.dave@rediffmail.com',
        'phone' => '094275 88291',
        'whatsapp' => '9427588291',
        'consumer_number' => '02109/55120/8',
        'discom' => 'Torrent Power',
        'sanctioned_load' => 4.0,
        'monthly_bill' => 3600.0,
        'solar_capacity_kw' => 3.0,
        'subsidy_amount' => 78000.0,
        'product_group' => 'PM Surya Ghar Yojana',
        'customer_group' => 'Residential',
        'lead_potential' => 'High',
        'lead_type' => 'Rooftop On-Grid',
        'society_name' => null,
        'approval_status' => 'New Inbound',
        'franchise' => 'Ahmedabad West',
        'lead_source' => 'PM Surya Ghar Portal',
        'location' => 'Naranpura, Ahmedabad',
        'area_locality' => 'Naranpura',
        'full_address' => '12, Mangaldeep Society, Near AEC Char Rasta, Naranpura, Ahmedabad 380013',
        'pincode' => '380013',
        'city' => 'Ahmedabad',
        'state' => 'Gujarat',
        'estimated_budget' => 165000.0,
        'priority' => 'medium',
        'status' => 'new',
        'assigned' => $omkar,
        'industry' => 'Residential Rooftop',
        'rating' => '4.9',
        'reviews' => '8',
    ],
    [
        'company_name' => 'Jay Ambe Agro Farms (KUSUM Solar Pump)',
        'contact_person' => 'Ambalalbhai Padhiyar',
        'email' => 'jayambe.agro@gmail.com',
        'phone' => '098240 19283',
        'whatsapp' => '9824019283',
        'consumer_number' => '08901/77291/4',
        'discom' => 'MGVCL',
        'sanctioned_load' => 10.0,
        'monthly_bill' => 8500.0,
        'solar_capacity_kw' => 7.5,
        'subsidy_amount' => 125000.0,
        'product_group' => 'Solar Water Pump (PM KUSUM)',
        'customer_group' => 'Agricultural',
        'lead_potential' => 'High',
        'lead_type' => 'Ground Mount',
        'society_name' => null,
        'approval_status' => 'Portal Applied',
        'franchise' => 'Vadodara Central',
        'lead_source' => 'Kisan Mela 2026',
        'location' => 'Padra, Vadodara',
        'area_locality' => 'Padra',
        'full_address' => 'Survey No. 142/2, Village Bhoj, Padra Taluka, Vadodara 391440',
        'pincode' => '391440',
        'city' => 'Vadodara',
        'state' => 'Gujarat',
        'estimated_budget' => 340000.0,
        'priority' => 'medium',
        'status' => 'new',
        'assigned' => $rohan,
        'industry' => 'Agriculture & Horticulture',
        'rating' => '5.0',
        'reviews' => '5',
    ],
    [
        'company_name' => 'Desai Duplex (Rooftop 5kW)',
        'contact_person' => 'Nirav Desai',
        'email' => 'nirav.desai@gmail.com',
        'phone' => '098250 77312',
        'whatsapp' => '9825077312',
        'consumer_number' => '18902/33401/6',
        'discom' => 'DGVCL',
        'sanctioned_load' => 6.0,
        'monthly_bill' => 6400.0,
        'solar_capacity_kw' => 5.0,
        'subsidy_amount' => 78000.0,
        'product_group' => 'PM Surya Ghar Yojana',
        'customer_group' => 'Residential',
        'lead_potential' => 'High',
        'lead_type' => 'Rooftop On-Grid',
        'society_name' => null,
        'approval_status' => 'New Inbound',
        'franchise' => 'Surat Hub',
        'lead_source' => 'PM Surya Ghar Portal',
        'location' => 'Adajan, Surat',
        'area_locality' => 'Adajan',
        'full_address' => 'Bungalow 7, Prime City Row Houses, Near Star Bazaar, Adajan, Surat 395009',
        'pincode' => '395009',
        'city' => 'Surat',
        'state' => 'Gujarat',
        'estimated_budget' => 260000.0,
        'priority' => 'high',
        'status' => 'new',
        'assigned' => $omkar,
        'industry' => 'Residential Rooftop',
        'rating' => '4.9',
        'reviews' => '15',
    ],
    [
        'company_name' => 'Narmada Bio-Chem Fertilizers',
        'contact_person' => 'Subhash Chandra Verma',
        'email' => 'scverma@narmadabiochem.in',
        'phone' => '098252 88410',
        'whatsapp' => '9825288410',
        'consumer_number' => '19502/11902/8',
        'discom' => 'DGVCL',
        'sanctioned_load' => 350.0,
        'monthly_bill' => 450000.0,
        'solar_capacity_kw' => 300.0,
        'subsidy_amount' => 0.0,
        'product_group' => 'C&I Industrial Solar',
        'customer_group' => 'Industrial',
        'lead_potential' => 'Enterprise',
        'lead_type' => 'Commercial CAPEX',
        'society_name' => null,
        'approval_status' => 'Inquiry Logged',
        'franchise' => 'Surat Hub',
        'lead_source' => 'Direct Referral',
        'location' => 'Bharuch GIDC',
        'area_locality' => 'GIDC Bharuch',
        'full_address' => 'Plot 310, GIDC Chemical Zone, Bharuch 392015',
        'pincode' => '392015',
        'city' => 'Bharuch',
        'state' => 'Gujarat',
        'estimated_budget' => 11400000.0,
        'priority' => 'urgent',
        'status' => 'new',
        'assigned' => $admin,
        'industry' => 'Chemicals & Fertilizers',
        'rating' => '4.6',
        'reviews' => '92',
    ]
];

$stmtLead = $pdo->prepare("
    INSERT INTO crm_leads (
        id, company_name, contact_person, email, phone, whatsapp, consumer_number, discom,
        sanctioned_load, monthly_bill, solar_capacity_kw, subsidy_amount, product_group,
        customer_group, lead_potential, lead_type, society_name, approval_status, franchise,
        lead_source, location, requirement, estimated_budget, priority, status, assigned_to_user_id,
        area_locality, full_address, pincode, city, state, rating, total_reviews, has_website,
        created_at, updated_at
    ) VALUES (
        :id, :company_name, :contact_person, :email, :phone, :whatsapp, :consumer_number, :discom,
        :sanctioned_load, :monthly_bill, :solar_capacity_kw, :subsidy_amount, :product_group,
        :customer_group, :lead_potential, :lead_type, :society_name, :approval_status, :franchise,
        :lead_source, :location, :requirement, :estimated_budget, :priority, :status, :assigned_to_user_id,
        :area_locality, :full_address, :pincode, :city, :state, :rating, :total_reviews, :has_website,
        NOW(), NOW()
    )
");

$createdLeads = [];

foreach ($leadDefs as $idx => $d) {
    $leadId = Str::uuid()->toString();
    $kw = $d['solar_capacity_kw'];
    $req = "Turnkey EPC Solar Rooftop installation ({$kw} kWp). High-efficiency Monocrystalline Bifacial modules (550Wp), On-grid string inverter with bi-directional net-metering synchronization under {$d['discom']} DISCOM regulations.";

    $stmtLead->execute([
        ':id' => $leadId,
        ':company_name' => $d['company_name'],
        ':contact_person' => $d['contact_person'],
        ':email' => $d['email'],
        ':phone' => $d['phone'],
        ':whatsapp' => $d['whatsapp'],
        ':consumer_number' => $d['consumer_number'],
        ':discom' => $d['discom'],
        ':sanctioned_load' => $d['sanctioned_load'],
        ':monthly_bill' => $d['monthly_bill'],
        ':solar_capacity_kw' => $d['solar_capacity_kw'],
        ':subsidy_amount' => $d['subsidy_amount'],
        ':product_group' => $d['product_group'],
        ':customer_group' => $d['customer_group'],
        ':lead_potential' => $d['lead_potential'],
        ':lead_type' => $d['lead_type'],
        ':society_name' => $d['society_name'],
        ':approval_status' => $d['approval_status'],
        ':franchise' => $d['franchise'],
        ':lead_source' => $d['lead_source'],
        ':location' => $d['location'],
        ':requirement' => $req,
        ':estimated_budget' => $d['estimated_budget'],
        ':priority' => $d['priority'],
        ':status' => $d['status'],
        ':assigned_to_user_id' => $d['assigned'],
        ':area_locality' => $d['area_locality'],
        ':full_address' => $d['full_address'],
        ':pincode' => $d['pincode'],
        ':city' => $d['city'],
        ':state' => $d['state'],
        ':rating' => $d['rating'],
        ':total_reviews' => $d['reviews'],
        ':has_website' => 'NO',
    ]);

    $createdLeads[$idx] = [
        'id' => $leadId,
        'def' => $d,
    ];
}

echo "Successfully seeded " . count($createdLeads) . " realistic Solar EPC leads!\n\n";

// -------------------------------------------------------------
// 3. SEEDING WON CLIENTS, COMPANIES, CONTACTS & PROJECTS
// -------------------------------------------------------------
echo "=== 3. SEEDING SOLAR CLIENTS & EPC EXECUTION PROJECTS ===\n";

$stmtClient = $pdo->prepare("
    INSERT INTO crm_clients (
        id, company_id, primary_contact_id, lead_id, client_code, name, email, phone, whatsapp,
        address, industry, client_since, status, notes, created_at, updated_at
    ) VALUES (
        :id, :company_id, :primary_contact_id, :lead_id, :client_code, :name, :email, :phone, :whatsapp,
        :address, :industry, :client_since, 'active', :notes, NOW(), NOW()
    )
");

$stmtCompany = $pdo->prepare("
    INSERT INTO crm_companies (
        id, name, industry, website, email, phone, address, city, state, country, gst_vat_number,
        company_type, status, notes, created_at, updated_at
    ) VALUES (
        :id, :name, :industry, :website, :email, :phone, :address, :city, :state, 'India', :gst,
        'Client', 'active', :notes, NOW(), NOW()
    )
");

$stmtContact = $pdo->prepare("
    INSERT INTO crm_contacts (
        id, company_id, first_name, last_name, job_title, email, phone, whatsapp, preferred_contact_method,
        notes, created_at, updated_at
    ) VALUES (
        :id, :company_id, :first_name, :last_name, :job_title, :email, :phone, :whatsapp, 'Phone',
        :notes, NOW(), NOW()
    )
");

$stmtProject = $pdo->prepare("
    INSERT INTO crm_projects (
        id, client_id, company_id, name, project_type, description, start_date, deadline, budget,
        status, priority, project_manager_id, progress, notes, created_at, updated_at
    ) VALUES (
        :id, :client_id, :company_id, :name, :project_type, :description, :start_date, :deadline, :budget,
        :status, :priority, :project_manager_id, :progress, :notes, NOW(), NOW()
    )
");

$stmtMilestone = $pdo->prepare("
    INSERT INTO crm_project_milestones (
        id, project_id, title, due_date, is_completed, completed_at, created_at, updated_at
    ) VALUES (
        :id, :project_id, :title, :due_date, :is_completed, :completed_at, NOW(), NOW()
    )
");

$stmtPayment = $pdo->prepare("
    INSERT INTO crm_payments (
        id, client_id, project_id, invoice_number, amount, payment_date, due_date, payment_method, status,
        notes, created_at, updated_at
    ) VALUES (
        :id, :client_id, :project_id, :invoice_number, :amount, :payment_date, :due_date, 'Bank Transfer (NEFT/RTGS)', :status,
        :notes, NOW(), NOW()
    )
");

// Filter Won leads (first 6 items)
$wonLeads = array_slice($createdLeads, 0, 6);
$gstNumbers = [
    '24AABCB1234D1Z2',
    '24AAAGR9876E1Z5',
    '24AAHCK5521F1Z8',
    '24AABDP4410G1Z1',
    '24AAEAE8819H1Z3',
    '24AASRC3391J1Z6',
];

$projectProgresses = [85, 95, 60, 100, 45, 30];

foreach ($wonLeads as $wIdx => $w) {
    $d = $w['def'];
    $leadId = $w['id'];
    $clientCode = sprintf('SOL-2026-%03d', $wIdx + 1);

    $companyId = Str::uuid()->toString();
    $contactId = Str::uuid()->toString();
    $clientId = Str::uuid()->toString();
    $projectId = Str::uuid()->toString();

    // 1. Company
    $stmtCompany->execute([
        ':id' => $companyId,
        ':name' => $d['company_name'],
        ':industry' => $d['industry'],
        ':website' => 'https://' . Str::slug($d['company_name']) . '.in',
        ':email' => $d['email'],
        ':phone' => $d['phone'],
        ':address' => $d['full_address'],
        ':city' => $d['city'],
        ':state' => $d['state'],
        ':gst' => $gstNumbers[$wIdx] ?? '24AAACT1234F1Z0',
        ':notes' => "Commissioned Solar EPC Plant client ({$d['solar_capacity_kw']} kWp).",
    ]);

    // 2. Primary Contact
    $nameParts = explode(' ', $d['contact_person']);
    $first = $nameParts[0] ?? 'Mr';
    $last = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : 'Solar Consumer';

    $stmtContact->execute([
        ':id' => $contactId,
        ':company_id' => $companyId,
        ':first_name' => $first,
        ':last_name' => $last,
        ':job_title' => str_contains($d['company_name'], 'Hospital') ? 'Medical Director' : (str_contains($d['company_name'], 'Soc') ? 'Society Chairman' : 'Managing Director'),
        ':email' => $d['email'],
        ':phone' => $d['phone'],
        ':whatsapp' => $d['whatsapp'],
        ':notes' => "Primary decision maker for Solar Rooftop Plant.",
    ]);

    // 3. Client
    $stmtClient->execute([
        ':id' => $clientId,
        ':company_id' => $companyId,
        ':primary_contact_id' => $contactId,
        ':lead_id' => $leadId,
        ':client_code' => $clientCode,
        ':name' => $d['company_name'],
        ':email' => $d['email'],
        ':phone' => $d['phone'],
        ':whatsapp' => $d['whatsapp'],
        ':address' => $d['full_address'],
        ':industry' => $d['industry'],
        ':client_since' => date('Y-m-d', strtotime('-' . ($wIdx * 15 + 20) . ' days')),
        ':notes' => "Commissioned Solar Plant. Consumer No: {$d['consumer_number']}, DISCOM: {$d['discom']}.",
    ]);

    // 4. Project
    $projName = "{$d['solar_capacity_kw']} kWp Solar Plant — {$d['company_name']}";
    $prog = $projectProgresses[$wIdx];
    $status = $prog === 100 ? 'completed' : 'in_progress';

    $stmtProject->execute([
        ':id' => $projectId,
        ':client_id' => $clientId,
        ':company_id' => $companyId,
        ':name' => $projName,
        ':project_type' => $d['product_group'],
        ':description' => "Complete EPC installation of {$d['solar_capacity_kw']} kWp On-Grid solar plant. Features DCR Tier-1 Bifacial modules, high-efficiency grid-tied inverter, lightning arresters, and bi-directional DISCOM net-metering.",
        ':start_date' => date('Y-m-d', strtotime('-' . ($wIdx * 20 + 30) . ' days')),
        ':deadline' => date('Y-m-d', strtotime('+' . (60 - $wIdx * 10) . ' days')),
        ':budget' => $d['estimated_budget'],
        ':status' => $status,
        ':priority' => $d['priority'],
        ':project_manager_id' => $d['assigned'],
        ':progress' => $prog,
        ':notes' => "Net Metering Consumer No: {$d['consumer_number']}. Feeder Sanction: {$d['sanctioned_load']} kW.",
    ]);

    // 5. Project Milestones
    $milestones = [
        ['Site Survey & Structural Load Testing', true, -25],
        ['DISCOM Net-Metering Sanction Application', true, -18],
        ['Module Mounting Structure & Panel Installation', $prog >= 50, -10],
        ['Inverter, DCDB/ACDB & Earthing Grid Wiring', $prog >= 75, -5],
        ['CEI Electrical Inspectorate Clearance', $prog >= 85, -2],
        ['Bi-directional Net-Meter Installation & Sync', $prog >= 100, 0],
    ];

    foreach ($milestones as $m) {
        $stmtMilestone->execute([
            ':id' => Str::uuid()->toString(),
            ':project_id' => $projectId,
            ':title' => $m[0],
            ':due_date' => date('Y-m-d', strtotime("{$m[2]} days")),
            ':is_completed' => $m[1] ? 1 : 0,
            ':completed_at' => $m[1] ? date('Y-m-d H:i:s', strtotime("{$m[2]} days")) : null,
        ]);
    }

    // 6. Payments
    $advance = round($d['estimated_budget'] * 0.20, 2);
    $materialDelivery = round($d['estimated_budget'] * 0.50, 2);

    $stmtPayment->execute([
        ':id' => Str::uuid()->toString(),
        ':client_id' => $clientId,
        ':project_id' => $projectId,
        ':invoice_number' => sprintf('INV-SOL-2026-%03dA', $wIdx + 1),
        ':amount' => $advance,
        ':payment_date' => date('Y-m-d', strtotime('-25 days')),
        ':due_date' => date('Y-m-d', strtotime('-20 days')),
        ':status' => 'paid',
        ':notes' => 'Advance booking & DISCOM net-metering processing fees (20%).',
    ]);

    if ($prog >= 50) {
        $stmtPayment->execute([
            ':id' => Str::uuid()->toString(),
            ':client_id' => $clientId,
            ':project_id' => $projectId,
            ':invoice_number' => sprintf('INV-SOL-2026-%03dB', $wIdx + 1),
            ':amount' => $materialDelivery,
            ':payment_date' => $prog >= 80 ? date('Y-m-d', strtotime('-8 days')) : null,
            ':due_date' => date('Y-m-d', strtotime('+5 days')),
            ':status' => $prog >= 80 ? 'paid' : 'pending',
            ':notes' => 'Module & Inverter dispatch milestone invoice (50%).',
        ]);
    }
}

echo "Successfully created 6 Solar Clients, Companies, Contacts, Projects, Milestones & Payments!\n\n";

// -------------------------------------------------------------
// 4. SEEDING SOLAR QUOTATIONS & NET METERING APPLICATIONS
// -------------------------------------------------------------
echo "=== 4. SEEDING SOLAR QUOTATIONS & NET METERING ===\n";

$stmtQuote = $pdo->prepare("
    INSERT INTO crm_solar_quotations (
        id, lead_id, quotation_number, system_capacity_kw, panel_type, inverter_type,
        total_cost, subsidy_amount, net_payable, annual_generation_units, annual_savings,
        status, valid_until, notes, created_at, updated_at
    ) VALUES (
        :id, :lead_id, :quotation_number, :system_capacity_kw, :panel_type, :inverter_type,
        :total_cost, :subsidy_amount, :net_payable, :annual_generation_units, :annual_savings,
        :status, :valid_until, :notes, NOW(), NOW()
    )
");

$stmtNetMeter = $pdo->prepare("
    INSERT INTO crm_solar_net_metering (
        id, lead_id, application_number, consumer_number, discom, sanctioned_load,
        stage, submitted_at, approved_at, commissioned_at, subsidy_credited_at,
        created_at, updated_at
    ) VALUES (
        :id, :lead_id, :app_no, :consumer_number, :discom, :sanctioned_load,
        :stage, :submitted_at, :approved_at, :commissioned_at, :subsidy_credited_at,
        NOW(), NOW()
    )
");

foreach (array_slice($createdLeads, 0, 10) as $qIdx => $ql) {
    $ld = $ql['def'];
    $lid = $ql['id'];
    $kw = $ld['solar_capacity_kw'];
    $budget = $ld['estimated_budget'];
    $subsidy = $ld['subsidy_amount'];
    $net = max(0, $budget - $subsidy);
    $units = round($kw * 1400);
    $savings = round($units * 7.5);

    $stmtQuote->execute([
        ':id' => Str::uuid()->toString(),
        ':lead_id' => $lid,
        ':quotation_number' => sprintf('QT-SOL-2026-%04d', $qIdx + 101),
        ':system_capacity_kw' => $kw,
        ':panel_type' => $kw >= 50 ? 'Waaree 550Wp DCR Bifacial TopCon' : 'Adani Solar 545Wp Mono PERC',
        ':inverter_type' => $kw >= 50 ? 'Sungrow 3-Phase Grid-Tied Inverter' : 'Growatt Dual-MPPT String Inverter',
        ':total_cost' => $budget,
        ':subsidy_amount' => $subsidy,
        ':net_payable' => $net,
        ':annual_generation_units' => $units,
        ':annual_savings' => $savings,
        ':status' => $ld['status'] === 'won' ? 'accepted' : 'sent',
        ':valid_until' => date('Y-m-d', strtotime('+30 days')),
        ':notes' => "Official quotation including 25-year performance warranty and PM Surya Ghar registration support.",
    ]);

    // Net Metering application
    $stage = $ld['status'] === 'won' ? 'commissioned' : ($ld['status'] === 'negotiation' ? 'technical_sanction' : 'document_verification');
    $subDate = date('Y-m-d H:i:s', strtotime('-' . (30 - $qIdx * 2) . ' days'));
    $appDate = ($stage === 'technical_sanction' || $stage === 'commissioned') ? date('Y-m-d H:i:s', strtotime('-10 days')) : null;
    $commDate = ($stage === 'commissioned') ? date('Y-m-d H:i:s', strtotime('-2 days')) : null;
    $subCredit = ($stage === 'commissioned' && $subsidy > 0) ? date('Y-m-d H:i:s', strtotime('-1 day')) : null;

    $stmtNetMeter->execute([
        ':id' => Str::uuid()->toString(),
        ':lead_id' => $lid,
        ':app_no' => sprintf('%s/SR/2026/%05d', $ld['discom'], 4800 + $qIdx),
        ':consumer_number' => $ld['consumer_number'],
        ':discom' => $ld['discom'],
        ':sanctioned_load' => $ld['sanctioned_load'],
        ':stage' => $stage,
        ':submitted_at' => $subDate,
        ':approved_at' => $appDate,
        ':commissioned_at' => $commDate,
        ':subsidy_credited_at' => $subCredit,
    ]);
}

echo "Successfully seeded 10 Solar Quotations & Net-Metering Applications!\n\n";

// -------------------------------------------------------------
// 5. SEEDING REAL SOLAR FOLLOW-UPS
// -------------------------------------------------------------
echo "=== 5. SEEDING SOLAR FOLLOW-UPS ===\n";

$stmtFup = $pdo->prepare("
    INSERT INTO crm_followups (
        id, lead_id, assigned_to_user_id, scheduled_at, follow_up_type, notes, status,
        created_at, updated_at
    ) VALUES (
        :id, :lead_id, :assigned, :scheduled_at, :type, :notes, :status, NOW(), NOW()
    )
");

$followupSamples = [
    ['site_survey', 'Terrace structural shadow analysis with drone & 3D sketch. Consumer present on site.', '+1 day', 'scheduled', $bhavik],
    ['proposal', 'Walkthrough of Waaree 550Wp Bifacial ROI model and ₹78,000 DBT subsidy claim procedure.', '+2 days', 'scheduled', $rohan],
    ['meeting', 'DISCOM Sub-division visit with Junior Engineer for sanctioned load enhancement from 6kW to 10kW.', '+3 days', 'scheduled', $omkar],
    ['call', 'Follow-up regarding society managing committee approval and AGM resolution document collection.', '+4 days', 'scheduled', $rohan],
    ['demo', 'Finalize quotation specifications, sign turnkey EPC agreement and collect booking advance.', '+5 days', 'scheduled', $bhavik],
    ['call', 'Confirm bi-directional net-meter installation appointment with MGVCL testing laboratory.', '-2 days', 'completed', $omkar],
    ['site_survey', 'Completed RCC terrace measurement (540 sq ft shadow-free). Sanctioned load verified.', '-4 days', 'completed', $bhavik],
    ['proposal', 'Sent official quotation QT-SOL-2026-0103 with 5-year generation forecast chart.', '-6 days', 'completed', $rohan],
];

foreach ($followupSamples as $idx => $f) {
    $targetLead = $createdLeads[$idx % count($createdLeads)]['id'];
    $stmtFup->execute([
        ':id' => Str::uuid()->toString(),
        ':lead_id' => $targetLead,
        ':assigned' => $f[4],
        ':scheduled_at' => date('Y-m-d H:i:s', strtotime($f[2])),
        ':type' => $f[0],
        ':notes' => $f[1],
        ':status' => $f[3],
    ]);
}

echo "Successfully seeded realistic Solar follow-ups!\n\n";

// -------------------------------------------------------------
// 6. SEEDING SOLAR NOTES & KNOWLEDGE VAULT
// -------------------------------------------------------------
echo "=== 6. SEEDING HIGH-GRADE SOLAR TECHNICAL NOTES ===\n";

$stmtNote = $pdo->prepare("
    INSERT INTO crm_notes (
        id, user_id, notable_type, notable_id, title, content, is_pinned, created_at, updated_at
    ) VALUES (
        :id, :user_id, 'App\\\\Models\\\\Lead', :notable_id, :title, :content, :is_pinned, NOW(), NOW()
    )
");

$solarNotes = [
    [
        'title' => 'PM Surya Ghar: Muft Bijli Yojana — Gujarat 2026 Central Subsidy & SLA Norms',
        'content' => "Key provisions under the National Portal for PM Surya Ghar:
1. Central Financial Assistance (CFA) DBT slabs:
   - 1 kWp system: ₹30,000
   - 2 kWp system: ₹60,000
   - 3 kWp and above (Residential): Fixed ₹78,000 direct bank transfer to consumer.
2. Mandatory Technical Compliance:
   - All modules MUST be ALMM-listed and DCR (Domestic Content Requirement) certified.
   - Micro-inverters or string inverters must comply with IEC 61683 and IEC 62116 anti-islanding standards.
3. Turnkey Vendor SLA:
   - Commissioning and meter installation must be completed within 30 days of registration.",
        'pinned' => 1,
    ],
    [
        'title' => 'MGVCL LT & HT Net-Metering Interconnection Technical Standards',
        'content' => "Guidelines for Madhya Gujarat Vij Company Ltd (MGVCL) grid synchronization:
- LT Rooftop Systems (up to 10 kW): Single-phase allowed up to 3 kW; 3-phase connection mandatory for >3 kW.
- Transformer Capacity Limit: Total connected solar capacity on a distribution transformer (DT) cannot exceed 80% of the transformer's rated kVA.
- Consumer load extension required if solar capacity exceeds sanctioned load.
- Mandatory 2 independent earthing pits: 1 for AC side and 1 for DC side + Lightning Arrester.",
        'pinned' => 1,
    ],
    [
        'title' => 'PEB Industrial Shed Solar Structural Load & Safety Lifeline Protocol',
        'content' => "Engineering guidelines for C&I metal roof installations (Makarpura & Savli GIDC):
1. Chartered Structural Engineer load certificate mandatory confirming minimum 15 kg/sq.m dead load capacity.
2. Standing seam clamps (non-penetrating) must be used for trapezoidal metal sheets to maintain 100% roof waterproofing.
3. Continuous wire lifeline and anti-skid walkway installation mandatory along roof ridge.
4. DC cabling to be routed in HD GI perforated cable trays with UV-resistant covers.",
        'pinned' => 0,
    ],
    [
        'title' => 'Group Housing Society (GHS) Common Facility Solar Financial Model',
        'content' => "Regulatory framework for Residential Societies (RWAs & CHS):
- Eligible for ₹18,000 per kW central subsidy for common facilities (lighting, lifts, water pumps) up to 500 kW capacity.
- Requires 75% quorum AGM special resolution authorizing the Chairman/Secretary.
- Financial ROI: 20 kW common meter solar plant saves approximately ₹24,000 to ₹28,000 per month on society electricity bills, delivering complete payback in under 3.1 years.",
        'pinned' => 0,
    ],
    [
        'title' => 'Optimal DC:AC Overloading Ratio & Inverter Clipping Calculations',
        'content' => "Standard solar array sizing rules for Western India irradiance profile (Vadodara / Surat / Ahmedabad):
- Recommended DC:AC ratio is 1.25x to 1.35x.
- Example: 6.6 kWp DC module array installed with a 5 kW AC inverter.
- Yield benefits: Boosts morning and late afternoon shoulder generation by ~14% without exceeding maximum DC input current or causing thermal inverter clipping.",
        'pinned' => 0,
    ],
];

foreach ($solarNotes as $nIdx => $sn) {
    $stmtNote->execute([
        ':id' => Str::uuid()->toString(),
        ':user_id' => $admin,
        ':notable_id' => $createdLeads[$nIdx % count($createdLeads)]['id'],
        ':title' => $sn['title'],
        ':content' => $sn['content'],
        ':is_pinned' => $sn['pinned'],
    ]);
}

echo "Successfully seeded 5 comprehensive Solar technical memos!\n\n";

// -------------------------------------------------------------
// 7. SEEDING SOLAR TASKS
// -------------------------------------------------------------
echo "=== 7. SEEDING SOLAR ENGINEERING & OPERATIONAL TASKS ===\n";

$stmtTask = $pdo->prepare("
    INSERT INTO crm_tasks (
        id, client_id, assigned_to_user_id, created_by_user_id, title, description,
        priority, status, due_date, estimated_hours, actual_hours, created_at, updated_at
    ) VALUES (
        :id, :client_id, :assigned, :created_by, :title, :description,
        :priority, :status, :due_date, :est, :act, NOW(), NOW()
    )
");

$taskSamples = [
    ['Collect latest 6 months MGVCL electricity bills for load enhancement', 'Obtain consumer bills, verify tariff category (R-LT vs NR-LT) and calculate 12-month peak demand.', 'urgent', 'in_progress', '+1 day', 2.0, 1.0, $rohan],
    ['Conduct 3D drone shadow analysis for north-east parapet wall', 'Map winter solstice and summer solstice shadow patterns on RCC terrace to optimize module tilt angle at 22°.', 'high', 'in_progress', '+2 days', 4.0, 2.5, $bhavik],
    ['Upload CEI inspection receipt on National Solar Portal', 'Attach Electrical Inspectorate approval test report and earth pit resistance test certificates (< 5 ohms).', 'high', 'todo', '+3 days', 1.5, 0.0, $omkar],
    ['Schedule DISCOM Junior Engineer for meter testing and seal installation', 'Coordinate with MGVCL testing division for bi-directional net meter accuracy test and seal delivery.', 'urgent', 'todo', '+4 days', 3.0, 0.0, $rohan],
    ['Generate Performance Ratio (PR) report for 100 kW plant', 'Extract daily SCADA irradiation vs inverter AC output data. Benchmark PR against 78% target guarantee.', 'medium', 'completed', '-1 day', 3.0, 3.0, $bhavik],
    ['Submit final subsidy claim disbursement dossier for Patel Residence', 'Submit commissioned plant photograph with consumer, GPS tag, and canceled bank passbook on PM Surya Ghar portal.', 'high', 'completed', '-2 days', 2.0, 2.0, $omkar],
];

foreach ($taskSamples as $tIdx => $t) {
    $stmtTask->execute([
        ':id' => Str::uuid()->toString(),
        ':client_id' => null,
        ':assigned' => $t[7],
        ':created_by' => $admin,
        ':title' => $t[0],
        ':description' => $t[1],
        ':priority' => $t[2],
        ':status' => $t[3],
        ':due_date' => date('Y-m-d', strtotime($t[4])),
        ':est' => $t[5],
        ':act' => $t[6],
    ]);
}

echo "Successfully seeded Solar operational tasks!\n\n";

// -------------------------------------------------------------
// 8. SEEDING REAL SOLAR ACTIVITY HISTORY
// -------------------------------------------------------------
echo "=== 8. SEEDING SOLAR ACTIVITY STREAM ===\n";

$stmtAct = $pdo->prepare("
    INSERT INTO crm_activities (
        id, user_id, subject_type, subject_id, type, description, created_at, updated_at
    ) VALUES (
        :id, :user_id, 'App\\\\Models\\\\Lead', :lead_id, :type, :desc, :created_at, :created_at
    )
");

$activities = [
    ['status_change', 'Lead stage progressed from "Contacted" to "Qualified" following successful shadow feasibility.', -1],
    ['note', 'Site survey report uploaded: RCC terrace structural condition excellent, 520 sq ft clear area.', -2],
    ['status_change', 'Proposal QT-SOL-2026-0102 sent via WhatsApp and Email.', -3],
    ['status_change', 'Pipeline stage moved to "Site Survey & Feasibility". Engineer assigned.', -5],
    ['status_change', 'New Solar Inquiry registered from PM Surya Ghar National Portal.', -7],
];

foreach ($activities as $aIdx => $act) {
    $stmtAct->execute([
        ':id' => Str::uuid()->toString(),
        ':user_id' => $bhavik,
        ':lead_id' => $createdLeads[0]['id'],
        ':type' => $act[0],
        ':desc' => $act[1],
        ':created_at' => date('Y-m-d H:i:s', strtotime("{$act[2]} days")),
    ]);
}

echo "=== DATABASE SEEDING COMPLETED WITH ZERO ERRORS! ===\n";
