<?php

namespace Database\Seeders;

use App\Domains\User\Models\Role;
use App\Domains\User\Models\User;
use App\Models\Activity;
use App\Models\Client;
use App\Models\Company;
use App\Models\Contact;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\Note;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Website;
use App\Models\WebsiteCredential;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CrmDatabaseSeeder extends Seeder
{
    /**
     * Run database seeds for WebForge Agency CRM & Project Management.
     */
    public function run(): void
    {
        // 1. Ensure Developer and Admin Roles exist
        $adminRole = Role::firstOrCreate(['name' => 'administrator', 'guard_name' => 'web']);
        $devRole = Role::firstOrCreate(['name' => 'developer', 'guard_name' => 'web']);

        // 2. Seed 4 Team Members: Rohan, Abhishek, Bhavik, Omkar
        $rohan = User::updateOrCreate(
            ['email' => 'rohan@webforge.agency'],
            [
                'name' => 'Rohan',
                'password' => Hash::make('password'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $rohan->syncRoles([$adminRole]);

        $abhishek = User::updateOrCreate(
            ['email' => 'abhishek@webforge.agency'],
            [
                'name' => 'Abhishek',
                'password' => Hash::make('password'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $abhishek->syncRoles([$devRole]);

        $bhavik = User::updateOrCreate(
            ['email' => 'bhavik@webforge.agency'],
            [
                'name' => 'Bhavik',
                'password' => Hash::make('password'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $bhavik->syncRoles([$devRole]);

        $omkar = User::updateOrCreate(
            ['email' => 'omkar@webforge.agency'],
            [
                'name' => 'Omkar',
                'password' => Hash::make('password'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $omkar->syncRoles([$devRole]);

        // 3. Seed Companies
        $brightTech = Company::firstOrCreate(['name' => 'BrightTech Solutions'], [
            'industry' => 'Software & IT Services',
            'website' => 'https://brighttech.io',
            'email' => 'info@brighttech.io',
            'phone' => '+1 (555) 234-5678',
            'address' => '450 Innovation Way, Suite 800',
            'city' => 'San Francisco',
            'state' => 'CA',
            'country' => 'United States',
            'company_type' => 'Client',
            'status' => 'active',
            'notes' => 'Enterprise SaaS client requiring high availability and regular maintenance.',
        ]);

        $greenLeaf = Company::firstOrCreate(['name' => 'GreenLeaf Interiors'], [
            'industry' => 'Interior Design & Architecture',
            'website' => 'https://greenleafinteriors.com',
            'email' => 'contact@greenleafinteriors.com',
            'phone' => '+1 (555) 876-5432',
            'address' => '120 Design Blvd',
            'city' => 'Austin',
            'state' => 'TX',
            'country' => 'United States',
            'company_type' => 'Client',
            'status' => 'active',
            'notes' => 'Custom e-commerce store with high-res furniture catalog and 3D previews.',
        ]);

        $primeDental = Company::firstOrCreate(['name' => 'Prime Dental Care'], [
            'industry' => 'Healthcare & Dentistry',
            'website' => 'https://primedentalcare.net',
            'email' => 'appointments@primedentalcare.net',
            'phone' => '+1 (555) 345-6789',
            'address' => '78 Medical Plaza',
            'city' => 'Chicago',
            'state' => 'IL',
            'country' => 'United States',
            'company_type' => 'Client',
            'status' => 'active',
            'notes' => 'Clinic portal with patient online appointment booking and SMS reminders.',
        ]);

        $urbanCafe = Company::firstOrCreate(['name' => 'Urban Cafe & Roastery'], [
            'industry' => 'Hospitality & Food Service',
            'website' => 'https://urbancaferoastery.com',
            'email' => 'hello@urbancaferoastery.com',
            'phone' => '+1 (555) 987-6543',
            'address' => '14 Downtown Market St',
            'city' => 'Seattle',
            'state' => 'WA',
            'country' => 'United States',
            'company_type' => 'Client',
            'status' => 'active',
            'notes' => 'Online coffee subscription store with POS integration.',
        ]);

        // 4. Seed Contacts
        $contactRahul = Contact::firstOrCreate(['email' => 'rahul@brighttech.io'], [
            'company_id' => $brightTech->id,
            'first_name' => 'Rahul',
            'last_name' => 'Patel',
            'job_title' => 'Chief Executive Officer',
            'phone' => '+1 (555) 234-5679',
            'whatsapp' => '+1 (555) 234-5679',
            'preferred_contact_method' => 'Email',
            'notes' => 'Primary decision maker for all enterprise upgrades.',
        ]);

        $contactAmit = Contact::firstOrCreate(['email' => 'amit@brighttech.io'], [
            'company_id' => $brightTech->id,
            'first_name' => 'Amit',
            'last_name' => 'Shah',
            'job_title' => 'VP of Marketing',
            'phone' => '+1 (555) 234-5680',
            'whatsapp' => '+1 (555) 234-5680',
            'preferred_contact_method' => 'WhatsApp',
        ]);

        $contactPriya = Contact::firstOrCreate(['email' => 'priya@greenleafinteriors.com'], [
            'company_id' => $greenLeaf->id,
            'first_name' => 'Priya',
            'last_name' => 'Mehta',
            'job_title' => 'Creative Director & Co-Founder',
            'phone' => '+1 (555) 876-5433',
            'whatsapp' => '+1 (555) 876-5433',
            'preferred_contact_method' => 'Phone',
        ]);

        $contactDavid = Contact::firstOrCreate(['email' => 'david@primedentalcare.net'], [
            'company_id' => $primeDental->id,
            'first_name' => 'Dr. David',
            'last_name' => 'Miller',
            'job_title' => 'Managing Director',
            'phone' => '+1 (555) 345-6790',
            'preferred_contact_method' => 'Email',
        ]);

        $contactSophia = Contact::firstOrCreate(['email' => 'sophia@urbancaferoastery.com'], [
            'company_id' => $urbanCafe->id,
            'first_name' => 'Sophia',
            'last_name' => 'Zhang',
            'job_title' => 'Operations Manager',
            'phone' => '+1 (555) 987-6544',
            'preferred_contact_method' => 'WhatsApp',
        ]);

        // 5. Seed Leads in all 7 Pipeline Stages
        $lead1 = Lead::firstOrCreate(['company_name' => 'Apex Logistics Global'], [
            'contact_person' => 'Carlos Mendez',
            'email' => 'carlos@apexlogistics.com',
            'phone' => '+1 (555) 111-2233',
            'whatsapp' => '+1 (555) 111-2233',
            'website' => 'https://apexlogistics.com',
            'industry' => 'Transportation & Freight',
            'lead_source' => 'Google',
            'location' => 'Miami, FL',
            'requirement' => 'Complete customer shipment tracking portal and fleet management dashboard.',
            'estimated_budget' => 14500.00,
            'priority' => 'urgent',
            'status' => 'new',
            'assigned_to_user_id' => $rohan->id,
            'next_followup_date' => Carbon::today(),
            'notes' => 'Inbound request via contact form. Requested a live Zoom demo this week.',
        ]);

        $lead2 = Lead::firstOrCreate(['company_name' => 'CloudNine SaaS'], [
            'contact_person' => 'Jennifer Scott',
            'email' => 'jennifer@cloudninesoftware.com',
            'phone' => '+1 (555) 222-3344',
            'whatsapp' => '+1 (555) 222-3344',
            'website' => 'https://cloudninesoftware.com',
            'industry' => 'Cloud Infrastructure',
            'lead_source' => 'LinkedIn',
            'location' => 'New York, NY',
            'requirement' => 'Marketing landing page overhaul with high-converting pricing calculator and blog.',
            'estimated_budget' => 6800.00,
            'priority' => 'high',
            'status' => 'contacted',
            'assigned_to_user_id' => $bhavik->id,
            'last_contact_date' => Carbon::now()->subDays(2),
            'next_followup_date' => Carbon::tomorrow(),
            'notes' => 'Sent initial case studies and Figma layout preview.',
        ]);

        $lead3 = Lead::firstOrCreate(['company_name' => 'Vanguard Fitness Clubs'], [
            'contact_person' => 'Mark Robinson',
            'email' => 'mark@vanguardfitness.com',
            'phone' => '+1 (555) 333-4455',
            'lead_source' => 'Referral',
            'industry' => 'Health & Fitness',
            'requirement' => 'Mobile-responsive member portal with class scheduling and Stripe subscriptions.',
            'estimated_budget' => 11000.00,
            'priority' => 'high',
            'status' => 'qualified',
            'assigned_to_user_id' => $abhishek->id,
            'next_followup_date' => Carbon::now()->addDays(2),
            'notes' => 'Budget approved by board. Scope checklist verified.',
        ]);

        $lead4 = Lead::firstOrCreate(['company_name' => 'Artisan Craft Bakery'], [
            'contact_person' => 'Lucille Dupont',
            'email' => 'lucille@artisancraft.com',
            'phone' => '+1 (555) 444-5566',
            'lead_source' => 'Instagram',
            'industry' => 'Food & Bakery',
            'requirement' => 'Custom cake ordering customizer and catering booking calendar.',
            'estimated_budget' => 5200.00,
            'priority' => 'medium',
            'status' => 'proposal_sent',
            'assigned_to_user_id' => $bhavik->id,
            'last_contact_date' => Carbon::now()->subDays(3),
            'next_followup_date' => Carbon::today(),
            'notes' => 'Proposal #WP-2026-08 sent. Reviewing with co-owner.',
        ]);

        $lead5 = Lead::firstOrCreate(['company_name' => 'Summit Real Estate Partners'], [
            'contact_person' => 'Jonathan Hayes',
            'email' => 'jhayes@summitre.com',
            'phone' => '+1 (555) 555-6677',
            'lead_source' => 'Cold Outreach',
            'industry' => 'Real Estate',
            'requirement' => 'MLS IDX property search portal with interactive map pins and CRM capture.',
            'estimated_budget' => 17500.00,
            'priority' => 'urgent',
            'status' => 'negotiation',
            'assigned_to_user_id' => $rohan->id,
            'next_followup_date' => Carbon::now()->addDays(1),
            'notes' => 'Negotiating maintenance SLA and milestone payment schedule.',
        ]);

        $leadWon = Lead::firstOrCreate(['company_name' => 'BrightTech Solutions'], [
            'contact_person' => 'Rahul Patel',
            'email' => 'rahul@brighttech.io',
            'phone' => '+1 (555) 234-5679',
            'lead_source' => 'Website',
            'industry' => 'Software & IT Services',
            'estimated_budget' => 12500.00,
            'priority' => 'high',
            'status' => 'won',
            'assigned_to_user_id' => $rohan->id,
            'notes' => 'Converted successfully into active client account.',
        ]);

        $leadLost = Lead::firstOrCreate(['company_name' => 'Budget Auto Sales'], [
            'contact_person' => 'Tom Bradley',
            'email' => 'tom@budgetautosales.com',
            'lead_source' => 'Upwork',
            'industry' => 'Automotive',
            'estimated_budget' => 1500.00,
            'priority' => 'low',
            'status' => 'lost',
            'assigned_to_user_id' => $omkar->id,
            'notes' => 'Budget mismatched; requested template site below agency minimum tier.',
        ]);

        // 6. Seed Clients
        $clientBright = Client::firstOrCreate(['client_code' => 'CLI-1001'], [
            'company_id' => $brightTech->id,
            'primary_contact_id' => $contactRahul->id,
            'lead_id' => $leadWon->id,
            'name' => 'BrightTech Solutions',
            'email' => 'info@brighttech.io',
            'phone' => '+1 (555) 234-5678',
            'address' => '450 Innovation Way, Suite 800, San Francisco, CA',
            'industry' => 'Software & IT Services',
            'client_since' => Carbon::now()->subMonths(6),
            'status' => 'active',
            'notes' => 'Core client with ongoing support contract.',
        ]);

        $clientGreen = Client::firstOrCreate(['client_code' => 'CLI-1002'], [
            'company_id' => $greenLeaf->id,
            'primary_contact_id' => $contactPriya->id,
            'name' => 'GreenLeaf Interiors',
            'email' => 'contact@greenleafinteriors.com',
            'phone' => '+1 (555) 876-5432',
            'address' => '120 Design Blvd, Austin, TX',
            'industry' => 'Interior Design & Architecture',
            'client_since' => Carbon::now()->subMonths(4),
            'status' => 'active',
        ]);

        $clientPrime = Client::firstOrCreate(['client_code' => 'CLI-1003'], [
            'company_id' => $primeDental->id,
            'primary_contact_id' => $contactDavid->id,
            'name' => 'Prime Dental Care',
            'email' => 'appointments@primedentalcare.net',
            'phone' => '+1 (555) 345-6789',
            'address' => '78 Medical Plaza, Chicago, IL',
            'industry' => 'Healthcare & Dentistry',
            'client_since' => Carbon::now()->subMonths(3),
            'status' => 'active',
        ]);

        $clientUrban = Client::firstOrCreate(['client_code' => 'CLI-1004'], [
            'company_id' => $urbanCafe->id,
            'primary_contact_id' => $contactSophia->id,
            'name' => 'Urban Cafe & Roastery',
            'email' => 'hello@urbancaferoastery.com',
            'phone' => '+1 (555) 987-6543',
            'address' => '14 Downtown Market St, Seattle, WA',
            'industry' => 'Hospitality & Food Service',
            'client_since' => Carbon::now()->subMonths(2),
            'status' => 'active',
        ]);

        // 7. Seed Projects & Websites
        $project1 = Project::firstOrCreate(['name' => 'BrightTech Enterprise Platform Redesign'], [
            'client_id' => $clientBright->id,
            'company_id' => $brightTech->id,
            'project_type' => 'Custom Web App',
            'description' => 'Scalable microservices architecture redesign with customer portal and analytics dashboard.',
            'start_date' => Carbon::now()->subMonths(2),
            'deadline' => Carbon::now()->addDays(20),
            'estimated_hours' => 160.0,
            'actual_hours' => 128.5,
            'budget' => 14500.00,
            'status' => 'development',
            'priority' => 'urgent',
            'project_manager_id' => $rohan->id,
            'progress' => 75,
            'repository' => 'https://github.com/webforge/brighttech-platform',
            'staging_url' => 'https://staging.brighttech.io',
            'production_url' => 'https://brighttech.io',
        ]);
        $project1->developers()->sync([$rohan->id, $abhishek->id, $omkar->id]);

        $project2 = Project::firstOrCreate(['name' => 'GreenLeaf E-Commerce Storefront'], [
            'client_id' => $clientGreen->id,
            'company_id' => $greenLeaf->id,
            'project_type' => 'E-commerce',
            'description' => 'Boutique furniture store with customized checkout, Stripe Payments, and AR room preview.',
            'start_date' => Carbon::now()->subMonths(1),
            'deadline' => Carbon::now()->addDays(14),
            'estimated_hours' => 120.0,
            'actual_hours' => 84.0,
            'budget' => 9800.00,
            'status' => 'testing',
            'priority' => 'high',
            'project_manager_id' => $bhavik->id,
            'progress' => 88,
            'repository' => 'https://github.com/webforge/greenleaf-shop',
            'staging_url' => 'https://staging.greenleafinteriors.com',
            'production_url' => 'https://greenleafinteriors.com',
        ]);
        $project2->developers()->sync([$bhavik->id, $abhishek->id]);

        $project3 = Project::firstOrCreate(['name' => 'Prime Dental Appointment Portal'], [
            'client_id' => $clientPrime->id,
            'company_id' => $primeDental->id,
            'project_type' => 'Corporate Website',
            'description' => 'Patient booking workflow with dentist schedule synchronization and automated SMS notifications.',
            'start_date' => Carbon::now()->subDays(20),
            'deadline' => Carbon::now()->addMonths(1),
            'estimated_hours' => 90.0,
            'actual_hours' => 35.0,
            'budget' => 7500.00,
            'status' => 'design',
            'priority' => 'medium',
            'project_manager_id' => $rohan->id,
            'progress' => 40,
            'repository' => 'https://github.com/webforge/primedental-portal',
        ]);
        $project3->developers()->sync([$bhavik->id, $omkar->id]);

        $project4 = Project::firstOrCreate(['name' => 'Urban Cafe Coffee Subscription Engine'], [
            'client_id' => $clientUrban->id,
            'company_id' => $urbanCafe->id,
            'project_type' => 'E-commerce',
            'description' => 'Recurring monthly coffee bean deliveries with grind selection and delivery date picker.',
            'start_date' => Carbon::now()->subDays(10),
            'deadline' => Carbon::now()->addMonths(2),
            'estimated_hours' => 80.0,
            'actual_hours' => 15.0,
            'budget' => 6200.00,
            'status' => 'planning',
            'priority' => 'medium',
            'project_manager_id' => $abhishek->id,
            'progress' => 15,
        ]);
        $project4->developers()->sync([$abhishek->id, $bhavik->id]);

        // 8. Seed Websites with domain & hosting expiry + credentials vault
        $siteBright = Website::firstOrCreate(['name' => 'BrightTech Official Web App'], [
            'client_id' => $clientBright->id,
            'company_id' => $brightTech->id,
            'project_id' => $project1->id,
            'title' => 'BrightTech Cloud Intelligence Platform',
            'url' => 'https://brighttech.io',
            'domain' => 'brighttech.io',
            'domain_provider' => 'Cloudflare Registrar',
            'domain_expiry_date' => Carbon::now()->addDays(8),
            'hosting_provider' => 'AWS US-East (EC2 & RDS)',
            'hosting_expiry_date' => Carbon::now()->addDays(24),
            'technology' => 'Laravel 13 & Vue 3 SPA',
            'framework' => 'Laravel 13',
            'cms' => 'Custom Headless Admin',
            'repository_url' => 'https://github.com/webforge/brighttech-platform',
            'server_ip' => '54.210.144.92',
            'deployment_platform' => 'Laravel Forge + Cloudflare',
            'database_type' => 'MySQL 8.0 RDS',
            'ssl_status' => 'active',
            'ssl_expiry_date' => Carbon::now()->addDays(75),
            'status' => 'live',
            'design_progress' => 100,
            'dev_progress' => 85,
            'testing_progress' => 60,
        ]);

        WebsiteCredential::firstOrCreate(['website_id' => $siteBright->id, 'credential_type' => 'SSH/SFTP'], [
            'title' => 'Production Server SSH',
            'username' => 'forge_deployer',
            'password_encrypted' => encrypt('S3cur3#SSH!9921_Forge'),
            'host_or_url' => '54.210.144.92',
            'port' => '22',
            'notes' => 'Public IP restricted to agency office IP only.',
        ]);

        WebsiteCredential::firstOrCreate(['website_id' => $siteBright->id, 'credential_type' => 'CMS Admin'], [
            'title' => 'Super Administrator CMS Portal',
            'username' => 'admin@brighttech.io',
            'password_encrypted' => encrypt('BrightAdmin_2026!MasterKey'),
            'host_or_url' => 'https://brighttech.io/admin/login',
            'notes' => 'Master credentials handed over to Rahul Patel.',
        ]);

        $siteGreen = Website::firstOrCreate(['name' => 'GreenLeaf Interiors Store'], [
            'client_id' => $clientGreen->id,
            'company_id' => $greenLeaf->id,
            'project_id' => $project2->id,
            'title' => 'GreenLeaf Luxury Modern Interiors',
            'url' => 'https://greenleafinteriors.com',
            'domain' => 'greenleafinteriors.com',
            'domain_provider' => 'Namecheap',
            'domain_expiry_date' => Carbon::now()->addMonths(8),
            'hosting_provider' => 'DigitalOcean Droplet',
            'hosting_expiry_date' => Carbon::now()->addDays(12),
            'technology' => 'Tailwind CSS, Alpine.js, Laravel Cashier',
            'framework' => 'Laravel 13',
            'cms' => 'Statamic CMS',
            'server_ip' => '165.227.88.14',
            'ssl_status' => 'active',
            'ssl_expiry_date' => Carbon::now()->addMonths(5),
            'status' => 'development',
            'design_progress' => 100,
            'dev_progress' => 90,
            'testing_progress' => 75,
        ]);

        WebsiteCredential::firstOrCreate(['website_id' => $siteGreen->id, 'credential_type' => 'Database'], [
            'title' => 'PostgreSQL Production DB',
            'username' => 'doadmin',
            'password_encrypted' => encrypt('PgSql_99#GreenLeaf_Pass'),
            'host_or_url' => 'db-greenleaf-do-user-1234.b.db.ondigitalocean.com',
            'port' => '25060',
        ]);

        $sitePrime = Website::firstOrCreate(['name' => 'Prime Dental Clinic Website'], [
            'client_id' => $clientPrime->id,
            'company_id' => $primeDental->id,
            'project_id' => $project3->id,
            'title' => 'Prime Dental Care Chicago',
            'url' => 'https://primedentalcare.net',
            'domain' => 'primedentalcare.net',
            'domain_provider' => 'GoDaddy',
            'domain_expiry_date' => Carbon::now()->addDays(5),
            'hosting_provider' => 'SiteGround Cloud',
            'technology' => 'Next.js & Tailwind CSS',
            'status' => 'design',
            'design_progress' => 80,
            'dev_progress' => 30,
            'testing_progress' => 10,
        ]);

        // 9. Seed Project Milestones
        $project1->milestones()->createMany([
            ['title' => 'Phase 1: Architecture & API Schema Definition', 'due_date' => Carbon::now()->subMonths(1), 'is_completed' => true, 'completed_at' => Carbon::now()->subMonths(1)],
            ['title' => 'Phase 2: Database Migration & Sanctum Auth Core', 'due_date' => Carbon::now()->subDays(15), 'is_completed' => true, 'completed_at' => Carbon::now()->subDays(14)],
            ['title' => 'Phase 3: High-Frequency Analytics Dashboard UI', 'due_date' => Carbon::now()->addDays(5), 'is_completed' => false],
            ['title' => 'Phase 4: Load Testing & Staging Verification', 'due_date' => Carbon::now()->addDays(18), 'is_completed' => false],
        ]);

        // 10. Seed Tasks across Todo, In Progress, Review, Blocked, Completed
        Task::create([
            'project_id' => $project1->id,
            'website_id' => $siteBright->id,
            'client_id' => $clientBright->id,
            'assigned_to_user_id' => $rohan->id,
            'title' => 'Optimize MySQL query indexing for enterprise audit reports',
            'description' => 'Analyze slow query logs on RDS and add composite indexes on created_at and user_id.',
            'priority' => 'urgent',
            'status' => 'in_progress',
            'due_date' => Carbon::today(),
            'estimated_hours' => 4.0,
            'actual_hours' => 2.5,
        ]);

        Task::create([
            'project_id' => $project1->id,
            'website_id' => $siteBright->id,
            'client_id' => $clientBright->id,
            'assigned_to_user_id' => $abhishek->id,
            'title' => 'Integrate Webhooks dispatch engine with retry exponential backoff',
            'description' => 'Handle failed client webhook dispatches with automatic 3x retries using Redis queues.',
            'priority' => 'high',
            'status' => 'review',
            'due_date' => Carbon::now()->addDays(2),
            'estimated_hours' => 6.0,
            'actual_hours' => 5.0,
        ]);

        Task::create([
            'project_id' => $project2->id,
            'website_id' => $siteGreen->id,
            'client_id' => $clientGreen->id,
            'assigned_to_user_id' => $bhavik->id,
            'title' => 'Build 3D Furniture interactive viewer with Three.js',
            'description' => 'Implement canvas overlay for rotating living room furniture models on product pages.',
            'priority' => 'high',
            'status' => 'in_progress',
            'due_date' => Carbon::now()->addDays(3),
            'estimated_hours' => 8.0,
            'actual_hours' => 4.0,
        ]);

        Task::create([
            'project_id' => $project2->id,
            'website_id' => $siteGreen->id,
            'client_id' => $clientGreen->id,
            'assigned_to_user_id' => $abhishek->id,
            'title' => 'Configure Stripe Checkout webhook handler for instant order fulfillment',
            'description' => 'Listen to checkout.session.completed and generate digital invoice PDF.',
            'priority' => 'urgent',
            'status' => 'todo',
            'due_date' => Carbon::now()->subDays(1),
            'estimated_hours' => 3.5,
            'actual_hours' => 0.0,
        ]);

        Task::create([
            'project_id' => $project3->id,
            'website_id' => $sitePrime->id,
            'client_id' => $clientPrime->id,
            'assigned_to_user_id' => $omkar->id,
            'title' => 'Setup Twilio SMS appointment reminder automated cron',
            'description' => 'Send SMS reminders 24 hours prior to patient appointment slots.',
            'priority' => 'medium',
            'status' => 'blocked',
            'due_date' => Carbon::now()->addDays(5),
            'estimated_hours' => 5.0,
            'notes' => 'Awaiting Twilio API production credentials from Dr. David Miller.',
        ]);

        Task::create([
            'project_id' => $project1->id,
            'website_id' => $siteBright->id,
            'client_id' => $clientBright->id,
            'assigned_to_user_id' => $rohan->id,
            'title' => 'Initial database schema design and Eloquent relationships',
            'priority' => 'high',
            'status' => 'completed',
            'due_date' => Carbon::now()->subDays(10),
            'completed_at' => Carbon::now()->subDays(10),
            'estimated_hours' => 8.0,
            'actual_hours' => 7.5,
        ]);

        // 11. Seed Follow-ups
        FollowUp::create([
            'lead_id' => $lead1->id,
            'assigned_to_user_id' => $rohan->id,
            'scheduled_at' => Carbon::today()->setHour(14)->setMinute(30),
            'follow_up_type' => 'demo',
            'notes' => 'Present live shipment tracking architecture walkthrough to Carlos Mendez.',
            'status' => 'scheduled',
        ]);

        FollowUp::create([
            'lead_id' => $lead4->id,
            'assigned_to_user_id' => $bhavik->id,
            'scheduled_at' => Carbon::today()->setHour(16)->setMinute(0),
            'follow_up_type' => 'proposal_followup',
            'notes' => 'Check in with Lucille regarding cake customizer proposal package.',
            'status' => 'scheduled',
        ]);

        FollowUp::create([
            'client_id' => $clientGreen->id,
            'contact_id' => $contactPriya->id,
            'assigned_to_user_id' => $abhishek->id,
            'scheduled_at' => Carbon::tomorrow()->setHour(11)->setMinute(0),
            'follow_up_type' => 'meeting',
            'notes' => 'Weekly sprint review and design sign-off for GreenLeaf storefront.',
            'status' => 'scheduled',
        ]);

        // 12. Seed Payments & Invoices
        Payment::create([
            'client_id' => $clientBright->id,
            'project_id' => $project1->id,
            'invoice_number' => 'INV-2026-1001',
            'amount' => 7250.00,
            'payment_date' => Carbon::now()->subDays(40),
            'due_date' => Carbon::now()->subDays(30),
            'payment_method' => 'Bank Wire',
            'status' => 'paid',
            'notes' => '50% upfront project milestone deposit.',
        ]);

        Payment::create([
            'client_id' => $clientBright->id,
            'project_id' => $project1->id,
            'invoice_number' => 'INV-2026-1002',
            'amount' => 3625.00,
            'payment_date' => null,
            'due_date' => Carbon::now()->addDays(10),
            'payment_method' => 'Bank Wire',
            'status' => 'pending',
            'notes' => '25% development phase completion milestone.',
        ]);

        Payment::create([
            'client_id' => $clientGreen->id,
            'project_id' => $project2->id,
            'invoice_number' => 'INV-2026-1003',
            'amount' => 4900.00,
            'payment_date' => Carbon::now()->subDays(25),
            'due_date' => Carbon::now()->subDays(20),
            'payment_method' => 'Stripe',
            'status' => 'paid',
            'notes' => 'Storefront launch deposit.',
        ]);

        Payment::create([
            'client_id' => $clientPrime->id,
            'project_id' => $project3->id,
            'invoice_number' => 'INV-2026-1004',
            'amount' => 3750.00,
            'payment_date' => null,
            'due_date' => Carbon::now()->subDays(4),
            'payment_method' => 'Bank Transfer',
            'status' => 'overdue',
            'notes' => 'Portal kickoff invoice pending accounts approval.',
        ]);

        // 13. Seed Activities & Notes
        Activity::create([
            'user_id' => $rohan->id,
            'subject_type' => Lead::class,
            'subject_id' => $lead1->id,
            'type' => 'call',
            'description' => 'Phone discussion with Carlos regarding Apex Logistics tracking requirements.',
        ]);

        Activity::create([
            'user_id' => $bhavik->id,
            'subject_type' => Project::class,
            'subject_id' => $project2->id,
            'type' => 'deployment',
            'description' => 'Deployed v0.8 staging build to staging.greenleafinteriors.com.',
        ]);

        Note::create([
            'user_id' => $rohan->id,
            'notable_type' => Client::class,
            'notable_id' => $clientBright->id,
            'title' => 'SLA & Server Specs',
            'content' => 'Client requires 99.9% uptime with automated database backups every 6 hours.',
            'is_pinned' => true,
        ]);

        Note::create([
            'user_id' => $abhishek->id,
            'notable_type' => Website::class,
            'notable_id' => $siteBright->id,
            'title' => 'SSL Renewal Procedure',
            'content' => 'Cloudflare Origin CA certificate valid until May 2026; Auto-renewal enabled.',
            'is_pinned' => false,
        ]);
    }
}
