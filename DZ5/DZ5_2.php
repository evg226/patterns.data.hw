<?php


class CircleAreaLib
{
    public function getCircleArea(float $diagonal)
    {
       $area = (M_PI * $diagonal**2)/4;
       return $area;
   }
}

class SquareAreaLib
{
    public function getSquareArea(float $diagonal)
    {
       $area = ($diagonal**2)/2;
       return $area;
    }
}

interface ISquare
{
    function squareArea(int $sideSquare);
}

interface ICircle
{
    function circleArea(int $circumference);
}

/**
 * Адаптер квадрата
 */
class SquareAdapter implements ISquare {
    /**
     * @var SquareAreaLib
     */
    protected $squareArea;

    public function __construct(SquareAreaLib $squareArea)
    {
        $this->squareArea=$squareArea;
    }

    function squareArea(int $sideSquare)
    {
        return $this->squareArea->getSquareArea(sqrt(2)*$sideSquare);
    }
}

/**
 * Адаптер круга
 */
class CircleAdapter implements ICircle{
    /**
     * @var CircleAreaLib
     */
    protected $circleLib;

    public function __construct(CircleAreaLib $circleLib)
    {
        $this->circleLib=$circleLib;
    }

    function circleArea(int $circumference)
    {
        return $this->circleLib->getCircleArea($circumference/M_PI);
    }
}

/**
 *  Тесты
 */

$squareAdapter=new SquareAdapter(new SquareAreaLib());
$side=2;
echo "Площадь квадрата со стороной $side: " .
    $squareAdapter->squareArea($side)."\n";

$circleAdapter=new CircleAdapter(new CircleAreaLib());
$circumference=12;
echo "Площадь круга с длиной окружности $circumference: " .
    $circleAdapter->circleArea($circumference)."\n";
