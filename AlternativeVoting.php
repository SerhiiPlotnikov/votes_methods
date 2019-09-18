<?php

/**
 * Created by PhpStorm.
 * User: Serhii
 * Date: 27.04.2018
 * Time: 23:41
 */

/**
 * Class AlternativeVoting
 * Описывает метод альтернативных голосов
 * Находим наихудшего кандидата методом относительного большинства
 */
class AlternativeVoting extends Strategy
{
    /**
     * Коллективная ранжировка
     * @var array
     */
    private $collectiveRanking = [];

    public function __construct(array $profile)
    {
        parent::__construct($profile);
    }

    public function calculateCollectiveRanking():array
    {
        $data = $this->profile;
        $this->getSumOfVotes($data);
//        var_dump($this->candidatesArray);
        //Находим наихудшего
        end($this->candidatesArray);
        $worstCandidate = key($this->candidatesArray);
        array_pop($this->candidatesArray);
        //Заносим в коллективную ранжировку
        array_unshift($this->collectiveRanking, $worstCandidate);
//        var_dump($this->collectiveRanking);
        //Удаляем наихудшего с профиля
        for ($i = 0; $i < count($this->profile); $i++) {
            foreach ($this->profile[$i] as &$candidates) {
                foreach ($candidates as $index => $candidate) {
                    if ($candidate == $worstCandidate) {
                        unset($candidates[$index]);
                    }
                }
                $candidates = array_values($candidates);
            }
        }
        unset($candidates);
//        var_dump($this->profile);
        foreach ($this->candidatesArray as $candidate => $count) {
            $this->candidatesArray[$candidate] = 0;
        }
        unset($candidate);
        if (count($this->collectiveRanking) < 4) {
            $this->calculateCollectiveRanking();
        }
        return $this->collectiveRanking;
    }

}