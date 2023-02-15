<?php

use Illuminate\Database\Seeder;

use App\Models\PlenaryEvent;

class PlenaryEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plenary_events = [
            // Start of Day 1
                ['date' => '2022-10-26', 'title' => 'Opening Ceremonies', 'speaker_description' => 'AVP PRESENTATION',
                    'starts_at' => '8:00', 'ends_at' => '9:00'],
                ['date' => '2022-10-26', 'title' => 'Mixed Reality Technology in Procedural Dermatology', 'speaker_description' => 'Rungsima Wanitphakdeedecha, MD',
                    'starts_at' => '9:00', 'ends_at' => '9:20'],
                ['date' => '2022-10-26', 'title' => 'Dermatosurgery - What to do, When to do it, How I do it', 'speaker_description' => 'Prof. Kee Yang Chung, MD',
                    'starts_at' => '9:20', 'ends_at' => '9:40'],
                ['date' => '2022-10-26', 'title' => 'Hormonal Therapy: The Missing Link in Acne Management', 'speaker_description' => 'Rachel Reynolds, MD',
                    'starts_at' => '9:40', 'ends_at' => '10:00'],
                ['date' => '2022-10-26', 'title' => 'Open forum', 'speaker_description' => 'RCD 2022 Moderators',
                    'starts_at' => '10:00', 'ends_at' => '10:15'],
                ['date' => '2022-10-26', 'title' => 'Urticaria - The Best Aproach For difficult cases', 'speaker_description' => 'Prof. Torsten Zuberbier, MD',
                    'starts_at' => '10:15', 'ends_at' => '10:35'],
                ['date' => '2022-10-26', 'title' => 'Functional Medicine - Strategies in Skin Care', 'speaker_description' => 'Cesar Holgado, MD',
                    'starts_at' => '10:35', 'ends_at' => '10:55'],
                ['date' => '2022-10-26', 'title' => 'Open Forum', 'speaker_description' => 'RCD 2022 Moderators',
                    'starts_at' => '10:55', 'ends_at' => '11:10'],
                ['date' => '2022-10-26', 'title' => 'Industry Sponsored Symposium 1', 'speaker_description' => 'Galderma',
                    'starts_at' => '11:15', 'ends_at' => '12:00'],
                // ['date' => '2022-10-26', 'title' => 'Reconnaissance', 'speaker_description' => '',
                //     'starts_at' => '8:00', 'ends_at' => '12:00'],
            // End of Day 1

            // Start of Day 2
                ['date' => '2022-10-27', 'title' => 'Addressing lasers and EBD complications', 'speaker_description' => 'Prof. Goh Chee Leok, MD',
                    'starts_at' => '9:00', 'ends_at' => '9:20'],
                ['date' => '2022-10-27', 'title' => 'Facing Pigmentary disorders - the past, the present, the future', 'speaker_description' => 'Prof. Rashmi Sarkar, MD',
                    'starts_at' => '9:20', 'ends_at' => '9:40'],
                ['date' => '2022-10-27', 'title' => 'Mycology Evolution from RCD 1992 to RCD 2022 and beyond', 'speaker_description' => 'Rataporn Ungpakorn, MD',
                    'starts_at' => '9:40', 'ends_at' => '10:00'],
                ['date' => '2022-10-27', 'title' => 'Open Forum', 'speaker_description' => 'RCD 2022 Moderators',
                    'starts_at' => '10:00', 'ends_at' => '10:15'],
                ['date' => '2022-10-27', 'title' => "What's New in Hair Disorder", 'speaker_description' => 'Prof. Antonella Tosti, MD',
                    'starts_at' => '10:15', 'ends_at' => '10:35'],
                ['date' => '2022-10-27', 'title' => 'The Delicate Art of chemical peeling - the essence of brightening and lightening', 'speaker_description' => 'Philippe Deprez, MD',
                    'starts_at' => '10:35', 'ends_at' => '10:55'],
                ['date' => '2022-10-27', 'title' => 'Open Forum', 'speaker_description' => 'RCD 2022 Moderators',
                    'starts_at' => '10:55', 'ends_at' => '11:10'],
                ['date' => '2022-10-27', 'title' => 'Turnover Ceremonies', 'speaker_description' => 'RCD 2022 organising committee',
                    'starts_at' => '11:10', 'ends_at' => '11:40'],
                ['date' => '2022-10-27', 'title' => 'Industry Sponsored Symposium 3', 'speaker_description' => 'Bayer',
                    'starts_at' => '11:15', 'ends_at' => '12:00'],
                ['date' => '2022-10-27', 'title' => 'Industry Sponsored Symposium 4', 'speaker_description' => 'Leopharma',
                    'starts_at' => '12:00', 'ends_at' => '12:45'],
                // ['date' => '2022-10-27', 'title' => 'Controversies', 'speaker_description' => '',
                //     'starts_at' => '8:00', 'ends_at' => '12:00'],
            // End of Day 2

            // Start of Day 3
                ['date' => '2022-10-28', 'title' => 'Psoriasis = Difficulty, Resiliency = How I deal with it', 'speaker_description' => 'Prof. Pravit Asawanonda, MD',
                    'starts_at' => '9:00', 'ends_at' => '9:20'],
                ['date' => '2022-10-28', 'title' => 'Pearls and pitfalls in pediatric dermatology - ADRs in children', 'speaker_description' => 'Srie Prihianti, MD',
                    'starts_at' => '9:20', 'ends_at' => '9:40'],
                ['date' => '2022-10-28', 'title' => 'Genodermatoses 101 = making things simple', 'speaker_description' => 'Prof. John McGrath, MD',
                    'starts_at' => '9:40', 'ends_at' => '10:00'],
                ['date' => '2022-10-28', 'title' => 'Open Forum', 'speaker_description' => 'RCD 2022 Moderators',
                    'starts_at' => '10:00', 'ends_at' => '10:15'],
                ['date' => '2022-10-28', 'title' => 'Dermatologic presentation of COVID-19 in Pediatrics', 'speaker_description' => 'Benjamin Co, MD',
                    'starts_at' => '10:15', 'ends_at' => '10:35'],
                ['date' => '2022-10-28', 'title' => 'Different Faces of HIV: Factoring In Age Sex and Socio-demographic Patterns', 'speaker_description' => 'Junice Melgar , MD',
                    'starts_at' => '10:35', 'ends_at' => '10:55'],
                ['date' => '2022-10-28', 'title' => 'Vaccines for all intents and purposes - Where are we now ?', 'speaker_description' => 'Rontgene Solante, MD',
                    'starts_at' => '10:55', 'ends_at' => '11:15'],
                ['date' => '2022-10-28', 'title' => 'Open Forum', 'speaker_description' => 'RCD 2022 Moderators',
                    'starts_at' => '11:15', 'ends_at' => '11:30'],
                ['date' => '2022-10-28', 'title' => 'Industry Sponsored Symposium 1', 'speaker_description' => 'Menarini',
                    'starts_at' => '11:30', 'ends_at' => '12:15'],
                ['date' => '2022-10-28', 'title' => 'Industry Sponsored Symposium 2', 'speaker_description' => 'Solta Medical',
                    'starts_at' => '12:15', 'ends_at' => '13:00'],
                // ['date' => '2022-10-28', 'title' => 'Dialogues in Dermatology', 'speaker_description' => '',
                //     'starts_at' => '8:00', 'ends_at' => '12:00'],
            // End of Day 3
        ];

        foreach($plenary_events as $plenary_event) {
            try {
                $event = PlenaryEvent::where([
                        ['date', $plenary_event['date']],
                        ['title', $plenary_event['title']],
                        ['starts_at', $plenary_event['starts_at']],
                        ['ends_at', $plenary_event['ends_at']]
                    ])
                    ->first();

                if(is_null($event)) {
                    PlenaryEvent::create($plenary_event);
                } else {
                    $event->update($plenary_event);
                }

                DB::commit();
            } catch(Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }
}