<?php
declare(strict_types=1);

/**
 * Default content used to seed the services and faqs tables the first time
 * each is empty. This is only ever inserted, never overwrites existing rows,
 * so editing content from the admin area is always safe.
 */

function seedServicesData(): array
{
    return [
        ['slug' => 'one-on-one-travel-advice', 'title' => 'One-on-One Travel Advice', 'summary' => 'Personalized travel advice tailored to your needs.', 'icon' => 'compass'],
        ['slug' => 'airbnb-booking', 'title' => 'Airbnb/Booking.com Booking', 'summary' => 'Assistance with booking accommodations through Airbnb or Booking.com.', 'icon' => 'home'],
        ['slug' => 'visa-application-assistance', 'title' => 'Visa Application Assistance', 'summary' => 'Guidance and assistance throughout the visa application process.', 'icon' => 'document'],
        ['slug' => 'online-passport-form-filling', 'title' => 'Online Passport Form Filling', 'summary' => 'Assistance with filling out passport application forms online.', 'icon' => 'passport'],
        ['slug' => 'us-visa-form-filling-assistance', 'title' => 'US Visa Form Filling Assistance', 'summary' => 'Help with filling out US visa application forms accurately.', 'icon' => 'flag-us'],
        ['slug' => 'uk-visa-form-filling-assistance', 'title' => 'UK Visa Form Filling Assistance', 'summary' => 'Help with filling out UK visa application forms accurately.', 'icon' => 'flag-uk'],
        ['slug' => 'visa-application-fee-payment', 'title' => 'Visa Application Fee Payment', 'summary' => 'Assistance with the payment of visa application fees.', 'icon' => 'card'],
        ['slug' => 'visa-pick-up-service', 'title' => 'Visa Pick Up Service', 'summary' => 'Service for picking up your visa documents on your behalf.', 'icon' => 'briefcase'],
        ['slug' => 'counselling-service', 'title' => 'Counselling for Prospective Students', 'summary' => 'Guidance and counseling for students planning to study abroad.', 'icon' => 'graduate'],
        ['slug' => 'travel-insurance-assistance', 'title' => 'Travel Insurance Assistance', 'summary' => 'Assistance with obtaining travel insurance for your trip.', 'icon' => 'shield'],
    ];
}

function seedFaqsData(): array
{
    return [
        ['q' => 'How soon should I apply for my appointment?', 'a' => 'As early as possible. Interview wait times vary by embassy and season and can run from a few days to several weeks. We recommend starting the process at least 6–8 weeks before you plan to travel, and holding off on booking flights or hotels until your visa is issued.'],
        ['q' => 'How long does my passport have to be valid in order to apply for a visa?', 'a' => 'Most destinations require your passport to remain valid for at least six months beyond your intended stay, though some countries have bilateral agreements that shorten this. We’ll check the specific requirement for your destination as part of our assistance.'],
        ['q' => 'My passport is damaged or expiring soon: what should I do?', 'a' => 'Renew it before your interview. A passport that is damaged, expiring within the validity window required for your destination, or too full of stamps to add a visa should be replaced first. We can guide you through the renewal process.'],
        ['q' => 'Do I qualify for a Visa Waiver Program (e.g. ESTA)?', 'a' => 'It depends on your nationality, travel purpose, and passport type. Citizens of participating countries travelling for short business or tourism visits may be able to travel without a traditional visa by obtaining an electronic travel authorization instead. We can help confirm your eligibility.'],
        ['q' => 'What is the fee for an electronic travel authorization and who has to pay it?', 'a' => 'Programs like ESTA charge a modest, fixed registration fee per traveler, payable online at the time of application. We can walk you through the payment step so nothing is missed or duplicated.'],
        ['q' => 'What happens if I travel without the required travel authorization?', 'a' => 'Travellers under a visa waiver scheme who have not obtained the required electronic authorization in advance should expect to be denied boarding by their airline, or denied entry on arrival. Always secure your authorization before you fly.'],
        ['q' => 'Can I apply for a visa in a country where I am not a citizen or resident?', 'a' => 'Generally, applicants are advised to apply in their country of nationality or legal residence. Some embassies will accept third-country applications on a case-by-case basis, and we can help you confirm the policy that applies to you.'],
        ['q' => 'Do all visa applicants have to attend an interview?', 'a' => 'Most applicants do, though a small number qualify for an interview waiver based on age, visa renewal status, or other criteria set by the embassy. We’ll help you check whether you qualify before you book an appointment.'],
        ['q' => 'My visa is expiring soon: do I need to reapply from scratch?', 'a' => 'Not always. Many countries offer a simplified renewal path for recently expired visas of the same category, sometimes without a new interview. We can assess your situation and guide you through whichever path applies.'],
    ];
}

function seedSettingsData(): array
{
    return [
        'facebook_url'      => '',
        'instagram_url'     => '',
        'notification_email' => '',
    ];
}
