<?php
declare(strict_types=1);

/**
 * Service-specific application forms, ported field-for-field (names, labels,
 * option lists, required flags) from the equivalent pages on the live
 * andsontravelconsult.com site. Only services that actually had a working
 * form there get an entry here; everything else falls back to the generic
 * apply form in public/apply.php.
 *
 * Each field may set 'core' => 'full_name' | 'email' | 'phone' | 'message' to
 * also populate that column on the applications table (so the admin list/
 * detail views still show name/email/phone at a glance); every field's
 * answer is additionally stored verbatim in applications.extra_fields.
 */
function getServiceFormSchema(string $slug): ?array
{
    $schemas = serviceFormSchemas();
    return $schemas[$slug] ?? null;
}

function serviceFormSchemas(): array
{
    $yesNo = ['YES', 'NO'];

    return [
        'one-on-one-travel-advice' => [
            'submit_label' => 'Submit',
            'fields' => [
                ['key' => 'name', 'label' => 'Name of applicant', 'type' => 'text', 'required' => true, 'core' => 'full_name'],
                ['key' => 'telephoneNumber', 'label' => 'Telephone Number', 'type' => 'tel', 'required' => true, 'core' => 'phone'],
                ['key' => 'whatsappNumber', 'label' => 'Whatsapp Number', 'type' => 'tel', 'required' => false, 'help' => 'If not, we will contact you via email'],
                ['key' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true, 'core' => 'email'],
                ['key' => 'adviceType', 'label' => 'Type of advice needed', 'type' => 'select', 'required' => true, 'options' => ['Immigration Rules', 'Travel for Study', 'Travel for Pleasure', 'Travel for Work', 'Travel for Business', 'Migration Advice']],
                ['key' => 'message', 'label' => 'Additional Message', 'type' => 'textarea', 'required' => false, 'core' => 'message'],
            ],
        ],

        'online-passport-form-filling' => [
            'submit_label' => 'Submit',
            'fields' => [
                ['key' => 'fullName', 'label' => 'Name of Applicant in Full (as in Birth Certificate)', 'type' => 'text', 'required' => true, 'core' => 'full_name'],
                ['key' => 'dateOfBirth', 'label' => 'Date of Birth', 'type' => 'date', 'required' => true],
                ['key' => 'sex', 'label' => 'Sex', 'type' => 'select', 'required' => true, 'options' => ['Male', 'Female']],
                ['key' => 'phoneNumber', 'label' => 'Phone Number', 'type' => 'tel', 'required' => true, 'core' => 'phone'],
                ['key' => 'whatsappNumber', 'label' => 'Whatsapp Number', 'type' => 'tel', 'required' => false, 'help' => 'If not, we will contact you via email'],
                ['key' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true, 'core' => 'email'],
                ['key' => 'applicationType', 'label' => 'Application Type', 'type' => 'select', 'required' => true, 'options' => ['New Application', 'Renewal']],
                ['key' => 'additionalMessage', 'label' => 'Additional Message', 'type' => 'textarea', 'required' => false, 'core' => 'message'],
            ],
        ],

        'us-visa-form-filling-assistance' => [
            'submit_label' => 'Submit',
            'fields' => [
                ['key' => 'fullName', 'label' => 'Full Name of Applicant as it appears in Passport', 'type' => 'text', 'required' => true, 'core' => 'full_name'],
                ['key' => 'dateOfBirth', 'label' => 'Date of Birth', 'type' => 'date', 'required' => true],
                ['key' => 'placeOfBirth', 'label' => 'Place of Birth (i.e. Country of Birth)', 'type' => 'text', 'required' => true],
                ['key' => 'nationality', 'label' => 'Nationality', 'type' => 'text', 'required' => true],
                ['key' => 'homeAddress', 'label' => 'Home Address', 'type' => 'text', 'required' => true],
                ['key' => 'mailingAddress', 'label' => 'Mailing/Postal Address', 'type' => 'text', 'required' => true],
                ['key' => 'phoneNumber', 'label' => 'Telephone Number', 'type' => 'tel', 'required' => true, 'core' => 'phone'],
                ['key' => 'email', 'label' => 'Email Address', 'type' => 'email', 'required' => true, 'core' => 'email'],
                ['key' => 'nationalIdNumber', 'label' => 'National ID Number', 'type' => 'text', 'required' => true],
                ['key' => 'passportIssuingCityCountry', 'label' => 'Passport Issuing City/Country', 'type' => 'text', 'required' => true],
                ['key' => 'passportIssuedDate', 'label' => 'Date Passport Issued', 'type' => 'date', 'required' => true],
                ['key' => 'passportExpiryDate', 'label' => 'Passport Expiry Date', 'type' => 'date', 'required' => true],
                ['key' => 'firstTimeTraveller', 'label' => 'First Time Traveller to the US?', 'type' => 'select', 'required' => true, 'options' => $yesNo],
                ['key' => 'deniedVisa', 'label' => 'Have you ever been denied a visa to the US?', 'type' => 'select', 'required' => true, 'options' => $yesNo],
                ['key' => 'previousUSTravel', 'label' => 'Previous US travel within the last 5 years', 'type' => 'text', 'required' => true],
                ['key' => 'countriesTravelled', 'label' => 'Name all the countries you have travelled to in the last 5 years', 'type' => 'text', 'required' => true],
                ['key' => 'intendedArrivalDate', 'label' => 'Intended Arrival Date in US', 'type' => 'date', 'required' => false],
                ['key' => 'intendedReturnDate', 'label' => 'Intended Return Date from US', 'type' => 'date', 'required' => false],
                ['key' => 'usDestination', 'label' => 'Where are you going in the US?', 'type' => 'text', 'required' => true],
                ['key' => 'usAddress', 'label' => 'Address where you will be staying in the US', 'type' => 'text', 'required' => true],
                ['key' => 'usContactName', 'label' => 'Name of Contact Person in the US', 'type' => 'text', 'required' => true],
                ['key' => 'usContactRelationship', 'label' => 'Relationship to You', 'type' => 'text', 'required' => true],
                ['key' => 'tripPurpose', 'label' => 'Purpose of Trip to the United States', 'type' => 'text', 'required' => true],
                ['key' => 'familyInUS', 'label' => 'Do you have any family members living in the US?', 'type' => 'select', 'required' => true, 'options' => $yesNo],
                ['key' => 'tripSponsor', 'label' => 'Who is sponsoring your trip?', 'type' => 'text', 'required' => true],
                ['key' => 'sponsorRelationship', 'label' => 'Relationship of sponsor to you', 'type' => 'text', 'required' => true],
                ['key' => 'travellingInGroup', 'label' => 'Are you travelling in a group?', 'type' => 'select', 'required' => true, 'options' => $yesNo],
                ['key' => 'educationLevel', 'label' => 'Education Level', 'type' => 'text', 'required' => true],
                ['key' => 'schoolHistory', 'label' => 'School History', 'type' => 'textarea', 'required' => true],
                ['key' => 'currentEmployment', 'label' => 'Current Employment', 'type' => 'text', 'required' => true],
                ['key' => 'employmentHistory', 'label' => 'Full Employment History', 'type' => 'textarea', 'required' => true],
                ['key' => 'presentIncome', 'label' => 'Present Income', 'type' => 'text', 'required' => true],
                ['key' => 'recentUSPassportPicture', 'label' => 'Do you have a recent (not more than 6 months old) US application passport picture?', 'type' => 'select', 'required' => true, 'options' => $yesNo],
                ['key' => 'securityBackgroundChecks', 'label' => 'Security Background Checks: Have you ever been involved in any form of financial, sexual, violent, cyber crimes?', 'type' => 'select', 'required' => true, 'options' => $yesNo],
            ],
        ],

        'uk-visa-form-filling-assistance' => [
            'submit_label' => 'Submit',
            'fields' => [
                ['key' => 'fullName', 'label' => 'Full Name', 'type' => 'text', 'required' => true, 'core' => 'full_name'],
                ['key' => 'dateOfBirth', 'label' => 'Date of Birth', 'type' => 'date', 'required' => true],
                ['key' => 'placeOfBirth', 'label' => 'Place of Birth (Country)', 'type' => 'text', 'required' => true],
                ['key' => 'nationality', 'label' => 'Nationality', 'type' => 'text', 'required' => true],
                ['key' => 'homeAddress', 'label' => 'Home Address', 'type' => 'textarea', 'required' => true],
                ['key' => 'mailingAddress', 'label' => 'Mailing Address', 'type' => 'textarea', 'required' => true],
                ['key' => 'phoneNumber', 'label' => 'Phone Number', 'type' => 'tel', 'required' => true, 'core' => 'phone'],
                ['key' => 'email', 'label' => 'Email Address', 'type' => 'email', 'required' => true, 'core' => 'email'],
                ['key' => 'nationalIdNumber', 'label' => 'National ID Number', 'type' => 'text', 'required' => true],
                ['key' => 'passportIssuingCityCountry', 'label' => 'Passport Issuing City/Country', 'type' => 'text', 'required' => true],
                ['key' => 'travelDates', 'label' => 'The dates you’re planning to travel to the UK', 'type' => 'text', 'required' => true],
                ['key' => 'stayAddress', 'label' => 'Where you’ll be staying during your visit', 'type' => 'text', 'required' => true],
                ['key' => 'tripCost', 'label' => 'How much you think your trip will cost', 'type' => 'number', 'required' => true],
                ['key' => 'parentsInfo', 'label' => 'Your parents’ names and dates of birth (if known)', 'type' => 'textarea', 'required' => true],
                ['key' => 'annualIncome', 'label' => 'How much you earn in a year (if you have an income)', 'type' => 'number', 'required' => true],
                ['key' => 'offences', 'label' => 'Details of any criminal, civil or immigration offences you may have committed', 'type' => 'textarea', 'required' => true],
                ['key' => 'travelHistory', 'label' => 'Details of your travel history for the past 10 years', 'type' => 'textarea', 'required' => false],
                ['key' => 'employerInfo', 'label' => 'Your employer’s address and telephone number', 'type' => 'textarea', 'required' => false],
                ['key' => 'partnerInfo', 'label' => 'Your partner’s name, date of birth and passport number', 'type' => 'textarea', 'required' => false],
                ['key' => 'tripSponsor', 'label' => 'The name and address of anyone paying for your trip', 'type' => 'textarea', 'required' => false],
                ['key' => 'ukFamily', 'label' => 'The name, address and passport number of any family members you have in the UK', 'type' => 'textarea', 'required' => false],
                ['key' => 'tbTest', 'label' => 'A certificate proving that you’ve had a tuberculosis (TB) test if you’re visiting for more than 6 months', 'type' => 'text', 'required' => false],
            ],
        ],

        'visa-application-fee-payment' => [
            'submit_label' => 'Submit',
            'fields' => [
                ['key' => 'fullName', 'label' => 'Name of Applicant', 'type' => 'text', 'required' => true, 'core' => 'full_name'],
                ['key' => 'dateOfBirth', 'label' => 'Date of Birth', 'type' => 'date', 'required' => true],
                ['key' => 'phone', 'label' => 'Phone', 'type' => 'tel', 'required' => true, 'core' => 'phone'],
                ['key' => 'whatsappNumber', 'label' => 'Whatsapp Number', 'type' => 'tel', 'required' => false, 'help' => 'If not, we will contact you via email'],
                ['key' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true, 'core' => 'email'],
                ['key' => 'countryVisa', 'label' => 'Country Visa', 'type' => 'select', 'required' => true, 'options' => ['US', 'UK', 'Schengen Country']],
            ],
        ],

        'visa-pick-up-service' => [
            'submit_label' => 'Schedule Pick Up',
            'fields' => [
                ['key' => 'fullName', 'label' => 'Name of Applicant', 'type' => 'text', 'required' => true, 'core' => 'full_name'],
                ['key' => 'dateOfBirth', 'label' => 'Date of Birth', 'type' => 'date', 'required' => true],
                ['key' => 'phone', 'label' => 'Phone', 'type' => 'tel', 'required' => true, 'core' => 'phone'],
                ['key' => 'whatsappNumber', 'label' => 'Whatsapp Number', 'type' => 'tel', 'required' => false],
                ['key' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true, 'core' => 'email'],
                ['key' => 'countryVisa', 'label' => 'Country Visa', 'type' => 'select', 'required' => true, 'options' => ['USA', 'UK', 'Schengen Country']],
            ],
        ],

        'counselling-service' => [
            'submit_label' => 'Submit',
            'fields' => [
                ['key' => 'name', 'label' => 'Name of Prospective Student', 'type' => 'text', 'required' => true, 'core' => 'full_name'],
                ['key' => 'email', 'label' => 'Email Address', 'type' => 'email', 'required' => true, 'core' => 'email'],
                ['key' => 'phoneNumber', 'label' => 'Phone number', 'type' => 'tel', 'required' => true, 'core' => 'phone'],
                ['key' => 'whatsappContact', 'label' => 'Whatsapp Contact', 'type' => 'tel', 'required' => false],
                ['key' => 'countriesOfInterest', 'label' => 'Countries of Interest for Study', 'type' => 'text', 'required' => true],
            ],
        ],
    ];
}
