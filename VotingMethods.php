<?php

/**
 * Created by PhpStorm.
 * User: Serhii
 * Date: 27.04.2018
 * Time: 17:51
 */
class VotingMethods
{
    private $votingMethod;

    public function __construct(Strategy $votingMethod)
    {
        $this->votingMethod = $votingMethod;
    }

    /**
     * @param Strategy $votingMethod
     */
    public function setVotingMethod(Strategy $votingMethod)
    {
        $this->votingMethod = $votingMethod;
    }

    public function calculateCollectiveRanking():array
    {
        return $this->votingMethod->calculateCollectiveRanking();
    }

}