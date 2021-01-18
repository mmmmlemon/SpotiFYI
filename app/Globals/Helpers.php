<?php

    namespace App\Globals;
    use Auth;
    use Carbon\Carbon;
    use SpotifyWebAPI;
    use Cookie;
    use File;

    //Helpers
    //глобальные функции на разные случаи
    class Helpers
    {
        //pickTheWord
        //выбрать правильное окончание для словосочетания (десять минуТ, две минуТЫ, одна минуТА и т.п)
        //параметры: число для которого нужно подобрать слово с нужным окончанием, 
        //первый вариант окончания (firstWord, 5 минуТ, 0 минуТ, 10 минуТ, 11-19 минуТ), 
        //второй вариант окончания (secondWord, 1 минуТА), 
        //третий вариант окончания (thirdWord, 2, 3, 4 минуТЫ)
        //возвращает подходящее слово из трех, либо false если в параметре $number не было указано число
        public static function pickTheWord($number, $firstWord, $secondWord, $thirdWord)
        {
            //проверяем содержит ли $number число
            //если нет, то возвращаем false
            if(is_numeric($number) == false)
            { return response()->json(false); }
            else
            {
                //получаем последнюю цифру в числе
                $lastNumber = strval($number)[strlen(strval($number)) - 1];

                //если число двузначное или больше
                //получаем последние два числа на случай если там будет число от 11 до 19
                $lastTwoNumbers = "";

                if(strlen(strval($number)) >= 2)
                { $lastTwoNumbers = strval($number)[strlen(strval($number)) - 2] . strval($number)[strlen(strval($number)) - 1]; }

                //если число от 11-ти до 19-ти, то возвращаем firstWord
                if(intval($lastTwoNumbers) >= 10 && intval($lastTwoNumbers) <= 19)
                { return $firstWord; }
                else
                {
                    //если число заканчивается на 1, то возвращаем secondWord
                    if($lastNumber == "1")
                    { return $secondWord; }
                    //если заканчивается на 2, 3 или 4, то возвращаем thirdWord
                    elseif($lastNumber == "2" || $lastNumber == "3" || $lastNumber == "4")
                    { return $thirdWord; }
                    //в иных случаях возвращаем firstWord
                    else
                    { return $firstWord; }
                }
            }
        }

        //getFullNameOfItem
        //получить полное название трека или альбома 
        //параметры: track - трек из SpotifyAPI, type - artist или fullname
        //возвращает строку с полным названием, либо false
        public static function getFullNameOfItem($item, $type = "fullname")
        {   
            //проверяем существуют ли параметры artists и name
            if(property_exists($item, 'artists') && property_exists($item, 'name'))
            {
                //если артистов больше нуля
                if(count($item->artists) > 0)
                {
                    $artists = "";
                
                    //записываем имена артистов через запятую
                    for($i = 1; $i <= count($item->artists); $i++)
                    {
                        if($i != count($item->artists) && count($item->artists) > 1)
                        { $artists .= $item->artists[$i-1]->name . ", ";}
                        else
                        { $artists .= $item->artists[$i-1]->name; }
                    }

                    //если type - artist, то возвращаем список артистов
                    if($type == "artist")
                    {
                        return $artists;
                    }
                    //если type - fullname, то возвращаем
                    else if($type == "fullname")
                    { return $artists . " - " . $item->name; }
                    //иначе возвращаем false
                    else
                    { return response()->json(false);  }
                    
                }
                else
                { return response()->json(false); } 
            }
            //если параметров artists и name нет, то возвращаем false
            else
            { return response()->json(false); }
        }

        //sortFunction
        //функция сортировки по ключу, по убыванию или по возрастанию
        //параметры: ключ и порядок сортировки
        //возвращает отсортированные массив, либо false если не был задан верный порядок сортировки
        private static function sortFunction($key, $order) 
        {
            if($order == "asc") //если порядок - по возрастанию
            {
                return function ($a, $b) use ($key) {
                    return strnatcmp($a[$key], $b[$key]);
                };
            }
            else if($order == "desc") //если порядок - по убыванию
            {
                return function ($a, $b) use ($key) {
                    return strnatcmp($b[$key], $a[$key]);
                };
            }
            else
            { return false; } 
        }

        //sortArrayByKey
        //сортировка массива по ключу
        //параметры: массив, ключ, порядок сортировки
        public static function sortArrayByKey($array, $key, $order)
        {
            $arrayCopy = $array;

            usort($arrayCopy, Helpers::sortFunction($key, $order));

            return $arrayCopy;
        }

        //randomHslColor
        //сгенерировать случайный цвет в формате HSL
        //параметр: сдвиг по оси цветов
        //возвращает строку с цветом в формате HSL
        public static function randomHslColor($offset = 0)
        {   
            //генерируем цвет в RGB, добавляем offset в начало
            $randNum = rand($offset,360);

            //записываем в строку, параметры saturation, brightness и opacity 
            //это параметры будут всегда одинаковые чтобы цвет был яркий
            $hslColor = "hsla(".$randNum.",100%,39%,1)";

            return $hslColor;
        }
            
        //trackDuration
        //перевести длительность трека из миллисекунд в минуты и секунды
        //возвращает строку с длиной трека в минутах и секундах
        //параметры: длина трека в миллисекундах
        public static function getTrackDuration($durationMs)
        {
            //проверяем явялется ли durationMs - числовым
            if(is_numeric($durationMs))
            {   
                //получаем длительность в минутах (мс / 1000 / 60, округляем до пяти знаков после запятой)
                $durationMinutes= round($durationMs / 1000 / 60, 5);

                //получаем длительность в часах ( мин / 60, округляем до пяти знаков после запятой)
                $durationHours = round($durationMinutes / 60, 5);

                //минуты из остатка часов (60 * часы - остаток)
                $durationMinutes = 60 * ($durationHours - floor($durationHours));

                //секунды из остатка минут (60 * минуты - остаток)
                $durationSeconds = floor(60 * ($durationMinutes - floor($durationMinutes)));

                $hoursStr = "";
                $minutesStr = "";
                $secondsStr = "";
                $durationStr = "";

                //получаем строковые значения часов, минут и секунд
                $hoursStr = strval(floor($durationHours));
                $minutesStr = strval(floor($durationMinutes));
                $secondsStr = strval($durationSeconds);
                
                //записываем время в строку
                if(strlen($hoursStr) == 1 && floor($durationHours) > 0)
                { $hoursStr = "0" . $hoursStr . ":"; }
                else if (floor($durationHours) == 0)
                { $hoursStr = ""; }
                else
                {  $hoursStr .= ":"; }

                if(strlen($minutesStr) == 1)
                { $minutesStr = "0" . $minutesStr; }

                if(strlen($secondsStr) == 1)
                { $secondsStr = "0" . $secondsStr; }

                $durationStr = $hoursStr.$minutesStr.":".$secondsStr;
                
                return $durationStr;
            }
            else
            { return false;  }

        }

        //getDurationInHours
        //получить длительность в часах из миллисекунд
        //возвращает строку с длительностью в часах
        //параметры: длительность в милисекундах
        public static function getDurationInHours($durationMs)
        {
            //проверяем является ли durationMs числом
            if(is_numeric($durationMs))
            {
                //получить минуты из миллисекунд (мс / 1000 / 60, округлить до пяти знаков после запятой)
                $durationMinutes = round($durationMs / 1000 / 60, 5);

                //получаем длительность в часах ( мин / 60, округляем до пяти знаков после запятой)
                $durationHours = round($durationMinutes / 60, 5);

                //минуты из остатка часов (60 * часы - остаток)
                $durationMinutes = 60 * ($durationHours - floor($durationHours));

                //секунды из остатка минут (60 * минуты - остаток)
                $durationSeconds = floor(60 * ($durationMinutes - floor($durationMinutes)));

                $hoursStr = "";
                $minutesStr = "";
                $secondsStr = "";
                $durationStr = "";

                //получаем строковые значения часов, минут и секунд
                $hoursStr = strval(floor($durationHours));
                $minutesStr = strval(floor($durationMinutes));
                $secondsStr = strval($durationSeconds);
                
                //записываем время в строку
                $durationStr = $hoursStr." ч. ".$minutesStr." мин. ".$secondsStr. " с.";
            
                return $durationStr;
            }
            else
            { return false; }
        }

        //getItemReleaseDate
        //получить дату выхода трека или альбома в виде года или полной даты
        //возвращает строку с датой выхода
        //параметры: трек или альбом из Spotify API, строка указание на то трек это или альбом, 
        //строка указание на тип даты: год или полная дата
        public static function getItemReleaseDate($item, $itemType = "track", $dateType = "long")
        {
            if(property_exists($item, "album") || property_exists($item, "release_date") || property_exists($item, "release_date_precision"))
            {
                $date = ""; //дата
                $releaseDatePrecision  = ""; //тип даты, год или полная дата 
    
                //записываем дату и тип даты из объекта
                if($itemType == "track")
                {
                    //получить дату из альбома
                    $date = $item->album->release_date;
                    $releaseDatePrecision = $item->album->release_date_precision;
                }
                else if ($itemType == "album")
                {
                    //получить дату
                    $date = $item->release_date;
                    $releaseDatePrecision = $item->release_date_precision;
                }
                else
                { return false; }
                
                //если dataType - short, то возвращаем год
                if($dateType == "short")
                {
                    //если дата рализа указана с точностью до дня или до месяца, то вырезаем из даты год
                    if($releaseDatePrecision == "day" || $releaseDatePrecision == "month")
                    { $date = substr($date, 0, 4); }
                    //если нет, то просто возвращаем дату
                    return $date;
                }
                //если dataType - long, то возвращаем полную дату
                else if ($dateType == "long")
                { return $date; }
                else
                { return false; }   
            }
            else
            { return false; }
        }

        //getArtistsGenres
        //получает список жанров артиста
        //возвращает строку со списком жанров артиста
        //параметры: артист и кол-во жанров которые нужно вывести
        public static function getArtistsGenres($artist, $count = 5)
        {
            if(property_exists($artist, "genres"))
            {
                $genres = "";
                //считаем сколько всего жанров записано у артиста
                $genresMax = count($artist->genres);

                //кол-во жанров которые нужно получить, по умолчанию равно параметру count
                $iMax = $count;

                //если кол-во жанров записанных у артиста меньше чем count, то iMax будет равно genresMax
                if($genresMax < $count)
                {  $iMax = $genresMax; }
                
                //записываем жанры в строку через цикл
                for($i = 0; $i < $iMax; $i++)
                {
                    $genres .= $artist->genres[$i];
                    if($i != $iMax - 1)
                    { $genres .=", "; }
                }
    
                return $genres;
            }
            else
            { return false; }
        }
    }
        