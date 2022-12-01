<?php

class Board
{
    private int $gridSize;
    private array $boardTiles;
    private array $possibleMoves;

    /**
     * @throws Exception
     */
    public function __construct(array $boardTiles)
    {
        $gridSize= sqrt(count($boardTiles));
        if(floor($gridSize) == $gridSize && array_unique($boardTiles) == $boardTiles){
            $this->gridSize = $gridSize;
            $this->boardTiles = $boardTiles;
            $this->setPossibleMoves();
        }else{
            throw new Exception("Board is not set right!");
        }
    }

    public function getBoardTiles(): array
    {
        return $this->boardTiles;
    }

    public function getGridSize()
    {
        return $this->gridSize;
    }

    public function getTile(int $position){
        if($this->positionExists($position)){
            return $this->boardTiles[$position];
        }
    }

    public function setPossibleMoves(): void
    {
        $emptyPosition = array_search(null, $this->boardTiles);

        $possibleMoves = [
            'down' => $emptyPosition - $this->getGridSize(),
            'left' => $emptyPosition + 1,
            'up' => $emptyPosition + $this->getGridSize(),
            'right' => $emptyPosition - 1,
        ];

        $this->possibleMoves = array_filter($possibleMoves, function($position) use ($emptyPosition){
            return $this->positionExists($position)
                && ($this->areInSameRow($position, $emptyPosition)
                    || $this->areInSameColumn($position, $emptyPosition));
        });
    }

    public function getPossibleMoves(){
        return $this->possibleMoves;
    }

    public function tilesThatCanMove()
    {
        $tilesThatCanMove = [];
        foreach($this->possibleMoves() as $tilePosition){
            $tilesThatCanMove[] = $this->getTile($tilePosition);
        }
        return $tilesThatCanMove;
    }

    public function canMoveTile(int $tile){
        return in_array($tile, $this->tilesThatCanMove());
    }

    private function positionExists(int $position): bool
    {
        return $position >= 0 && $position < pow($this->getGridSize(), 2);
    }

    private function tileExists(int $position): bool
    {
        return $position > 0 && $position < 10;
    }

    private function areInSameRow(int $position1, int $position2): bool
    {
        $rows = [];
        for($i = 0; $i < $this->getGridSize(); $i++){
            $rowI = [];
            for($j = $i * $this->getGridSize(); $j < $i * $this->getGridSize() + $this->getGridSize(); $j++){
                $rowI[] = $j;
            }
            $rows[] = $rowI;
        }

        foreach ($rows as $row){
            if(in_array($position1, $row) && in_array($position2, $row)){
                return true;
            }
        }
        return false;
    }

    private function areInSameColumn(int $position1, int $position2): bool
    {
        $columns = [];
        for($i = 0; $i < $this->getGridSize(); $i++){
            $columnI = [];
            for($j = 0; $j < $this->getGridSize(); $j++){
                $columnI[] = $i + ($j *  $this->getGridSize());
            }
            $columns[] = $columnI;
        }

        foreach ($columns as $column){
            if(in_array($position1, $column) && in_array($position2, $column)){
                return true;
            }
        }
        return false;
    }

    public function moveTile(int $tile){
        if($this->canMoveTile($tile)){
            $emptyPosition = array_search(null, $this->boardTiles);
            $tilePosition = array_search($tile, $this->boardTiles);

            $this->boardTiles[$emptyPosition] = $tile;
            $this->boardTiles[$tilePosition] = null;
            $this->setPossibleMoves();
        }
    }

    public function areTilesInOrder(){
        foreach ($this->boardTiles as $position => $tile){
            if($position + 1 === $tile){
                continue;
            }else{
                if($tile === null && $position === pow($this->getGridSize(), 2) - 1){
                    return true;
                }
                return false;
            }
        }
    }
}