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
            //в параметрах: число, первый вариант окончания (5 минуТ, 0 минуТ, 10 минуТ), второй вариант окончания (1 минуТА), третий вариант окончания (2, 3, 4 минуТЫ)
            //возвращает строку со словосочетанием, либо false если в параметре $number не было указано целое число
            public static function pickTheWord($number, $firstWord, $secondWord, $thirdWord)
            {
                //проверяем содержит ли $number число
                //если нет, то возвращаем false
                if(is_numeric($number) == false)
                { 
                    return response()->json(false); 
                }
                else
                {
                    //получаем последнюю цифру в числе
                    $lastNumber = strval($number)[strlen(strval($number)) - 1];

                    if($lastNumber == "1")
                    { 
                        return " " . $secondWord; 
                    }
                    elseif($lastNumber == "2" || $lastNumber == "3" || $lastNumber == "4")
                    { 
                        return " " . $thirdWord; 
                    }
                    else
                    { 
                        return " " . $firstWord;
                    }
                }
            }

            //getFullNameOfItem
            //получить полное название трека или альбома 
            //исполнители через запятую + название, например "Queen, David Bowie - Under Pressure"
            //в параметре трек или альбом из JSON'а Spotify API
            //возвращает строку с полным названием, либо false
            public static function getFullNameOfItem($item)
            {   
                if(count($item->artists) > 0)
                {
                    $artists = "";
                
                    for($j = 1; $j <= count($item->artists); $j++)
                    {
                        if($j != count($item->artists) && count($item->artists) > 1)
                        { $artists .= $item->artists[$j-1]->name . ", ";}
                        else
                        { $artists .= $item->artists[$j-1]->name; }
                    }
        
                    return $artists . " - " . $item->name;
                }
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
            public static function randomHslColor($param)
            {   
                $offset = 0;

                if($param['offset'] != null)
                { $offset = $param['offset']; }

                //генерируем цвет в RGB
                $randNum = rand($offset,360);

                //записываем в строку, параметры saturation, brightness и opacity 
                //будут всегда одинаковые чтобы цвет был яркий
                $hslColor = "hsla(".$randNum.",100%,39%,1)";

                return $hslColor;
            }

            
            //trackDurationToMinutes
            //перевести длительность трека из миллисекунд в минуты и секунды
            //возвращает строку с длиной трека в минутах и секундах
            //параметры: длина трека в миллисекундах
            public static function trackDurationToMinutes($durationMs)
            {
                if(is_numeric($durationMs))
                {
                    $durationS = $durationMs / 1000;
                    $durationMinutes = round($durationS / 60, 3);
                    
                    $durationSeconds = floor(60 * ($durationMinutes - floor($durationMinutes)));
    
                    $minutesStr = strval(floor($durationMinutes));
                    $secondsStr = strval($durationSeconds);
                    
                    if(strlen($secondsStr) == 1)
                    { $secondsStr = "0" . $secondsStr; }
    
                    $durationStr = $minutesStr.":".$secondsStr;
    
                    return $durationStr;
                }
                else
                { return false;  }

            }

            //getItemReleaseDate
            //получить дату выхода трека или альбома в виде года или полной даты
            //возвращает строку с датой выхода
            //параметры: трек или альбом из Spotify API, строка указание на то трек это или альбом, 
            //строка указание на тип даты: год или полная дата
            public static function getItemReleaseDate($item, $itemType,  $dateType)
            {
                $date = "";
                $releaseDatePrecision  = "";
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
               
                if($dateType == "short")
                {
                      //если дата рализа указана с точностью до дня или до месяца, то вырезаем из даты год
                      if($releaseDatePrecision == "day" || $releaseDatePrecision == "month")
                      { $date = substr($date, 0, 4); }

                      return $date;
                }
                else if ($dateType == "long")
                {
                    return $date;
                }
                else
                { return false; }    
            }

    }
        