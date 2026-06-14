<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\SanghParticipant;
use App\Models\Sangh;
use App\Models\Sponsor;
use App\Models\Stoppage;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name'     => 'Temple Admin',
            'email'    => 'admin@kuldevta.com',
            'password' => Hash::make('admin123'),
            'role'     => 'super_admin',
        ]);

        // Sangh 2025 — create Event first, then Sangh linked to it
        $sanghEvent = Event::create([
            'title_en'       => 'Shri Kuldevta Sangh 2025',
            'title_gu'       => 'શ્રી કુળદેવતા સંઘ ૨૦૨૫',
            'description_en' => 'Annual 35km padyatra from village to Shri Kuldevta Temple.',
            'description_gu' => 'ગ્રામ થી શ્રી કુળદેવતા મંદિર સુધી ૩૫ કિ.મી.ની વાર્ષિક પદ્યયાત્રા.',
            'event_type'     => 'sangh',
            'event_date'     => '2025-11-15',
            'event_time'     => '05:00:00',
            'venue_en'       => 'Village to Temple',
            'venue_gu'       => 'ગ્રામ થી મંદિર',
            'status'         => 'upcoming',
            'is_featured'    => true,
        ]);

        $sangh = Sangh::create([
            'event_id'                => $sanghEvent->id,
            'year'                    => 2025,
            'end_date'                => '2025-11-16',
            'registration_open_from'  => '2025-10-01',
            'registration_open_until' => '2025-11-10',
            'total_distance_km'       => 35,
            'status'                  => 'registration_open',
        ]);

        $stoppageData = [
            ['name_en' => 'Starting Point',     'name_gu' => 'પ્રારંભ સ્થળ',  'km_marker' => 0,  'sort_order' => 1, 'has_water' => true,  'has_food' => false, 'has_tea' => true,  'has_medical' => true,  'has_rest' => false, 'address_en' => 'Village Centre',         'address_gu' => 'ગ્રામ કેન્દ્ર'],
            ['name_en' => 'Rampur Crossroads',  'name_gu' => 'રામપુર ચોક',    'km_marker' => 7,  'sort_order' => 2, 'has_water' => true,  'has_food' => false, 'has_tea' => true,  'has_medical' => false, 'has_rest' => true,  'address_en' => 'Rampur Village',          'address_gu' => 'રામપુર ગામ'],
            ['name_en' => 'Shivpura Rest Area', 'name_gu' => 'શિવપુરા વિરામ', 'km_marker' => 14, 'sort_order' => 3, 'has_water' => true,  'has_food' => true,  'has_tea' => true,  'has_medical' => true,  'has_rest' => true,  'address_en' => 'Shivpura Temple Ground',  'address_gu' => 'શિવપુરા મેદાન'],
            ['name_en' => 'Halfway Point',      'name_gu' => 'અડધું અંતર',     'km_marker' => 18, 'sort_order' => 4, 'has_water' => true,  'has_food' => false, 'has_tea' => true,  'has_medical' => false, 'has_rest' => true,  'address_en' => 'Highway Rest Stop',       'address_gu' => 'હાઇવે'],
            ['name_en' => 'Night Camp',         'name_gu' => 'રાત્રિ શિબિર',   'km_marker' => 22, 'sort_order' => 5, 'has_water' => true,  'has_food' => true,  'has_tea' => true,  'has_medical' => true,  'has_rest' => true,  'address_en' => 'Dharmashala Ground',      'address_gu' => 'ધર્મશાળા'],
            ['name_en' => 'Morning Chai Stop',  'name_gu' => 'સવારની ચા',      'km_marker' => 28, 'sort_order' => 6, 'has_water' => true,  'has_food' => false, 'has_tea' => true,  'has_medical' => false, 'has_rest' => false, 'address_en' => 'Morning stop',            'address_gu' => 'સવારી'],
            ['name_en' => 'Temple Entrance',    'name_gu' => 'મંદિર પ્રવેશ',   'km_marker' => 35, 'sort_order' => 7, 'has_water' => true,  'has_food' => true,  'has_tea' => true,  'has_medical' => true,  'has_rest' => true,  'address_en' => 'Shri Kuldevta Temple',    'address_gu' => 'શ્રી કુળદેવતા મંદિર'],
        ];

        foreach ($stoppageData as $s) {
            Stoppage::create(array_merge($s, ['sangh_id' => $sangh->id]));
        }

        // Sample registrations
        $names = [
            ['Ramesh Patel',   '9876500001', 'confirmed'],
            ['Suresh Shah',    '9876500002', 'confirmed'],
            ['Mahesh Desai',   '9876500003', 'confirmed'],
            ['Dinesh Modi',    '9876500004', 'registered'],
            ['Haresh Joshi',   '9876500005', 'registered'],
            ['Naresh Trivedi', '9876500006', 'registered'],
            ['Bhavesh Bhatt',  '9876500007', 'registered'],
            ['Rajesh Thakkar', '9876500008', 'registered'],
            ['Umesh Amin',     '9876500009', 'registered'],
            ['Nilesh Dave',    '9876500010', 'registered'],
        ];

        foreach ($names as [$name, $mobile, $status]) {
            SanghParticipant::create([
                'sangh_id'                 => $sangh->id,
                'name'                     => $name,
                'mobile'                   => $mobile,
                'village'                  => 'Sample Village',
                'age'                      => rand(20, 60),
                'gender'                   => 'male',
                'emergency_contact_name'   => 'Family Member',
                'emergency_contact_mobile' => '9900000000',
                'registered_by'            => 'admin',
                'status'                   => $status,
                'confirmed_at'             => $status === 'confirmed' ? now() : null,
            ]);
        }

        // Volunteers
        Volunteer::create(['sangh_id' => $sangh->id, 'name' => 'Kantibhai Patel',  'mobile' => '9898001001', 'role' => 'coordinator',       'village_city' => 'Ahmedabad']);
        Volunteer::create(['sangh_id' => $sangh->id, 'name' => 'Bharatbhai Shah',  'mobile' => '9898001002', 'role' => 'registration_desk', 'village_city' => 'Surat']);
        Volunteer::create(['sangh_id' => $sangh->id, 'name' => 'Dr. Manish Desai', 'mobile' => '9898001003', 'role' => 'medical',            'village_city' => 'Vadodara']);

        // Other events (non-sangh)
        Event::create(['title_en' => 'Monthly Havan - November 2025', 'title_gu' => 'માસિક હવન - નવેમ્બર ૨૦૨૫', 'event_type' => 'monthly_havan', 'event_date' => '2025-11-01', 'event_time' => '07:00:00', 'venue_en' => 'Temple Hall',    'venue_gu' => 'મંદિર હૉલ',   'status' => 'upcoming', 'is_featured' => false, 'description_en' => null, 'description_gu' => null]);
        Event::create(['title_en' => 'Navratri Mahotsav 2025',        'title_gu' => 'નવરાત્રી મહોત્સવ ૨૦૨૫',   'event_type' => 'special',        'event_date' => '2025-10-02', 'event_time' => '18:00:00', 'venue_en' => 'Temple Ground', 'venue_gu' => 'મંદિર મેદાન', 'status' => 'upcoming', 'is_featured' => true,  'description_en' => null, 'description_gu' => null]);
        Event::create(['title_en' => 'Diwali Havan 2025',             'title_gu' => 'દિવાળી હવન ૨૦૨૫',         'event_type' => 'havan',          'event_date' => '2025-10-20', 'event_time' => '08:00:00', 'venue_en' => 'Temple Hall',    'venue_gu' => 'મંદિર હૉલ',   'status' => 'upcoming', 'is_featured' => true,  'description_en' => null, 'description_gu' => null]);

        // Sponsors
        Sponsor::create(['sponsorable_type' => Sangh::class, 'sponsorable_id' => $sangh->id, 'name' => 'Patel Enterprises', 'mobile' => '9876543210', 'village_city' => 'Ahmedabad', 'amount' => 51000, 'sponsor_type' => 'main',   'description_en' => 'Main Sponsor 2025', 'description_gu' => 'મુખ્ય પ્રાયોજક ૨૦૨૫']);
        Sponsor::create(['sponsorable_type' => Sangh::class, 'sponsorable_id' => $sangh->id, 'name' => 'Shah Brothers',     'mobile' => '9876543211', 'village_city' => 'Surat',     'amount' => 21000, 'sponsor_type' => 'gold',   'description_en' => null,                'description_gu' => null]);
        Sponsor::create(['sponsorable_type' => Sangh::class, 'sponsorable_id' => $sangh->id, 'name' => 'Modi Traders',      'mobile' => '9876543212', 'village_city' => 'Rajkot',    'amount' => 11000, 'sponsor_type' => 'silver', 'description_en' => null,                'description_gu' => null]);
    }
}
