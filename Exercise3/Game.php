<?php

require_once "Board.php";

class Game
{
    private Board $board;

    private int $moves = 0;

    public function getMoves(): int
    {
        return $this->moves;
    }

    /**
     * @throws Exception
     */
    public function __construct(array $board = null)
    {
        if($board){
            $this->board = new Board($board);

        }else{
            $this->board = new Board($this::createBoard());

        }
        print("Type the number of the tile you want to move!\n");
        $this->gameLoop();
    }

    public static function createBoard()
    {
        print("Set grid size:");
        while(true) {
            $gridSize = readline('Move Tile: ');
            if(settype($gridSize, 'int')){
                break;
            }
        }

        $tempBoard = [];
        for ($i = 0; $i < $gridSize * $gridSize - 1; $i++){
            $tempBoard[$i] = $i + 1;
        }

        $board = [];
        while(!empty($tempBoard)) {
            print("Available tiles: " . Game::displayArray($tempBoard));
            print("Board Tiles: " . Game::displayArray($board));
            $input = readline('Move Tile: ');
            if(settype($input, 'int') && (in_array($input, $tempBoard))){
                unset($tempBoard[array_search($input, $tempBoard)]);
                $board[] = $input;
            }
        }
        $board[] = null;
        return $board;
    }

    private static function displayArray(array $array)
    {
        $string = "";
        foreach ($array as $value){
            $string .= $value . ',';
        }
        return substr($string, 0, -1) . "\n";
    }

    private function displayBoard() : void
    {
        $board = $this->board->getBoardTiles();
        $board[array_search(null, $board)] = " ";

        $biggestNumberChars = strlen((string)pow($this->board->getGridSize(), 2) - 1);
        foreach ($board as &$tile){
            $tile = str_repeat(' ', $biggestNumberChars - strlen((string)$tile)). $tile;
        }
        $row = str_repeat('%s ', $this->board->getGridSize());
        $boardString = str_repeat($row . "\n", $this->board->getGridSize());

        print(vsprintf($boardString, $board));
    }

    private function gameLoop(){
        $this->displayBoard();
        if($this->board->areTilesInOrder()){
            print("You won \n");
        }else{
            $this->displayPossibleMoves();
            $this->board->moveTile($this->readNextmove());
            $this->moves++;
            $this->gameLoop();
        }
    }

    private function displayPossibleMoves(){
        foreach($this->board->getPossibleMoves() as $moveDirection => $tilePosition){
            print("Move tile [" . $this->board->getTile($tilePosition)."] $moveDirection.\n");
        }
    }

    private function readNextmove() : int
    {
        while(true) {
            $input = readline('Move Tile: ');
            if(settype($input, 'int') && $this->board->canMoveTile($input)){
                return $input;
            }
        }
    }
}
