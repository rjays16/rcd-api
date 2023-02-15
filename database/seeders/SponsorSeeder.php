<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Sponsor;
use App\Models\ConventionMember;

use App\Enum\RoleEnum;
use App\Enum\UserStatusEnum;
use App\Enum\SponsorTypeEnum;

class SponsorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sponsor_details = [
            ['name' => 'GALDERMA', 'email' => 'christine.legaspi@galderma.com', 'type' => SponsorTypeEnum::PLATINUM, 'rep_name' => 'Christine Legaspi',
                'kuula_iframe' => '<iframe width="100%" height="640" style="width: 100%; height: 640px; border: none; max-width: 100%;" frameborder="0" allowfullscreen allow="xr-spatial-tracking; gyroscope; accelerometer" scrolling="no" src="https://kuula.co/share/collection/7vFd4?logo=-1&info=0&fs=1&vr=1&thumbs=3&inst=0&ovd=virtualmediaevents.viewin360.co"></iframe>',
                'is_lecture_only' => false,
                'has_industry_lecture' => true,
                'slug' => 'galderma'
            ],
            ['name' => 'NOVARTIS', 'email' => 'lucelle.berdin@novartis.com', 'type' => SponsorTypeEnum::PLATINUM, 'rep_name' => 'Lucelle Berdin',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => true,
                'slug' => 'novartis'
            ],
            ['name' => 'LEOPHARMA', 'email' => 'JFOPH@leo-pharma.com', 'type' => SponsorTypeEnum::PLATINUM, 'rep_name' => 'Joane Opulencia',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => true,
                'slug' => 'leopharma'
            ],
            ['name' => 'BAYER', 'email' => 'japvendiola@profinsights.biz', 'type' => SponsorTypeEnum::PLATINUM, 'rep_name' => 'Jam Vendiola',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => true,
                'slug' => 'bayer'
            ],
            ['name' => 'MENARINI', 'email' => 'annmarie.granados@menariniapac.com', 'type' => SponsorTypeEnum::PLATINUM, 'rep_name' => 'Ann Granados',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => true,
                'slug' => 'menarini'
            ],
            ['name' => 'SOLTA MEDICAL', 'email' => 'KarinaNieva.Laurena@bausch.com', 'type' => SponsorTypeEnum::PLATINUM, 'rep_name' => 'Karina Laurena',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => true,
                'slug' => 'solta-medical'
            ],

            ['name' => 'CREATIVE SKIN', 'email' => 'reginecandice_velez@yahoo.com', 'type' => SponsorTypeEnum::GOLD, 'rep_name' => 'Candice Velez',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => true,
                'slug' => 'creative-skin'
            ],
            ['name' => 'JOHNSON AND JOHNSON', 'email' => 'mangele9@its.jnj.com', 'type' => SponsorTypeEnum::GOLD, 'rep_name' => 'Marvin Angeles',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => true,
                'slug' => 'johnson-johnson'
            ],
            ['name' => 'ZUELLIG PHARMA THERAPEUTICS', 'email' => 'mcatis@zuelligpharma.com', 'type' => SponsorTypeEnum::GOLD, 'rep_name' => 'Michelle Catis',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => true,
                'slug' => 'zuellig-pharma'
            ],
            ['name' => 'VIATRIS', 'email' => 'viatris.sponsor@gmail.com', 'type' => SponsorTypeEnum::GOLD, 'rep_name' => 'Viatris',
                'kuula_iframe' => null,
                'is_lecture_only' => true,
                'has_industry_lecture' => true,
                'slug' => 'viatris'
            ],
            
            ['name' => 'DMARK', 'email' => 'cookie.nadal@dmarkmultisales.com', 'type' => SponsorTypeEnum::SILVER, 'rep_name' => 'Cookie Nadal',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'dmark'
            ],
            ['name' => 'KARIHOME', 'email' => 'jamaica.paz@oepgroup.com', 'type' => SponsorTypeEnum::SILVER, 'rep_name' => 'Jamaica Paz',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'karihome'
            ],
            ['name' => 'EI SKIN', 'email'=> 'ana.monta@mixexpert.com.ph', 'type' => SponsorTypeEnum::SILVER, 'rep_name' => 'Ana Maria Monta', # EI SKIN (MIX EXPERT TRADING & SERVICES INC.)
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => true,
                'slug' => 'mix-expert'
            ],
            ['name' => 'APOGEE LABORATORIES', 'email'=> 'ss@apogeelaboratories.com', 'type' => SponsorTypeEnum::SILVER, 'rep_name' => 'Aizza Gimotea',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'apogee-laboratories'
            ],
            ['name' => 'GLENMARK', 'email'=> 'Paolo.Martinez@glenmarkpharma.com', 'type' => SponsorTypeEnum::SILVER, 'rep_name' => 'Paolo Martinez',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'glenmark'
            ],
            ['name' => 'KUSUM', 'email'=> 'lhailalyn.dayao@kusum.com.ph', 'type' => SponsorTypeEnum::SILVER, 'rep_name' => 'Lhailalyn Dayao',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'kusum'
            ],
            ['name' => 'UNILEVER', 'email'=> 'honeyheart.tejero@sms-philippines.com', 'type' => SponsorTypeEnum::SILVER, 'rep_name' => 'Hans Tejero',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'unilever'
            ],
            ['name' => 'UNILAB', 'email'=> 'cpsantos1@unilab.com.ph', 'type' => SponsorTypeEnum::SILVER, 'rep_name' => 'Christopher Alain Santos',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'unilab'
            ],
            ['name' => 'GSK', 'email'=> 'czarina.n.eugenio@gsk.com', 'type' => SponsorTypeEnum::SILVER, 'rep_name' => 'Sarah Eugenio',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => true,
                'slug' => 'gsk'
            ],
            ['name' => 'HEALTHSPAN GLOBAL', 'email'=> 'elinor@healthspan.ph', 'type' => SponsorTypeEnum::SILVER, 'rep_name' => 'Elinor Sta. Clara',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'healthspan-global'
            ],

            ['name' => 'LS SKIN LAB', 'email'=> 'lanuzacarmelita@yahoo.com', 'type' => SponsorTypeEnum::BRONZE, 'rep_name' => 'Carmelita Lanuza',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'ls-skin'
            ],
            ['name' => 'ALLERGAN', 'email'=> 'dividina_emily@allergan.com', 'type' => SponsorTypeEnum::BRONZE, 'rep_name' => 'Emily Dividina',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => true,
                'slug' => 'allergan'
            ],
            ['name' => 'MEGA LIFE', 'email'=> 'grace@megawecare.com', 'type' => SponsorTypeEnum::BRONZE, 'rep_name' => 'Mary Grace Caladiao',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'mega-life'
            ],
            ['name' => 'METRO PHARMA-INNODERM', 'email'=> 'mvmauricio@metropharma.com', 'type' => SponsorTypeEnum::BRONZE, 'rep_name' => 'Maricel Mauricio',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'metro-pharma'
            ],
            ['name' => 'CURATIO', 'email'=> 'rosaline.reyes@curatiohealthcare.ph', 'type' => SponsorTypeEnum::BRONZE, 'rep_name' => 'Elinor Reyes',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'curatio'
            ],
            ['name' => 'SKIN MEDICINE', 'email'=> 'qt.skinmedicineimc@gmail.com', 'type' => SponsorTypeEnum::BRONZE, 'rep_name' => 'Querubin Tolentino',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'skin-medicine'
            ],
            ['name' => 'HOVID', 'email'=> 'kvitug@hovidinc.com.ph', 'type' => SponsorTypeEnum::BRONZE, 'rep_name' => 'Kristel Vitug',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'hovid'
            ],
            ['name' => 'DREAMAX HEALTHCARE', 'email'=> 'maryannesilava2016@gmail.com', 'type' => SponsorTypeEnum::BRONZE, 'rep_name' => 'Mary Silava',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'dreamax-healthcare'
            ],
            ['name' => 'STADA', 'email'=> 'arlette.fernandez@stada.com.ph', 'type' => SponsorTypeEnum::BRONZE, 'rep_name' => 'Arlette Fernandez',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'stada'
            ],
            ['name' => 'MEDEV MEDICAL DEVICES', 'email'=> 'social.medev@gmail.com', 'type' => SponsorTypeEnum::BRONZE, 'rep_name' => 'Sasheen Dawn Salazar',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'medev'
            ],
            ['name' => 'DERMSKIN', 'email'=> 'mhaydmoreno@yahoo.com', 'type' => SponsorTypeEnum::BRONZE, 'rep_name' => 'Mhay Moreno',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'dermskin'
            ],
            ['name' => 'EON PHARMATEX, INC.', 'email'=> 'marketing02@eonpharma.com', 'type' => SponsorTypeEnum::BRONZE, 'rep_name' => 'Roselyn Nieves',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'eon-pharmatex'
            ],
            ['name' => 'AJ RESEARCH AND PHARMA', 'email'=> 'christopher.ignacio@ajrph.com.ph', 'type' => SponsorTypeEnum::BRONZE, 'rep_name' => 'Christopher Ignacio',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'aj-research'
            ],
            ['name' => 'CALEE', 'email'=> 'calee.technomedics@gmail.com', 'type' => SponsorTypeEnum::BRONZE, 'rep_name' => 'Eileen Aringo',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'calee'
            ],
            ['name' => 'PHARMA GALENX', 'email'=> 'jjstamaria@maridan.com.ph', 'type' => SponsorTypeEnum::BRONZE, 'rep_name' => 'Jan Vincent Sta. Maria',
                'kuula_iframe' => null,
                'is_lecture_only' => false,
                'has_industry_lecture' => false,
                'slug' => 'calee'
            ],
        ];

        foreach($sponsor_details as $sponsor_detail) {
            try {
                $user = User::where('email', $sponsor_detail["email"])->first();
                if(is_null($user)) {
                    $user = new User();
                }
                $user->first_name = $sponsor_detail["name"];
                $user->role = RoleEnum::SPONSOR;
                $user->email = $sponsor_detail["email"];
                $user->password = Hash::make(config('settings.DEFAULT_SPONSOR_PASSWORD'));
                $user->status = UserStatusEnum::REGISTERED;
                $user->save();

                $sponsor = Sponsor::where('name', $user->first_name)->first();
                if(is_null($sponsor)) {
                    $sponsor = new Sponsor();
                }
                $sponsor->user_id = $user->id;
                $sponsor->name = $user->first_name;
                $sponsor->company_email = $user->email;
                $sponsor->sponsor_type_id = $sponsor_detail["type"];
                $sponsor->kuula_iframe = $sponsor_detail["kuula_iframe"];
                $sponsor->is_lecture_only = $sponsor_detail["is_lecture_only"];
                $sponsor->has_industry_lecture = $sponsor_detail["has_industry_lecture"];
                $sponsor->slug = $sponsor_detail["slug"];
                $sponsor->save();
                
                $member = $user->member;
                if(is_null($member)) {
                    $member = new ConventionMember();
                }
                $member->user_id = $user->id;
                $member->is_sponsor_exhibitor = true;
                $member->save();

                DB::commit();
            } catch(Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }
}