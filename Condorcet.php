<?php

/**
 * Created by PhpStorm.
 * User: Serhii
 * Date: 27.04.2018
 * Time: 21:16
 */

/**
 * Class Condorcet
 * Описывает метод Кондорсе
 */
class Condorcet extends Strategy
{
    /**
     * Массив кол-ва побед над остальными участниками
     * @var array
     */
    private $countOfWins = [];

    public function __construct(array $profile)
    {
        parent::__construct($profile);
    }


    public function calculateCollectiveRanking():array
    {
        $candidatesArray = [];
        foreach ($this->profile[0] as $candidates) {
            foreach ($candidates as $candidate) {
                array_push($candidatesArray, $candidate);
            }
            sort($candidatesArray);
            break;
        }

        //Делаем сравниения между всеми кандидатами
        $sum = [];
        //Сравниваем каждого кандидата со всеми другими
        foreach ($candidatesArray as $candidate1) {
            $sum[$candidate1] = [];
            foreach ($candidatesArray as $candidate2) {
                //Если это один и тот же кандидат, пропускаем
                if ($candidate1 == $candidate2) continue;
                $s = 0;
                //Кол-во голосов, при которых candidate1 лучше чем candidate2
                $sum[$candidate1][$candidate2] = [];
                //Делаем обход по профилю
                for ($i = 0; $i < count($this->profile); $i++) {
                    foreach ($this->profile[$i] as $count => $candidates) {
                        //Индексы местоположения в матрице 1 и 2 кандидатов
                        $indexFirstCandidate = $indexSecondCandidate = 0;
                        for ($j = 0; $j < count($candidates); $j++) {
                            if ($candidate1 == $candidates[$j]) {
                                $indexFirstCandidate = $j;
                            }
                            if ($candidate2 == $candidates[$j]) {
                                $indexSecondCandidate = $j;
                            }
                        }
                        /*
                         * Если первый кандидат набрал больше голосов, чем второй(его индекс меньше чем у второго),то
                         * добавляем кол-во голосов, при которых 1 кандидат лучше второго
                         */
                        if ($indexFirstCandidate < $indexSecondCandidate) {
                            $s += $count;
                        }
                    }
                }
                $sum[$candidate1][$candidate2] = $s;
            }
        }
//        var_dump($sum);

        //Подсчитываем кол-во побед каждого из кандидатов над другими кандидатами во всех личных голосованиях
        foreach ($sum as $candidate1 => $candidates) {
            $this->countOfWins[$candidate1] = 0;
//            echo $candidate1 . ':<br>';
            foreach ($candidates as $candidate2 => $count) {
//                echo $count . '- ' . $sum[$candidate2][$candidate1] . '<br>';
                if ($count > $sum[$candidate2][$candidate1]) {
                    $this->countOfWins[$candidate1]++;
                }
            }
//            echo '<hr>';
        }
        arsort($this->countOfWins);
        return array_keys($this->countOfWins);
    }
}