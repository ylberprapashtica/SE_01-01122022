<?php

class Board
{

    private array $boardTiles = [1, 8, 2, 4, 3, 5, 7, 6, NULL];

    public function getBoardTiles(): array
    {
        return $this->boardTiles;
    }

    public function getTile(int $position){
        if($this->positionExists($position)){
            return $this->boardTiles[$position];
        }
    }

    public function possibleMoves(): array
    {
        $emptyPosition = array_search(null, $this->boardTiles);

        $possibleMoves = [
            'down' => $emptyPosition - 3,
            'left' => $emptyPosition + 1,
            'up' => $emptyPosition + 3,
            'right' => $emptyPosition - 1,
        ];

        return array_filter($possibleMoves, function($position) use ($emptyPosition){
            return $this->positionExists($position)
                && ($this->areInSameRow($position, $emptyPosition)
                || $this->areInSameColumn($position, $emptyPosition));
        });
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
        return $position >= 0 && $position < 9;
    }

    private function tileExists(int $position): bool
    {
        return $position > 0 && $position < 10;
    }

    private function areInSameRow(int $position1, int $position2): bool
    {
        $rows = [[0, 1, 2], [3, 4, 5], [6, 7, 8]];
        foreach ($rows as $row){
            if(in_array($position1, $row) && in_array($position2, $row)){
                return true;
            }
        }
        return false;
    }

    private function areInSameColumn(int $position1, int $position2): bool
    {
        $columns = [[0, 3, 6], [1, 4, 7], [2, 5, 8]];
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
        }
    }

    public function areTilesInOrder(){
        foreach ($this->boardTiles as $position => $tile){
            if($position + 1 === $tile){
                continue;
            }else{
                if($tile === null && $position === 8){
                    return true;
                }
                return false;
            }
        }
    }
}