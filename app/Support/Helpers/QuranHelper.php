<?php

namespace App\Support\Helpers;

class QuranHelper
{
    /**
     * Data 114 Surah Al-Qur'an
     * Format: [number, name_latin, name_arabic, total_ayat, revelation_type, juz_start]
     */
    protected static array $surahs = [
        1 => ['number' => 1, 'name_latin' => 'Al-Fatihah', 'name_arabic' => 'الفاتحة', 'total_ayat' => 7, 'revelation' => 'Makkiyah', 'juz_start' => 1],
        2 => ['number' => 2, 'name_latin' => 'Al-Baqarah', 'name_arabic' => 'البقرة', 'total_ayat' => 286, 'revelation' => 'Madaniyah', 'juz_start' => 1],
        3 => ['number' => 3, 'name_latin' => 'Ali \'Imran', 'name_arabic' => 'آل عمران', 'total_ayat' => 200, 'revelation' => 'Madaniyah', 'juz_start' => 3],
        4 => ['number' => 4, 'name_latin' => 'An-Nisa\'', 'name_arabic' => 'النساء', 'total_ayat' => 176, 'revelation' => 'Madaniyah', 'juz_start' => 4],
        5 => ['number' => 5, 'name_latin' => 'Al-Ma\'idah', 'name_arabic' => 'المائدة', 'total_ayat' => 120, 'revelation' => 'Madaniyah', 'juz_start' => 6],
        6 => ['number' => 6, 'name_latin' => 'Al-An\'am', 'name_arabic' => 'الأنعام', 'total_ayat' => 165, 'revelation' => 'Makkiyah', 'juz_start' => 7],
        7 => ['number' => 7, 'name_latin' => 'Al-A\'raf', 'name_arabic' => 'الأعراف', 'total_ayat' => 206, 'revelation' => 'Makkiyah', 'juz_start' => 8],
        8 => ['number' => 8, 'name_latin' => 'Al-Anfal', 'name_arabic' => 'الأنفال', 'total_ayat' => 75, 'revelation' => 'Madaniyah', 'juz_start' => 9],
        9 => ['number' => 9, 'name_latin' => 'At-Taubah', 'name_arabic' => 'التوبة', 'total_ayat' => 129, 'revelation' => 'Madaniyah', 'juz_start' => 10],
        10 => ['number' => 10, 'name_latin' => 'Yunus', 'name_arabic' => 'يونس', 'total_ayat' => 109, 'revelation' => 'Makkiyah', 'juz_start' => 11],
        11 => ['number' => 11, 'name_latin' => 'Hud', 'name_arabic' => 'هود', 'total_ayat' => 123, 'revelation' => 'Makkiyah', 'juz_start' => 11],
        12 => ['number' => 12, 'name_latin' => 'Yusuf', 'name_arabic' => 'يوسف', 'total_ayat' => 111, 'revelation' => 'Makkiyah', 'juz_start' => 12],
        13 => ['number' => 13, 'name_latin' => 'Ar-Ra\'d', 'name_arabic' => 'الرعد', 'total_ayat' => 43, 'revelation' => 'Madaniyah', 'juz_start' => 13],
        14 => ['number' => 14, 'name_latin' => 'Ibrahim', 'name_arabic' => 'ابراهيم', 'total_ayat' => 52, 'revelation' => 'Makkiyah', 'juz_start' => 13],
        15 => ['number' => 15, 'name_latin' => 'Al-Hijr', 'name_arabic' => 'الحجر', 'total_ayat' => 99, 'revelation' => 'Makkiyah', 'juz_start' => 14],
        16 => ['number' => 16, 'name_latin' => 'An-Nahl', 'name_arabic' => 'النحل', 'total_ayat' => 128, 'revelation' => 'Makkiyah', 'juz_start' => 14],
        17 => ['number' => 17, 'name_latin' => 'Al-Isra\'', 'name_arabic' => 'الإسراء', 'total_ayat' => 111, 'revelation' => 'Makkiyah', 'juz_start' => 15],
        18 => ['number' => 18, 'name_latin' => 'Al-Kahf', 'name_arabic' => 'الكهف', 'total_ayat' => 110, 'revelation' => 'Makkiyah', 'juz_start' => 15],
        19 => ['number' => 19, 'name_latin' => 'Maryam', 'name_arabic' => 'مريم', 'total_ayat' => 98, 'revelation' => 'Makkiyah', 'juz_start' => 16],
        20 => ['number' => 20, 'name_latin' => 'Taha', 'name_arabic' => 'طه', 'total_ayat' => 135, 'revelation' => 'Makkiyah', 'juz_start' => 16],
        21 => ['number' => 21, 'name_latin' => 'Al-Anbiya\'', 'name_arabic' => 'الأنبياء', 'total_ayat' => 112, 'revelation' => 'Makkiyah', 'juz_start' => 17],
        22 => ['number' => 22, 'name_latin' => 'Al-Hajj', 'name_arabic' => 'الحج', 'total_ayat' => 78, 'revelation' => 'Madaniyah', 'juz_start' => 17],
        23 => ['number' => 23, 'name_latin' => 'Al-Mu\'minun', 'name_arabic' => 'المؤمنون', 'total_ayat' => 118, 'revelation' => 'Makkiyah', 'juz_start' => 18],
        24 => ['number' => 24, 'name_latin' => 'An-Nur', 'name_arabic' => 'النور', 'total_ayat' => 64, 'revelation' => 'Madaniyah', 'juz_start' => 18],
        25 => ['number' => 25, 'name_latin' => 'Al-Furqan', 'name_arabic' => 'الفرقان', 'total_ayat' => 77, 'revelation' => 'Makkiyah', 'juz_start' => 18],
        26 => ['number' => 26, 'name_latin' => 'Ash-Shu\'ara\'', 'name_arabic' => 'الشعراء', 'total_ayat' => 227, 'revelation' => 'Makkiyah', 'juz_start' => 19],
        27 => ['number' => 27, 'name_latin' => 'An-Naml', 'name_arabic' => 'النمل', 'total_ayat' => 93, 'revelation' => 'Makkiyah', 'juz_start' => 19],
        28 => ['number' => 28, 'name_latin' => 'Al-Qasas', 'name_arabic' => 'القصص', 'total_ayat' => 88, 'revelation' => 'Makkiyah', 'juz_start' => 20],
        29 => ['number' => 29, 'name_latin' => 'Al-\'Ankabut', 'name_arabic' => 'العنكبوت', 'total_ayat' => 69, 'revelation' => 'Makkiyah', 'juz_start' => 20],
        30 => ['number' => 30, 'name_latin' => 'Ar-Rum', 'name_arabic' => 'الروم', 'total_ayat' => 60, 'revelation' => 'Makkiyah', 'juz_start' => 21],
        31 => ['number' => 31, 'name_latin' => 'Luqman', 'name_arabic' => 'لقمان', 'total_ayat' => 34, 'revelation' => 'Makkiyah', 'juz_start' => 21],
        32 => ['number' => 32, 'name_latin' => 'As-Sajdah', 'name_arabic' => 'السجدة', 'total_ayat' => 30, 'revelation' => 'Makkiyah', 'juz_start' => 21],
        33 => ['number' => 33, 'name_latin' => 'Al-Ahzab', 'name_arabic' => 'الأحزاب', 'total_ayat' => 73, 'revelation' => 'Madaniyah', 'juz_start' => 21],
        34 => ['number' => 34, 'name_latin' => 'Saba\'', 'name_arabic' => 'سبإ', 'total_ayat' => 54, 'revelation' => 'Makkiyah', 'juz_start' => 22],
        35 => ['number' => 35, 'name_latin' => 'Fatir', 'name_arabic' => 'فاطر', 'total_ayat' => 45, 'revelation' => 'Makkiyah', 'juz_start' => 22],
        36 => ['number' => 36, 'name_latin' => 'Yasin', 'name_arabic' => 'يس', 'total_ayat' => 83, 'revelation' => 'Makkiyah', 'juz_start' => 22],
        37 => ['number' => 37, 'name_latin' => 'As-Saffat', 'name_arabic' => 'الصافات', 'total_ayat' => 182, 'revelation' => 'Makkiyah', 'juz_start' => 23],
        38 => ['number' => 38, 'name_latin' => 'Sad', 'name_arabic' => 'ص', 'total_ayat' => 88, 'revelation' => 'Makkiyah', 'juz_start' => 23],
        39 => ['number' => 39, 'name_latin' => 'Az-Zumar', 'name_arabic' => 'الزمر', 'total_ayat' => 75, 'revelation' => 'Makkiyah', 'juz_start' => 23],
        40 => ['number' => 40, 'name_latin' => 'Ghafir', 'name_arabic' => 'غافر', 'total_ayat' => 85, 'revelation' => 'Makkiyah', 'juz_start' => 24],
        41 => ['number' => 41, 'name_latin' => 'Fussilat', 'name_arabic' => 'فصلت', 'total_ayat' => 54, 'revelation' => 'Makkiyah', 'juz_start' => 24],
        42 => ['number' => 42, 'name_latin' => 'Ash-Shura', 'name_arabic' => 'الشورى', 'total_ayat' => 53, 'revelation' => 'Makkiyah', 'juz_start' => 25],
        43 => ['number' => 43, 'name_latin' => 'Az-Zukhruf', 'name_arabic' => 'الزخرف', 'total_ayat' => 89, 'revelation' => 'Makkiyah', 'juz_start' => 25],
        44 => ['number' => 44, 'name_latin' => 'Ad-Dukhan', 'name_arabic' => 'الدخان', 'total_ayat' => 59, 'revelation' => 'Makkiyah', 'juz_start' => 25],
        45 => ['number' => 45, 'name_latin' => 'Al-Jathiyah', 'name_arabic' => 'الجاثية', 'total_ayat' => 37, 'revelation' => 'Makkiyah', 'juz_start' => 25],
        46 => ['number' => 46, 'name_latin' => 'Al-Ahqaf', 'name_arabic' => 'الأحقاف', 'total_ayat' => 35, 'revelation' => 'Makkiyah', 'juz_start' => 26],
        47 => ['number' => 47, 'name_latin' => 'Muhammad', 'name_arabic' => 'محمد', 'total_ayat' => 38, 'revelation' => 'Madaniyah', 'juz_start' => 26],
        48 => ['number' => 48, 'name_latin' => 'Al-Fath', 'name_arabic' => 'الفتح', 'total_ayat' => 29, 'revelation' => 'Madaniyah', 'juz_start' => 26],
        49 => ['number' => 49, 'name_latin' => 'Al-Hujurat', 'name_arabic' => 'الحجرات', 'total_ayat' => 18, 'revelation' => 'Madaniyah', 'juz_start' => 26],
        50 => ['number' => 50, 'name_latin' => 'Qaf', 'name_arabic' => 'ق', 'total_ayat' => 45, 'revelation' => 'Makkiyah', 'juz_start' => 26],
        51 => ['number' => 51, 'name_latin' => 'Adh-Dhariyat', 'name_arabic' => 'الذاريات', 'total_ayat' => 60, 'revelation' => 'Makkiyah', 'juz_start' => 26],
        52 => ['number' => 52, 'name_latin' => 'At-Tur', 'name_arabic' => 'الطور', 'total_ayat' => 49, 'revelation' => 'Makkiyah', 'juz_start' => 27],
        53 => ['number' => 53, 'name_latin' => 'An-Najm', 'name_arabic' => 'النجم', 'total_ayat' => 62, 'revelation' => 'Makkiyah', 'juz_start' => 27],
        54 => ['number' => 54, 'name_latin' => 'Al-Qamar', 'name_arabic' => 'القمر', 'total_ayat' => 55, 'revelation' => 'Makkiyah', 'juz_start' => 27],
        55 => ['number' => 55, 'name_latin' => 'Ar-Rahman', 'name_arabic' => 'الرحمن', 'total_ayat' => 78, 'revelation' => 'Madaniyah', 'juz_start' => 27],
        56 => ['number' => 56, 'name_latin' => 'Al-Waqi\'ah', 'name_arabic' => 'الواقعة', 'total_ayat' => 96, 'revelation' => 'Makkiyah', 'juz_start' => 27],
        57 => ['number' => 57, 'name_latin' => 'Al-Hadid', 'name_arabic' => 'الحديد', 'total_ayat' => 29, 'revelation' => 'Madaniyah', 'juz_start' => 27],
        58 => ['number' => 58, 'name_latin' => 'Al-Mujadila', 'name_arabic' => 'المجادلة', 'total_ayat' => 22, 'revelation' => 'Madaniyah', 'juz_start' => 28],
        59 => ['number' => 59, 'name_latin' => 'Al-Hashr', 'name_arabic' => 'الحشر', 'total_ayat' => 24, 'revelation' => 'Madaniyah', 'juz_start' => 28],
        60 => ['number' => 60, 'name_latin' => 'Al-Mumtahanah', 'name_arabic' => 'الممتحنة', 'total_ayat' => 13, 'revelation' => 'Madaniyah', 'juz_start' => 28],
        61 => ['number' => 61, 'name_latin' => 'As-Saff', 'name_arabic' => 'الصف', 'total_ayat' => 14, 'revelation' => 'Madaniyah', 'juz_start' => 28],
        62 => ['number' => 62, 'name_latin' => 'Al-Jumu\'ah', 'name_arabic' => 'الجمعة', 'total_ayat' => 11, 'revelation' => 'Madaniyah', 'juz_start' => 28],
        63 => ['number' => 63, 'name_latin' => 'Al-Munafiqun', 'name_arabic' => 'المنافقون', 'total_ayat' => 11, 'revelation' => 'Madaniyah', 'juz_start' => 28],
        64 => ['number' => 64, 'name_latin' => 'At-Taghabun', 'name_arabic' => 'التغابن', 'total_ayat' => 18, 'revelation' => 'Madaniyah', 'juz_start' => 28],
        65 => ['number' => 65, 'name_latin' => 'At-Talaq', 'name_arabic' => 'الطلاق', 'total_ayat' => 12, 'revelation' => 'Madaniyah', 'juz_start' => 28],
        66 => ['number' => 66, 'name_latin' => 'At-Tahrim', 'name_arabic' => 'التحريم', 'total_ayat' => 12, 'revelation' => 'Madaniyah', 'juz_start' => 28],
        67 => ['number' => 67, 'name_latin' => 'Al-Mulk', 'name_arabic' => 'الملك', 'total_ayat' => 30, 'revelation' => 'Makkiyah', 'juz_start' => 29],
        68 => ['number' => 68, 'name_latin' => 'Al-Qalam', 'name_arabic' => 'القلم', 'total_ayat' => 52, 'revelation' => 'Makkiyah', 'juz_start' => 29],
        69 => ['number' => 69, 'name_latin' => 'Al-Haqqah', 'name_arabic' => 'الحاقة', 'total_ayat' => 52, 'revelation' => 'Makkiyah', 'juz_start' => 29],
        70 => ['number' => 70, 'name_latin' => 'Al-Ma\'arij', 'name_arabic' => 'المعارج', 'total_ayat' => 44, 'revelation' => 'Makkiyah', 'juz_start' => 29],
        71 => ['number' => 71, 'name_latin' => 'Nuh', 'name_arabic' => 'نوح', 'total_ayat' => 28, 'revelation' => 'Makkiyah', 'juz_start' => 29],
        72 => ['number' => 72, 'name_latin' => 'Al-Jinn', 'name_arabic' => 'الجن', 'total_ayat' => 28, 'revelation' => 'Makkiyah', 'juz_start' => 29],
        73 => ['number' => 73, 'name_latin' => 'Al-Muzzammil', 'name_arabic' => 'المزمل', 'total_ayat' => 20, 'revelation' => 'Makkiyah', 'juz_start' => 29],
        74 => ['number' => 74, 'name_latin' => 'Al-Muddaththir', 'name_arabic' => 'المدثر', 'total_ayat' => 56, 'revelation' => 'Makkiyah', 'juz_start' => 29],
        75 => ['number' => 75, 'name_latin' => 'Al-Qiyamah', 'name_arabic' => 'القيامة', 'total_ayat' => 40, 'revelation' => 'Makkiyah', 'juz_start' => 29],
        76 => ['number' => 76, 'name_latin' => 'Al-Insan', 'name_arabic' => 'الانسان', 'total_ayat' => 31, 'revelation' => 'Madaniyah', 'juz_start' => 29],
        77 => ['number' => 77, 'name_latin' => 'Al-Mursalat', 'name_arabic' => 'المرسلات', 'total_ayat' => 50, 'revelation' => 'Makkiyah', 'juz_start' => 29],
        78 => ['number' => 78, 'name_latin' => 'An-Naba\'', 'name_arabic' => 'النبإ', 'total_ayat' => 40, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        79 => ['number' => 79, 'name_latin' => 'An-Nazi\'at', 'name_arabic' => 'النازعات', 'total_ayat' => 46, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        80 => ['number' => 80, 'name_latin' => '\'Abasa', 'name_arabic' => 'عبس', 'total_ayat' => 42, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        81 => ['number' => 81, 'name_latin' => 'At-Takwir', 'name_arabic' => 'التكوير', 'total_ayat' => 29, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        82 => ['number' => 82, 'name_latin' => 'Al-Infitar', 'name_arabic' => 'الإنفطار', 'total_ayat' => 19, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        83 => ['number' => 83, 'name_latin' => 'Al-Mutaffifin', 'name_arabic' => 'المطففين', 'total_ayat' => 36, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        84 => ['number' => 84, 'name_latin' => 'Al-Inshiqaq', 'name_arabic' => 'الإنشقاق', 'total_ayat' => 25, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        85 => ['number' => 85, 'name_latin' => 'Al-Buruj', 'name_arabic' => 'البروج', 'total_ayat' => 22, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        86 => ['number' => 86, 'name_latin' => 'At-Tariq', 'name_arabic' => 'الطارق', 'total_ayat' => 17, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        87 => ['number' => 87, 'name_latin' => 'Al-A\'la', 'name_arabic' => 'الأعلى', 'total_ayat' => 19, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        88 => ['number' => 88, 'name_latin' => 'Al-Ghashiyah', 'name_arabic' => 'الغاشية', 'total_ayat' => 26, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        89 => ['number' => 89, 'name_latin' => 'Al-Fajr', 'name_arabic' => 'الفجر', 'total_ayat' => 30, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        90 => ['number' => 90, 'name_latin' => 'Al-Balad', 'name_arabic' => 'البلد', 'total_ayat' => 20, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        91 => ['number' => 91, 'name_latin' => 'Ash-Shams', 'name_arabic' => 'الشمس', 'total_ayat' => 15, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        92 => ['number' => 92, 'name_latin' => 'Al-Lail', 'name_arabic' => 'الليل', 'total_ayat' => 21, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        93 => ['number' => 93, 'name_latin' => 'Ad-Duha', 'name_arabic' => 'الضحى', 'total_ayat' => 11, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        94 => ['number' => 94, 'name_latin' => 'Ash-Sharh', 'name_arabic' => 'الشرح', 'total_ayat' => 8, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        95 => ['number' => 95, 'name_latin' => 'At-Tin', 'name_arabic' => 'التين', 'total_ayat' => 8, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        96 => ['number' => 96, 'name_latin' => 'Al-\'Alaq', 'name_arabic' => 'العلق', 'total_ayat' => 19, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        97 => ['number' => 97, 'name_latin' => 'Al-Qadr', 'name_arabic' => 'القدر', 'total_ayat' => 5, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        98 => ['number' => 98, 'name_latin' => 'Al-Bayyinah', 'name_arabic' => 'البينة', 'total_ayat' => 8, 'revelation' => 'Madaniyah', 'juz_start' => 30],
        99 => ['number' => 99, 'name_latin' => 'Az-Zalzalah', 'name_arabic' => 'الزلزلة', 'total_ayat' => 8, 'revelation' => 'Madaniyah', 'juz_start' => 30],
        100 => ['number' => 100, 'name_latin' => 'Al-\'Adiyat', 'name_arabic' => 'العاديات', 'total_ayat' => 11, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        101 => ['number' => 101, 'name_latin' => 'Al-Qari\'ah', 'name_arabic' => 'القارعة', 'total_ayat' => 11, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        102 => ['number' => 102, 'name_latin' => 'At-Takathur', 'name_arabic' => 'التكاثر', 'total_ayat' => 8, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        103 => ['number' => 103, 'name_latin' => 'Al-\'Asr', 'name_arabic' => 'العصر', 'total_ayat' => 3, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        104 => ['number' => 104, 'name_latin' => 'Al-Humazah', 'name_arabic' => 'الهمزة', 'total_ayat' => 9, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        105 => ['number' => 105, 'name_latin' => 'Al-Fil', 'name_arabic' => 'الفيل', 'total_ayat' => 5, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        106 => ['number' => 106, 'name_latin' => 'Quraish', 'name_arabic' => 'قريش', 'total_ayat' => 4, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        107 => ['number' => 107, 'name_latin' => 'Al-Ma\'un', 'name_arabic' => 'الماعون', 'total_ayat' => 7, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        108 => ['number' => 108, 'name_latin' => 'Al-Kauthar', 'name_arabic' => 'الكوثر', 'total_ayat' => 3, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        109 => ['number' => 109, 'name_latin' => 'Al-Kafirun', 'name_arabic' => 'الكافرون', 'total_ayat' => 6, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        110 => ['number' => 110, 'name_latin' => 'An-Nasr', 'name_arabic' => 'النصر', 'total_ayat' => 3, 'revelation' => 'Madaniyah', 'juz_start' => 30],
        111 => ['number' => 111, 'name_latin' => 'Al-Masad', 'name_arabic' => 'المسد', 'total_ayat' => 5, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        112 => ['number' => 112, 'name_latin' => 'Al-Ikhlas', 'name_arabic' => 'الإخلاص', 'total_ayat' => 4, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        113 => ['number' => 113, 'name_latin' => 'Al-Falaq', 'name_arabic' => 'الفلق', 'total_ayat' => 5, 'revelation' => 'Makkiyah', 'juz_start' => 30],
        114 => ['number' => 114, 'name_latin' => 'An-Nas', 'name_arabic' => 'الناس', 'total_ayat' => 6, 'revelation' => 'Makkiyah', 'juz_start' => 30],
    ];

    /**
     * Get all surahs.
     */
    public static function getAllSurahs(): array
    {
        return self::$surahs;
    }

    /**
     * Get surah by number.
     */
    public static function getSurah(int $number): ?array
    {
        return self::$surahs[$number] ?? null;
    }

    /**
     * Get surah name (Latin).
     */
    public static function getSurahName(int $number): string
    {
        return self::$surahs[$number]['name_latin'] ?? "Surah {$number}";
    }

    /**
     * Get surah name (Arabic).
     */
    public static function getSurahNameArabic(int $number): string
    {
        return self::$surahs[$number]['name_arabic'] ?? '';
    }

    /**
     * Get maximum ayat in a surah.
     */
    public static function getMaxAyat(int $surahNumber): int
    {
        return self::$surahs[$surahNumber]['total_ayat'] ?? 0;
    }

    /**
     * Get juz number based on surah and ayat.
     * This is simplified - actual juz boundaries are more complex.
     */
    public static function getJuzNumber(int $surahNumber, int $ayatStart): int
    {
        // Simplified juz mapping
        $juzBoundaries = [
            1 => [1, 1],    // Al-Fatihah ayat 1
            2 => [2, 142],  // Al-Baqarah ayat 142
            3 => [2, 253],  // Al-Baqarah ayat 253
            4 => [3, 93],   // Ali Imran ayat 93
            5 => [4, 24],   // An-Nisa ayat 24
            6 => [4, 148],  // An-Nisa ayat 148
            7 => [5, 82],   // Al-Ma'idah ayat 82
            8 => [6, 111],  // Al-An'am ayat 111
            9 => [7, 88],   // Al-A'raf ayat 88
            10 => [8, 41],  // Al-Anfal ayat 41
            11 => [9, 93],  // At-Taubah ayat 93
            12 => [11, 6],  // Hud ayat 6
            13 => [12, 53], // Yusuf ayat 53
            14 => [15, 1],  // Al-Hijr ayat 1
            15 => [17, 1],  // Al-Isra ayat 1
            16 => [18, 75], // Al-Kahf ayat 75
            17 => [21, 1],  // Al-Anbiya ayat 1
            18 => [23, 1],  // Al-Mu'minun ayat 1
            19 => [25, 21], // Al-Furqan ayat 21
            20 => [27, 56], // An-Naml ayat 56
            21 => [29, 46], // Al-Ankabut ayat 46
            22 => [33, 31], // Al-Ahzab ayat 31
            23 => [36, 28], // Yasin ayat 28
            24 => [39, 32], // Az-Zumar ayat 32
            25 => [41, 47], // Fussilat ayat 47
            26 => [46, 1],  // Al-Ahqaf ayat 1
            27 => [51, 31], // Adh-Dhariyat ayat 31
            28 => [58, 1],  // Al-Mujadila ayat 1
            29 => [67, 1],  // Al-Mulk ayat 1
            30 => [78, 1],  // An-Naba ayat 1
        ];

        foreach ($juzBoundaries as $juz => $boundary) {
            [$juzSurah, $juzAyat] = $boundary;

            if ($surahNumber < $juzSurah) {
                return max(1, $juz - 1);
            }

            if ($surahNumber == $juzSurah && $ayatStart < $juzAyat) {
                return max(1, $juz - 1);
            }
        }

        return 30;
    }

    /**
     * Get expected ayat count per juz (approximate).
     * This is simplified - actual count varies per juz.
     */
    public static function getJuzAyatCount(int $juzNumber): int
    {
        // Average ayat per juz (6236 total ayat / 30 juz ≈ 208 ayat)
        // But this is very approximate, use for validation only
        return 200; // Simplified
    }
}
