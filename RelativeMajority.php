<?php

/**
 * Created by PhpStorm.
 * User: Serhii
 * Date: 27.04.2018
 * Time: 17:53
 */

/**
 * Class RelativeMajority
 * Класс описывающий метод относительного большинства
 */
class RelativeMajority extends Strategy
{
    /**
     * Массив коллективной ранжировки
     * @var array
     */
    private $collectiveRanking = [];

    public function __construct(array $profile)
    {
        parent::__construct($profile);
    }

    /**
     * Заполнение массива кандидатов
     */
    protected function fillArrayCandidates():void
    {
        parent::fillArrayCandidates();
        $this->candidatesList = $this->candidatesArray;
        return;
    }

    /**
     * Обновление списка кандидатов
     */
    private function updateCandidatesArray():void
    {
        $this->candidatesArray = $this->candidatesList;
        return;
    }


    /**
     * Нахождение коллективного ранжирования
     */
    public function calculateCollectiveRanking():array
    {
        $data = $this->profile;
        $this->getSumOfVotes($data);
        //Получаем кандидата-победителя
        $winner = key($this->candidatesArray);
        //Добавляем победтеля в массив коллективной ранжировки
        array_push($this->collectiveRanking, $winner);
        //Удаляем с данных кандидата-победителя
        for ($i = 0; $i < count($this->profile); $i++) {
            foreach ($this->profile[$i] as &$candidates) {
                foreach ($candidates as $num => $candidate) {
                    if ($candidate == $winner)
                        unset($candidates[$num]);
                }
                $candidates = array_values($candidates);
            }
        }
        unset($candidates);

        //Переходим к определению след. кандидата-поедителя
        if (count($this->collectiveRanking) < 4) {
            $this->updateCandidatesArray();
            $this->calculateCollectiveRanking();
        }
        return $this->collectiveRanking;
    }

}