<?php

require_once "Board.php";

class Game
{
    private Board $board;

    public function __construct()
    {
        $this->board = new Board();
        print("Type the number of the tile you want to move!\n");
        $this->gameLoop();
    }

    private function displayBoard() : void
    {
        $board = $this->board->getBoardTiles();
        $board[array_search(null, $board)] = " ";
        print(vsprintf("%s %s %s \n%s %s %s \n%s %s %s\n", $board));
    }

    private function gameLoop(){
        $this->displayBoard();
        $this->displayPossibleMoves();
        $tileToMove = $this->readInput();
        $this->board->moveTile($tileToMove);
        if($this->board->areTilesInOrder()){
            print("You won");
        }else{
            $this->gameLoop();

        }
    }

    private function displayPossibleMoves(){
        foreach($this->board->possibleMoves() as $moveDirection => $tilePosition){
            print("Move tile [" . $this->board->getTile($tilePosition)."] $moveDirection.\n");
        }
    }

    private function readInput() : int
    {
        while(true) {
            $input = readline('Move Tile: ');
            if(settype($input, 'int') && $this->board->canMoveTile($input)){
                return $input;
            }
        }
    }
}

$game = new Game();
