<?php

namespace Database\Seeders;

use App\Models\Movie;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        // Töröld a meglévő filmeket (csak ha nincsenek vetítések hozzájuk!)
        Movie::query()->delete();
        
        $movies = [
            [
                'title' => 'Oppenheimer',
                'description' => 'A történelmi dráma J. Robert Oppenheimer fizikus életét meséli el, aki az atombomba kifejlesztésében játszott kulcsszerepet. A film bemutatja a Manhattan Project történetét és az atomkorszak hajnalát.',
                'duration' => 180,
                'genre' => 'Történelmi dráma, Életrajzi',
                'director' => 'Christopher Nolan',
                'age_rating' => 12,
                'poster_url' => 'https://image.tmdb.org/t/p/w500/8Gxv8gSFCU0XGDykEGv7zR1n2ua.jpg',
                'is_active' => true,
            ],
            [
                'title' => 'Barbie',
                'description' => 'Barbie és Ken tökéletes életét felforgatja, amikor ellátogatnak az igazi világba, és rádöbbennek, milyen is az élet a plasztik tökéletességen kívül. Egy vidám és színes kaland az önmegtalálásról.',
                'duration' => 114,
                'genre' => 'Vígjáték, Fantasy',
                'director' => 'Greta Gerwig',
                'age_rating' => 6,
                'poster_url' => 'https://image.tmdb.org/t/p/w500/iuFNMS8U5cb6xfzi51Dbkovj7vM.jpg',
                'is_active' => true,
            ],
            [
                'title' => 'Dűne: Második rész',
                'description' => 'Paul Atreides legendás utazása folytatódik, miközben újra egyesül Chani-val és a Fremenekkel, hogy bosszút álljon azoknak az összeesküvőknek, akik megsemmisítették családját. Epikus sci-fi kaland a sivatagi bolygón.',
                'duration' => 166,
                'genre' => 'Sci-fi, Kaland',
                'director' => 'Denis Villeneuve',
                'age_rating' => 12,
                'poster_url' => 'https://image.tmdb.org/t/p/w500/1pdfLvkbY9ohJlCjQH2CZjjYVvJ.jpg',
                'is_active' => true,
            ],
            [
                'title' => 'Killers of the Flower Moon',
                'description' => 'Az 1920-as évek Oklahomájában az olajban gazdag Osage Nation tagjait célozzák meg gyilkosságok sorozata. Martin Scorsese mestermunkája az amerikiai történelem egyik legsötétebb fejezetéről.',
                'duration' => 206,
                'genre' => 'Krimi, Dráma',
                'director' => 'Martin Scorsese',
                'age_rating' => 16,
                'poster_url' => 'https://image.tmdb.org/t/p/w500/dB6Krk806zeqd0YNp2ngQ9zXteH.jpg',
                'is_active' => true,
            ],
            [
                'title' => 'A vadon hangja',
                'description' => 'Buck, egy háziasított kutya elrabolják kaliforniai otthonából, és az alaszkai vadonba kerül az aranyláz idején. Családi kalandfilm az önmegtalálásról és a természet erejéről.',
                'duration' => 100,
                'genre' => 'Kaland, Családi',
                'director' => 'Chris Sanders',
                'age_rating' => 6,
                'poster_url' => 'https://image.tmdb.org/t/p/w500/33VdppGbeNxICrFUtW2WpGHvfYc.jpg',
                'is_active' => true,
            ],
            [
                'title' => 'Poor Things',
                'description' => 'Bella Baxter története, egy fiatal nőé, akit egy zseniális sebész támaszt fel az életben. Emma Stone Oscar-díjas alakítása egy különleges viktoriánus fantáziában.',
                'duration' => 141,
                'genre' => 'Fantasy, Vígjáték',
                'director' => 'Yorgos Lanthimos',
                'age_rating' => 18,
                'poster_url' => 'https://image.tmdb.org/t/p/w500/kCGlIMHnOm8JPXq3rXM6c5wMxcT.jpg',
                'is_active' => true,
            ],
            [
                'title' => 'Wonka',
                'description' => 'A fiatal Willy Wonka kalandjai, mielőtt megnyitotta volna világhírű csokoládégyárát. Timothy Chalamet főszereplésével készült musical.',
                'duration' => 116,
                'genre' => 'Musical, Fantasy, Családi',
                'director' => 'Paul King',
                'age_rating' => 6,
                'poster_url' => 'https://image.tmdb.org/t/p/w500/qhb1qOilapbapxWQn9jtRCMwXJF.jpg',
                'is_active' => true,
            ],
            [
                'title' => 'The Holdovers',
                'description' => '1970-ben, egy New England-i bentlakásos iskolában egy cinikus tanár kénytelen a karácsonyi szünetben is az iskolában maradni néhány diákkal. Szívmelengető dráma.',
                'duration' => 133,
                'genre' => 'Dráma, Vígjáték',
                'director' => 'Alexander Payne',
                'age_rating' => 12,
                'poster_url' => 'https://image.tmdb.org/t/p/w600_and_h900_face/inQIcvGDpLFp1F0DY1oC0DfSBMf.jpg',
                'is_active' => true,
            ],
            [
                'title' => 'The Zone of Interest',
                'description' => 'A auschwitz-i koncentrációs tábor parancsnokának családja békés életet él egy falon túl, miközben a másik oldalon szörnyűségek történnek. Megdöbbentő történelmi dráma.',
                'duration' => 105,
                'genre' => 'Történelmi dráma, Háborús',
                'director' => 'Jonathan Glazer',
                'age_rating' => 16,
                'poster_url' => 'https://image.tmdb.org/t/p/w600_and_h900_face/jex9yWOSCC5KtACteFpwJdY8elY.jpg',
                'is_active' => true,
            ],
            [
                'title' => 'Kung Fu Panda 4',
                'description' => 'Po újabb kalandja, ahol új ellenféllel kell megküzdenie, miközben új mestert keres, aki átveheti a Sárkány Harcos címet. Családi animációs film.',
                'duration' => 94,
                'genre' => 'Animáció, Akció, Vígjáték',
                'director' => 'Mike Mitchell',
                'age_rating' => 6,
                'poster_url' => 'https://image.tmdb.org/t/p/w500/kDp1vUBnMpe8ak4rjgl3cLELqjU.jpg',
                'is_active' => true,
            ],
            [
                'title' => 'Dűne',
                'description' => 'Paul Atreides története, egy briliáns fiatal férfié, akinek különleges sorsa vár. A család Arrakisra költözik, a legveszélyesebb bolygóra az univerzumban.',
                'duration' => 155,
                'genre' => 'Sci-fi, Kaland',
                'director' => 'Denis Villeneuve',
                'age_rating' => 12,
                'poster_url' => 'https://image.tmdb.org/t/p/w500/d5NXSklXo0qyIYkgV94XAgMIckC.jpg',
                'is_active' => true,
            ],
            [
                'title' => 'A Ház',
                'description' => 'Egy család költözik be álmaik házába, de hamarosan rájönnek, hogy valami sötét és gonosz titok rejtőzik a falak között. Horror thriller.',
                'duration' => 107,
                'genre' => 'Horror, Thriller',
                'director' => 'Lee Cronin',
                'age_rating' => 16,
                'poster_url' => 'https://image.tmdb.org/t/p/w500/5aXp2s4l6g5PcMMesIj63mx8hmJ.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($movies as $movie) {
            Movie::updateOrCreate(
                ['title' => $movie['title']], // Keresési feltétel
                $movie // Frissítendő/létrehozandó adatok
            );
        }
        
        $this->command->info('✅ ' . count($movies) . ' film feltöltve!');
    }
}