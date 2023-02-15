<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\ConventionMember;
use App\Models\Sponsor;
use App\Models\SponsorExhibitor;

use App\Enum\RoleEnum;
use App\Enum\UserStatusEnum;
use App\Enum\SponsorTypeEnum;

class SponsorExhibitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        $sponsor_exhibitor_details = [
            // Platinum - 8
            ['sponsor' => ['name' => 'GALDERMA', 'email' => 'christine.legaspi@galderma.com', 'type' => SponsorTypeEnum::PLATINUM], // OK
                'exhibitor_emails' => ['cristammy.au@mygalderma.com']],
            ['sponsor' => ['name' => 'NOVARTIS', 'email' => 'lucelle.berdin@novartis.com', 'type' => SponsorTypeEnum::PLATINUM], // OK
                'exhibitor_emails' => ['mark_jayson.malaluan@novartis.com']],
            ['sponsor' => ['name' => 'LEOPHARMA', 'email' => 'JFOPH@leo-pharma.com', 'type' => SponsorTypeEnum::PLATINUM], // OK
                'exhibitor_emails' => ['LEORCD2022@gmail.com']],
            ['sponsor' => ['name' => 'BAYER', 'email' => 'japvendiola@profinsights.biz', 'type' => SponsorTypeEnum::PLATINUM], // OK
                'exhibitor_emails' => ['pmiibayer2022@gmail.com']],
            ['sponsor' => ['name' => 'MENARINI', 'email' => 'annmarie.granados@menariniapac.com', 'type' => SponsorTypeEnum::PLATINUM], // TFU - To follow up
                'exhibitor_emails' => ['menarnini.test@gmail.com', 'jessine.sade@menariniapac.com']],
            ['sponsor' => ['name' => 'SOLTA MEDICAL', 'email' => 'KarinaNieva.Laurena@bausch.com', 'type' => SponsorTypeEnum::PLATINUM], // TFU - To follow up
                'exhibitor_emails' => ['soltemedical.test@gmail.com', 'KarinaNieva.Laurena@bauschcloud.com']],
            
            // Gold - 3
            ['sponsor' => ['name' => 'CREATIVE SKIN', 'email' => 'reginecandice_velez@yahoo.com', 'type' => SponsorTypeEnum::GOLD], // OK
                'exhibitor_emails' => ['candice.csmi@gmail.com']],
            ['sponsor' => ['name' => 'JOHNSON AND JOHNSON', 'email' => 'mangele9@its.jnj.com', 'type' => SponsorTypeEnum::GOLD], // OK
                'exhibitor_emails' => ['hvigo@its.jnj.com']],
            ['sponsor' => ['name' => 'ZUELLIG PHARMA THERAPEUTICS', 'email' => 'mcatis@zuelligpharma.com', 'type' => SponsorTypeEnum::GOLD], // TFU - To follow up
                'exhibitor_emails' => ['zuelig.test@gmail.com']],
            ['sponsor' => ['name' => 'VIATRIS', 'email' => 'viatris.sponsor@gmail.com', 'type' => SponsorTypeEnum::GOLD], // TFU - To follow up
                'exhibitor_emails' => ['viatris.sp_exhibitor@gmail.com']],
            
            ['sponsor' => ['name' => 'DMARK', 'email' => 'cookie.nadal@dmarkmultisales.com', 'type' => SponsorTypeEnum::SILVER], // TFU - To follow up
                'exhibitor_emails' => ['dmark.test@gmail.com']],
            ['sponsor' => ['name' => 'KARIHOME', 'email' => 'jamaica.paz@oepgroup.com', 'type' => SponsorTypeEnum::SILVER],
                'exhibitor_emails' => ['karihome.test@gmail.com']],
            ['sponsor' => ['name' => 'EI SKIN', 'email'=> 'ana.monta@mixexpert.com.ph', 'type' => SponsorTypeEnum::SILVER], // OK
                'exhibitor_emails' => ['anamariapapasinmonta@gmail.com']],
            ['sponsor' => ['name' => 'APOGEE LABORATORIES', 'email'=> 'ss@apogeelaboratories.com', 'type' => SponsorTypeEnum::SILVER], // OK
                'exhibitor_emails' => ['mr1@apogeelaboratories.com']],
            ['sponsor' => ['name' => 'GLENMARK', 'email'=> 'Paolo.Martinez@glenmarkpharma.com', 'type' => SponsorTypeEnum::SILVER], // OK
                'exhibitor_emails' => ['jardyl.vergara@glenmarkpharma.com']],
            ['sponsor' => ['name' => 'KUSUM', 'email'=> 'lhailalyn.dayao@kusum.com.ph', 'type' => SponsorTypeEnum::SILVER], // OK
                'exhibitor_emails' => ['lhailalyndayao.kusum@gmail.com']],
            ['sponsor' => ['name' => 'UNILEVER', 'email'=> 'honeyheart.tejero@sms-philippines.com', 'type' => SponsorTypeEnum::SILVER],  // OK
                'exhibitor_emails' => ['smsdigital.2022@gmail.com']],
            ['sponsor' => ['name' => 'UNILAB', 'email'=> 'cpsantos1@unilab.com.ph', 'type' => SponsorTypeEnum::SILVER], // TFU - To follow up
                'exhibitor_emails' => ['unilab.test@gmail.com']],
            ['sponsor' => ['name' => 'GSK', 'email'=> 'czarina.n.eugenio@gsk.com', 'type' => SponsorTypeEnum::SILVER], // TFU - To follow up
                'exhibitor_emails' => ['gskczarina.test@gmail.com', 'gskczarina.industry@gmail.com']],
            ['sponsor' => ['name' => 'HEALTHSPAN GLOBAL', 'email'=> 'elinor@healthspan.ph', 'type' => SponsorTypeEnum::SILVER], // TFU - To follow up
               'exhibitor_emails' => ['healthspanglobal.test@gmail.com']],

            ['sponsor' => ['name' => 'LS SKIN LAB', 'email'=> 'lanuzacarmelita@yahoo.com', 'type' => SponsorTypeEnum::BRONZE], // OK
                'exhibitor_emails' => ['lsskinlabpharma2022@yahoo.com']],
            ['sponsor' => ['name' => 'ALLERGAN', 'email'=> 'dividina_emily@allergan.com', 'type' => SponsorTypeEnum::BRONZE], // OK
                'exhibitor_emails' => ['dividina_emily@allergan.com']],
            ['sponsor' => ['name' => 'MEGA LIFE', 'email'=> 'grace@megawecare.com', 'type' => SponsorTypeEnum::BRONZE], // OK
                'exhibitor_emails' => ['macphinereyes.megawecare@gmail.com']],
            ['sponsor' => ['name' => 'METRO PHARMA-INNODERM', 'email'=> 'mvmauricio@metropharma.com', 'type' => SponsorTypeEnum::SILVER],
                'exhibitor_emails' => ['metropharmainnoderm.test@gmail.com']],
            ['sponsor' => ['name' => 'CURATIO', 'email'=> 'rosaline.reyes@curatiohealthcare.ph', 'type' => SponsorTypeEnum::BRONZE],
                'exhibitor_emails' => ['curatio.test@gmail.com']],
            ['sponsor' => ['name' => 'SKIN MEDICINE', 'email'=> 'qt.skinmedicineimc@gmail.com', 'type' => SponsorTypeEnum::BRONZE],
                'exhibitor_emails' => ['skinmedicine.test@gmail.com']],
            ['sponsor' => ['name' => 'HOVID', 'email'=> 'kvitug@hovidinc.com.ph', 'type' => SponsorTypeEnum::BRONZE],
                'exhibitor_emails' => ['hovid.test@gmail.com']],
            ['sponsor' => ['name' => 'DREAMAX HEALTHCARE', 'email'=> 'maryannesilava2016@gmail.com', 'type' => SponsorTypeEnum::BRONZE], // OK 
                'exhibitor_emails' => ['shynethb@gmail.com']],
            ['sponsor' => ['name' => 'STADA', 'email'=> 'arlette.fernandez@stada.com.ph', 'type' => SponsorTypeEnum::BRONZE], // OK
                'exhibitor_emails' => ['arlette.fernandez@stada.com.ph']],
            ['sponsor' => ['name' => 'MEDEV MEDICAL DEVICES', 'email'=> 'social.medev@gmail.com', 'type' => SponsorTypeEnum::BRONZE],
                'exhibitor_emails' => ['medev.test@gmail.com']],
            ['sponsor' => ['name' => 'DERMSKIN', 'email'=> 'mhaydmoreno@yahoo.com', 'type' => SponsorTypeEnum::BRONZE], // OK
                'exhibitor_emails' => ['dermskinpharmacy@yahoo.com']],
            ['sponsor' => ['name' => 'EON PHARMATEX, INC.', 'email'=> 'marketing02@eonpharma.com', 'type' => SponsorTypeEnum::BRONZE], // OK
                'exhibitor_emails' => ['marketing01@eonpharma.com']],
            ['sponsor' => ['name' => 'AJ RESEARCH AND PHARMA', 'email'=> 'christopher.ignacio@ajrph.com.ph', 'type' => SponsorTypeEnum::BRONZE],
                'exhibitor_emails' => ['ajresearchandpharma.test@gmail.com']],
            ['sponsor' => ['name' => 'CALEE', 'email'=> 'calee.technomedics@gmail.com', 'type' => SponsorTypeEnum::BRONZE], // OK
                'exhibitor_emails' => ['eileenhoaringo@gmail.com']],
            ['sponsor' => ['name' => 'PHARMA GALENX', 'email'=> 'jjstamaria@maridan.com.ph', 'type' => SponsorTypeEnum::BRONZE], // OK
                'exhibitor_emails' => ['msanigan@maridan.com.ph']],
        ];

        foreach($sponsor_exhibitor_details as $sponsor_exhibitor_detail) {
            // dd($sponsor_exhibitor_detail["exhibitor_emails"]);
            // foreach($sponsor_exhibitor_detail["exhibitor_emails"] as $exhibitor_email) {
            //     echo "$exhibitor_email"." \n";
                // dd($exhibitor_email);
            // }
            try {
                $sponsor = Sponsor::where([
                        ['name', $sponsor_exhibitor_detail["sponsor"]['name']],
                        ['sponsor_type_id', $sponsor_exhibitor_detail["sponsor"]['type']]
                    ])
                    ->whereHas('user', function ($query) use ($sponsor_exhibitor_detail) {
                        $query->where('email', $sponsor_exhibitor_detail["sponsor"]['email']);
                    })
                    ->first();

                if(!is_null($sponsor)) {
                    $sponsor_type = $sponsor->type;
                    foreach($sponsor_exhibitor_detail["exhibitor_emails"] as $exhibitor_email) {
                        $user = User::where('email', $exhibitor_email)->first();
                        $is_new_user = false;
                        if(is_null($user)) {
                            $user = new User();
                            $is_new_user = true;
                        }
                        $user->first_name = $sponsor_exhibitor_detail["sponsor"]['name']." Exhibitor";
                        $user->role = RoleEnum::CONVENTION_MEMBER;
                        $user->email = $exhibitor_email;
                        $user->password = Hash::make(config('settings.DEFAULT_SPONSOR_EXHIBITOR_PASSWORD'));
                        $user->status = UserStatusEnum::REGISTERED;
                        $user_saved = $user->save();

                        $member = $user->member;
                        if(is_null($member)) {
                            $member = new ConventionMember();
                        }
                        $member->user_id = $user->id;
                        $member->is_sponsor_exhibitor = true;
                        $member->save();

                        if($user_saved && $is_new_user) {
                            $sponsor_exhibitor = SponsorExhibitor::where('user_id', $user->id)->where('sponsor_id', $sponsor->id)->first();

                            if(is_null($sponsor_exhibitor)) {
                                $sponsor_exhibitor = new SponsorExhibitor();
                                $sponsor_exhibitor->user_id = $user->id;
                                $sponsor_exhibitor->sponsor_id = $sponsor->id;
                                $sponsor_exhibitor->save();
                            }
                        }
                    }
                }

                DB::commit();
            } catch(Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }
}