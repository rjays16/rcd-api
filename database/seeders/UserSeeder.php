<?php
use Illuminate\Database\Seeder;
use App\Enum\RoleEnum;
use App\Enum\UserStatusEnum;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->updateOrInsert([
            'id' => 1
        ], [
            'first_name' => 'Admin',
            'email' => 'rcdsupp2022@gmail.com',
            'password' => app('hash')->make(config('settings.DEFAULT_ADMIN_PASSWORD')),
            'role' => RoleEnum::ADMIN,
        ]);

        DB::table('users')->updateOrInsert([
            'id' => 2
        ], [
            'first_name' => 'Admin',
            'last_name' => 'Dave',
            'email' => 'dave.c@rightteamprovider.com',
            'password' => app('hash')->make(config('settings.DEFAULT_ADMIN_PASSWORD')),
            'role' => RoleEnum::SUPER_ADMIN
        ]);

        DB::table('users')->updateOrInsert([
            'id' => 3
        ], [
            'first_name' => 'Admin',
            'last_name' => 'John',
            'email' => 'john.r@rightteamprovider.com',
            'password' => app('hash')->make(config('settings.DEFAULT_ADMIN_PASSWORD')),
            'role' => RoleEnum::SUPER_ADMIN
        ]);

        DB::table('users')->updateOrInsert([
            'id' => 4
        ], [
            'first_name' => 'Admin',
            'email' => 'samuel.s@rightteamprovider.com',
            'password' => app('hash')->make(config('settings.DEFAULT_ADMIN_PASSWORD')),
            'role' => RoleEnum::SUPER_ADMIN
        ]);

        // DB::table('users')->updateOrInsert([
        //     'id' => 5
        // ], [
        //     'first_name' => 'Zharlah',
        //     'last_name' => 'Flores',
        //     'email' => 'zharlmd@gmail.com',
        //     'password' => app('hash')->make(config('settings.DEFAULT_ADMIN_PASSWORD')),
        //     'role' => RoleEnum::ADMIN
        // ]);

        // DB::table('users')->updateOrInsert([
        //     'id' => 6
        // ], [
        //     'first_name' => 'Janelle Geronimo',
        //     'last_name' => 'Go',
        //     'email' => 'janellegomd@gmail.com',
        //     'password' => app('hash')->make(config('settings.DEFAULT_ADMIN_PASSWORD')),
        //     'role' => RoleEnum::ADMIN
        // ]);

        // DB::table('users')->updateOrInsert([
        //     'id' => 7
        // ], [
        //     'first_name' => 'Riza',
        //     'last_name' => 'Milante',
        //     'email' => 'rizaskinmd@gmail.com',
        //     'password' => app('hash')->make(config('settings.DEFAULT_ADMIN_PASSWORD')),
        //     'role' => RoleEnum::ADMIN
        // ]);

        // DB::table('users')->updateOrInsert([
        //     'id' => 8
        // ], [
        //     'first_name' => 'Marise',
        //     'last_name' => 'Abejo',
        //     'email' => 'marise327md@gmail.com',
        //     'password' => app('hash')->make(config('settings.DEFAULT_ADMIN_PASSWORD')),
        //     'role' => RoleEnum::ADMIN
        // ]);

        // DB::table('users')->updateOrInsert([ # INTERNATIONAL_LADS
        //     'id' => 5
        // ], [
        //     'first_name' => 'Arnold',
        //     'last_name' => 'Delegate',
        //     'middle_name' => 'D.',
        //     'country' => 'Philippines',
        //     'certificate_name' => 'Arnold D. Delegate',
        //     'email' => 'ideahubtester02020@gmail.com',
        //     'password' => app('hash')->make(config('settings.DEFAULT_MEMBER_PASSWORD')),
        //     'role' => RoleEnum::CONVENTION_MEMBER,
        //     'status' => UserStatusEnum::IMPORTED_PENDING
        // ]);

        // DB::table('users')->updateOrInsert([ # INTERNATIONAL_NON_LADS
        //     'id' => 6
        // ], [
        //     'first_name' => 'Beth',
        //     'last_name' => 'Delegate',
        //     'middle_name' => 'D.',
        //     'country' => 'Philippines',
        //     'certificate_name' => 'Beth D. Delegate',
        //     'email' => 'ideahubtester12020@gmail.com',
        //     'password' => app('hash')->make(config('settings.DEFAULT_MEMBER_PASSWORD')),
        //     'role' => RoleEnum::CONVENTION_MEMBER,
        //     'status' => UserStatusEnum::IMPORTED_PENDING
        // ]);

        // DB::table('users')->updateOrInsert([ # INTERNATIONAL_RESIDENT
        //     'id' => 7
        // ], [
        //     'first_name' => 'Carl',
        //     'last_name' => 'Delegate',
        //     'middle_name' => 'D.',
        //     'country' => 'Philippines',
        //     'certificate_name' => 'Carl D. Delegate',
        //     'email' => 'ideahubtester22020@gmail.com',
        //     'password' => app('hash')->make(config('settings.DEFAULT_MEMBER_PASSWORD')),
        //     'role' => RoleEnum::CONVENTION_MEMBER,
        //     'status' => UserStatusEnum::IMPORTED_PENDING
        // ]);

        // DB::table('users')->updateOrInsert([ # LOCAL_PDS_MEMBER, with good standing
        //     'id' => 8
        // ], [
        //     'first_name' => 'Dustin',
        //     'last_name' => 'Delegate',
        //     'middle_name' => 'D.',
        //     'country' => 'Philippines',
        //     'certificate_name' => 'Dustin D. Delegate',
        //     'email' => 'ideahubtester32020@gmail.com',
        //     'password' => app('hash')->make(config('settings.DEFAULT_MEMBER_PASSWORD')),
        //     'role' => RoleEnum::CONVENTION_MEMBER,
        //     'status' => UserStatusEnum::IMPORTED_PENDING
        // ]);

        // DB::table('users')->updateOrInsert([ # LOCAL_PDS_MEMBER, not in good standing
        //     'id' => 9
        // ], [
        //     'first_name' => 'Ella',
        //     'last_name' => 'Delegate',
        //     'middle_name' => 'D.',
        //     'country' => 'Philippines',
        //     'certificate_name' => 'Ella D. Delegate',
        //     'email' => 'ideahubtester42020@gmail.com',
        //     'password' => app('hash')->make(config('settings.DEFAULT_MEMBER_PASSWORD')),
        //     'role' => RoleEnum::CONVENTION_MEMBER,
        //     'status' => UserStatusEnum::IMPORTED_PENDING
        // ]);

        // DB::table('users')->updateOrInsert([ # LOCAL_PDS_RESIDENT
        //     'id' => 10
        // ], [
        //     'first_name' => 'Filo',
        //     'last_name' => 'Delegate',
        //     'middle_name' => 'D.',
        //     'country' => 'Philippines',
        //     'certificate_name' => 'Filo D. Delegate',
        //     'email' => 'ideahubtester52020@gmail.com',
        //     'password' => app('hash')->make(config('settings.DEFAULT_MEMBER_PASSWORD')),
        //     'role' => RoleEnum::CONVENTION_MEMBER,
        //     'status' => UserStatusEnum::IMPORTED_PENDING
        // ]);

        // DB::table('users')->updateOrInsert([ # LOCAL_NON_PDS_MD
        //     'id' => 11
        // ], [
        //     'first_name' => 'Glen',
        //     'last_name' => 'Delegate',
        //     'middle_name' => 'D.',
        //     'country' => 'Philippines',
        //     'certificate_name' => 'Glen D. Delegate',
        //     'email' => 'ideahubtester62020@gmail.com',
        //     'password' => app('hash')->make(config('settings.DEFAULT_MEMBER_PASSWORD')),
        //     'role' => RoleEnum::CONVENTION_MEMBER,
        //     'status' => UserStatusEnum::IMPORTED_PENDING
        // ]);

        // DB::table('users')->updateOrInsert([ # LOCAL_NON_PDS_RESIDENT_OF_APPLICANTS_INSTITUTIONS
        //     'id' => 12
        // ], [
        //     'first_name' => 'Han',
        //     'last_name' => 'Delegate',
        //     'middle_name' => 'D.',
        //     'country' => 'Philippines',
        //     'certificate_name' => 'Han D. Delegate',
        //     'email' => 'ideahubtester72020@gmail.com',
        //     'password' => app('hash')->make(config('settings.DEFAULT_MEMBER_PASSWORD')),
        //     'role' => RoleEnum::CONVENTION_MEMBER,
        //     'status' => UserStatusEnum::IMPORTED_PENDING
        // ]);

        // DB::table('users')->updateOrInsert([ # SPEAKER
        //     'id' => 13
        // ], [
        //     'first_name' => 'Ian',
        //     'last_name' => 'Lads',
        //     'middle_name' => 'S.',
        //     'country' => 'Cambodia',
        //     'certificate_name' => 'Ian S. Lads',
        //     'email' => 'ideahubtester82020@gmail.com',
        //     'password' => app('hash')->make(config('settings.DEFAULT_MEMBER_PASSWORD')),
        //     'role' => RoleEnum::CONVENTION_MEMBER,
        //     'status' => UserStatusEnum::IMPORTED_PENDING
        // ]);

        // DB::table('users')->updateOrInsert([ # SPEAKER
        //     'id' => 14
        // ], [
        //     'first_name' => 'John',
        //     'last_name' => 'Speaker',
        //     'middle_name' => 'S.',
        //     'country' => 'Philippines',
        //     'certificate_name' => 'John S. Speaker',
        //     'email' => 'ideahubtester92020@gmail.com',
        //     'password' => app('hash')->make(config('settings.DEFAULT_MEMBER_PASSWORD')),
        //     'role' => RoleEnum::CONVENTION_MEMBER,
        //     'status' => UserStatusEnum::IMPORTED_PENDING
        // ]);

        // DB::table('users')->updateOrInsert([ # DELEGATE
        //     'id' => 15
        // ], [
        //     'first_name' => 'Karlo',
        //     'last_name' => 'Delegate',
        //     'middle_name' => 'D.',
        //     'country' => 'Philippines',
        //     'certificate_name' => 'Karlo D. Delegate',
        //     'email' => 'ideahubtester102020@gmail.com',
        //     'password' => app('hash')->make(config('settings.DEFAULT_MEMBER_PASSWORD')),
        //     'role' => RoleEnum::CONVENTION_MEMBER,
        //     'status' => UserStatusEnum::IMPORTED_PENDING
        // ]);

        // DB::table('users')->updateOrInsert([ # DELEGATE
        //     'id' => 16
        // ], [
        //     'first_name' => 'Leila',
        //     'last_name' => 'Speaker',
        //     'middle_name' => 'S.',
        //     'country' => 'Philippines',
        //     'certificate_name' => 'Leila S. Speaker',
        //     'email' => 'ideahubtester112020@gmail.com',
        //     'password' => app('hash')->make(config('settings.DEFAULT_MEMBER_PASSWORD')),
        //     'role' => RoleEnum::CONVENTION_MEMBER,
        //     'status' => UserStatusEnum::IMPORTED_PENDING
        // ]);

        // DB::table('users')->updateOrInsert([
        //     'id' => 17
        // ], [
        //     'first_name' => 'Test',
            
        //     'last_name' => 'Sponsor',
        //     'email' => 'kurtdoe444@gmail.com',
        //     'password' => app('hash')->make(config('settings.DEFAULT_SPONSOR_PASSWORD')),
        //     'role' => RoleEnum::SPONSOR,
        //     'status' => UserStatusEnum::IMPORTED_PENDING
        // ]);
    }
}