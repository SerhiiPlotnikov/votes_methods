<?php

/**
 * Created by PhpStorm.
 * User: Serhii
 * Date: 27.04.2018
 * Time: 18:25
 */
abstract class Strategy
{
    /**
     * Профиль голосования
     * @var array
     */
    protected $profile = [];

    /**
     * Список кандидатов
     * @var array
     */
    protected $candidatesArray = [];

    protected $candidatesList = [];


    public function __construct(array $profile)
    {
        $this->profile = $profile;
        //Заполянем массив кандидатов
        $this->fillArrayCandidates();
    }

    /**
     * Заполнение массива кандидатов
     */
    protected function fillArrayCandidates():void
    {
        foreach ($this->profile[0] as $candidates) {
            foreach ($candidates as $candidate) {
                $this->candidatesArray[$candidate] = 0;
            }
        }
        return;
    }

    /**
     * Получение суммы голосов для кандидатов, занявших первые места в индивидуальных предпочтениях избирателей
     * и их сортировка в порядке убывания голосов
     * @param array $data
     */
    protected function getSumOfVotes(array $data):void
    {
        for ($i = 0; $i < count($data); $i++) {
            foreach ($data[$i] as $count => $candidates) {
                $this->candidatesArray[$candidates[0]] += $count;
            }
        }
        //Сортируем кандидатов в порядке убывания
        arsort($this->candidatesArray);
        return;
    }

    /**
     * Нахождение коллективного ранжирования
     */
    public abstract function calculateCollectiveRanking():array;
}