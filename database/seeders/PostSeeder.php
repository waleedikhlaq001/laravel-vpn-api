<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $posts = [
            [
                
                'user_id' => 1,
                'type' => 'Record',
                'subject' => 'this is subject',
                'status' => 'published',
                'references' => 'TCVD 12.04.2023 198',
                'visibility' => 'public',
            ],
            [
                'user_id' => 1,
                'type' => 'Record',
                'subject' => 'this is subject',
                'status' => 'published',
                'references' => 'ATF 24.02.2023 6B_15/2022',
                'visibility' => 'public',
            ],
            [
                
                'user_id' => 3,
                'type' => 'Record',
                'subject' => 'this is subject',
                'status' => 'published',
                'references' => 'ATF 148 IV 356; ATF 25.08.2022 6B_536/2022',
                'visibility' => 'public',
            ],
            [
                
                'user_id' => 4,
                'type' => 'Memo',
                'subject' => 'this is subject',
                'status' => 'published',
                'references' => 'ATF 02.02.2022 6B_261/2021, c. 2.1.3',
                'visibility' => 'public',
            ],
            [
                
                'user_id' => 1,
                'type' => 'Memo',
                'subject' => 'this is subject',
                'status' => 'published',
                'references' => 'ATF 1B_132/2020',
                'visibility' => 'public',
            ]
        ];
        $translations = [
            1 => [
                'title' => "Droit d'être entendu (art. 29 al. 2 Cst.; art. 107 CPP; art. 3 al. 2 let. c CPP). N'implique pas une obligation préalable pour l'autorité d'interpeller un justiciable avant de statuer sur un quelconque objet.",
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tincidunt porta augue. Fusce ullamcorper justo nisi, vel rutrum elit blandit id. Aliquam ut ornare tortor. Vivamus sem quam, tempor sed dui non, mattis ornare nibh. Fusce justo ligula, tempor in nulla vel, aliquam vehicula turpis. Praesent sit amet tellus eu sapien consequat gravida. Phasellus tempus fermentum velit, non elementum enim sollicitudin eu. Morbi condimentum convallis fringilla. Proin sit amet ornare tortor. Nam arcu eros, rutrum id neque id, tincidunt iaculis orci. Mauris augue est, tempus ac nibh quis, pretium volutpat neque. Duis ipsum tortor, dignissim in iaculis vitae, vehicula sit amet arcu. Vestibulum in nisl quis mauris finibus tempus lacinia vitae sem. Aenean scelerisque enim sed risus elementum, sed convallis ex pellentesque. Aenean nulla risus, pulvinar vel sodales sit amet, vehicula nec augue. Proin tempus libero libero, in porta felis tempus ac.                ',
                'lang' => 'fr',
            ],
            2 => [
                'title' => 'Légitime défense (art. 15 CP). Tir policier. Rappel des conditions et des règles de proportionnalité à appliquer.',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tincidunt porta augue. Fusce ullamcorper justo nisi, vel rutrum elit blandit id. Aliquam ut ornare tortor. Vivamus sem quam, tempor sed dui non, mattis ornare nibh. Fusce justo ligula, tempor in nulla vel, aliquam vehicula turpis. Praesent sit amet tellus eu sapien consequat gravida. Phasellus tempus fermentum velit, non elementum enim sollicitudin eu. Morbi condimentum convallis fringilla. Proin sit amet ornare tortor. Nam arcu eros, rutrum id neque id, tincidunt iaculis orci. Mauris augue est, tempus ac nibh quis, pretium volutpat neque. Duis ipsum tortor, dignissim in iaculis vitae, vehicula sit amet arcu. Vestibulum in nisl quis mauris finibus tempus lacinia vitae sem. Aenean scelerisque enim sed risus elementum, sed convallis ex pellentesque. Aenean nulla risus, pulvinar vel sodales sit amet, vehicula nec augue. Proin tempus libero libero, in porta felis tempus ac.                ',
                'lang' => 'fr',
            ],
            3 => [
                'title' => "Instruction d'office (art. 6 CPP). Etablissement de la situation personnelle (art. 195 al. 2 CPP). Avant de rendre son jugement, un tribunal est tenu d'actualiser d'office l'extrait du casier judiciaire pour tenir compte de la situation pénale actuelle du prévenu.",
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tincidunt porta augue. Fusce ullamcorper justo nisi, vel rutrum elit blandit id. Aliquam ut ornare tortor. Vivamus sem quam, tempor sed dui non, mattis ornare nibh. Fusce justo ligula, tempor in nulla vel, aliquam vehicula turpis. Praesent sit amet tellus eu sapien consequat gravida. Phasellus tempus fermentum velit, non elementum enim sollicitudin eu. Morbi condimentum convallis fringilla. Proin sit amet ornare tortor. Nam arcu eros, rutrum id neque id, tincidunt iaculis orci. Mauris augue est, tempus ac nibh quis, pretium volutpat neque. Duis ipsum tortor, dignissim in iaculis vitae, vehicula sit amet arcu. Vestibulum in nisl quis mauris finibus tempus lacinia vitae sem. Aenean scelerisque enim sed risus elementum, sed convallis ex pellentesque. Aenean nulla risus, pulvinar vel sodales sit amet, vehicula nec augue. Proin tempus libero libero, in porta felis tempus ac.                ',
                'lang' => 'fr',
            ],
            4 => [
                'title' => "Notions d'unité juridique d'actions et d'unité naturelle d'actions. Interprétation restrictive de cette dernière. Rappels.",
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tincidunt porta augue. Fusce ullamcorper justo nisi, vel rutrum elit blandit id. Aliquam ut ornare tortor. Vivamus sem quam, tempor sed dui non, mattis ornare nibh. Fusce justo ligula, tempor in nulla vel, aliquam vehicula turpis. Praesent sit amet tellus eu sapien consequat gravida. Phasellus tempus fermentum velit, non elementum enim sollicitudin eu. Morbi condimentum convallis fringilla. Proin sit amet ornare tortor. Nam arcu eros, rutrum id neque id, tincidunt iaculis orci. Mauris augue est, tempus ac nibh quis, pretium volutpat neque. Duis ipsum tortor, dignissim in iaculis vitae, vehicula sit amet arcu. Vestibulum in nisl quis mauris finibus tempus lacinia vitae sem. Aenean scelerisque enim sed risus elementum, sed convallis ex pellentesque. Aenean nulla risus, pulvinar vel sodales sit amet, vehicula nec augue. Proin tempus libero libero, in porta felis tempus ac.                ',
                'lang' => 'fr',
            ],
            5 => [
                'title' => "Surveillance des frappes par un keylogger - art. 280 CPP applicable et pas 269ter CPP, pour autant que la communication ne soit pas interceptée.",
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tincidunt porta augue. Fusce ullamcorper justo nisi, vel rutrum elit blandit id. Aliquam ut ornare tortor. Vivamus sem quam, tempor sed dui non, mattis ornare nibh. Fusce justo ligula, tempor in nulla vel, aliquam vehicula turpis. Praesent sit amet tellus eu sapien consequat gravida. Phasellus tempus fermentum velit, non elementum enim sollicitudin eu. Morbi condimentum convallis fringilla. Proin sit amet ornare tortor. Nam arcu eros, rutrum id neque id, tincidunt iaculis orci. Mauris augue est, tempus ac nibh quis, pretium volutpat neque. Duis ipsum tortor, dignissim in iaculis vitae, vehicula sit amet arcu. Vestibulum in nisl quis mauris finibus tempus lacinia vitae sem. Aenean scelerisque enim sed risus elementum, sed convallis ex pellentesque. Aenean nulla risus, pulvinar vel sodales sit amet, vehicula nec augue. Proin tempus libero libero, in porta felis tempus ac.                ',
                'lang' => 'fr',
            ]

        ];

        $groups = [
            1 => [1],
            2 => [2],
            3 => [3],
            4 => [4],
            5 => [1, 2]
        ];

        foreach ($posts as $post) {
            $post = Post::create($post);
            // $post->addMediaFromUrl('https://picsum.photos/1200/800')->toMediaCollection('posts');
            $translation = $translations[$post->id];
            $post->translations()->create($translation);
            if ($post->visibility == 'public') {
                $s = $groups[$post->id];
                $post->groups()->attach($s);
            }
        }
    }
}
