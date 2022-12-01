<?php

require_once "Game.php";

class HighScoreGame
{
    private array $scores;

    public function __construct()
    {
        $numberOfGames = $this->setNumberOfGames();
        #Left this so you do not need to type the whole board.
        #$board = [1, 2, 3, 4, 5, 6, 7, null, 8];
        $board = Game::createBoard();
        for ($i = 0; $i < $numberOfGames; $i++){
            $game = new Game($board);
            $this->scores[] = $game->getMoves();
        }

        $this->printGames();
    }

    private function setNumberOfGames() : int
    {
        print("How many games do you want to play: ");
        while(true) {
            $numberOfGames = readline('Number of Games: ');
            if(settype($numberOfGames, 'int') && $numberOfGames > 0){
                return $numberOfGames;
            }
        }
    }

    private function printGames() : void
    {
        foreach ($this->scores as $round => $moves){
            print('Game ' . ($round + 1) . " won with $moves moves. \n");
        }
    }
}

new HighScoreGame();